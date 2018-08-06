<?php

namespace App\Core\Services\Searcher;

use App\Core\Exceptions\Exception as AppException;
use Illuminate\Support\Collection;

/**
 * This class provides a base for an Eloquent-based searcher service.
 * Contrary to the @see EloquentSearcher, yoy should inherit from this class.
 */
abstract class AbstractEloquentSearcher implements SearcherInterface
{

    /**
     * @var EloquentSearcher
     */
    protected $searcher;

    /**
     * @param EloquentSearcher $searcher
     */
    public function __construct(
        EloquentSearcher $searcher
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