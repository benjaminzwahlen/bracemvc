<?php

namespace benjaminzwahlen\bracemvc;

use benjaminzwahlen\bracemvc\common\exceptions\InvalidRoutesFileException;

class Route
{
    public ?string $controllerName;
    public ?string $functionName;

    public ?string $requiredPermission = null;
    public ?string $requiredModule = null;
    public bool $isAjax = false;

    public ?array $tokenArray = null;


    public static function parse(array $r, ?array $tokenArray_ = null): Route
    {
        $route = new Route();
        try {
            $route->controllerName = $r['controller'];
            $route->functionName = $r['function'];
            $route->requiredPermission = $r['permission'] ?? "ALL";
            $route->requiredModule = $r['module'] ?? null;
            $route->isAjax = $r['ajax'] ?? false;
            $route->tokenArray = $tokenArray_;
        } catch (\Throwable $e) {
            throw new InvalidRoutesFileException($e);
        }
        return $route;
    }
}
