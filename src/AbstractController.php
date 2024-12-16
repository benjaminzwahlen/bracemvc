<?php
namespace benjaminzwahlen\bracemvc;

use benjaminzwahlen\bracemvc\interceptors\InterceptorInterface;

abstract class AbstractController
{
	public string $layoutName = "default";

	private array $interceptors = [];

	public function __construct(Request $request)
	{
	}

	public function getInterceptors() : array
	{
		return $this->interceptors;
	}
	
	public function registerInterceptor(InterceptorInterface $interceptor)
	{
		$this->interceptors[] = $interceptor;
	}

	public function render(string $name, array &$params = array())
	{
		return View::renderView($this->layoutName, $name, $params);

	}

	public function redirect($url)
	{
		header("Location:" . $url);
		exit;
	}
}
