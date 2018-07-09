<?php

namespace App\Models\Interfaces;

/**
 * This interface is used to mark model as "morphable", meaning it can be e.g.
 * used in routing.
 */
interface Morphable
{

    /**
     * Returns id of this morphable, e.g. `5`.
     * Used to fill the "morphable_id" field.
     *
     * @return int
     */
    public function getMorphableId(): int;

    /**
     * Returns name of this morphable, e.g. 'page' or 'language'.
     * Used to fill the "morphable_name" field.
     *
     * @return string
     */
    public static function getMorphableType(): string;

}