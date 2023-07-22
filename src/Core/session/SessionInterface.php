<?php

namespace Web\App\Core\session;

interface SessionInterface
{
    /**
     * This function helps to get a key from the associative superglobal variable array.
     * @param string $key the key to get.
     * @param mixed $default = null by default that parameter is null.
     * @return mixed either a string or an array.
     */
    public function get(?string $key = null, $default = null);

    /**
     * This function helps to set a new key to an associative superglobal variable array.
     * @param string $key the key to set.
     * @param mixed $value the value of the key.
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * This function helps to remove a key from the superglobal variable array.
     * @param string $key the key to remove.
     * @return void
     */
    public function remove(string $key): void;
}