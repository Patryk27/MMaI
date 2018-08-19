<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\PageVariant;
use App\Routes\Models\Route;
use App\Tags\Exceptions\TagException;
use App\Tags\Queries\GetTagByIdQuery;
use App\Tags\TagsFacade;
use Carbon\Carbon;

class PageVariantsUpdater
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
     * @param PageVariant $pageVariant
     * @param array $pageVariantData
     * @return void
     *
     * @throws PageException
     * @throws TagException
     */
    public function update(PageVariant $pageVariant, array $pageVariantData): void
    {
        $this->updatePublishedAt($pageVariant, $pageVariantData);
        $this->updateRoute($pageVariant, array_get($pageVariantData, 'url', ''));
        $this->updateTags($pageVariant, array_get($pageVariantData, 'tag_ids', []));

        $pageVariant->fill(
            array_only($pageVariantData, ['status', 'title', 'lead', 'content'])
        );

        $this->pageVariantsValidator->validate($pageVariant);
    }

    /**
     * @param PageVariant $pageVariant
     * @param array $pageVariantData
     * @return void
     */
    private function updatePublishedAt(PageVariant $pageVariant, array $pageVariantData): void
    {
        // If page variant is being published for the first time, fill the
        // "published at" with current date & time.
        if (array_get($pageVariantData, 'status') === PageVariant::STATUS_PUBLISHED) {
            if (!$pageVariant->isPublished()) {
                $pageVariant->published_at = Carbon::now();
            }
        }
    }

    /**
     * @param PageVariant $pageVariant
     * @param string $routeUrl
     * @return void
     */
    private function updateRoute(PageVariant $pageVariant, string $routeUrl): void
    {
        if (isset($pageVariant->route)) {
            /**
             * Case #1: This PV has a route - we may update or delete it.
             */

            $oldRouteUrl = $pageVariant->route->url;

            if ($routeUrl !== $oldRouteUrl) {
                if (strlen($routeUrl) === 0) {
                    $pageVariant->setRelation('route', null);
                } else {
                    $newRoute = new Route([
                        'subdomain' => $pageVariant->language->slug,
                        'url' => $routeUrl,
                    ]);

                    $pageVariant->setRelation('route', $newRoute);
                }
            }
        } else {
            /**
             * Case #2: this PV does not have a route - we may create it.
             */

            if (strlen($routeUrl) > 0) {
                $newRoute = new Route([
                    'subdomain' => $pageVariant->language->slug,
                    'url' => $routeUrl,
                ]);

                $pageVariant->setRelation('route', $newRoute);
            }
        }
    }

    /**
     * @param PageVariant $pageVariant
     * @param int[] $tagIds
     * @return void
     *
     * @throws TagException
     */
    private function updateTags(PageVariant $pageVariant, array $tagIds): void
    {
        $tagIds = array_map('intval', $tagIds);

        // Step 1: remove tags present in the page variant, but not present
        // inside given tag ids
        $pageVariant->setRelation(
            'tags',
            $pageVariant->tags->whereIn('id', $tagIds)
        );

        // Step 2: add tags present in the given tag ids, but not present inside
        // the page variant
        foreach ($tagIds as $tagId) {
            // If page variant already contains this tag, skip it (we do not
            // want tags to be duplicated, after all)
            if ($pageVariant->tags->contains('id', $tagId)) {
                continue;
            }

            $pageVariant->tags->push(
                $this->tagsFacade->queryOne(
                    new GetTagByIdQuery($tagId)
                )
            );
        }
    }

}