<?php

namespace Tests\Unit\Tags;

use App\Tags\Events\TagCreated;
use App\Tags\Models\Tag;
use App\Tags\Requests\CreateTag;
use Throwable;

class CreateTest extends TestCase {

    /**
     * @return void
     * @throws Throwable
     */
    public function testCreatesTagInTheDatabase(): void {
        $this->tagsFacade->create(new CreateTag([
            'website_id' => 100,
            'name' => 'foo',
        ]));

        $this->assertCount(1, $this->tagsRepository->getAll());

        /** @var Tag $tag */
        $tag = $this->tagsRepository->getAll()->first();

        $this->assertEquals(100, $tag->website_id);
        $this->assertEquals('foo', $tag->name);

        $this->eventsDispatcher->assertDispatched(TagCreated::class);
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function testForbidsToCreateDuplicateTagOnTheSameWebsite(): void {
        $this->tagsFacade->create(new CreateTag([
            'website_id' => 100,
            'name' => 'foo',
        ]));

        $this->tagsFacade->create(new CreateTag([
            'website_id' => 200,
            'name' => 'foo',
        ]));

        $this->expectExceptionMessage('The given data was invalid.');

        $this->tagsFacade->create(new CreateTag([
            'website_id' => 200,
            'name' => 'foo',
        ]));
    }

}
