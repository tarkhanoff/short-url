<?php

require_once BASE_DIR . 'lib/Config.php';
require_once BASE_DIR . 'lib/Database.php';
require_once BASE_DIR . 'lib/Request.php';
require_once BASE_DIR . 'lib/Response.php';
require_once BASE_DIR . 'model/UrlsTable.php';

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
			$this->procShortUrl();
		}
		else
		{
			Response::error404();
		}
	}
	
	/**
	 * Validates if param is well-formated short name for URL
	 */
	private function isValidShortName($str)
	{
		return preg_match('/^[a-zA-Z0-9]+$/', $str);
	}
	
	private function procShortUrl()
	{
		$request = Request::getInstance();
		$short = substr($request->getURI(), 1);
		
		// Find short name in the table
		$urlsTable = new UrlsTable();
		$entry = $urlsTable->findUrl($short);
		if (!$entry)
		{
			Response::error404();
		}
		
		// Increment 'used' counter
		$entry['used']++;
		$urlsTable->updateEntry($entry);
		
		// Redirect
		header('Location: ' . $entry['full_url']);
		die();
	}
}