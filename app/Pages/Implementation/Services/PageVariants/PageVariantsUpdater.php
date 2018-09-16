<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\PageVariant;
use App\Routes\Models\Route;
use App\Tags\Exceptions\TagException;
use App\Tags\Queries\GetTagByIdQuery;
use App\Tags\TagsFacade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

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
                        'subdomain' => $pageVariant->language->slug ?? '',
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
                    'subdomain' => $pageVariant->language->slug ?? '',
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
        $pageVariant->setRelation('tags', new EloquentCollection());

        foreach ($tagIds as $tagId) {
            $tag = $this->tagsFacade->queryOne(
                new GetTagByIdQuery($tagId)
            );

            $pageVariant->tags->push($tag);
        }
    }

}
