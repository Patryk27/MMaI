<?php

namespace App\Core\Exceptions;

class RepositoryException extends Exception
{

    /**
     * @param string $reason
     * @return self
     */
    public static function makeModelIsInvalid(string $reason): self
    {
        return new self(
            sprintf('Given model is not invalid for this repository: %s', $reason)
        );
    }

}