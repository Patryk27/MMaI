<?php

namespace App\Languages\Queries;

/**
 * This class defines a query which will return single language with specified
 * slug.
 */
final class GetLanguageBySlugQuery implements LanguagesQueryInterface
{

    /**
     * @var string
     */
    private $slug;

    /**
     * @param string $slug
     */
    public function __construct(
        string $slug
    ) {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

}