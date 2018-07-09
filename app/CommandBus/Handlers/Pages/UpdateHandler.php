<?php

namespace App\CommandBus\Handlers\Pages;

use App\CommandBus\Commands\Pages\UpdateCommand;
use App\Exceptions\Exception as AppException;
use App\Models\PageVariant;
use App\Repositories\PagesRepository;
use App\Services\PageVariants\Creator as PageVariantsCreator;
use App\Services\PageVariants\Updater as PageVariantsUpdater;
use Illuminate\Database\Connection as DatabaseConnection;
use Throwable;

class UpdateHandler
{

    /**
     * @var DatabaseConnection
     */
    private $db;

    /**
     * @var PagesRepository
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
     * @param DatabaseConnection $db
     * @param PagesRepository $pagesRepository
     * @param PageVariantsCreator $pageVariantsCreator
     * @param PageVariantsUpdater $pageVariantsUpdater
     */
    public function __construct(
        DatabaseConnection $db,
        PagesRepository $pagesRepository,
        PageVariantsCreator $pageVariantsCreator,
        PageVariantsUpdater $pageVariantsUpdater
    ) {
        $this->db = $db;
        $this->pagesRepository = $pagesRepository;
        $this->pageVariantsCreator = $pageVariantsCreator;
        $this->pageVariantsUpdater = $pageVariantsUpdater;
    }

    /**
     * @param UpdateCommand $command
     * @return void
     *
     * @throws Throwable
     */
    public function handle(UpdateCommand $command): void
    {
        $page = $command->getPage();
        $data = $command->getData();

        foreach ($data['pageVariants'] as $pageVariantData) {
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

        $this->db->transaction(function () use ($page) {
            $this->pagesRepository->persist($page);
        });
    }

}
