<?php

namespace App\Services\Core\Searcher;

use App\Exceptions\Exception as AppException;
use Illuminate\Support\Collection;

/**
 * This is an abstract searcher used to facilitate creating searcher services by
 * providing basic implementations for methods required to create such service.
 *
 * Contrary to the @see GenericSearcher, you should inherit from this one.
 */
abstract class AbstractSearcher implements SearcherInterface
{

    /**
     * @var GenericSearcher
     */
    protected $searcher;

    /**
     * @param GenericSearcher $searcher
     */
    public function __construct(
        GenericSearcher $searcher
    ) {
        $this->searcher = $searcher;
    }

    /**
     * @inheritDoc
     *
     * @throws AppException
     */
    public function orderBy(string $field, bool $ascending): void
    {
        $this->searcher->orderBy($field, $ascending);
    }

    /**
     * @inheritDoc
     */
    public function forPage(int $page, int $perPage): void
    {
        $this->searcher->forPage($page, $perPage);
    }

    /**
     * @inheritDoc
     */
    public function get(): Collection
    {
        return $this->searcher->get();
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        return $this->searcher->getCount();
    }

}