<?php

namespace App\Core\Exceptions;

use LogicException;

class UnimplementedException extends LogicException
{
    public function __construct(string $functionName)
    {
        parent::__construct(sprintf(
            '[%s] has not been implemented yet.', $functionName
        ));
    }
}
