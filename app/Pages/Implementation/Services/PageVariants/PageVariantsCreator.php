<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Routes\Models\Route;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

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
     * @param array $data
     * @return PageVariant
     *
     * @throws AppException
     */
    public function create(Page $page, array $data): PageVariant
    {
        $pageVariant = new PageVariant();
        $pageVariant->setRelations([
            'page' => $page,
            'route' => null,
            'tags' => new EloquentCollection(),
        ]);

        $pageVariant->fill(
            array_only($data, ['language_id', 'status', 'title', 'lead', 'content'])
        );

        $this->createRoute($pageVariant, $data);

        $this->pageVariantsValidator->validate($pageVariant);

        return $pageVariant;
    }

    /**
     * @param PageVariant $pageVariant
     * @param array $data
     * @return void
     */
    private function createRoute(PageVariant $pageVariant, array $data): void
    {
        $url = array_get($data, 'route');

        if (strlen($url) > 0) {
            $route = new Route([
                'url' => $url,
            ]);

            $pageVariant->setRelation('route', $route);
        }
    }

}