<?php

namespace App\Languages\Queries;

final class GetLanguageById implements LanguagesQuery {

    /** @var int */
    private $id;

    public function __construct(int $id) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

}
