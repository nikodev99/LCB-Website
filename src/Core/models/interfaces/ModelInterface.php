<?php

namespace Web\App\Core\models\interfaces;

use stdClass;

interface ModelInterface {

    /**
     * This function helps to insert data to a database table.
     * @param array $data the table of data to insert into a database table.
     * @param bool $insertId By default this parameter is set to false. If it is true, the inserted row id is returned.
     * @return void|int This function has not a return value. However, if $insertId is set to true, 
     * it returns an integer. That integer is an id of the inserted row into a database table.
     */
    public function insert(array $data, bool $insertId = false);

    /**
     * This function helps to retrieve all data from a database table.
     * NOTE: When using this function either $column or $columns must remain as by default.
     * @param string $column A single colum of the database table which to retrieve data. 
     * By default, its value is null. That means all data of all columns will be retrieve.
     * @param array $columns a table of columns name of the database table to retrieve data. 
     * By default its value is an empty array. That means all data of all columns will be retrieve.
     * @param array $conditions The condition that the WHERE statement must verify in order to retrieve data at a specific places
     * @param array $orders In this table, the order of retrieving and the limit of data to retrieve can be specify.
     * By default its value is an empty array. That means, The order of retrieving is ASC and there is no limit of data to retrieve.
     * @return array An array or table of data retrieved.
     */
    public function findAll(string $column = null, array $colums = [], array $conditions = [], array $orders = []): array;

    /**
     * This function helps to retrieve specific data from a database table.
     * NOTE: When using this function either $column or $columns must remain as by default.
     * @param array $condition The condition that the WHERE statement must verify in order to retrieve data at a specific place.
     * @param string $column A single colum of the database table which to retrieve data. 
     * By default, its value is null. That means all data of all columns will be retrieve.
     * @param array $columns a table of columns name of the database table to retrieve data. 
     * By default its value is an empty array. That means all data of all columns will be retrieve.
     * @param array $order In this table, the order of retrieving and the limit of data to retrieve can be specify.
     * By default its value is an empty array. That means, The order of retrieving is ASC and there is no limit of data to retrieve.
     * @return null|stdClass This function returns an instance of stdClass however, if the condition doesn't occur, it returns null.
     */
    public function find(array $condition, string $column = null, array $data = [], array $order = []): ?stdClass;

    /**
     * This function helps to update data from a database table at a specific place.
     * @param array $conditions The condition that the WHERE statement must verify in order to update data at a specific place.
     * @param array $data the table of data that will replace the data that exists into a database table at the place specify by the condition.
     * @return void
     */
    public function update(array $condition, array $data): void;

    /**
     * This function helps to delete data from a database table at a specific place.
     * @param array $condition The condition that the WHERE statement must verify in order to update data at a specific place.
     * @return void
     */
    public function delete(array $condition): void;

}