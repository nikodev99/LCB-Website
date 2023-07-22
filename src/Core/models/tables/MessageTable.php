<?php

namespace Web\App\Core\models\tables;

use stdClass;
use Web\App\Core\models\Query;
use Web\App\Core\models\Table;

class MessageTable extends Table {

    public function __construct()
    {
        parent::__construct('messages');
    }

    public function getAll(array $condition =[], ?string $column = null, array $columns = [], array $order = []): array
    {
        return $this->query()->findAll($column, $columns, $condition, $order);
    }

    public function get(array $conditions =[], ?string $column = null, array $columns = []): ?stdClass
    {
        return $this->query()->find($conditions, $column, $columns);
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