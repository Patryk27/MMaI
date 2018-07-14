<?php

namespace App\Services\PageVariants;

use App\Models\PageVariant;
use App\ValueObjects\RenderedPageVariant;
use Exception;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\Collection;
use League\CommonMark\CommonMarkConverter;

class Renderer
{

    /**
     * @var ContainerContract
     */
    protected $container;

    /**
     * @var CommonMarkConverter
     */
    protected $commonMarkConverter;

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