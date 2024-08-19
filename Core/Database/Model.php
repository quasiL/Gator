<?php

namespace Gator\Core\Database;

abstract class Model
{
    private static array $data = [];

    protected function __construct()
    {}

    public static function getTableName(): string
    {
        $className = static::class;
        $classNameParts = explode('\\', $className);
        return strtolower(end($classNameParts) . 's');
    }

    public static function create(array $data = []): static
    {
        self::$data = $data;
        return new static();
    }

    public static function persist(): void
    {
        Burt::table(self::getTableName())->insert(self::$data);
    }
}