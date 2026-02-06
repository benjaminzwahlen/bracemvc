<?php

namespace benjaminzwahlen\bracemvc;

use benjaminzwahlen\bracemvc\common\enums\Environment;
use benjaminzwahlen\bracemvc\common\exceptions\ControllerNotFoundException;
use benjaminzwahlen\bracemvc\common\exceptions\FunctionNotFoundException;
use benjaminzwahlen\bracemvc\common\RequestTimer;
use benjaminzwahlen\bracemvc\Request;
use benjaminzwahlen\bracemvc\Router;


require 'functions.php';


class App
{
	private RouterInterface $router;
	public Environment $env;
	public bool $isAjax = false;
	private AbstractController $controller;

	public function __construct(RouterInterface &$router, Environment $env_)
	{
		$this->router =  &$router;
		$this->env =  $env_;
		if ($this->env == Environment::PROD) {
			error_reporting(E_ERROR | E_PARSE);
		}
	}

	public function run(string $path, string $requestMethod, bool $isAjax, array &$_G, array &$_P, $onError = null)
	{
		try {
			RequestTimer::mark("app_start");
			$routePathString = "/" . trim($path, "/");

			$request = Request::parse($this->router, $routePathString, $requestMethod, $isAjax, $_G, $_P, $this->env);
			RequestTimer::mark("route_found");

			$this->controller = new $request->route->controllerName($request);
			RequestTimer::mark("instantiated controller");

			if (!method_exists($this->controller, $request->route->functionName))
				throw new FunctionNotFoundException("MVC: Could not find function " . $request->route->functionName . " on " . $request->route->controllerName);

			foreach ($this->controller->getInterceptors() as $i) {
				$i->intercept($request);
			}

			RequestTimer::mark("interceptors_done");

			if ($request->route->tokenArray != null)
				$page = call_user_func_array([$this->controller, $request->route->functionName], array($request, ...$request->route->tokenArray));
			else
				$page = call_user_func_array([$this->controller, $request->route->functionName], array($request));
			RequestTimer::mark("controller_done");
			
			if ($page != null) {
				if ($isAjax)
					header("Content-type: application/json; charset=utf-8");
				else
					header("Content-type: text/html; charset=utf-8");

				RequestTimer::outputHeader();

				print($page);
			}
		} catch (\Throwable $e) {

			if (is_callable($onError))
				$onError($e, $isAjax);
			else
				throw $e;
		}
	}
}
