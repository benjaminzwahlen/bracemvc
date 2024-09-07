<?php

namespace benjaminzwahlen\bracemvc;

use benjaminzwahlen\bracemvc\common\exceptions\ControllerNotFoundException;
use benjaminzwahlen\bracemvc\common\exceptions\FunctionNotFoundException;
use benjaminzwahlen\bracemvc\Request;
use benjaminzwahlen\bracemvc\Router;

require 'functions.php';


class App
{
	private array $_CONFIG;
	private Router $router;
	private $db;

	private AbstractController $controller;


	public function __construct(Router &$router, array &$_C)
	{
		$this->_CONFIG =  &$_C;
		$this->router =  &$router;
		// Create connection
		$this->db = new \mysqli($_C['db']['host'], $_C['db']['user'], $_C['db']['password'], $_C['db']['db_name']);

		// Check connection
		if ($this->db->connect_error) {
			die("Connection failed");
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



	public function run(string $path, string $requestMethod, array &$_G, array &$_P)
	{
		$routePathString = "/" . trim($path, "/");



		$request = Request::parse($this->router, $routePathString, $requestMethod, $_G, $_P);

		$controllerPath = $this->searchForController('../app/controllers', $request->route->controllerName);
		if ($controllerPath == null)
			throw new ControllerNotFoundException($request->route->controllerName);


		require_once $controllerPath;

		$this->controller = new $request->route->controllerName($this->_CONFIG, $this->db);

		if (!method_exists($this->controller, $request->route->functionName))
			throw new FunctionNotFoundException("Not Found: " . $request->route->functionName . "(...)");

		foreach ($this->controller->getInterceptors() as $i) {
			$i->intercept($request);
		}


		if ($request->route->tokenArray != null)
			$page = call_user_func_array([$this->controller, $request->route->functionName], array($request, ...$request->route->tokenArray));
		else
			$page = call_user_func_array([$this->controller, $request->route->functionName], array($request));

		if ($page != null)
			print($page);
	}
}
