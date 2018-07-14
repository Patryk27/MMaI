<?php

use App\Models\Language;
use App\Models\Page;
use App\Models\PageVariant;
use App\Models\Route;
use App\Models\Tag;
use Carbon\Carbon;

class PagesSeeder extends Seeder
{

    /**
     * @var Carbon
     */
    private $now;

    /**
     * @return void
     * @throws Throwable
     */
    public function run(): void
    {
        $this->now = Carbon::now();

        $this->createAboutPages();
        $this->createExamplePosts();
    }

    /**
     * Creates default "about me" pages.
     *
     * Language | Page URL
     * -------- | --------
     * English  | en/about-me
     * Polish   | pl/o-mnie
     *
     * @return void
     * @throws Throwable
     */
    private function createAboutPages(): void
    {
        $this->createPage([
            'type' => Page::TYPE_CMS,

            'variants' => [
                'en' => [
                    'url' => 'en/about-me',
                    'status' => PageVariant::STATUS_PUBLISHED,

                    'title' => 'About me',
                    'content' => 'This page is all about Me, Myself and I!',

                    'published_at' => $this->now,
                ],

                'pl' => [
                    'url' => 'pl/o-mnie',
                    'status' => PageVariant::STATUS_PUBLISHED,

                    'title' => 'O mnie',
                    'content' => 'Ta strona jest o mnie i ze mną w roli głównej!',

                    'published_at' => $this->now,
                ],
            ],
        ]);
    }

    /**
     * Creates default example posts.
     *
     * Language | Post URL
     * -------- | --------
     * English  | en/xxxx-yy/hi
     * Polish   | pl/xxxx-yy/hejo
     *
     * The "xxxx-yy" part depends on current date.
     *
     * @return void
     * @throws Throwable
     */
    private function createExamplePosts(): void
    {
        $date = sprintf('%04d-%02d', $this->now->year, $this->now->month);

        $faker = Faker\Factory::create('en');

        for ($i = 0; $i < 50; ++$i) {
            $this->createPage([
                'type' => Page::TYPE_BLOG,

                'variants' => [
                    'en' => [
                        'url' => sprintf('%s/%s/%s', 'en', $date, $i),
                        'status' => PageVariant::STATUS_PUBLISHED,

                        'title' => $faker->words(mt_rand(2, 8), true),
                        'lead' => $faker->realText(),
                        'content' => $faker->paragraphs(mt_rand(1, 10), true),

                        'published_at' => $this->now,

                        'tags' => [
                            'programming',
                            'music',
                        ],
                    ],
                ],
            ]);
        }

        $this->createPage([
            'type' => Page::TYPE_BLOG,

            'variants' => [
                'en' => [
                    'url' => sprintf('%s/%s/%s', 'en', $date, 'hi'),
                    'status' => PageVariant::STATUS_PUBLISHED,

                    'title' => 'My first post',
                    'lead' => 'Hi!',
                    'content' => 'It\'s great to meet you, world! :-)',

                    'published_at' => $this->now,

                    'tags' => [
                        'programming',
                        'music',
                    ],
                ],

                'pl' => [
                    'url' => sprintf('%s/%s/%s', 'pl', $date, 'hejo'),
                    'status' => PageVariant::STATUS_PUBLISHED,

                    'title' => 'Mój pierwszy post',
                    'lead' => 'Hejo!',
                    'content' => 'Miło Cię poznać, świecie! :-)',

                    'published_at' => $this->now,

                    'tags' => [
                        'programowanie',
                        'muzyka',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Creates a single page; automatically saves it into the database.
     *
     * @param array $pageData
     * @return void
     * @throws Throwable
     */
    private function createPage(array $pageData): void
    {
        // Firstly, create the page itself
        $page = Page::create([
            'type' => $pageData['type'],
        ]);

        // Then proceed to create its page variants
        // (PVs need to know their parent-page id, that's why the page must be
        // created first.)
        foreach ($pageData['variants'] as $pageVariantLanguage => $pageVariantData) {
            $this->createPageVariant($page, $pageVariantLanguage, $pageVariantData);
        }
    }

    /**
     * Creates a single page variant and automatically saves it into the
     * database.
     *
     * @param Page $page
     * @param string $languageName
     * @param array $pageVariantData
     * @throws Throwable
     */
    private function createPageVariant(Page $page, string $languageName, array $pageVariantData): void
    {
        // Find language to which this PV is going to be bound
        $language = Language::where('slug', $languageName)->firstOrFail();

        // Create the page variant
        $pageVariant = PageVariant::create([
            'page_id' => $page->id,
            'language_id' => $language->id,
            'status' => $pageVariantData['status'],
            'title' => $pageVariantData['title'],
            'lead' => array_get($pageVariantData, 'lead'),
            'content' => $pageVariantData['content'],
            'published_at' => $pageVariantData['published_at'],
        ]);

        // Create page variant's tags
        foreach (array_get($pageVariantData, 'tags', []) as $tagName) {
            $tag = Tag::query()
                ->where('language_id', $language->id)
                ->where('name', $tagName)
                ->firstOrFail();

            $pageVariant->tags()->attach($tag->id);
        }

        // Create PV's route
        Route::buildFor(
            $pageVariantData['url'],
            $pageVariant
        )->saveOrFail();
    }

}