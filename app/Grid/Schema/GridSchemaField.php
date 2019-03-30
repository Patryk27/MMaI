<?php

namespace App\Grid\Schema;

use App\Core\ValueObjects\HasInitializationConstructor;
use Illuminate\Contracts\Support\Arrayable;

final class GridSchemaField implements Arrayable {

    use HasInitializationConstructor;

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var GridSchemaFieldType */
    private $type;

    /** @var array */
    private $values = [];

    /** @var bool */
    private $sortable = false;

    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return GridSchemaFieldType
     */
    public function getType(): GridSchemaFieldType {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getValues(): array {
        return $this->values;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool {
        return $this->sortable;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->getType(),
            'values' => $this->values,
            'sortable' => $this->sortable,
        ];
    }

}
