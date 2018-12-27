<?php

use App\Languages\Models\Language;
use App\Pages\Models\Page;
use App\Routes\Models\Route;
use App\Tags\Models\Tag;
use App\Websites\Models\Website;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Collection;

final class RandomPostsSeeder extends Seeder
{
    private const POST_PER_LANGUAGE = 50;

    /** @var FakerGenerator[] */
    private $fakers;

    /** @var Collection|Language[] */
    private $languages;

    /** @var Collection|Website[] */
    private $websites;

    /** @var Collection|Tag[] */
    private $tags;

    public function __construct()
    {
        $this->fakers = [];
        $this->languages = Language::all();
        $this->websites = Website::all();
        $this->tags = Tag::all();

        foreach ($this->languages as $language) {
            $this->fakers[$language->id] = FakerFactory::create(
                sprintf('%s_%s', $language->iso639_code, strtoupper($language->iso3166_code))
            );
        }
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function run(): void
    {
        $this->command->line(sprintf(
            'About to create <info>%d</info> random posts...', $this->languages->count() * self::POST_PER_LANGUAGE
        ));

        /** @var OutputStyle $output */
        $output = $this->command->getOutput();

        // Create a progress bar
        $progressBar = $output->createProgressBar(
            $this->languages->count() * self::POST_PER_LANGUAGE
        );

        // Begin to create some random posts
        for ($i = 0; $i < self::POST_PER_LANGUAGE; ++$i) {
            $this->createRandomPosts();

            $progressBar->advance(
                $this->languages->count()
            );
        }

        // Finish the progress bar
        $progressBar->finish();
        $this->command->info('');

        // Re-index Elasticsearch
        $this->command->info('');
        $this->command->call('app:search-engine:reindex-all');
    }

    /**
     * @return void
     * @throws Throwable
     */
    private function createRandomPosts(): void
    {
        foreach ($this->websites as $website) {
            $page = $this->createRandomPost($website);

            $this->assignTags($page);
            $this->assignRoute($page);
        }
    }

    /**
     * @param Website $website
     * @return Page
     * @throws Throwable
     */
    private function createRandomPost(Website $website): Page
    {
        $faker = $this->fakers[$website->language->id];

        $page = new Page([
            'website_id' => $website->id,

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

            'type' => Page::TYPE_PAGE,
            'status' => Page::STATUS_PUBLISHED,

            'published_at' => Carbon::now(),
        ]);

        $page->saveOrFail();

        return $page;
    }

    /**
     * @param Page $page
     * @return void
     */
    private function assignTags(Page $page): void
    {
        $tags = $this->tags->where('language_id', $page->website->language->id);

        if ($tags->isEmpty()) {
            return;
        }

        $tags = $tags->random(
            mt_rand(1, $tags->count())
        );

        $page->tags()->sync(
            $tags->pluck('id')
        );
    }

    /**
     * @param Page $page
     * @return void
     * @throws Throwable
     */
    private function assignRoute(Page $page): void
    {
        $route = Route::build(
            $page->website->slug,
            sprintf('%04d/%02d/%s', $page->published_at->year, $page->published_at->month, kebab_case($page->title)),
            $page
        );

        $route->saveOrFail();
    }
}
