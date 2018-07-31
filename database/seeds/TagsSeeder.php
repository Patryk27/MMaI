<?php

use App\Languages\Models\Language;
use App\Tags\Models\Tag;

class TagsSeeder extends Seeder
{

    /**
     * @return void
     *
     * @throws Throwable
     */
    public function run(): void
    {
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
     *
     * @throws Throwable
     */
    private function createTag(string $languageSlug, string $name): void
    {
        $language = Language::where('slug', $languageSlug)->firstOrFail();

        Tag::create([
            'language_id' => $language->id,
            'name' => $name,
        ]);
    }

}