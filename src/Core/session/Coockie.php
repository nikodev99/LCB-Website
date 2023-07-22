<?php

namespace Web\App\Core\session;

class Coockie implements SessionInterface
{
    public function get(?string $key = null, $default = null)
    {
        if (is_null($key)) return $_COOKIE;
        if (array_key_exists($key, $_COOKIE)) {
            return $_COOKIE[$key];
        }
        return $default;
    }

    public function set(string $key, $value): void
    {
        setcookie($key, $value, [
            'expires'   =>  time() + 3600 * 24 * 3,
            'path'  =>  '/',
            'domain'    =>  '',
            'secure'    =>  false,
            'httponly'  =>  true
        ]);
    }

    public function remove(string $key): void
    {
        setcookie($key, '', [
            'expires'   =>  time() - 60 * 60,
            'path'  =>  '/',
            'domain'    =>  '',
            'secure'    =>  false,
            'httponly'  =>  true
        ]);
    }
}