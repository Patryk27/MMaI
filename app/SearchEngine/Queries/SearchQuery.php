<?php

namespace App\SearchEngine\Queries;

use App\Languages\Models\Language;

final class SearchQuery
{

    /**
     * Text query which will be used to find posts / pages.
     * E.g. "arduino".
     *
     * @var string
     */
    private $query;

    /**
     * If set, returned page variants are limited to those matching given
     * page type.
     *
     * Default: null.
     *
     * @var string|null
     */
    private $pageType;

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
     * @param array $payload
     */
    public function __construct(
        array $payload
    ) {
        $this->query = array_get($payload, 'query', '');
        $this->pageType = array_get($payload, 'pageType');
        $this->language = array_get($payload, 'language');
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
    public function hasPageType(): bool
    {
        return isset($this->pageType);
    }

    /**
     * @return null|string
     */
    public function getPageType(): ?string
    {
        return $this->pageType;
    }

}