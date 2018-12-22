<?php

use App\Languages\Models\Language;

final class LanguagesSeeder extends Seeder
{
    /**
     * @return void
     * @throws Throwable
     */
    public function run(): void
    {
        $this->createLanguage('pl', 'pl', 'pl', 'Polish', 'Polski');
        $this->createLanguage('en', 'en', 'us', 'English', 'English');
    }

    /**
     * @param string $slug
     * @param string $iso639Code
     * @param string $iso3166Code
     * @param string $englishName
     * @param string $nativeName
     * @return void
     */
    private function createLanguage(
        string $slug,
        string $iso639Code,
        string $iso3166Code,
        string $englishName,
        string $nativeName
    ): void {
        Language::create([
            'slug' => $slug,
            'iso_639_code' => $iso639Code,
            'iso_3166_code' => $iso3166Code,
            'english_name' => $englishName,
            'native_name' => $nativeName,
        ]);
    }
}
