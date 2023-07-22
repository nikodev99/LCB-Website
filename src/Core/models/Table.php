<?php

namespace Web\App\Core\models;

use Web\App\Components\connect\DBConnector;

class Table {

    private $table;

    protected function __construct(string $table)
    {
        $this->table = $table;
    }

    protected function mysqldb (): Query
    {
        return new Query(DBConnector::getConnectedToMYSQL(), $this->table);
    }

    protected function sqlitedb (string $sqlitedb): Query
    {
        return new Query(DBConnector::getConnectedToSQLITE($sqlitedb), $this->table);
    }

}