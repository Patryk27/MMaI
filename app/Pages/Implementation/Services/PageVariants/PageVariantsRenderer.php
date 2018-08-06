<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Pages\Models\PageVariant;
use App\Pages\ValueObjects\RenderedPage;
use League\CommonMark\CommonMarkConverter;

class PageVariantsRenderer
{

    /**
     * @var CommonMarkConverter
     */
    private $commonMarkConverter;

    public function __construct()
    {
        $this->commonMarkConverter = new CommonMarkConverter();
    }

    /**
     * @param PageVariant $pageVariant
     * @return RenderedPage
     */
    public function render(PageVariant $pageVariant): RenderedPage
    {
        return new RenderedPage([
            'pageVariant' => $pageVariant,

            'lead' => $this->commonMarkConverter->convertToHtml($pageVariant->lead ?? ''),
            'content' => $this->commonMarkConverter->convertToHtml($pageVariant->content ?? ''),
        ]);
    }

}