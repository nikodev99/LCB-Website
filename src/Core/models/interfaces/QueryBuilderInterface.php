<?php

namespace Web\App\Core\models\interfaces;

interface QueryBuilderInterface
{
    /**
     * This function helps to build all the sql statement that begins with the key word SELECT.
     * @param string $column is the colum to select. If it is set to null, its value is '*' which means all.
     * @param array $columns initially empty. If no, it contains columns to select.
     * @return string the string sql statement using a SELECT keyword.
     */
    public function select(?string $column = null, array $columns = []): string;

    /**
     * This function helps to build all the sql statement that begins with the keyword FROM.
     * @param string $table the table where to select data.
     * @return string the string sql statement using a FROM keyword.
     */
    public function from(string $table): string;

    /**
     * This function helps to build all the sql statement that begins with the keyword WHERE
     * @param array $data the table of data to compare. Each key of the table is compare to its value
     * @param bool $regexp the regexp on which tre WHERE statement is performed.
     * @return string the string sql statement using the WHERE keyword
     */
    public function where(array $data, bool $regexp = false): string;

    /**
     * This function helps to build all the sql statement that begins with the keyword JOIN.
     * @param array $data the table of data which determines the condition of the joining. Each key of the table is compare to its value
     * @return string the string sql statement using the WHERE keyword
     */
    public function join(array $Join_condition): string;

    /**
     * This function helps to build all the sql statement that begins with the keyword INSERT.
     * @param string $table the table of data to join.
     * @param array $data the table of tada to insert. Each key of the table is the field and the the value, the value to insert in that field.
     * @return string the string sql statement using the INSERT keyword.
     */
    public function insert(string $table, array $data): string;

    /**
     * This function helps to build all the sql statement that begins with the keyword DELETE.
     * @return string The string sql statement using the keyword DELETE.
     */
    public function delete(): string;

    /**
     * This function helps to build all the sql statement that begins with the keyword UPDATE.
     * @param string $table the table where to data have to be updated.
     * @param array $data the table of data to replace the initial one.
     * @return string The string sql statement using the keyword UPDATE.
     */
    public function update(string $table, array $data): string;

    /**
     * This function helps to build all the sql statement that begins with the keyword ORDER.
     * @param string $column the column to order data.
     * @param int $order The order to follow asc or desc. By default, $order equals 1 which means the order is ASC.
     * @return string The string sql statement using the keyword ORDER.
     */
    public function order(string $column, int $order = 1): string;

    /**
     * This function helps to build all the sql statement that begins with the keyword LIMIT.
     * @param int $limit The limit of data to retrieve per request. By default, $limit equals 5.
     * @return string The string sql statement using the keyword LIMIT.
     */
    public function limit(int $limit = 5): string;

    /**
     * This function helps to build all the sql statement that begins with the keyword REGEXP.
     * @return string The string sql statement using the keyword REGEXP
     */
    public function regexp(): string;
}