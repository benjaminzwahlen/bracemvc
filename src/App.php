<?php

namespace benjaminzwahlen\bracemvc;

use benjaminzwahlen\bracemvc\common\enums\Environment;
use benjaminzwahlen\bracemvc\common\exceptions\ControllerNotFoundException;
use benjaminzwahlen\bracemvc\common\exceptions\FunctionNotFoundException;
use benjaminzwahlen\bracemvc\Request;
use benjaminzwahlen\bracemvc\Router;


require 'functions.php';


class App
{
	private Router $router;
	public Environment $env;
	public bool $isAjax = false;
	private AbstractController $controller;

	public function __construct(Router &$router, Environment $env_)
	{
		$this->router =  &$router;
		$this->env =  $env_;
		if ($this->env == Environment::PROD) {
			error_reporting(E_ERROR | E_PARSE);
		}
	}
	private function searchForController($dir, $search)
	{
		$ffs = scandir($dir);

		unset($ffs[array_search('.', $ffs, true)]);
		unset($ffs[array_search('..', $ffs, true)]);

		// prevent empty ordered elements
		if (count($ffs) < 1)
			return;

		foreach ($ffs as $ff) {
			if (is_dir($dir . '/' . $ff)) {
				$res = $this->searchForController($dir . '/' . $ff, $search);
				if ($res != null)
					return $res;
			} else if ($ff === $search . ".php")
				return $dir . '/' . $ff;
		}
		return null;
	}



	public function run(string $path, string $requestMethod, bool $isAjax, array &$_G, array &$_P, $onError = null)
	{
		try {
			$routePathString = "/" . trim($path, "/");

			$request = Request::parse($this->router, $routePathString, $requestMethod, $isAjax, $_G, $_P);

			$this->controller = new $request->route->controllerName($request);

			if (!method_exists($this->controller, $request->route->functionName))
				throw new FunctionNotFoundException("MVC: Could not find function " . $request->route->functionName . " on " . $request->route->controllerName);

			foreach ($this->controller->getInterceptors() as $i) {
				$i->intercept($request);
			}


			if ($request->route->tokenArray != null)
				$page = call_user_func_array([$this->controller, $request->route->functionName], array($request, ...$request->route->tokenArray));
			else
				$page = call_user_func_array([$this->controller, $request->route->functionName], array($request));

				if ($page != null) {
					if ($isAjax)
						header("Content-type: application/json; charset=utf-8");
					else
						header("Content-type: text/html; charset=utf-8");
	
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
	