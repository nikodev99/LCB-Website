<?php

namespace Web\App\Core\models\tables;

use stdClass;
use Web\App\Core\models\Query;
use Web\App\Core\models\Table;

class MultipleFeatures extends Table {

    public function __construct()
    {
        parent::__construct('multiple_features');
    }

    public function getAll(array $condition): array
    {
        return $this->query()->findAll(null, [], $condition);
    }

    public function get(array $conditions, ?string $column = null): ?stdClass
    {
        return $this->query()->find($conditions, $column);
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