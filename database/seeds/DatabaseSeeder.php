<?php

final class DatabaseSeeder extends Seeder {

    /**
     * @return void
     */
    public function run(): void {
        $this->truncate([
            'attachments',
            'events',
            'languages',
            'menu_items',
            'page_tag',
            'pages',
            'routes',
            'tags',
            'users',
            'websites',
        ]);

        $this->call(UsersSeeder::class);
        $this->call(LanguagesSeeder::class);
        $this->call(WebsitesSeeder::class);
        $this->call(MenusSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(PagesSeeder::class);

        $this->command->info('');
        $this->command->call('app:search-engine:reindex-all');

        $this->command->info('');
        $this->command->info('MMaI\'s database has been initialized.');
    }

}
