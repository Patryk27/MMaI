<?php

/**
 * @return void
 */
function unimplemented(): void
{
    $frames = debug_backtrace();
    $previousFrame = $frames[1];

    throw new LogicException(
        sprintf('[%s::%s] has not been implemented yet.', $previousFrame['class'], $previousFrame['function'])
    );
}