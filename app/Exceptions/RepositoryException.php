<?php

namespace App\Exceptions;

class RepositoryException extends Exception
{

    /**
     * @param string $modelClass
     * @param string $attributeName
     * @param string $attributeValue
     * @return RepositoryException
     */
    public static function makeModelNotFound(string $modelClass, string $attributeName, string $attributeValue): self
    {
        return new self(
            sprintf('A model of class [%s] with [%s = %s] could not have been found.', $modelClass, $attributeName,
                $attributeValue)
        );
    }

    /**
     * @param string $reason
     * @return RepositoryException
     */
    public static function makeModelIsInvalid(string $reason): self
    {
        return new self(
            sprintf('Given model is not invalid for this repository: %s', $reason)
        );
    }

}