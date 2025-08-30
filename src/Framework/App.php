<?php

declare(strict_types=1);

namespace Framework;

class App
{
    private Router $router;
    private Container $container;

    public function __construct(?string $containerDefinitionsPath = null)
    {
        $this->router = new Router();
        $this->container = new Container();

        if ($containerDefinitionsPath) {
            $containerDefinition = include $containerDefinitionsPath;
            $this->container->addDefinition($containerDefinition);
        }
    }

    public function run()
    {
        //Odczytujemy adres z przeglądarki i wycinamy tylko ścieżkę
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        //Odczytujemy z adresu metode HTTP (GET lub POST lub ....)
        $method = $_SERVER['REQUEST_METHOD'];
        //Dla tego obiektu uruchamiamy metode dispatch klasy router przekazując odczytane parametry
        $this->router->dispatch($path, $method, $this->container);
    }

    public function get(string $path, array $controller): App
    {
        // Do otrzymanych parametrów dodajemy metode HTTP i
        //uruchamiamy w obiekcie router klasy Router metode dodawania trasy do listy routingu
        $this->router->add('GET', $path, $controller);

        return $this;
    }

    public function post(string $path, array $controller): App
    {
        $this->router->add('POST', $path, $controller);

        return $this;
    }

    public function addMiddleware(string $middleware)
    {
        $this->router->addMiddleware($middleware);
    }

    public function add(string $middleware)
    {
        $this->router->addRouteMiddleware($middleware);
    }

    public function delete(string $path, array $controller): App
    {
        $this->router->add('DELETE', $path, $controller);

        return $this;
    }

    public function setErrorHandler(array $controller)
    {
        $this->router->setErrorHandler($controller);
    }
}
