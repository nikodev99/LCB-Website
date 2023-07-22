<?php

namespace Web\App\Core\models;

use Web\App\Core\Constant;
use Web\App\Core\models\interfaces\QueryBuilderInterface;

class QueryBuilder implements QueryBuilderInterface
{

    public function select(?string $column = null, array $columns = []): string
    {
        $sql_statement = 'SELECT *';
        if (!is_null($column)) $sql_statement = str_replace('*', $column, $sql_statement);
        if (!empty($columns)) {
            $cols = [];
            foreach($columns as $key => $col) {
                $cols[] = $col;
            }
            $sql_statement = str_replace('*', implode(', ', $cols), $sql_statement);
        }
        return $sql_statement;
    }

    public function from(string $table): string
    {
        return "FROM {$table}";
    }

    public function insert(string $table, array $data): string
    {
        $fields = array_keys($data);
        $field = implode(', ', $fields);
        $values = [];
        foreach($fields as $value) {
            $values[] = ':' . $value;
        }
        $val = implode(', ', $values);
        $sql_statement = "INSERT INTO {$table} ({$field}) VALUES ({$val})";
        return $sql_statement;
    }

    public function update(string $table, array $data): string
    {
        $sql_statement = 'UPDATE ' . $table;
        $values = [];
        foreach($data as $key => $value) {
            $values[] = "$key = :$key";
        }
        $sql_statement.= ' SET '. implode(', ', $values);
        return $sql_statement;
    }

    public function delete(): string
    {
        return 'DELETE';
    }

    public function where(array $data, bool $regexp = false): string
    {
        $sql_statement = "WHERE ";
        $comparison = $this->compare($data, $regexp);
        $sql_statement .= $this->dataValuesChecker($data, $comparison);
        return $sql_statement;
    }

    public function join(array $join_condition): string
    {
        $sql_statement = "JOIN ";
        $comparison = [];
        $joins = [];
        foreach($join_condition as $table => $comparisons) {
            if (is_array($comparisons)) {
                foreach($comparisons as $k => $v) {
                    $comparison[] = "$k = $v";
                }
            }
            $joins[] = $table . ' ON ' . implode(' AND ', $comparison);
        }
        $sql_statement.= implode(' JOIN ', $joins);
        return $sql_statement;
    }

    public function order(string $column, int $order = 1): string
    {
        $sql_statement = "ORDER BY $column";
        switch($order) {
            case 1:
                $sql_statement.= ' ' . Constant::ASC;
            break;
            case 2:
                $sql_statement.= ' ' . Constant::DESC;
            break;
        }
        return $sql_statement;
    }

    public function limit(int $limit = 5): string
    {
        return "LIMIT " . $limit;
    }

    public function regexp(): string
    {
        return "REGEXP ";
    }

    private function compare(array $data, bool $regexp): array
    {
        $comparison = [];
        if ($regexp) {
            foreach($data as $key => $value) {
                $comparison[] = "$key {$this->regexp()}:$key";
            }
        }else {
            foreach($data as $key => $value) {
                $comparison[] = "$key = :$key";
            }
        }
        return $comparison;
    }

    private function dataValuesChecker(array $data, array $comparison): string
    {
        return (count(array_count_values($data)) === 1) ? implode(' OR ', $comparison) : implode(' AND ', $comparison);
    }
}