<?php

namespace App\Pages\Events;

use App\Pages\Models\Page;
use Illuminate\Queue\SerializesModels;

final class PageCreated {

    use SerializesModels;

    /** @var Page */
    private $page;

    public function __construct(Page $page) {
        $this->page = $page;
    }

    /**
     * @return Page
     */
    public function getPage(): Page {
        return $this->page;
    }

}
