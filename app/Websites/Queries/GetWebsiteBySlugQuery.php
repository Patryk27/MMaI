<?php

namespace App\Websites\Queries;

final class GetWebsiteBySlugQuery implements WebsitesQuery {
    /** @var string */
    private $slug;

    public function __construct(string $slug) {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug(): string {
        return $this->slug;
    }
}
