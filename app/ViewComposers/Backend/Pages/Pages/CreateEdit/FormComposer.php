<?php

namespace App\ViewComposers\Backend\Pages\Pages\CreateEdit;

use App\Repositories\LanguagesRepository;
use Illuminate\Contracts\View\View as ViewContract;

class FormComposer
{

    /**
     * @var LanguagesRepository
     */
    private $languagesRepository;

    /**
     * @param LanguagesRepository $languagesRepository
     */
    public function __construct(
        LanguagesRepository $languagesRepository
    ) {
        $this->languagesRepository = $languagesRepository;
    }

    /**
     * @param ViewContract $view
     * @return void
     */
    public function compose(ViewContract $view): void
    {
        $view->with([
            'languages' => $this->languagesRepository->getAll(),
        ]);
    }

}