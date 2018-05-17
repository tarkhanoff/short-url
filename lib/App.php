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
			$this->procMain();
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
	
	private function procMain()
	{
		$request = Request::getInstance();
		if ($request->postParam('btnCreate') !== false)
		{
			$fullUrl = $request->postParam('inputURL');
			$shortName = trim($request->postParam('inputShort', ''));
			
			// TODO: Generate new short name
			if ($shortName == '')
			{
				$shortName = 'test' . date('YmdHis');
			}
			else
			{
				// TODO: Check uniqueness
			}
			
			// TODO: URL validation
			
			// Insert entry to the table
			$entry = array(
				'short_name' => $shortName,
				'full_url' => $fullUrl
			);
			
			$urlsTable = new UrlsTable();
			$urlsTable->insertEntry($entry);
			
			// Finally, show created URL
			Response::renderTemplate('created', array('short_name' => $request->getAppURL() . $shortName));
		}
		
		Response::renderTemplate('main');
	}
}