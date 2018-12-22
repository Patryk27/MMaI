<?php

use App\Languages\Models\Language;
use App\Menus\Models\MenuItem;

final class MenuItemsSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
//        $this->createMenuItem('en', 0, 'en/about-me', 'About me'); @todo
        $this->createMenuItem('en', 1, 'https://google.com', 'My favorite site');

//        $this->createMenuItem('pl', 0, 'pl/o-mnie', 'O mnie'); @todo
        $this->createMenuItem('pl', 1, 'https://google.pl', 'Moja ulubiona strona');
    }

    /**
     * Creates a navigation item which points at URL.
     *
     * @param string $languageSlug
     * @param int $position
     * @param string $url
     * @param string $title
     * @return void
     */
    private function createMenuItem(
        string $languageSlug,
        int $position,
        string $url,
        string $title
    ): void {
        $language = Language::where('slug', $languageSlug)->firstOrFail();

        MenuItem::create([
            'language_id' => $language->id,
            'position' => $position,
            'url' => $url,
            'title' => $title,
        ]);
    }
}
