<?php

use App\IntrinsicPages\Models\IntrinsicPage;
use App\Routes\Models\Route;

class IntrinsicPagesSeeder extends Seeder
{

    /**
     * @return void
     *
     * @throws Throwable
     */
    public function run(): void
    {
        $this->createIntrinsicPage(IntrinsicPage::TYPE_HOME, [
            'en',
            'pl',
        ]);

        $this->createIntrinsicPage(IntrinsicPage::TYPE_SEARCH, [
            'en/search',
            'pl/szukaj',
        ]);
    }

    /**
     * Creates an intrinsic page and routes pointing at it.
     *
     * @param string $type
     * @param string[] $routes
     * @return void
     *
     * @throws Throwable
     */
    private function createIntrinsicPage(string $type, array $routes): void
    {
        $intrinsicPage = IntrinsicPage::create([
            'type' => $type,
        ]);

        foreach ($routes as $route) {
            Route::buildFor($route, $intrinsicPage)
                ->saveOrFail();
        }
    }

}