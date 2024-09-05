<?php




class Request
{

    public ?string $routeString;
    public ?Method $method = null;
    public ?Bag $getParams = null;
    public ?Bag $postParams = null;
    public ?Route $route = null;


    public function isPost(): bool
    {
        return $this->method == Method::POST;
    }

    public static function parse(Router $router, string $routePathString, string $methodString, array &$get, array &$post): ?Request
    {
        $route = $router->match($routePathString, $methodString);

        $request = new Request();
        $request->routeString = $routePathString;
        $request->route = $route;
        $request->method = Method::fromName($methodString);
        $request->getParams = Bag::load($get);
        $request->postParams = Bag::load($post);
        return $request;
    }
}
