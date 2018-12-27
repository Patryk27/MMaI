<?php

namespace App\SearchEngine\Queries;

use App\Websites\Models\Website;

final class SearchQuery
{
    /**
     * Text query which will be used to find pages, e.g.: "arduino".
     * @var string
     */
    private $query;

    /**
     * If set, returned pages are limited to given type.
     * @var string|null
     */
    private $type;

    /**
     * If set, returned pages are limited to given website.
     * @var Website|null
     */
    private $website;

    public function __construct(array $payload)
    {
        $this->query = array_get($payload, 'query', '');
        $this->type = array_get($payload, 'type');
        $this->website = array_get($payload, 'website');
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
    public function hasWebsite(): bool
    {
        return isset($this->website);
    }

    /**
     * @return Website|null
     */
    public function getWebsite(): ?Website
    {
        return $this->website;
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
