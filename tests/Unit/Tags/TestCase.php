<?php

namespace Tests\Unit\Tags;

use App\Core\Repositories\InMemoryRepository;
use App\Tags\Implementation\Repositories\InMemoryTagsRepository;
use App\Tags\Implementation\Services\Searcher\InMemoryTagsSearcher;
use App\Tags\TagsFacade;
use App\Tags\TagsFactory;
use Tests\Unit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    /**
     * @var InMemoryTagsRepository
     */
    protected $tagsRepository;

    /**
     * @var TagsFacade
     */
    protected $tagsFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tagsRepository = new InMemoryTagsRepository(
            new InMemoryRepository()
        );

        $tagsSearcher = new InMemoryTagsSearcher();

        $this->tagsFacade = TagsFactory::build($this->tagsRepository, $tagsSearcher);
    }

}