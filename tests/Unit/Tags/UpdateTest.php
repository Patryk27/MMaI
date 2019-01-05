<?php

namespace Tests\Unit\Tags;

use App\Tags\Exceptions\TagException;

class UpdateTest extends TestCase {
    /**
     * This test checks basic functionality of the "update()" method.
     *
     * @return void
     * @throws TagException
     */
    public function testUpdate(): void {
        // Create some tag
        $tag = $this->tagsFacade->create([
            'language_id' => 100,
            'name' => 'some tag',
        ]);

        // Update its name
        $this->tagsFacade->update($tag, [
            'name' => 'some other tag',
        ]);

        // Re-load it
        $tag = $this->tagsRepository->getById($tag->id);

        // Make sure it was properly updated & persisted
        $this->assertEquals('some other tag', $tag->name);
    }

    /**
     * This test makes sure that the update() method does not try to change the
     * tag's language id, since it shouldn't be possible - by the definition -
     * to move tags between languages.
     *
     * @return void
     * @throws TagException
     */
    public function testDoesNotUpdateLanguageId(): void {
        // Create some tag
        $tag = $this->tagsFacade->create([
            'language_id' => 100,
            'name' => 'some tag',
        ]);

        // Update its language id;
        // No exception should be thrown, it's just a no-op.
        $this->tagsFacade->update($tag, [
            'language_id' => 150,
        ]);

        // Re-load it
        $tag = $this->tagsRepository->getById($tag->id);

        // Make sure it was not changed
        $this->assertEquals(100, $tag->language_id);
    }

    /**
     * This test makes sure that the update() method forbids to rename tag to a
     * name which is already taken.
     *
     * @return void
     * @throws TagException
     */
    public function testForbidsDuplicates(): void {
        $this->tagsFacade->create([
            'language_id' => 100,
            'name' => 'tag A',
        ]);

        $tagB = $this->tagsFacade->create([
            'language_id' => 100,
            'name' => 'tag B',
        ]);

        $this->expectExceptionMessage('Tag with such name already exists');

        $this->tagsFacade->update($tagB, [
            'name' => 'tag A',
        ]);
    }
}
