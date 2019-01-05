<?php

use App\Pages\Models\Page;
use App\Routes\Models\Route;
use App\Tags\Models\Tag;
use Carbon\Carbon;

final class PagesSeeder extends Seeder {
    /*** @var Carbon */
    private $now;

    /**
     * @return void
     * @throws Throwable
     */
    public function run(): void {
        $this->now = Carbon::now();

        $this->createAboutMePages();
        $this->createExamplePosts();
    }

    /**
     * @return void
     * @throws Throwable
     */
    private function createAboutMePages(): void {
        $this->createPage([
            'type' => Page::TYPE_PAGE,
            'status' => Page::STATUS_PUBLISHED,

            'website' => 'en',
            'url' => 'about-me',

            'title' => 'About me',
            'content' => 'This page is all about me, myself and I!',

            'published_at' => $this->now,
        ]);

        $this->createPage([
            'type' => Page::TYPE_PAGE,
            'status' => Page::STATUS_PUBLISHED,

            'website' => 'pl',
            'url' => 'o-mnie',

            'title' => 'O mnie',
            'content' => 'Ta strona jest o mnie i ze mną w roli głównej!',

            'published_at' => $this->now,
        ]);
    }

    /**
     * @return void
     * @throws Throwable
     */
    private function createExamplePosts(): void {
        $date = sprintf('%04d/%02d', $this->now->year, $this->now->month);

        $this->createPage([
            'type' => Page::TYPE_POST,
            'status' => Page::STATUS_PUBLISHED,

            'website' => 'en',
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

            'website' => 'pl',
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
     * @param array $data
     * @return void
     * @throws Throwable
     */
    private function createPage(array $data): void {
        $website = $this->getWebsite($data['website']);

        $page = Page::create([
            'website_id' => $website->id,

            'type' => $data['type'],
            'status' => $data['status'],

            'title' => $data['title'],
            'lead' => array_get($data, 'lead'),
            'content' => $data['content'],

            'published_at' => $data['published_at'],
        ]);

        $route = Route::build($website->slug, $data['url'], $page);
        $route->saveOrFail();

        foreach (array_get($data, 'tags', []) as $tagName) {
            $tag = Tag::query()
                ->where('website_id', $website->id)
                ->where('name', $tagName)
                ->firstOrFail();

            $page->tags()->attach($tag->id);
        }
    }
}
