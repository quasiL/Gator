<?php

namespace app\src;

use PDO;

class Database
{
	private PDO $pdo;
	private string $table;
	private array $fields;
	private array $createdFields = [];
	protected array $wheres = [];

	public function __construct(array $config)
	{
		$dsn = $config['dsn'] ?? '';
		$user = $config['user'] ?? '';
		$password = $config['password'] ?? '';
		$this->pdo = new PDO($dsn, $user, $password);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function table(string $table): static
	{
		$this->table = $table;
		return $this;
	}

	public function create(): void
	{
		$sql = 'CREATE ';
		$sql .= 'TABLE IF NOT EXISTS ' . $this->table . ' (';
		foreach ($this->createdFields as $field => $type) {
			$sql .= $field . ' ' . $type . ', ';
		}
		$sql = rtrim($sql, ', ');
		$sql .= ') ENGINE=INNODB;';
		$this->pdo->exec($sql);
		$this->createdFields = [];
		$this->flush();
	}

	public function drop(): void
	{
		$sql = 'DROP ';
		$sql .= 'TABLE IF EXISTS ' . $this->table;
		$this->pdo->exec($sql);
		$this->flush();
	}

	public function dropColumn(string $columnName): void
	{
		$sql = 'ALTER ';
		$sql .= 'TABLE ' . $this->table . ' DROP COLUMN ' . $columnName . ';';
		$this->pdo->exec($sql);
		$this->flush();
	}

	public function modify(): void
	{
		$sql = 'ALTER ';
		$sql .= 'TABLE ' . $this->table . ' ADD ';
		foreach ($this->createdFields as $field => $type) {
			$sql .= $field . ' ' . $type . ', ';
		}
		$sql = rtrim($sql, ', ');
		$sql .= ';';
		$this->pdo->exec($sql);
		$this->flush();
	}

	public function id(): static
	{
		$this->createdFields['id'] = 'INT AUTO_INCREMENT PRIMARY KEY';
		return $this;
	}

	public function notNull(): static
	{
		$array = array_keys($this->createdFields);
		$field = end($array);
		$this->createdFields[$field] .= ' NOT NULL';
		return $this;
	}

	public function default(string $defaultValue): static
	{
		$array = array_keys($this->createdFields);
		$field = end($array);
		$this->createdFields[$field] .= ' DEFAULT ' . $defaultValue;
		return $this;
	}

	public function int(string $columnName): static
	{
		$this->createdFields[$columnName] = 'INT';
		return $this;
	}

	public function string(string $columnName): static
	{
		$this->createdFields[$columnName] = 'VARCHAR(255)';
		return $this;
	}

	public function timestamp(string $columnName): static
	{
		$this->createdFields[$columnName] = 'TIMESTAMP';
		return $this;
	}

	public function insert(array $data): void
	{
		$fields = array_keys($data);
		$params = array_map(static fn($field) => ":$field", $fields);

		$sql = 'INSERT ';
		$sql .= 'INTO ' . $this->table . ' (' . implode(',', $fields) . ') ';
		$sql .= 'VALUES (' . implode(',', $params) . ');';

		$statement = $this->pdo->prepare($sql);
		foreach ($fields as $field) {
			$statement->bindValue(":$field", $data[$field]);
		}
		$statement->execute();
		$this->flush();
	}

	public function select(array $fields = ['*']): static
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

	public function orWhere(string $column, string $operator, string $value): static
	{
		$this->wheres[] = [
			'type' => ' OR',
			'column' => $column,
			'operator' => $operator,
			'value' => $value
		];
		return $this;
	}

	public function andWhere(string $column, string $operator, string $value): static
	{
		$this->wheres[] = [
			'type' => ' AND',
			'column' => $column,
			'operator' => $operator,
			'value' => $value
		];
		return $this;
	}

	public function getAll($mode = PDO::FETCH_ASSOC): ?array
	{
		$statement = $this->pdo->prepare($this->getSelect());
		$boundValues = array_column($this->wheres, 'value');
		$statement->execute($boundValues);
		$this->flush();
		return $statement->fetchAll($mode);
	}

	public function getFirst()
	{
		$statement = $this->pdo->prepare($this->getSelect());
		$boundValues = array_column($this->wheres, 'value');
		$statement->execute($boundValues);
		$class = substr_replace(ucfirst($this->table) ,"", -1);
		$record = $statement->fetchObject("\app\models\\" . $class);

		$this->flush();
		if (!$record) {
			return null;
		}
		return $record;
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

	private function flush(): void
	{
		$this->fields = [];
		$this->wheres = [];
		$this->createdFields = [];
	}
}