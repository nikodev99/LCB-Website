<?php

namespace Web\App\Core\session;

class Post implements SessionInterface
{
    public function get(?string $key = null, $default = null)
    {
        if (is_null($key)) return $_POST;
        if (!empty($_POST[$key]) && array_key_exists($key, $_POST)) {
            if (is_array($_POST[$key])) return $_POST[$key];
            return trim($_POST[$key]);
        }else {
            return $default;
        }
    }

    public function set(string $key, $value): void
    {
        $_POST[$key] = trim($value);
    }

    public function remove(string $key): void
    {
        unset($_POST[$key]);
    }

    public function isEmpty(?string $key = null): bool 
    {
        if(!is_null($key) && array_key_exists($key, $_POST)) {
            return empty($_POST[$key]);
        }
        return empty($_POST);
    }
}