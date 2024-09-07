<?php
namespace benjaminzwahlen\bracemvc;

use benjaminzwahlen\bracemvc\common\storage\session\User;
use benjaminzwahlen\bracemvc\interceptors\InterceptorInterface;

abstract class AbstractController
{
	public string $layoutName = "default";

	protected array $_CONFIG;
	protected $db;

	private array $interceptors = [];

	public function __construct(array &$config, &$db_)
	{
		$this->_CONFIG = &$config;
		$this->db = &$db_;
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
		return View::renderView($this->_CONFIG, $this->layoutName, $name, $params );

	}

	public function redirect($url)
	{
		header("Location:" . $url);
		exit;
	}
}
