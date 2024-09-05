<?php
namespace Benjaminzwahlen\Brace;

use Benjaminzwahlen\Brace\common\storage\session\User;

abstract class AbstractController
{
	public string $layoutName = "default";

	protected array $_CONFIG;
	protected User $_USER;
	protected $db;

	public function __construct(array &$config, &$db_, User &$_U)
	{
		$this->_CONFIG = &$config;
		$this->db = &$db_;
		$this->_USER = &$_U;
	}

	public function render(string $name, array &$params = array())
	{
		$filename = "../app/views/" . $name . ".view.php";

		$template = "../app/views/layouts/" . $this->layoutName . ".view.php";

		if (!file_exists($filename)) {
			return "View file not found: " . $filename;
		}


		$params['_CONFIG'] = $this->_CONFIG;
		$params['_USER'] = $this->_USER;
		extract(["_" => $params], EXTR_OVERWRITE);
		unset($params);

		ob_start();
		require_once $filename;
		$body = ob_get_contents();
		ob_end_clean();

		extract(["page_body_contents" => $body], EXTR_OVERWRITE);

		ob_start();
		require_once $template;
		$page = ob_get_contents();
		ob_end_clean();

		return $page;
	}

	public function redirect($url)
	{
		header("Location:" . $url);
		exit;
	}
}
