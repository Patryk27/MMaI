<?php

namespace App\Services\PageVariants;

use App\Models\PageVariant;
use App\Services\CustomPages\Handlers\HomePageHandler;
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
            'lead' => $this->renderLead($pageVariant->lead),
            'content' => $this->renderContent($pageVariant->content, $pageVariant->page->isCmsPage()),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function renderMany(Collection $pageVariants): Collection
    {
        return $pageVariants->map([$this, 'render']);
    }

    /**
     * @param string|null $lead
     * @return string
     */
    private function renderLead(?string $lead): string
    {
        return $this->commonMarkConverter->convertToHtml($lead ?? '');
    }

    /**
     * @param string|null $content
     * @param bool $allowSpecialPages
     * @return string
     *
     * @throws Exception
     */
    private function renderContent(?string $content, bool $allowSpecialPages): string
    {
        if ($allowSpecialPages) {
            switch ($content) {
                case '{{ @home }}':
                    return $this->container->make(HomePageHandler::class)->render();
            }
        }

        return $this->commonMarkConverter->convertToHtml($content ?? '');
    }

}