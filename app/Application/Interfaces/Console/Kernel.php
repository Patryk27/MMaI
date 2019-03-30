<?php

namespace App\Application\Interfaces\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel {

    /**
     * @return void
     */
    protected function commands(): void {
        $this->load(__DIR__ . '/Commands');
    }

}
