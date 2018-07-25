<?php

namespace App\Pages\Internal\Services\PageVariants;

use App\Pages\Models\PageVariant;
use App\Pages\ValueObjects\RenderedPageVariant;
use Exception;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\Collection;
use League\CommonMark\CommonMarkConverter;

class PageVariantsRenderer
{

    /**
     * @var ContainerContract
     */
    private $container;

    /**
     * @var CommonMarkConverter
     */
    private $commonMarkConverter;

    /**
     * @param ContainerContract $container
     */
    public function __construct(
        ContainerContract $container
    ) {
        $this->container = $container;
        $this->commonMarkConverter = new CommonMarkConverter();
    }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    public function render(PageVariant $pageVariant): RenderedPageVariant
    {
        return new RenderedPageVariant([
            'pageVariant' => $pageVariant,

            'lead' => $this->commonMarkConverter->convertToHtml($pageVariant->lead ?? ''),
            'content' => $this->commonMarkConverter->convertToHtml($pageVariant->content ?? ''),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function renderMany(Collection $pageVariants): Collection
    {
        return $pageVariants->map([$this, 'render']);
    }

}