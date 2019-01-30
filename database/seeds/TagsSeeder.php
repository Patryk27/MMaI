<?php

use App\Tags\Models\Tag;

final class TagsSeeder extends Seeder {

    /**
     * @return void
     * @throws Throwable
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
     * @param string $website
     * @param string $name
     * @return void
     * @throws Throwable
     */
    private function createTag(string $website, string $name): void {
        Tag::create([
            'website_id' => $this->getWebsite($website)->id,
            'name' => $name,
        ]);
    }

}
