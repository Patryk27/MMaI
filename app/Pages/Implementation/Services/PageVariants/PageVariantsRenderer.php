<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Pages\Models\PageVariant;
use App\Pages\ValueObjects\RenderedPageVariant;
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
     * @return RenderedPageVariant
     */
    public function render(PageVariant $pageVariant): RenderedPageVariant
    {
        return new RenderedPageVariant([
            'pageVariant' => $pageVariant,

            'lead' => $this->commonMarkConverter->convertToHtml($pageVariant->lead ?? ''),
            'content' => $this->commonMarkConverter->convertToHtml($pageVariant->content ?? ''),
        ]);
    }

}