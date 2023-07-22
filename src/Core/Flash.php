<?php

namespace Web\App\Core;

use Web\App\Core\session\Session;

class Flash
{
    private $session;

    private $session_key = 'flash';

    public function __construct()
    {
        $this->session = new Session();
    }

    public function success(string $message): void
    {
        $flash = $this->session->get($this->session_key, []);
        $flash['success'] = $message;
        $this->session->set($this->session_key, $flash);
    }

    public function info(string $message): void
    {
        $flash = $this->session->get($this->session_key, []);
        $flash['info'] = $message;
        $this->session->set($this->session_key, $flash);
    }

    /**
     * @param $message string|array
     */
    public function error($message): void
    {
        $flash = $this->session->get($this->session_key, []);
        if(is_string($message)) {
            $flash['error'] = $message;
        }else {
            $flash['error'] = $this->list_of_errors($message);
        }
        $this->session->set($this->session_key, $flash);
    }

    public function get(string $type): ?string
    {
        $flash = $this->session->get($this->session_key, []);
        if (array_key_exists($type, $flash))  {
            return $flash[$type];
        }
        return null;
    }

    public function flash(): ?array
    {
        return $this->session->get($this->session_key, []);
    }

    public function unset_flash(): void
    {
        $this->session->remove($this->session_key);
    }

    private function list_of_errors(array $errors): string
    {
        $list = null;
        $lis = [];
        foreach ($errors as $value) {
            $lis[] = '<li>'.$value.'</li>';
        }
        $list = implode(PHP_EOL, $lis);
        return <<<HTML
            <ul>
                {$list}
            </ul>
        HTML;
    }
}