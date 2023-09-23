<?php

namespace app\src;

abstract class Model
{
	public function loadData($data): void
	{
		foreach ($data as $key => $value) {
			if (property_exists($this, $key)) {
				$this->{$key} = $value;
			}
		}
	}

	public static function tableName(): string
	{
		$className = static::class;
		$classNameParts = explode('\\', $className);
		return strtolower(end($classNameParts) . 's');
	}
	public function attributes(): array
	{
		// TODO should contain only string properties
		return get_object_vars($this);
	}
	public static function primaryKey(): string
	{
		return 'id';
	}

	public function save(): void
	{
		$data = [];
		foreach ($this->attributes() as $key => $value) {
			$data[$this->attributes()[$key]] = $this->{$value};
		}
		DB::table(self::tableName())->insert($data);
	}

	public static function findOne($where)
	{
		return DB::table(self::tableName())
			->select()
			->where(array_keys($where)[0], '=', array_values($where)[0])
			->getFirst();
	}
}