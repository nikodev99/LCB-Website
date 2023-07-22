<?php

namespace Web\App\Core;

class Request
{

    public static function getRequest(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function checkCurrentURL(string $url): bool
    {
        return self::getRequest() === $url  ? true : false;
    }

}