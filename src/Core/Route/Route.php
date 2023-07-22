<?php


namespace Web\App\Core\Route;

use Web\App\Core\Constant;

class Route
{
    private $name;
    private $controller;
    private $params;

    public function __construct(string $name, callable $controller, array $params)
    {
        $this->name = $name;
        $this->controller = $controller;
        $this->params = $params;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getController(): callable
    {
        return $this->controller;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public static function redirect(string $url, int $status = Constant::HTTP_RESPONSE_CODE_MOVE): void
    {
        header("location: {$url}");
        http_response_code($status);
        exit();
        //header('HTTP/1.1 301 Moved Permanently');
    }
}