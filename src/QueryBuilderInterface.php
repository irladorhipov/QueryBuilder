<?php

namespace App;

interface QueryBuilderInterface
{
    public function select(array $colums = []): QueryBuilderInterface;

    public function from(string $tableName): QueryBuilderInterface;

    public function where(array $conditions):QueryBuilderInterface;

    public function OrderBy(string $column, $attr = ''): QueryBuilderInterface;

    public function update(string $table): QueryBuilderInterface;

    public function set(string $column, mixed $value): QueryBuilderInterface;

    public function delete(): QueryBuilderInterface;
    
    public function insert(string $table, array $data): QueryBuilderInterface;

    public function execute();
}