<?php

use App\Models\Language;
use App\Models\Tag;

final class TagsSeeder
    extends Seeder {

    /**
     * @throws Throwable
     * @return void
     */
    public function run(): void {
        $this->createTag('pl', 'programowanie');
        $this->createTag('en', 'programming');

        $this->createTag('pl', 'arduino');
        $this->createTag('en', 'arduino');

        $this->createTag('pl', 'muzyka');
        $this->createTag('en', 'music');
    }

    /**
     * @param string $languageSlug
     * @param string $name
     * @return void
     * @throws Throwable
     */
    private function createTag(
        string $languageSlug,
        string $name
    ): void {
        $language = Language::where('slug', $languageSlug)->firstOrFail();

        Tag::create([
            'language_id' => $language->id,
            'name' => $name,
        ]);
    }

}