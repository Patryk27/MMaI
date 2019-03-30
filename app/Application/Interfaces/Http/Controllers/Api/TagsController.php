<?php

namespace App\Application\Interfaces\Http\Controllers\Api;

use App\Application\Http\Controllers\Controller;
use App\Tags\Models\Tag;
use App\Tags\Requests\CreateTag;
use App\Tags\Requests\UpdateTag;
use App\Tags\TagsFacade;
use Illuminate\Validation\ValidationException;

class TagsController extends Controller {

    /** @var TagsFacade */
    private $tagsFacade;

    public function __construct(TagsFacade $tagsFacade) {
        $this->tagsFacade = $tagsFacade;
    }

    /**
     * @param CreateTag $request
     * @return Tag
     * @throws ValidationException
     */
    public function store(CreateTag $request): Tag {
        return $this->tagsFacade->create($request);
    }

    /**
     * @param Tag $tag
     * @param UpdateTag $request
     * @return Tag
     * @throws ValidationException
     */
    public function update(Tag $tag, UpdateTag $request): Tag {
        $this->tagsFacade->update($tag, $request);

        return $tag;
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function destroy(Tag $tag): void {
        $this->tagsFacade->delete($tag);
    }

}
