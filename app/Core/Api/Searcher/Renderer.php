<?php

namespace App\Core\Api\Searcher;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use LogicException;

final class Renderer
{
    /** @var ViewFactory */
    private $viewFactory;

    /** @var View[] */
    private $columns = [];

    public function __construct(ViewFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * @param string $columnName
     * @param string $viewName
     * @return void
     */
    public function addColumn(string $columnName, string $viewName): void
    {
        if (array_has($this->columns, $columnName)) {
            throw new LogicException(sprintf(
                'Column [%s] already exists.', $columnName
            ));
        }

        $this->columns[$columnName] = $this->viewFactory->make($viewName);
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function render($row): array
    {
        $result = [];

        foreach ($this->columns as $column) {
            $result[] = $column
                ->with('row', $row)
                ->render();
        }

        return $result;
    }
}
