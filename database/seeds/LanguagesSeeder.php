<?php

use App\Languages\Models\Language;

class LanguagesSeeder extends Seeder
{

    /**
     * @throws Throwable
     *
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
     * @param string $nativeName
     * @return void
     *
     * @throws Throwable
     */
    private function createLanguage(
        string $slug,
        string $isoName,
        string $englishName,
        string $nativeName
    ): void {
        Language::create([
            'slug' => $slug,
            'iso_name' => $isoName,
            'english_name' => $englishName,
            'native_name' => $nativeName,
        ]);
    }

}