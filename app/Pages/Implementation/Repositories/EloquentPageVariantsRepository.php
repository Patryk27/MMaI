<?php

namespace App\Pages\Implementation\Repositories;

use App\Core\Repositories\EloquentRepository;
use App\Pages\Models\PageVariant;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;

class EloquentPageVariantsRepository implements PageVariantsRepositoryInterface
{

    /**
     * @var EloquentRepository
     */
    private $repository;

    /**
     * @param PageVariant $pageVariant
     */
    public function __construct(
        PageVariant $pageVariant
    ) {
        $this->repository = new EloquentRepository($pageVariant);
    }

    /**
     * @inheritDoc
     */
    public function getByIds(array $ids): Collection
    {
        if (empty($ids)) {
            return new Collection();
        }

        $ids = array_map('intval', $ids);

        $stmt = $this->repository->newQuery();
        $stmt
            ->whereIn('id', $ids)
            ->orderByRaw(
                sprintf('field(id, %s)', implode(',', $ids))
            );

        return $stmt->get();
    }

    /**
     * @inheritDoc
     */
    public function getByTagId(int $tagId): Collection
    {
        return $this->repository->newQuery()
            ->whereHas('tags', function (EloquentBuilder $query) use ($tagId): void {
                $query->where('tags.id', $tagId);
            })
            ->get();
    }

}
