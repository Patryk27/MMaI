<?php

namespace App\Languages\Queries;

final class GetLanguageBySlugQuery implements LanguagesQuery
{
    /** @var string */
    private $slug;

    public function __construct(string $slug)
    {
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
