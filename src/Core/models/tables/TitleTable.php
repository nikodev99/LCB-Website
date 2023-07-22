<?php

namespace Web\App\Core\models\tables;

use stdClass;
use Web\App\Core\models\Query;
use Web\App\Core\models\Table;

class TitleTable extends Table {

    public function __construct()
    {
        parent::__construct('titles');
    }

    public function getAll(): array
    {
        return $this->query()->findAll();
    }

    public function get(array $conditions, array $columns = []): ?stdClass
    {
        return $this->query()->find($conditions, null, $columns);
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