<?php

namespace Web\App\Core\session;

use ArrayAccess;

class Session implements SessionInterface, ArrayAccess
{
    public function get(?string $key = null, $default = null)
    {
        $this->session_starter();
        if(is_null($key)) return $_SESSION;
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    public function set(string $key, $value): void
    {
        $this->session_starter();
        $_SESSION[$key] = $value;
    }

    public function remove(string $key): void
    {
        $this->session_starter();
        unset($_SESSION[$key]);
    }

    public function offsetExists($offset)
    {
        $this->session_starter();
        return array_key_exists($offset, $_SESSION);
    }

    public function offsetGet($offset)
    {
        $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    private function session_starter(): void
    {
        if (session_status() === PHP_SESSION_NONE) 
            session_start();
    }

}