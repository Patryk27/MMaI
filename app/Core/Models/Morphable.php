<?php

namespace App\Core\Models;

/**
 * This interface is used to mark model as "morphable", so it can be used in
 * polymorphic relationships.
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
