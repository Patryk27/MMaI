<?php

namespace App\Core\Searcher\Expressions;

interface ExpressionHandler {

    /**
     * Handles the "between" function.
     *
     * Example usage in expression:
     *   :between(100,200)
     *
     * @param mixed $min
     * @param mixed $max
     * @return void
     */
    public function between($min, $max): void;

    /**
     * Handles the "regex" function.
     *
     * Example usage in expression:
     *   :regex('^something')
     *
     * @param string $regex
     * @return void
     */
    public function regex($regex): void;

}
