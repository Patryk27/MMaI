<?php

use App\Models\Route;

final class RoutesSeeder
    extends Seeder {

    /**
     * @return void
     */
    public function run(): void {
        $this->createRedirection('en', 'en/home-page');
        $this->createRedirection('pl', 'pl/strona-glowna');

        $this->createRedirection('/', 'en');
    }

    /**
     * Creates a redirection from any given URL ($fromUrl) onto an already
     * existing route ($toUrl).
     *
     * @param string $fromUrl
     * @param string $toUrl
     * @return void
     * @throws Throwable
     */
    private function createRedirection(string $fromUrl, string $toUrl): void {
        $toUrlModel = Route::where('url', $toUrl)->firstOrFail();

        Route::buildFor($fromUrl, $toUrlModel)
            ->saveOrFail();
    }

}