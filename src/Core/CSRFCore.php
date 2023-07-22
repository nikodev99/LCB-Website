<?php

namespace Web\App\Core;

use ArrayAccess;
use Exception;
use TypeError;

class CSRFCore {

    private $limit;

    private $formKey;
    private $sessionKey;

    private $session;

    public function __construct(&$session, int $limit = 50, string $formKey = '_csrf', string $sessionKey = "csrf")
    {
        $this->validSession($session);
        $this->session = &$session;
        $this->formKey = $formKey;
        $this->sessionKey = $sessionKey;
        $this->limit = $limit;
    }

    public function process(array $method): bool
    {
        $params = array_keys($method) ?: [];
        if (!array_key_exists($this->formKey, $params)) {
            $this->reject;
        }else {
            $csrfList = $this->session[$this->sessionKey] ?? [];
            if (in_array($method[$this->formKey], $csrfList)) {
                return true;
            }else {
                return false;
            }
        }
    }

    public function generateToken(): string
    {
        $token = AppFunction::generate_ids(60, true);
        $csrfList = $this->session[$this->sessionKey] ?? [];
        $csrfList[] = $token;
        $this->session[$this->sessionKey] = $csrfList;
        $this->limitTokens();
        return $token;
    }

    private function useToken(string $token): void
    {
        $tokens = array_filter($this->session[$this->sessionKey], function(string $t) use ($token) {
            return $token !== $t;
        });
        $this->session[$this->sessionKey] = $tokens;
    }

    private function limitTokens(): void
    {
        $tokens = $this->session[$this->sessionKey] ?? [];
        if (count($tokens) > $this->limit) {
            array_shift($tokens);
        }
        $this->session[$this->sessionKey] = $tokens;
    }

    private function validSession($session) {
        if (!is_array($session) && !$session instanceof ArrayAccess) {
            throw new TypeError('La session n\'est pas un tableau');
        }
    }

    private function reject() {
        throw new Exception();
    }

}