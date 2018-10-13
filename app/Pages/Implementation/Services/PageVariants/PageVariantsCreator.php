<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Routes\Models\Route;
use App\Tags\Exceptions\TagException;
use App\Tags\Queries\GetTagByIdQuery;
use App\Tags\TagsFacade;

class PageVariantsCreator
{

    /**
     * @var PageVariantsValidator
     */
    private $pageVariantsValidator;

    /**
     * @var TagsFacade
     */
    private $tagsFacade;

    /**
     * @param PageVariantsValidator $pageVariantsValidator
     * @param TagsFacade $tagsFacade
     */
    public function __construct(
        PageVariantsValidator $pageVariantsValidator,
        TagsFacade $tagsFacade
    ) {
        $this->pageVariantsValidator = $pageVariantsValidator;
        $this->tagsFacade = $tagsFacade;
    }

    /**
     * @param Page $page
     * @param array $pageVariantData
     * @return PageVariant
     *
     * @throws PageException
     * @throws TagException
     */
    public function create(Page $page, array $pageVariantData): PageVariant
    {
        $pageVariant = $this->savePageVariant($page, $pageVariantData);

        $this->saveRoute($pageVariant, array_get($pageVariantData, 'url') ?? '');
        $this->saveTags($pageVariant, array_get($pageVariantData, 'tag_ids') ?? []);
        $this->validate($pageVariant);

        return $pageVariant;
    }

    /**
     * @param Page $page
     * @param array $pageVariantData
     * @return PageVariant
     */
    private function savePageVariant(Page $page, array $pageVariantData): PageVariant
    {
        // Create a brand-new page variant
        $pageVariant = new PageVariant();

        // Associate it with a page;
        // We cannot simply do `$pageVariant->page()->associate($page)`, because
        // the `$page` may not exist yet.
        $pageVariant->setRelation('page', $page);

        // Fill page with data from the request
        $pageVariant->language_id = (int)array_get($pageVariantData, 'language_id');
        $pageVariant->status = array_get($pageVariantData, 'status') ?? PageVariant::STATUS_DRAFT;
        $pageVariant->title = array_get($pageVariantData, 'title') ?? '';
        $pageVariant->lead = array_get($pageVariantData, 'lead') ?? '';
        $pageVariant->content = array_get($pageVariantData, 'content') ?? '';

        return $pageVariant;
    }

    /**
     * @param PageVariant $pageVariant
     * @param string $routeUrl
     * @return void
     */
    private function saveRoute(PageVariant $pageVariant, string $routeUrl): void
    {
        if (strlen($routeUrl) > 0) {
            $route = new Route([
                'subdomain' => $pageVariant->language->slug ?? '',
                'url' => $routeUrl,
            ]);

            $pageVariant->setRelation('route', $route);
        }
    }

    /**
     * @param PageVariant $pageVariant
     * @param int[] $tagIds
     * @return void
     *
     * @throws TagException
     */
    private function saveTags(PageVariant $pageVariant, array $tagIds): void
    {
        foreach ($tagIds as $tagId) {
            $tag = $this->tagsFacade->queryOne(
                new GetTagByIdQuery($tagId)
            );

            $pageVariant->tags->push($tag);
        }
    }

    /**
     * @param PageVariant $pageVariant
     *
     * @throws PageException
     */
    private function validate(PageVariant $pageVariant): void
    {
        $this->pageVariantsValidator->validate($pageVariant);
    }

}
