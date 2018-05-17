<?php

require_once BASE_DIR . 'lib/Config.php';
require_once BASE_DIR . 'lib/Database.php';
require_once BASE_DIR . 'lib/Request.php';

class App
{
	private function init()
	{
		$config = Config::getInstance();
	
		$db = Database::getInstance();
		$db->connect($config->get('db.server'), $config->get('db.user'), $config->get('db.pass'), $config->get('db.name'));
	}
	
	public function run()
	{
		$this->init();
		
		// Dispatch request
		$request = Request::getInstance();
		$uri = $request->getURI();
		
		if (($uri == '/') || ($uri == '/index.php'))
		{
			@require BASE_DIR . 'templates/layout.php';
		}
		else if ($this->isValidShortName(substr($uri, 1)))
		{
			echo 'short url';
		}
		else
		{
			header('HTTP/1.1 404 Not Found');
			@include BASE_DIR . 'www/404.html';
		}
	}
	
	/**
	 * Validates if param is well-formated short name for URL
	 */
	private function isValidShortName($str)
	{
		return preg_match('/^[a-zA-Z0-9]+$/', $str);
	}
}