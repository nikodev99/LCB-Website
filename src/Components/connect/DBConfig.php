<?php

namespace Web\App\Components\connect;

class DBConfig
{
    public const DB = [
        'mysql' => [
            'dbname'    =>  'lcbwebsite_main_db',
            'host'  =>  'localhost',
            'username'  =>  'root',
            'password'  =>  'password',
            'port'      =>  '3306'
        ],
        'sqlite'    => [
            'menu'  =>  '/resources/menu.db',
            'sections'  =>  '/resources/sections.sqlite'
        ],
    ];
}