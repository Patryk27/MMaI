<?php

namespace App\IntrinsicPages\Implementation\Services\Renderers;

use Illuminate\Contracts\View\Factory as ViewFactoryContract;

class SearchRenderer implements RendererInterface
{

    /**
     * @var ViewFactoryContract
     */
    private $viewFactory;

    /**
     * @param ViewFactoryContract $viewFactory
     */
    public function __construct(
        ViewFactoryContract $viewFactory
    ) {
        $this->viewFactory = $viewFactory;
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        unimplemented();

        $view = $this->viewFactory->make('frontend.intrinsic-pages.search');

        $view->with([
            'languages' => $this->languagesRepository->getAll(),
        ]);

        return $view->render();
    }

}