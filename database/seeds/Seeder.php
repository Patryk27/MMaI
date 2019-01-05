<?php

use App\Websites\Models\Website;
use Illuminate\Database\Seeder as BaseSeeder;

abstract class Seeder extends BaseSeeder {
    /**
     * @return void
     */
    abstract public function run(): void;

    /**
     * @param string|string[] $tables
     * @return void
     */
    protected function truncate($tables): void {
        if (!is_array($tables)) {
            $tables = [$tables];
        }

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
    }

    /**
     * @param string $slug
     * @return Website
     */
    protected function getWebsite(string $slug): Website {
        return Website::where('slug', $slug)->firstOrFail();
    }
}
