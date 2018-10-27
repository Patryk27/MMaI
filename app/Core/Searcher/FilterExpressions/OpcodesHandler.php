<?php

namespace App\Core\Searcher\FilterExpressions;

interface OpcodesHandler
{

    /**
     * @param mixed $min
     * @param mixed $max
     * @return void
     */
    public function between($min, $max): void;

    /**
     * @param string $regex
     * @return void
     */
    public function regex($regex): void;

}
