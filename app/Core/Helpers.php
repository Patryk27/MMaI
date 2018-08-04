<?php

use App\Core\Exceptions\UnimplementedException;

/**
 * Throws a @see UnimplementedException pointing at the caller's frame.
 *
 * @return void
 */
function unimplemented(): void
{
    $frames = debug_backtrace();
    $callerFrame = $frames[1];

    throw new UnimplementedException(
        sprintf('%s::%s', $callerFrame['class'], $callerFrame['function'])
    );
}