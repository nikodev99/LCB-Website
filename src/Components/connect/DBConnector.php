<?php

namespace Web\App\Components\connect;

use PDO;
use PDOException;

class DBConnector
{
    public static function getConnectedToMYSQL(): PDO
    {
        try {
            $mysqlDB = self::dbconfig('mysql');
            $dsn = 'mysql:dbname='.$mysqlDB['dbname'].';host='.$mysqlDB['host'].';port='.$mysqlDB['port'];
            return new PDO($dsn, $mysqlDB['username'], $mysqlDB['password'], [
                PDO::ATTR_ERRMODE   =>  PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT   =>  5,
                PDO::ATTR_DEFAULT_FETCH_MODE    =>  PDO::FETCH_OBJ,
                PDO::MYSQL_ATTR_INIT_COMMAND    =>  "SET NAMES utf8"
            ]);
        }catch(PDOException $pdo) {
            die('<h1>Problème de connection à la base de données. Veuillez contacter le developeur</h1>');
        }
        
    }

    public static function getConnectedToSQLITE(string $sqliteDB): PDO
    {
        try {
            $sqlite = SQLITE_PATH . self::dbconfig('sqlite')[$sqliteDB];
            return new PDO('sqlite:' . $sqlite, null, null, [
                PDO::ATTR_ERRMODE   =>  PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT   =>  5,
                PDO::ATTR_DEFAULT_FETCH_MODE    =>  PDO::FETCH_OBJ
            ]);
        }catch(PDOException $pdo) {
            die('<h1>Problème de connection à la base de données. Veuillez contacter le developeur</h1>');
        }
        
    }

    private static function dbconfig(string $db): array
    {
        return DBConfig::DB[$db];
    }
}