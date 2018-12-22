<?php

namespace App\Pages\Implementation\Services;

use App\Pages\Models\Page;
use App\Pages\ValueObjects\RenderedPage;
use League\CommonMark\CommonMarkConverter;

class PagesRenderer
{
    /** @var CommonMarkConverter */
    private $commonMarkConverter;

    public function __construct()
    {
        $this->commonMarkConverter = new CommonMarkConverter();
    }

    /**
     * @param Page $page
     * @return RenderedPage
     */
    public function render(Page $page): RenderedPage
    {
        return new RenderedPage([
            'page' => $page,
            'lead' => $this->commonMarkConverter->convertToHtml($page->lead ?? ''),
            'content' => $this->commonMarkConverter->convertToHtml($page->content ?? ''),
        ]);
    }
}
