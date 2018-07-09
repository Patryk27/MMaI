<?php

namespace App\Services\Core\Language;

use App\Exceptions\Exception as AppException;
use App\Exceptions\RepositoryException as AppRepositoryException;
use App\Models\Language;
use App\Repositories\RoutesRepository;
use Illuminate\Http\Request;

class Detector
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var RoutesRepository
     */
    private $routesRepository;

    /**
     * @param Request $request
     * @param RoutesRepository $routesRepository
     */
    public function __construct(
        Request $request,
        RoutesRepository $routesRepository
    ) {
        $this->request = $request;
        $this->routesRepository = $routesRepository;
    }

    /**
     * @inheritdoc
     *
     * @throws AppRepositoryException
     */
    public function getLanguage(): ?Language
    {
        $url = $this->request->route('url');

        if (empty($url)) {
            return null;
        }

        $route = $this->routesRepository->getByUrlOrFail($url);

        /** @noinspection PhpUndefinedFieldInspection */
        return $route->model->language ?? null;
    }

    /**
     * @inheritdoc
     *
     * @throws AppException
     */
    public function getLanguageOrFail(): Language
    {
        $language = $this->getLanguage();

        if (is_null($language)) {
            throw new AppException(
                sprintf('Failed to detect language for URL [%s].', $this->request->url())
            );
        }

        return $language;
    }

}