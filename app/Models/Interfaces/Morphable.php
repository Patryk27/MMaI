<?php

namespace App\Models\Interfaces;

/**
 * This interface is used to mark model as 'morphable', to usage in polymorphic
 * relationships.
 */
interface Morphable
{

    /**
     * Returns id (primary key) of this morphable, e.g. `5`.
     *
     * @return int
     */
    public function getMorphableId(): int;

    /**
     * Returns name of this morphable, e.g. 'page' or 'language'.
     *
     * @return string
     */
    public static function getMorphableType(): string;

}