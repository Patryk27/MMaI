<?php

namespace App\CommandBus\Commands\Pages;

use App\Models\Page;

class UpdateCommand
{

    /**
     * @var Page
     */
    private $page;

    /**
     * @var array
     */
    private $data;

    /**
     * @param Page $page
     * @param array $data
     */
    public function __construct(
        Page $page,
        array $data
    ) {
        $this->page = $page;
        $this->data = $data;
    }

    /**
     * @return Page
     */
    public function getPage(): Page
    {
        return $this->page;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

}