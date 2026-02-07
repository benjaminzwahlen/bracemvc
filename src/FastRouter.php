<?php

namespace benjaminzwahlen\bracemvc;

use benjaminzwahlen\bracemvc\common\enums\Environment;
use benjaminzwahlen\bracemvc\common\exceptions\InvalidRoutesFileException;
use benjaminzwahlen\bracemvc\common\exceptions\MethodNotAllowedException;
use benjaminzwahlen\bracemvc\common\exceptions\RouteNotFoundException;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\cachedDispatcher;

class FastRouter implements RouterInterface
{

    private string $routeFilePath;
    private $dispatcher = null;

    public function __construct(?string $filename = null)
    {
        if ($filename != null)
            $this->routeFilePath = $filename;
    }



    public function match(string $path, string $methodString, bool $isAjax_, Environment $env): ?Route
    {

        if (file_exists($this->routeFilePath) == false) {
            throw new InvalidRoutesFileException("MVC: Routes file not found: " . $this->routeFilePath);
        }

        if ($this->dispatcher == null) {
             $this->dispatcher = cachedDispatcher(function (RouteCollector $r) {
                require_once $this->routeFilePath;
            }, [
                'cacheFile' => __DIR__ . '/../../../../cache/' . basename($this->routeFilePath) . '.cache',
                'cacheDisabled' => $env == Environment::DEV,
            ]);
        }

        $routeInfo = $this->dispatcher->dispatch($methodString, $path);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new RouteNotFoundException("MVC: Unable to match path: " . $path);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException("MVC: method not allowed on " . $path . " tried: " . $methodString);
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $route = Route::parse($handler, $routeInfo[2]);

                if ($isAjax_ != $route->isAjax) {
                    throw new RouteNotFoundException("MVC: Ajax mismatch: " . $path);
                }

                return $route;
                break;
        }
        return null;
    }
}
