<?php

class DatabaseSeeder extends Seeder
{

    /**
     * @return void
     */
    public function run(): void
    {
        $this->truncate([
            'languages',
            'menu_items',
            'pages',
            'page_variants',
            'routes',
            'tags',
            'users',
        ]);

        $this->call(UsersSeeder::class);
        $this->call(LanguagesSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(PagesSeeder::class);
        $this->call(MenuItemsSeeder::class);
    }

}
