<?php

namespace App\CommandBus\Handlers\Pages;

use App\CommandBus\Commands\Pages\CreateCommand;
use App\Models\Page;
use App\Repositories\PagesRepository;
use App\Services\PageVariants\Creator as PageVariantsCreator;
use Illuminate\Database\Connection as DatabaseConnection;
use Throwable;

class CreateHandler
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
     * @param DatabaseConnection $db
     * @param PagesRepository $pagesRepository
     * @param PageVariantsCreator $pageVariantsCreator
     */
    public function __construct(
        DatabaseConnection $db,
        PagesRepository $pagesRepository,
        PageVariantsCreator $pageVariantsCreator
    ) {
        $this->db = $db;
        $this->pagesRepository = $pagesRepository;
        $this->pageVariantsCreator = $pageVariantsCreator;
    }

    /**
     * @param CreateCommand $command
     * @return Page
     *
     * @throws Throwable
     */
    public function handle(CreateCommand $command): Page
    {
        $data = $command->getData();

        $page = new Page([
            'type' => array_get($data, 'page.type'),
        ]);

        foreach ($data['pageVariants'] as $pageVariantData) {
            $page->pageVariants->push(
                $this->pageVariantsCreator->create($page, $pageVariantData)
            );
        }

        $this->pagesRepository->persist($page);

        return $page;
    }

}
