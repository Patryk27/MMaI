<?php

use App\Models\Language;

final class LanguagesSeeder extends Seeder
{

    /**
     * @throws Throwable
     * @return void
     */
    public function run(): void
    {
        $this->createLanguage('pl', 'pl-PL', 'Polish', 'Polski');
        $this->createLanguage('en', 'en-US', 'English', 'English');
    }

    /**
     * @param string $slug
     * @param string $isoName
     * @param string $englishName
     * @param string $translatedName
     * @return void
     * @throws Throwable
     */
    private function createLanguage(
        string $slug,
        string $isoName,
        string $englishName,
        string $translatedName
    ): void {
        Language::create([
            'slug' => $slug,
            'iso_name' => $isoName,
            'english_name' => $englishName,
            'translated_name' => $translatedName,
        ]);
    }

}