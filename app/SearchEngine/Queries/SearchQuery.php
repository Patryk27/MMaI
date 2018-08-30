<?php

namespace App\SearchEngine\Queries;

use App\Languages\Models\Language;

class SearchQuery
{

    /**
     * Query which will be used to find posts / pages.
     * E.g. "favourite food".
     *
     * @var string
     */
    private $query;

    /**
     * If set, returned page variants are limited to those matching given
     * language.
     *
     * Default: null.
     *
     * @var Language|null
     */
    private $language;

    /**
     * If set, only post-typed page variants are returned.
     *
     * Default: false.
     *
     * @var bool
     */
    private $postsOnly;

    /**
     * @param array $payload
     */
    public function __construct(
        array $payload
    ) {
        $this->query = array_get($payload, 'query', '');
        $this->language = array_get($payload, 'language', null);
        $this->postsOnly = array_get($payload, 'postsOnly', false);
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return bool
     */
    public function hasLanguage(): bool
    {
        return isset($this->language);
    }

    /**
     * @return Language|null
     */
    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @return bool
     */
    public function isPostsOnly(): bool
    {
        return $this->postsOnly;
    }

}