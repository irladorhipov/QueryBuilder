<?php

namespace App;

use App\helpers\QueryBuilderHelpers;
use App\Connection;
use Exception; 

class QueryBuilder implements QueryBuilderInterface
{   
    private string $sql;
    private object $connection;
    private bool $startMethodFlag = false;

    public function __construct(array $config)
    {
        $this->connection = new Connection($config);
    }

    // проверяем выбран ли стартовый метод для того чтобы можно было строить запрос
    private function checkStartMethod() {
        if ($this->startMethodFlag) {
            return true;
        } else {
            throw new Exception("Method `select(), delete(), update()` should be called before");
        }
    }

    public function select(array $columns = []): QueryBuilderInterface
    {
        $this->startMethodFlag = true;
        $this->sql = "SELECT " . QueryBuilderHelpers::selectColumns($columns);
        return $this;
    }

    public function from(string $table): QueryBuilderInterface
    {
        if ($this->checkStartMethod()) {
            $this->sql .= " FROM $table";
            return $this;
        }
    }
 
    public function where(string $column, string $operator, $value):QueryBuilderInterface
    {
        if ($this->checkStartMethod()) {
            $this->sql .= " WHERE $column $operator " . QueryBuilderHelpers::formatValue($value);
            return $this;
        }
    }

    public function OrderBy(string $column, $attr = ''): QueryBuilderInterface {
        if ($this->checkStartMethod()) {
            $this->sql .= " ORDER BY $column $attr"; 
            return $this;
        }
    }

    public function limit(int $limit): QueryBuilderInterface {
        if ($this->checkStartMethod()) {
            $this->sql .= " LIMIT $limit";
            return $this;
        }
    }

    public function insert(string $table, array $data): QueryBuilderInterface
    {
        $columns = QueryBuilderHelpers::getColumns($data);

        $values = [];
        
        foreach ($data as $value) {
            $values[] = QueryBuilderHelpers::formatValue($value);
        }

        $values = implode(',', $values);

        $this->sql = "INSERT INTO $table ($columns) VALUES ($values)";
        return $this;
    }

    public function delete(): QueryBuilderInterface {
        $this->startMethodFlag = true;
        $this->sql = "DELETE ";
        return $this;
    }

    public function update(string $table): QueryBuilderInterface {
        $this->startMethodFlag = true;
        $this->sql = "UPDATE $table";
        return $this;
    }

    public function set(string $column, mixed $value): QueryBuilderInterface {
        $this->sql .= " SET $column  = '$value'";
        return $this; 
    }

    // после каждого выполнения запроса возвращаем startMethodFlag в исходное состояние 
    public function execute()
    {
        $pdo = $this->connection->getPDO();
        $prepare = $pdo->prepare($this->sql);
        $prepare->execute();
        $this->startMethodFlag = false;
        return $prepare->fetchAll($pdo::FETCH_ASSOC);
    }
}

