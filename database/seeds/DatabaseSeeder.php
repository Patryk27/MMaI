<?php

class DatabaseSeeder extends Seeder
{

    /**
     * @return void
     */
    public function run(): void
    {
        $this->truncate([
            'intrinsic_pages',
            'languages',
            'menu_items',
            'page_variant_tag',
            'page_variants',
            'pages',
            'routes',
            'tags',
            'users',
        ]);

        $this->call(UsersSeeder::class);
        $this->call(LanguagesSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(PagesSeeder::class);
        $this->call(IntrinsicPagesSeeder::class);
        $this->call(RoutesSeeder::class);
        $this->call(MenuItemsSeeder::class);
    }

}
