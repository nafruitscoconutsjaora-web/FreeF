<?php
namespace App\Core;

class Router {
    private static array $routes = [];

    public static function get(string $path, $handler, array $middlewares = []): void {
        self::addRoute('GET', $path, $handler, $middlewares);
    }

    public static function post(string $path, $handler, array $middlewares = []): void {
        self::addRoute('POST', $path, $handler, $middlewares);
    }

    private static function addRoute(string $method, string $path, $handler, array $middlewares): void {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
    }

    public static function dispatch(Request $request): void {
        $method = $request->method();
        $uri = rtrim($request->uri(), '/') ?: '/';

        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            // Convert /service/{slug} to regex #^/service/([^/]+)$#
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . rtrim($pattern, '/') . '$#';
            if ($route['path'] === '/') {
                $pattern = '#^/$#';
            }

            if (preg_match($pattern, $uri, $matches)) {
                // Execute middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareInstance = new $middleware();
                    $middlewareInstance->handle($request);
                }

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (is_callable($route['handler'])) {
                    call_user_func_array($route['handler'], [$request, $params]);
                    return;
                }

                if (is_array($route['handler'])) {
                    [$controllerClass, $action] = $route['handler'];
                    $controller = new $controllerClass();
                    call_user_func_array([$controller, $action], [$request, $params]);
                    return;
                }
            }
        }

        // Route not found
        Response::abort(404, 'Route not found');
    }
}
