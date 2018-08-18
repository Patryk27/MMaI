<?php

namespace Tests\Unit\Tags;

use App\Core\Exceptions\Exception as AppException;
use App\Tags\Models\Tag;

class CreateTest extends TestCase
{

    /**
     * This test makes sure that the create() method saves tag in the database.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testCreate(): void
    {
        // Make sure repository is empty when we're starting
        $this->assertCount(0, $this->tagsRepository->getAll());

        // Create the tag
        $this->tagsFacade->create([
            'language_id' => 100,
            'name' => 'something',
        ]);

        // Make sure repository contains that tag
        $this->assertCount(1, $this->tagsRepository->getAll());

        /**
         * @var Tag $tag
         */
        $tag = $this->tagsRepository
            ->getAll()
            ->first();

        $this->assertEquals(100, $tag->language_id);
        $this->assertEquals('something', $tag->name);
    }

    /**
     * This test makes sure it is not possible to create a tag with a duplicated
     * name in the same language.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testForbidsDuplicates(): void
    {
        $this->tagsFacade->create([
            'language_id' => 100,
            'name' => 'foo',
        ]);

        $this->tagsFacade->create([
            'language_id' => 200,
            'name' => 'foo',
        ]);

        $this->expectExceptionMessage('Tag with such name already exists.');

        $this->tagsFacade->create([
            'language_id' => 200,
            'name' => 'foo',
        ]);
    }

}