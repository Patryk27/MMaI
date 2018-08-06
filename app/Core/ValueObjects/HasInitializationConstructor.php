<?php

namespace App\Core\ValueObjects;

use LogicException;

trait HasInitializationConstructor
{

    /**
     * @param array $properties
     *
     * @throws LogicException
     */
    public function __construct(array $properties)
    {
        foreach ($properties as $propertyName => $propertyValue) {
            $propertyName = camel_case($propertyName);

            if (!property_exists($this, $propertyName)) {
                throw new LogicException(
                    sprintf('Property [%s] does not exist in class [%s]', $propertyName, get_class($this))
                );
            }

            $this->$propertyName = $propertyValue;
        }

        if (method_exists($this, 'validate')) {
            $this->validate();
        }
    }
}