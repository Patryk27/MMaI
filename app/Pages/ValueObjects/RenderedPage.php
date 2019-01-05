<?php

namespace App\Pages\ValueObjects;

use App\Core\ValueObjects\HasInitializationConstructor;
use App\Pages\Models\Page;

final class RenderedPage {
    use HasInitializationConstructor;

    /** @var Page */
    private $page;

    /** @var string */
    private $lead;

    /** @var string */
    private $content;

    /**
     * @return Page
     */
    public function getPage(): Page {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getLead(): string {
        return $this->lead;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }
}
