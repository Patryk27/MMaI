<?php

namespace App\Services\InternalPages\Renderers;

use App\Repositories\LanguagesRepository;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;

class SearchRenderer implements RendererInterface
{

    /**
     * @var ViewFactoryContract
     */
    private $viewFactory;

    /**
     * @var LanguagesRepository
     */
    private $languagesRepository;

    /**
     * @param ViewFactoryContract $viewFactory
     * @param LanguagesRepository $languagesRepository
     */
    public function __construct(
        ViewFactoryContract $viewFactory,
        LanguagesRepository $languagesRepository
    ) {
        $this->viewFactory = $viewFactory;
        $this->languagesRepository = $languagesRepository;
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $view = $this->viewFactory->make('frontend.internal-pages.search');

        $view->with([
            'languages' => $this->languagesRepository->getAll(),
        ]);

        return $view->render();
    }

}