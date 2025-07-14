<?php

declare(strict_types=1);

namespace Framework;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $controller)
    {
        $path = $this->normalizePath($path);
        $this->routes[] = [
            'path' => $path,
            'method' => strtoupper($method),
            'controller' => $controller
        ];
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);
        return $path;
    }

    public function dispatch(string $path, string $method)
    {
        //normalizujemy przekazane parametry
        $path = $this->normalizePath($path);
        $method = strtoupper($method);
        //przeszukujemy trasy w poszukiwaniu tej przekazanej w parametrach
        foreach ($this->routes as $route) {
            if (
                !preg_match("#^{$route['path']}$#", $path) ||
                $route['method'] !== $method
            ) {
                //jeśli nie znajdzie w obecnej to przechodzi do następnej trasy
                continue;
            }

            //Jeśli znajdzie to odczytujemy z niej klase i funkcje
            [$class, $function] = $route['controller'];
            //Tworzymy obiekt odczytanej klasy
            $controllerInstatnce = new $class;
            //I uruchamiamy na tym obiekcie odczytaną funkcje
            $controllerInstatnce->{$function}();
        }
    }
}
