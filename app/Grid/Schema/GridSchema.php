<?php

namespace App\Grid\Schema;

use App\Core\ValueObjects\HasInitializationConstructor;
use Illuminate\Contracts\Support\Arrayable;

final class GridSchema implements Arrayable {

    use HasInitializationConstructor;

    /** @var GridSchemaField[] */
    private $fields;

    /**
     * @return GridSchemaField[]
     */
    public function getFields(): array {
        return $this->fields;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() {
        return [
            'fields' => array_map(function (GridSchemaField $field): array {
                return $field->toArray();
            }, $this->fields),
        ];
    }

}
