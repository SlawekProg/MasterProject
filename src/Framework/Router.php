<?php

declare(strict_types=1);

namespace Framework;

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    private array $errorHandler;

    public function add(string $method, string $path, array $controller)
    {
        $path = $this->normalizePath($path);
        $regexPath = preg_replace('#{[^/]+}#', '([^/]+)', $path);
        $this->routes[] = [
            'path' => $path,
            'method' => strtoupper($method),
            'controller' => $controller,
            'middlewares' => [],
            'regexPath' => $regexPath
        ];
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);
        return $path;
    }

    public function dispatch(string $path, string $method, ?Container $container = null)
    {
        //normalizujemy przekazane parametry
        $path = $this->normalizePath($path);
        $method = strtoupper($_POST['_METHOD'] ?? $method);
        //przeszukujemy trasy w poszukiwaniu tej przekazanej w parametrach
        foreach ($this->routes as $route) {
            if (
                !preg_match("#^{$route['regexPath']}$#", $path, $paramValues) ||
                $route['method'] !== $method
            ) {
                //jeśli nie znajdzie w obecnej to przechodzi do następnej trasy
                continue;
            }

            array_shift($paramValues);

            preg_match_all('#{([^/]+)}#', $route['path'], $paramKeys);

            $paramKeys = $paramKeys[1];

            $params = array_combine($paramKeys, $paramValues);

            [$class, $function] = $route['controller'];
            $controllerInstatnce = $container ?
                $container->resolve($class) :
                new $class;
            $action = fn() => $controllerInstatnce->{$function}($params);

            $allMiddleware = [...$route['middlewares'], ...$this->middlewares];

            foreach ($allMiddleware as $middleware) {
                $middlewareInstatce = $container ?
                    $container->resolve($middleware) :
                    new $middleware;
                $action = fn() => $middlewareInstatce->process($action);
            }

            $action();

            return;
        }

        $this->dispatchNotFound($container);
    }

    public function addMiddleware(string $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function addRouteMiddleware(string $middleware)
    {
        $lastRouteKey = array_key_last($this->routes);
        $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
    }


    public function setErrorHandler(array $controller)
    {
        $this->errorHandler = $controller;
    }

    public function dispatchNotFound(?Container $container)
    {
        [$class, $function] = $this->errorHandler;

        $controllerInstatnce = $container ? $container->resolve($class) : new $class;

        $action = fn() => $controllerInstatnce->$function();

        foreach ($this->middlewares as $middleware) {
            $middlewareInstatnce = $container ? $container->resolve($middleware) : new $middleware;
            $action = fn() => $middlewareInstatnce->process($action);
        }

        $action();
    }
}
