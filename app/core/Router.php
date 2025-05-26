<?php

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable $callback): void
    {
        $this->routes[$method][$path] = $callback;
    }

    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            if ($route === $uri) {
                call_user_func($callback);
                return;
            }
        }

        http_response_code(404);
        echo "Página não encontrada";
    }
}
