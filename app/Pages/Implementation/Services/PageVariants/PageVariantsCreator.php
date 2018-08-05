<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Routes\Models\Route;

class PageVariantsCreator
{

    /**
     * @var PageVariantsValidator
     */
    private $pageVariantsValidator;

    /**
     * @param PageVariantsValidator $pageVariantsValidator
     */
    public function __construct(
        PageVariantsValidator $pageVariantsValidator
    ) {
        $this->pageVariantsValidator = $pageVariantsValidator;
    }

    /**
     * @param Page $page
     * @param array $pageVariantData
     * @return PageVariant
     *
     * @throws AppException
     */
    public function create(Page $page, array $pageVariantData): PageVariant
    {
        $pageVariant = $this->createPageVariant($page, $pageVariantData);
        $this->createRoute($pageVariant, $pageVariantData);

        $this->pageVariantsValidator->validate($pageVariant);

        return $pageVariant;
    }

    /**
     * @param Page $page
     * @param array $pageVariantData
     * @return PageVariant
     */
    private function createPageVariant(Page $page, array $pageVariantData): PageVariant
    {
        // Create a brand-new page variant
        $pageVariant = new PageVariant();

        // Associate it with a page;
        // We cannot simply do `$pageVariant->page()->associate($page)`, because
        // the `$page` may not exist yet.
        $pageVariant->setRelations([
            'page' => $page,
        ]);

        $pageVariant->fill(
            array_only($pageVariantData, ['language_id', 'status', 'title', 'lead', 'content'])
        );

        return $pageVariant;
    }

    /**
     * @param PageVariant $pageVariant
     * @param array $pageVariantData
     * @return void
     */
    private function createRoute(PageVariant $pageVariant, array $pageVariantData): void
    {
        $url = array_get($pageVariantData, 'route');

        if (strlen($url) > 0) {
            $route = new Route([
                'url' => $url,
            ]);

            $pageVariant->setRelation('route', $route);
        }
    }

}