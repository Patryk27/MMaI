<?php

namespace App\Languages\Queries;

class GetLanguageBySlugQuery implements LanguageQueryInterface
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