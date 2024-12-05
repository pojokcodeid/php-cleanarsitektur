<?php

namespace App\Core;

class Router {
    private $routes = [];
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function add($route, $action, $methods = ['GET']) {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
        $route = '/^' . $route . '$/';
        $this->routes[$route] = ['action' => $action, 'methods' => $methods];
    }

    public function dispatch() {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = trim($url, '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route => $info) {
            if (preg_match($route, $url, $matches)) {
                if (!in_array($requestMethod, $info['methods'])) {
                    http_response_code(405);
                    echo json_encode(["error" => "Method not allowed"]);
                    return;
                }

                array_shift($matches);
                list($controller, $method) = explode('@', $info['action']);

                $controller = "App\\Controllers\\$controller";
                if (class_exists($controller)) {
                    $controllerObject = new $controller($this->db);
                    if (method_exists($controllerObject, $method)) {
                        call_user_func_array([$controllerObject, $method], $matches);
                    } else {
                        http_response_code(404);
                        echo json_encode(["error" => "Method $method not found in controller $controller"]);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(["error" => "Controller $controller not found"]);
                }
                return;
            }
        }

        http_response_code(404);
        echo json_encode(["error" => "Route not found"]);
    }
}
