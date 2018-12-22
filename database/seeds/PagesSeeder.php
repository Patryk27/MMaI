<?php

use App\Languages\Models\Language;
use App\Pages\Models\Page;
use App\Routes\Models\Route;
use App\Tags\Models\Tag;
use Carbon\Carbon;

final class PagesSeeder extends Seeder
{
    /*** @var Carbon */
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
     * @return void
     * @throws Throwable
     */
    private function createAboutPages(): void
    {
        $this->createPage([
            'type' => Page::TYPE_PAGE,
            'status' => Page::STATUS_PUBLISHED,

            'language' => 'en',
            'url' => 'about-me',

            'title' => 'About me',
            'content' => 'This page is all about me, myself and I!',

            'published_at' => $this->now,
        ]);

        $this->createPage([
            'type' => Page::TYPE_PAGE,
            'status' => Page::STATUS_PUBLISHED,

            'language' => 'pl',
            'url' => 'o-mnie',

            'title' => 'O mnie',
            'content' => 'Ta strona jest o mnie i ze mną w roli głównej!',

            'published_at' => $this->now,
        ]);
    }

    /**
     * Creates default example posts.
     *
     * @return void
     * @throws Throwable
     */
    private function createExamplePosts(): void
    {
        $date = sprintf('%04d/%02d', $this->now->year, $this->now->month);

        $this->createPage([
            'type' => Page::TYPE_POST,
            'status' => Page::STATUS_PUBLISHED,

            'language' => 'en',
            'url' => sprintf('%s/%s', $date, 'welcome'),

            'title' => 'My first post',
            'lead' => 'Hi!',
            'content' => 'It\'s great to meet you, world! :-)',

            'published_at' => $this->now,

            'tags' => [
                'programming',
                'music',
            ],
        ]);

        $this->createPage([
            'type' => Page::TYPE_POST,
            'status' => Page::STATUS_PUBLISHED,

            'language' => 'pl',
            'url' => sprintf('%s/%s', $date, 'witaj'),

            'title' => 'Mój pierwszy post',
            'lead' => 'Hejo!',
            'content' => 'Miło Cię poznać, świecie! :-)',

            'published_at' => $this->now,

            'tags' => [
                'programowanie',
                'muzyka',
            ],
        ]);
    }

    /**
     * @param array $pageData
     * @return void
     * @throws Throwable
     */
    private function createPage(array $pageData): void
    {
        $language = Language::where('slug', $pageData['language'])->firstOrFail();

        $page = Page::create([
            'type' => $pageData['type'],
            'status' => $pageData['status'],

            'language_id' => $language->id,

            'title' => $pageData['title'],
            'lead' => array_get($pageData, 'lead'),
            'content' => $pageData['content'],

            'published_at' => $pageData['published_at'],
        ]);

        $route = Route::buildFor($language->slug, $pageData['url'], $page);
        $route->saveOrFail();

        foreach (array_get($pageData, 'tags', []) as $tagName) {
            $tag = Tag::query()
                ->where('language_id', $language->id)
                ->where('name', $tagName)
                ->firstOrFail();

            $page->tags()->attach($tag->id);
        }
    }
}
