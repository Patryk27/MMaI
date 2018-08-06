<?php

namespace App\App\ViewComposers\Backend\Pages\Pages\CreateEdit;

use App\Languages\LanguagesFacade;
use App\Languages\Queries\GetAllLanguagesQuery;
use Illuminate\Contracts\View\View as ViewContract;

class FormComposer
{

    /**
     * @var LanguagesFacade
     */
    private $languagesFacade;

    /**
     * @param LanguagesFacade $languagesFacade
     */
    public function __construct(
        LanguagesFacade $languagesFacade
    ) {
        $this->languagesFacade = $languagesFacade;
    }

    /**
     * @param ViewContract $view
     * @return void
     */
    public function compose(ViewContract $view): void
    {
        $view->with([
            'languages' => $this->languagesFacade->queryMany(
                new GetAllLanguagesQuery()
            ),
        ]);
    }

}