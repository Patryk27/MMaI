<?php

use App\Menus\Models\MenuItem;

final class MenusSeeder extends Seeder {
    /**
     * @return void
     */
    public function run(): void {
        $this->createItem('en', 1, 'https://google.com', 'My favorite site');
        $this->createItem('pl', 1, 'https://google.pl', 'Moja ulubiona strona');
    }

    /**
     * @param string $website
     * @param int $position
     * @param string $url
     * @param string $title
     * @return void
     */
    private function createItem(
        string $website,
        int $position,
        string $url,
        string $title
    ): void {
        MenuItem::create([
            'website_id' => $this->getWebsite($website)->id,
            'position' => $position,
            'url' => $url,
            'title' => $title,
        ]);
    }
}
