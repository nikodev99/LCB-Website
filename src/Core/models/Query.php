<?php

namespace Web\App\Core\models;

use PDO;
use stdClass;
use PDOStatement;
use Web\App\Core\models\interfaces\ModelInterface;
use Web\App\Core\models\PaginationQuery;

class Query implements ModelInterface
{
    private $table;

    private $queryBuilder;
    private $db;

    public function __construct(PDO $db, string $table)
    {
        $this->table = $table;

        $this->queryBuilder = new QueryBuilder();
        $this->db = $db;        
    }

    public function findAll(?string $column = null, array $columns = [], array $conditions = [], array $orders = []): array
    {
        $select = $this->queryBuilder->select($column, $columns);
        $from = $this->queryBuilder->from($this->table);
        $where = !empty($conditions) ? $this->queryBuilder->where($conditions) : null;
        $order = !empty($orders) ? $this->queryBuilder->order($orders[0], $orders[1]) : null;
        $limit = !empty($orders) && array_key_exists('limit', $orders) ? $this->queryBuilder->limit($orders['limit']) : null;
        $query = $this->prepare($this->unset_array([$select, $from, $where, $order, $limit]));
        $query->execute($conditions);
        return $query->fetchAll();
    }

    public function findPaginated(array $orders = []): array
    {
        $select = $this->queryBuilder->select();
        $count = $this->queryBuilder->select("COUNT(id)");
        $from = $this->queryBuilder->from($this->table);
        $order = !empty($orders) ? $this->queryBuilder->order($orders[0], $orders[1]) : null;
        $query = $this->query([$select, $from, $order]);
        $countQuery = $this->query([$count, $from]);
        $paginatedQuery = new PaginationQuery($query, $countQuery, $this->db);
        $posts = $paginatedQuery->getItems();
        return [$posts, $paginatedQuery];
    }

    public function search(array $columns, array $conditions, array $orders): array
    {
        $select = $this->queryBuilder->select(null, $columns);
        $from = $this->queryBuilder->from($this->table);
        $where = $this->queryBuilder->where($conditions, true);
        $order = $this->queryBuilder->order($orders[0], $orders[1]);
        $query = $this->prepare($this->unset_array([$select, $from, $where, $order]));
        $query->execute($conditions);
        return $query->fetchAll();
    }

    public function find(array $conditions, ?string $column = null, array $columns = [], array $orders = []): ?stdClass
    {
        $select = $this->queryBuilder->select($column, $columns);
        $from = $this->queryBuilder->from($this->table);
        $where = $this->queryBuilder->where($conditions);
        $order = !empty($orders) ? $this->queryBuilder->order($orders[0], $orders[1]) : null;
        $limit = !empty($orders) && array_key_exists('limit', $orders) ? $this->queryBuilder->limit($orders['limit']) : null;
        $query = $this->prepare($this->unset_array([$select, $from, $where, $order, $limit]));
        $query->execute($conditions);
        $data = $query->fetch();
        return is_bool($data) ? null : $data;  
    }

    public function insert(array $data, bool $insertId = false)
    {
        $insert = $this->queryBuilder->insert($this->table, $data);
        $query = $this->prepare($this->unset_array([$insert]));
        $query->execute($data);
        if ($insertId) return $this->lastInsertID();
    }

    public function findJoin(array $joins, ?string $column = null, array $columns = [], array $conditions = []): array
    {
        $select = $this->queryBuilder->select($column, $columns);
        $from = $this->queryBuilder->from($this->table);
        $join = $this->queryBuilder->join($joins);
        $where = !empty($conditions) ? $this->queryBuilder->where($conditions) : null;
        $query = $this->prepare($this->unset_array([$select, $from, $join, $where]));
        $query->execute($conditions);
        return $query->fetchAll();
    }

    public function delete(array $conditions): void
    {
        $delete = $this->queryBuilder->delete();
        $from = $this->queryBuilder->from($this->table);
        $where = $this->queryBuilder->where($conditions);
        $query = $this->prepare($this->unset_array([$delete, $from, $where]));
        $query->execute($conditions);
    }

    public function update(array $conditions, array $data): void
    {
        $update = $this->queryBuilder->update($this->table, $data);
        $where = $this->queryBuilder->where($conditions);
        $query = $this->prepare($this->unset_array([$update, $where]));
        $query->execute(array_merge($data, $conditions));
    }

    private function lastInsertID(string $table = null): int
    {
        return $this->db->lastInsertId($table);
    }

    private function unset_array(array $vars): array
    {  
        $statement = [];
        foreach($vars as $k => $var) {
            if (is_null($var) | empty($var)) {
                unset($vars[$k]);
            }else {
                $statement[] = $var;
            }
        }
        return $statement;
    }
    private function prepare(array $vars): PDOStatement
    {
        //dd(implode(' ', $vars));
        return $this->db->prepare($this->query($vars));
    }

    private function query(array $vars): string
    {
        return trim(implode(' ', $vars));
    }
}