<?php

use App\Languages\Models\Language;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Routes\Models\Route;
use App\Tags\Models\Tag;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Support\Collection;

class RandomPostsSeeder extends Seeder
{

    private const NUMBER_OF_POSTS_PER_LANGUAGE = 50;

    /**
     * @var FakerGenerator[]
     */
    private $fakers;

    /**
     * @var Collection|Language[]
     */
    private $languages;

    /**
     * @var Collection|Tag[]
     */
    private $tags;

    public function __construct()
    {
        $this->fakers = [];
        $this->languages = Language::all();
        $this->tags = Tag::all();

        foreach ($this->languages as $language) {
            $this->fakers[$language->id] = FakerFactory::create(
                sprintf('%s_%s', $language->iso_639_code, strtoupper($language->iso_3166_code))
            );
        }
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function run(): void
    {
        $this->command->line(
            sprintf('About to create <info>%d</info> random posts...', $this->languages->count() * self::NUMBER_OF_POSTS_PER_LANGUAGE)
        );

        /**
         * @var \Illuminate\Console\OutputStyle $output
         */
        $output = $this->command->getOutput();

        // Create a progress bar
        $progressBar = $output->createProgressBar(
            $this->languages->count() * self::NUMBER_OF_POSTS_PER_LANGUAGE
        );

        // Begin to create some random posts
        for ($i = 0; $i < self::NUMBER_OF_POSTS_PER_LANGUAGE; ++$i) {
            $this->createRandomPosts();

            $progressBar->advance(
                $this->languages->count()
            );
        }

        // Finish the progress bar
        $progressBar->finish();
        $this->command->info('');

        // Perform Elasticsearch's re-indexation
        $this->command->info('');
        $this->command->call('app:search-engine:reindex-all');
    }

    /**
     * @return void
     *
     * @throws Throwable
     */
    private function createRandomPosts(): void
    {
        // Create a new page
        $page = new Page([
            'type' => Page::TYPE_BLOG,
        ]);

        $page->saveOrFail();

        // Create new page's page variants
        foreach ($this->languages as $language) {
            $pageVariant = $this->createRandomPageVariant($page, $language);

            $this->assignPageVariantToRandomTags($pageVariant);
            $this->createRouteForPageVariant($pageVariant);
        }
    }

    /**
     * @param Page $page
     * @param Language $language
     * @return PageVariant
     *
     * @throws Throwable
     */
    private function createRandomPageVariant(Page $page, Language $language): PageVariant
    {
        $faker = $this->fakers[$language->id];

        $pageVariant = new PageVariant([
            'page_id' => $page->id,
            'language_id' => $language->id,

            'status' => PageVariant::STATUS_PUBLISHED,

            'title' => $faker->words(
                $faker->numberBetween(3, 8),
                true
            ),

            'lead' => $faker->realText(
                $faker->numberBetween(100, 250)
            ),

            'content' => $faker->realText(
                $faker->numberBetween(3000, 10000)
            ),

            'published_at' => Carbon::now(),
        ]);

        $pageVariant->saveOrFail();

        return $pageVariant;
    }

    /**
     * @param PageVariant $pageVariant
     * @return void
     */
    private function assignPageVariantToRandomTags(PageVariant $pageVariant): void
    {
        $tags = $this->tags->where('language_id', $pageVariant->language->id);

        if ($tags->isEmpty()) {
            return;
        }

        $tags = $tags->random(
            mt_rand(1, $tags->count())
        );

        $pageVariant->tags()->sync(
            $tags->pluck('id')
        );
    }

    /**
     * @param PageVariant $pageVariant
     * @return void
     *
     * @throws Throwable
     */
    private function createRouteForPageVariant(PageVariant $pageVariant): void
    {
        $route = Route::buildFor(
            $pageVariant->language->slug,
            sprintf('%04d/%02d/%s', $pageVariant->published_at->year, $pageVariant->published_at->month, kebab_case($pageVariant->title)),
            $pageVariant
        );

        $route->saveOrFail();
    }

}
