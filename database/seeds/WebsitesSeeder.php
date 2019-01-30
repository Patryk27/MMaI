<?php

use App\Languages\Models\Language;
use App\Websites\Models\Website;

final class WebsitesSeeder extends Seeder {

    /**
     * @inheritDoc
     */
    public function run(): void {
        $this->createWebsite('en', 'en', 'English', 'Tis\' my site');
        $this->createWebsite('pl', 'pl', 'Polski', 'To moja strona');
    }

    /**
     * @param string $language
     * @param string $slug
     * @param string $name
     * @param string $description
     * @return void
     */
    private function createWebsite(
        string $language,
        string $slug,
        string $name,
        string $description
    ): void {
        $language = Language::where('iso639_code', $language)->firstOrFail();

        Website::create([
            'language_id' => $language->id,
            'slug' => $slug,
            'name' => $name,
            'description' => $description,
        ]);
    }

}
