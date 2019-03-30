<?php

namespace App\Grid\Schema;

final class GridSchemaFieldType {

    public const
        TYPE_ASSOCIATION = 'association',
        TYPE_DATETIME = 'datetime',
        TYPE_ENUM = 'enum',
        TYPE_INTEGER = 'integer',
        TYPE_STRING = 'string';

    /** @var string */
    private $type;

    private function __construct(string $type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @return self
     */
    public static function association(): self {
        return new self(self::TYPE_ASSOCIATION);
    }

    /**
     * @return self
     */
    public static function dateTime(): self {
        return new self(self::TYPE_DATETIME);
    }

    /**
     * @return self
     */
    public static function enum(): self {
        return new self(self::TYPE_ENUM);
    }

    /**
     * @return self
     */
    public static function integer(): self {
        return new self(self::TYPE_INTEGER);
    }

    /**
     * @return self
     */
    public static function string(): self {
        return new self(self::TYPE_STRING);
    }

}
