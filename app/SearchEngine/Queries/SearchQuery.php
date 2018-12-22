<?php

namespace App\SearchEngine\Queries;

use App\Languages\Models\Language;

final class SearchQuery
{
    /**
     * Text query which will be used to find pages, e.g.: "arduino".
     * @var string
     */
    private $query;

    /**
     * If set, returned pages are limited to those with given type.
     * @var string|null
     */
    private $type;

    /**
     * If set, returned pages are limited to those with given language.
     * @var Language|null
     */
    private $language;

    public function __construct(array $payload)
    {
        $this->query = array_get($payload, 'query', '');
        $this->type = array_get($payload, 'type');
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
    public function hasType(): bool
    {
        return isset($this->type);
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
