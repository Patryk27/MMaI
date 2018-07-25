<?php

namespace App\Pages\Internal\Services\PageVariants;

use App\Exceptions\Exception as AppException;
use App\Models\Page;
use App\Models\PageVariant;
use App\Models\Route;

class PageVariantsCreator
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
     * @param Page $page
     * @param array $data
     * @return PageVariant
     *
     * @throws AppException
     */
    public function create(Page $page, array $data): PageVariant
    {
        $pageVariant = new PageVariant(
            array_only($data, ['language_id', 'status', 'title', 'lead', 'content'])
        );

        $pageVariant->setRelation('page', $page);

        // Create route
        $url = array_get($data, 'route');

        if (strlen($url) > 0) {
            $route = new Route([
                'url' => $url,
            ]);

            $pageVariant->setRelation('route', $route);
        }

        // Validate page variant
        $this->pageVariantsValidator->validate($pageVariant);

        return $pageVariant;
    }

}