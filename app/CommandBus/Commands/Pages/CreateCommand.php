<?php

namespace App\CommandBus\Commands\Pages;

class CreateCommand
{

    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(
        array $data
    ) {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

}