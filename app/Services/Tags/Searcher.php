<?php

namespace App\Services\Tags;

use App\Exceptions\Exception as AppException;
use App\Models\Tag;
use App\Services\Core\Searcher\AbstractSearcher;
use App\Services\Core\Searcher\GenericSearcher;
use App\Services\Core\Searcher\SearcherInterface;

class Searcher extends AbstractSearcher implements SearcherInterface
{

    public const
        FIELD_ID = 'id',
        FIELD_NAME = 'name',

        FIELD_LANGUAGE_ID = 'language_id',
        FIELD_LANGUAGE_NAME = 'language_name';

    private const FIELDS_MAP = [
        self::FIELD_ID => 'tags.id',
        self::FIELD_NAME => 'tags.name',

        self::FIELD_LANGUAGE_ID => 'languages.id',
        self::FIELD_LANGUAGE_NAME => 'languages.name',
    ];

    /**
     * @param Tag $tag
     */
    public function __construct(
        Tag $tag
    ) {
        parent::__construct(
            new GenericSearcher($tag, self::FIELDS_MAP)
        );

        $builder = $this->searcher->getBuilder();
        $builder->selectRaw('tags.*');

        // Include languages
        $builder->join('languages', 'languages.id', 'tags.language_id');
    }

    /**
     * @inheritDoc
     *
     * @throws AppException
     */
    public function search(string $search): void
    {
        $this->searcher->search($search, [
            self::FIELD_NAME,
        ]);
    }

    /**
     * @inheritDoc
     *
     * @throws AppException
     */
    public function filter(array $fields): void
    {
        $this->searcher->filter($fields, [
            self::FIELD_ID => GenericSearcher::FILTER_OP_EQUAL,
            self::FIELD_NAME => GenericSearcher::FILTER_OP_LIKE,

            self::FIELD_LANGUAGE_ID => GenericSearcher::FILTER_OP_EQUAL,
            self::FIELD_LANGUAGE_NAME => GenericSearcher::FILTER_OP_LIKE,
        ]);
    }

}