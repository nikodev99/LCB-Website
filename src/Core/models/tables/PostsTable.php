<?php

namespace Web\App\Core\models\tables;

use stdClass;
use Web\App\Core\models\Query;
use Web\App\Core\models\Table;

class PostsTable extends Table {

    public function __construct()
    {
        parent::__construct('posts');
    }

    public function getAll(array $conditions = [], ?string $column = null, array $columns = [], array $order = []): array
    {
        return $this->query()->findAll($column, $columns, $conditions, $order);
    }

    public function getPagination(array $order = []): array
    {
        return $this->query()->findPaginated($order);
    }

    public function get(array $condition, ?string $column = null, array $columns = []): ?stdClass
    {
        return $this->query()->find($condition, $column, $columns);
    }

    public function searchPosts(array $columns, array $conditions, array $orders): array
    {
        return $this->query()->search($columns, $conditions, $orders);
    }

    public function insert(array $data): void
    {
       $this->query()->insert($data);
    }

    public function update(array $condition, array $data): void
    {
        $this->query()->update($condition, $data);
    }

    public function delete(array $conditions): void
    {
        $this->query()->delete($conditions);
    }

    private function query(): Query
    {
        return $this->mysqldb();
    }

}