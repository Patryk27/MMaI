<?php

namespace App\Analytics\Implementation\Listeners;

use App\Analytics\AnalyticsFacade;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class Listener implements ShouldQueue
{

    /**
     * @var AnalyticsFacade
     */
    protected $analyticsFacade;

    /**
     * @param AnalyticsFacade $analyticsFacade
     */
    public function __construct(
        AnalyticsFacade $analyticsFacade
    ) {
        $this->analyticsFacade = $analyticsFacade;
    }

}
