<?php

namespace App\Repositories;

use App\CommandBus\Commands\Routes\DeleteCommand as DeleteRouteCommand;
use App\CommandBus\Commands\Routes\RerouteCommand as RerouteRouteCommand;
use App\Exceptions\RepositoryException;
use App\Models\Page;
use App\Models\PageVariant;
use App\Models\Route;
use Illuminate\Database\ConnectionInterface as DatabaseConnectionInterface;
use Joselfonseca\LaravelTactician\CommandBusInterface;
use LogicException;
use Throwable;

class PagesRepository
{

    /**
     * @var DatabaseConnectionInterface
     */
    private $db;

    /**
     * @var CommandBusInterface
     */
    private $commandBus;

    /**
     * @var GenericRepository
     */
    private $repository;

    /**
     * @param DatabaseConnectionInterface $db
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        DatabaseConnectionInterface $db,
        CommandBusInterface $commandBus
    ) {
        $this->db = $db;
        $this->commandBus = $commandBus;

        $this->repository = new GenericRepository(
            new Page()
        );
    }

    /**
     * Returns page with given id or `null` if no such page exists.
     *
     * @param int $id
     * @return Page|null
     */
    public function getById(int $id): ?Page
    {
        return $this->repository->getById($id);
    }

    /**
     * Returns page with given id or throws an exception if no such page exists.
     *
     * @param int $id
     * @return Page
     *
     * @throws RepositoryException
     */
    public function getByIdOrFail(int $id): Page
    {
        return $this->repository->getByIdOrFail($id);
    }

    /**
     * Saves given page in the database.
     *
     * @param Page $page
     * @return void
     *
     * @throws Throwable
     */
    public function persist(Page $page): void
    {
        $this->db->transaction(function () use ($page) {
            $page->saveOrFail();

            foreach ($page->pageVariants as $pageVariant) {
                $this->persistPageVariant($page, $pageVariant);
            }
        });
    }

    /**
     * Saves a single page variant.
     *
     * @param Page $page
     * @param PageVariant $pageVariant
     * @return void
     *
     * @throws Throwable
     */
    private function persistPageVariant(Page $page, PageVariant $pageVariant): void
    {
        $originalPageVariant = $pageVariant->fresh();

        $this->persistRoute(
            isset($originalPageVariant) ? $originalPageVariant->route : null,
            $pageVariant->route
        );

        $pageVariant->page()->associate($page);
        $pageVariant->saveOrFail();

        // @todo check if condition below is required
        if ($pageVariant->route) {
            $pageVariant->route->setPointsAt($pageVariant);
            $pageVariant->route->saveOrFail();
        }
    }

    /**
     * Saves page variant's route.
     *
     * @param Route|null $oldRoute
     * @param Route|null $newRoute
     * @return void
     *
     * @throws Throwable
     */
    private function persistRoute(?Route $oldRoute, ?Route $newRoute): void
    {
        if (!isset($newRoute) && isset($oldRoute)) {
            $this->commandBus->dispatch(
                new DeleteRouteCommand($oldRoute)
            );
        }

        if (isset($newRoute, $oldRoute)) {
            if ($newRoute->url !== $oldRoute->url) {
                // When someone updates route of a page variant, they should
                // create a new route's model and assign it to the page variant
                // instead of modifying the original instance.
                if ($newRoute->id === $oldRoute->id) {
                    throw new LogicException('Assertion failed.');
                }

                $this->commandBus->dispatch(
                    new RerouteRouteCommand($oldRoute, $newRoute)
                );
            }
        }
    }

}