<?php

use App\Models\InternalPage;
use App\Models\Route;

class InternalPagesSeeder extends Seeder
{

    /**
     * @return void
     * 
     * @throws Throwable
     */
    public function run(): void
    {
        $this->createInternalPage(InternalPage::TYPE_HOME, [
            'en',
            'pl',
        ]);

        $this->createInternalPage(InternalPage::TYPE_SEARCH, [
            'en/search',
            'pl/szukaj',
        ]);
    }

    /**
     * Creates an internal page and then creates routes pointing at it.
     *
     * @param string $type
     * @param array $routes
     * @return void
     *
     * @throws Throwable
     */
    private function createInternalPage(string $type, array $routes): void
    {
        $internalPage = InternalPage::create([
            'type' => $type,
        ]);

        foreach ($routes as $route) {
            Route::buildFor($route, $internalPage)
                ->saveOrFail();
        }
    }

}