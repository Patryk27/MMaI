<?php

namespace App\Tags\Events;

use App\Tags\Models\Tag;
use Illuminate\Queue\SerializesModels;

final class TagCreated
{
    use SerializesModels;

    /** @var Tag */
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return Tag
     */
    public function getTag(): Tag
    {
        return $this->tag;
    }
}
