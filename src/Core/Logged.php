<?php

namespace Web\App\Core;

use Web\App\Core\Route\Route;
use Web\App\Core\session\Coockie;
use Web\App\Core\session\Session;

class Logged
{
    private $session;
    private $cookie;

    public function __construct()
    {
        $this->session = new Session();
        $this->cookie = new Coockie();
    }

    public function getConnected($value, string $url): void
    {
        $this->session->set('connected', $value);
        Route::redirect($url);
    }

    public function getLoggedOut(string $url): void
    {
        $this->session->remove('connected');
        Route::redirect($url);
    }

    public function get_session_data(string $key)
    {
        return $this->session->get($key);
    }

    public function session_exists(string $key): bool
    {
        $logged = false;
        if (!empty($this->get_session_data($key)))  $logged = true;
        return $logged;
    }

    public function setAuthentificationCookie($value): void
    {
        $this->cookie->set('auth', $value);
    }

    public function getCookie(string $key)
    {
        return $this->cookie->get($key);
    }

    public function removeCookie(string $key): void
    {
        $this->cookie->remove($key);
    }
}