<?php

namespace app\src;

use PDO;

class Database
{
	private PDO $pdo;
	private string $table;
	private array $fields;
	protected array $wheres = [];

	public function __construct(array $config)
	{
		$dsn = $config['dsn'] ?? '';
		$user = $config['user'] ?? '';
		$password = $config['password'] ?? '';
		$this->pdo = new PDO($dsn, $user, $password);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function table($table): static
	{
		$this->table = $table;
		return $this;
	}

	public function select($fields = ['*']): static
	{
		empty($fields) ? $this->fields =  ['*'] : $this->fields = $fields;
		return $this;
	}

	public function where($column, $operator, $value): static
	{
		$this->wheres[] = [
			'type' => 'AND',
			'column' => $column,
			'operator' => $operator,
			'value' => $value
		];
		return $this;
	}

	public function orWhere($column, $operator, $value): static
	{
		$this->wheres[] = [
			'type' => ' OR',
			'column' => $column,
			'operator' => $operator,
			'value' => $value
		];
		return $this;
	}

	public function andWhere($column, $operator, $value): static
	{
		$this->wheres[] = [
			'type' => ' AND',
			'column' => $column,
			'operator' => $operator,
			'value' => $value
		];
		return $this;
	}

	public function getAll(): ?array
	{
		$statement = $this->pdo->prepare($this->getSelect());
		$boundValues = array_column($this->wheres, 'value');
		$statement->execute($boundValues);
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getFirst(): ?array
	{
		$statement = $this->pdo->prepare($this->getSelect());
		$boundValues = array_column($this->wheres, 'value');
		$statement->execute($boundValues);
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	private function getSelect(): string
	{
		$sql = 'SELECT ' . implode(',', $this->fields) . ' FROM ' . $this->table;

		if (!empty($this->wheres)) {
			$sql .= ' WHERE ';
			foreach ($this->wheres as $index => $where) {
				if ($index > 0) {
					$sql .= $where['type'] . ' ';
				}
				$sql .= $where['column'] . ' ' . $where['operator'] . ' ' . '?';
			}
			$sql .= ';';
		}
		return $sql;
	}
}