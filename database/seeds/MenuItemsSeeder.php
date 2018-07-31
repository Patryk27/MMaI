<?php

use App\Languages\Models\Language;
use App\Menus\Models\MenuItem;
use App\Routes\Models\Route;

class MenuItemsSeeder extends Seeder
{

    /**
     * @return void
     */
    public function run(): void
    {
        $this->createItemToRoute('en', 0, 'en/about-me', 'About me');
        $this->createItemToRoute('pl', 0, 'pl/o-mnie', 'O mnie');

        $this->createItemToUrl('en', 1, 'https://google.com', 'My favorite site!');
        $this->createItemToUrl('pl', 1, 'https://google.pl', 'Moja ulubiona strona!');
    }

    /**
     * Creates a navigation item which points at route.
     *
     * @param string $languageSlug
     * @param int $position
     * @param string $url
     * @param string $title
     * @return void
     */
    private function createItemToRoute(string $languageSlug, int $position, string $url, string $title): void
    {
        $language = Language::where('slug', $languageSlug)->firstOrFail();
        $route = Route::where('url', $url)->firstOrFail();

        MenuItem::create([
            'language_id' => $language->id,
            'position' => $position,
            'route_id' => $route->id,
            'title' => $title,
        ]);
    }

    /**
     * Creates a navigation item which points at URL.
     *
     * @param string $languageSlug
     * @param int $position
     * @param string $string
     * @param string $title
     * @return void
     */
    private function createItemToUrl(string $languageSlug, int $position, string $string, string $title): void
    {
        $language = Language::where('slug', $languageSlug)->firstOrFail();

        MenuItem::create([
            'language_id' => $language->id,
            'position' => $position,
            'url' => $string,
            'title' => $title,
        ]);
    }

}