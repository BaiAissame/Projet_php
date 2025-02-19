<?php

class Router
{
    private array $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function get(string $path, string $controllerName, string $methodName): void
    {
        $this->routes[] = [
            "method" => "GET",
            "path" => $path,
            "controllerName" => $controllerName,
            "methodName" => $methodName
        ];
    }

    public function post(string $path, string $controllerName, string $methodName): void
    {
        $this->routes[] = [
            "method" => "POST",
            "path" => $path,
            "controllerName" => $controllerName,
            "methodName" => $methodName
        ];
    }

    public function start(): void
    {
        $method = $_SERVER["REQUEST_METHOD"];
        $path = $_SERVER["REQUEST_URI"];

        foreach ($this->routes as $route) {
            if ($method === $route["method"]) {
                $routePattern = preg_replace('/\{([^\/]+)\}/', '([^\/]+)', $route["path"]);
                $routePattern = "#^" . $routePattern . "$#";

                if (preg_match($routePattern, $path, $matches)) {
                    array_shift($matches); 

                    $methodName = $route["methodName"];
                    $controllerName = $route["controllerName"];

                    $controllerName::$methodName(...$matches);
                    return;
                }
            }
        }

        http_response_code(404);
        die("Erreur 404 : Page non trouvée.");
    }
}
