<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\PageVariant;
use App\Routes\Models\Route;
use Carbon\Carbon;

class PageVariantsUpdater
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
     * @param PageVariant $pageVariant
     * @param array $pageVariantData
     * @return void
     *
     * @throws AppException
     */
    public function update(PageVariant $pageVariant, array $pageVariantData): void
    {
        $this->updatePublishedAt($pageVariant, $pageVariantData);
        $this->updateRoute($pageVariant, $pageVariantData);

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
     * @param array $pageVariantData
     * @return void
     */
    private function updateRoute(PageVariant $pageVariant, array $pageVariantData): void
    {
        if (isset($pageVariant->route)) {
            /**
             * Case #1: This PV has a route - we may update or delete it.
             */

            $oldUrl = $pageVariant->route->url;
            $newUrl = array_get($pageVariantData, 'route', '');

            if ($newUrl !== $oldUrl) {
                if (strlen($newUrl) === 0) {
                    $pageVariant->setRelation('route', null);
                } else {
                    $newRoute = new Route([
                        'url' => $newUrl,
                    ]);

                    $pageVariant->setRelation('route', $newRoute);
                }
            }
        } else {
            /**
             * Case #2: this PV does not have a route - we may create it.
             */

            $url = array_get($pageVariantData, 'route', '');

            if (strlen($url) > 0) {
                $newRoute = new Route([
                    'url' => $url,
                ]);

                $pageVariant->setRelation('route', $newRoute);
            }
        }
    }

}