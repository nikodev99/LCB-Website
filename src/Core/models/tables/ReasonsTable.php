<?php

namespace Web\App\Core\models\tables;

use stdClass;
use Web\App\Core\models\Query;
use Web\App\Core\models\Table;

class ReasonsTable extends Table {

    public function __construct()
    {
        parent::__construct('reasons');
    }

    public function getAll(array $conditions): array
    {
        return $this->query()->findAll(null, [], $conditions);
    }

    public function get(array $conditions): ?stdClass
    {
        return $this->query()->find($conditions);
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
        return $this->sqlitedb('sections');
    }

}