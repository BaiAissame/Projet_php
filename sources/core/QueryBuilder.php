<?php

require_once __DIR__ . "/Database.php"; 

class QueryBuilder
{
    private PDO $pdo;
    private string $sql;
    private array $bindings;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->sql = "";
        $this->bindings = [];
    }

    public function select(array $columns = ['*']): self
    {
        $this->sql = "SELECT " . implode(", ", $columns);
        return $this;
    }

    public function from(string $tableName): self
    {
        $this->sql .= " FROM " . $tableName;
        return $this;
    }

    public function where(string $column, $value): self
    {
        $param = ":where_" . $column;
        $this->sql .= empty($this->bindings) ? " WHERE " : " AND ";
        $this->sql .= "$column = $param";
        $this->bindings[$param] = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->sql .= " ORDER BY $column $direction";
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->sql .= " LIMIT $limit";
        return $this;
    }

    public function insert(string $table, array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $this->sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->bindings = array_combine(
            explode(", ", $placeholders),
            array_values($data)
        );

        return $this->execute();
    }

    public function update(string $table, array $data): self
    {
        $setClauses = [];
        foreach ($data as $column => $value) {
            $param = ":update_$column";
            $setClauses[] = "$column = $param";
            $this->bindings[$param] = $value;
        }

        $this->sql = "UPDATE $table SET " . implode(", ", $setClauses);
        return $this;
    }

    public function delete(string $table): self
    {
        $this->sql = "DELETE FROM $table";
        return $this;
    }

    public function execute(): bool
    {
        $stmt = $this->pdo->prepare($this->sql);
        return $stmt->execute($this->bindings);
    }

    public function fetch(): ?array
    {
        $stmt = $this->pdo->prepare($this->sql);
        $stmt->execute($this->bindings);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function fetchAll(): array
    {
        $stmt = $this->pdo->prepare($this->sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
