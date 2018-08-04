<?php

namespace App\Pages\Implementation\Services\Pages;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Implementation\Repositories\PagesRepositoryInterface;
use App\Pages\Implementation\Services\PageVariants\PageVariantsCreator;
use App\Pages\Implementation\Services\PageVariants\PageVariantsUpdater;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;

class PagesUpdater
{

    /**
     * @var PagesRepositoryInterface
     */
    private $pagesRepository;

    /**
     * @var PageVariantsCreator
     */
    private $pageVariantsCreator;

    /**
     * @var PageVariantsUpdater
     */
    private $pageVariantsUpdater;

    /**
     * @param PagesRepositoryInterface $pagesRepository
     * @param PageVariantsCreator $pageVariantsCreator
     * @param PageVariantsUpdater $pageVariantsUpdater
     */
    public function __construct(
        PagesRepositoryInterface $pagesRepository,
        PageVariantsCreator $pageVariantsCreator,
        PageVariantsUpdater $pageVariantsUpdater
    ) {
        $this->pagesRepository = $pagesRepository;
        $this->pageVariantsCreator = $pageVariantsCreator;
        $this->pageVariantsUpdater = $pageVariantsUpdater;
    }

    /**
     * @param Page $page
     * @param array $pageData
     * @return void
     *
     * @throws AppException
     */
    public function update(Page $page, array $pageData): void
    {
        foreach ($pageData['pageVariants'] as $pageVariantData) {
            $this->updatePageVariant($page, $pageVariantData);
        }

        $this->pagesRepository->persist($page);
    }

    /**
     * @param Page $page
     * @param array $pageVariantData
     * @return void
     *
     * @throws AppException
     */
    private function updatePageVariant(Page $page, array $pageVariantData): void
    {
        if (array_has($pageVariantData, 'id')) {
            /**
             * @var PageVariant|null $pageVariant
             */
            $pageVariant = $page->pageVariants->firstWhere('id', $pageVariantData['id']);

            if (is_null($pageVariant)) {
                throw new AppException(
                    sprintf('Page variant with [id=%d] was not found inside page with [id=%d].', $pageVariantData['id'], $page->id)
                );
            }

            $this->pageVariantsUpdater->update($pageVariant, $pageVariantData);
        } else {
            $page->pageVariants->push(
                $this->pageVariantsCreator->create($page, $pageVariantData)
            );
        }
    }

}