<?php

namespace App\Services\PageVariants;

use App\Exceptions\Exception as AppException;
use App\Models\PageVariant;
use App\Models\Route;
use Carbon\Carbon;

class Updater
{

    /**
     * @var Validator
     */
    private $pageVariantsValidator;

    /**
     * @param Validator $pageVariantsValidator
     */
    public function __construct(
        Validator $pageVariantsValidator
    ) {
        $this->pageVariantsValidator = $pageVariantsValidator;
    }

    /**
     * @param PageVariant $pageVariant
     * @param array $data
     * @return void
     *
     * @throws AppException
     */
    public function update(PageVariant $pageVariant, array $data): void
    {
        $this->updatePublishedAt($pageVariant, $data);
        $this->updateRoute($pageVariant, $data);

        $pageVariant->fill(
            array_only($data, ['status', 'title', 'lead', 'content'])
        );

        $this->pageVariantsValidator->validate($pageVariant);
    }

    /**
     * @param PageVariant $pageVariant
     * @param array $data
     * @return void
     */
    private function updatePublishedAt(PageVariant $pageVariant, array $data): void
    {
        // If page variant is being published for the first time, fill the
        // "published at" with current date & time.
        if (array_get($data, 'status') === PageVariant::STATUS_PUBLISHED) {
            if (!$pageVariant->isPublished()) {
                $pageVariant->published_at = Carbon::now();
            }
        }
    }

    /**
     * @param PageVariant $pageVariant
     * @param array $data
     * @return void
     */
    private function updateRoute(PageVariant $pageVariant, array $data): void
    {
        if (isset($pageVariant->route)) {
            /**
             * Case #1: This PV has a route - we have to update or delete it.
             */

            $oldUrl = $pageVariant->route->url;
            $newUrl = array_get($data, 'route', '');

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
             * Case #2: this PV does not have a route - we have to create it.
             */

            $url = array_get($data, 'route', '');

            if (strlen($url) > 0) {
                $route = new Route([
                    'url' => $url,
                ]);

                $pageVariant->setRelation('route', $route);
            }
        }
    }

}