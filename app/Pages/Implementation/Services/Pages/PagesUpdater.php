<?php

namespace App\Pages\Implementation\Services\Pages;

use App\Pages\Exceptions\PageException;
use App\Pages\Implementation\Repositories\PagesRepositoryInterface;
use App\Pages\Implementation\Services\PageVariants\PageVariantsCreator;
use App\Pages\Implementation\Services\PageVariants\PageVariantsUpdater;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Tags\Exceptions\TagException;

/**
 * @see \Tests\Unit\Pages\UpdateTest
 */
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
     * @throws PageException
     * @throws TagException
     */
    public function update(Page $page, array $pageData): void
    {
        foreach (array_get($pageData, 'pageVariants', []) as $pageVariantData) {
            $this->updatePageVariant($page, $pageVariantData);
        }

        $this->pagesRepository->persist($page);
    }

    /**
     * @param Page $page
     * @param array $pageVariantData
     * @return void
     *
     * @throws PageException
     * @throws TagException
     */
    private function updatePageVariant(Page $page, array $pageVariantData): void
    {
        if (array_has($pageVariantData, 'id')) {
            /**
             * @var PageVariant|null $pageVariant
             */
            $pageVariant = $page->pageVariants->firstWhere('id', $pageVariantData['id']);

            if (is_null($pageVariant)) {
                throw new PageException(
                    sprintf('Page variant [id=%d] was not found inside page [id=%d].', $pageVariantData['id'], $page->id)
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