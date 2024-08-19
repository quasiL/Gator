<?php

namespace Gator\Core\Database;

use Gator\Core\Application;
use InvalidArgumentException;
use PDO;
use PDOException;

class DBHandler
{
    private ?PDO $pdo = null;
    private string $table;
    private array $fields;
    private array $createdFields = [];
    protected array $wheres = [];

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';;
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        try {
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
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
        try {
            $this->pdo->exec($sql);
            $this->flush();
        } catch (PDOException $e) {
            echo "Error creating table: " . $e->getMessage();
        }
    }

    public function drop(): void
    {
        $sql = 'DROP ';
        $sql .= 'TABLE IF EXISTS ' . $this->table;
        try {
            $this->pdo->exec($sql);
            $this->flush();
        } catch (PDOException $e) {
            echo "Error dropping table: " . $e->getMessage();
        }
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

    public function string(string $columnName, int $length = 255): static
    {
        $this->createdFields[$columnName] = "VARCHAR($length)";
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

    public function delete(): void
    {
        if (empty($this->wheres)) {
            throw new InvalidArgumentException("No WHERE conditions specified for DELETE.");
        }

        $sql = 'DELETE FROM ' . $this->table;

        $conditions = [];
        $values = [];
        foreach ($this->wheres as $where) {
            $conditions[] = $where['column'] . ' ' . $where['operator'] . ' :value' . count($values);
            $values[':value' . count($values)] = $where['value'];
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        try {
            $stmt = $this->pdo->prepare($sql);

            foreach ($values as $param => $value) {
                $stmt->bindValue($param, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }

            $stmt->execute();
            $this->flush();
        } catch (PDOException $e) {
            echo "Error deleting from table: " . $e->getMessage();
        }
    }

    public function select(array $fields = ['*']): static
    {
        empty($fields) ? $this->fields =  ['*'] : $this->fields = $fields;
        return $this;
    }

    public function where(string $column, string $operator, string $value): static
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
        return $this->where($column, $operator, $value);
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

    public function getTableColumns(): array
    {
        $sql = "DESCRIBE " . $this->table;
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
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

    public function createAuthTables(): void
    {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sqlFilePath = Application::$rootPath . '/init.sql';
            $sql = file_get_contents($sqlFilePath);
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            echo "Error creating Auth tables: " . $e->getMessage();
        }
    }
}