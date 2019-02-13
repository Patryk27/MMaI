<?php

namespace Tests\Unit\Tags;

use App\Tags\Events\TagCreated;
use App\Tags\Events\TagUpdated;
use App\Tags\Requests\CreateTag;
use App\Tags\Requests\UpdateTag;
use Throwable;

class UpdateTest extends TestCase {

    /**
     * @return void
     * @throws Throwable
     */
    public function testUpdatesTagInTheDatabase(): void {
        $tag = $this->tagsFacade->create(new CreateTag([
            'website_id' => 100,
            'name' => 'foo',
        ]));

        $this->tagsFacade->update($tag, new UpdateTag([
            'name' => 'bar',
        ]));

        $tag = $this->tagsRepository->getById($tag->id);
        
        $this->assertEquals('bar', $tag->name);

        $this->eventsDispatcher->assertDispatched(TagCreated::class);
        $this->eventsDispatcher->assertDispatched(TagUpdated::class);
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function testForbidsToCreateDuplicateTagOnTheSameWebsite(): void {
        $foo = $this->tagsFacade->create(new CreateTag([
            'website_id' => 100,
            'name' => 'foo',
        ]));

        $bar = $this->tagsFacade->create(new CreateTag([
            'website_id' => 100,
            'name' => 'bar',
        ]));

        $this->expectExceptionMessage('The given data was invalid.');

        $this->tagsFacade->update($foo, new UpdateTag([
            'name' => $bar->name,
        ]));
    }

}
