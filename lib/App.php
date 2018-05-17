<?php

require_once BASE_DIR . 'lib/Config.php';
require_once BASE_DIR . 'lib/Database.php';
require_once BASE_DIR . 'lib/Request.php';
require_once BASE_DIR . 'lib/Response.php';
require_once BASE_DIR . 'lib/UrlValidator.php';
require_once BASE_DIR . 'model/UrlsTable.php';

class App
{
	private function init()
	{
		$config = Config::getInstance();
		
		Logger::setVerboseLevel($config->get('log.verbose_level'));
	
		$db = Database::getInstance();
		$db->connect($config->get('db.server'), $config->get('db.user'), $config->get('db.pass'), $config->get('db.name'));
	}
	
	public function run()
	{
		$this->init();
		
		// Clean up the table, remove old entries
		// Move to cron??
		$urlsTable = new UrlsTable();
		$urlsTable->cleanup();
		
		// Dispatch request
		$request = Request::getInstance();
		$uri = $request->getURI();
		
		Logger::info('Request: ' . $uri);
		
		if (($uri == '/') || ($uri == '/index.php'))
		{
			$this->procMain();
		}
		else if ($uri == '/api')
		{
			$this->procAPI();
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
	
	private function generateShortName()
	{
		$urlsTable = new UrlsTable();
		$abc = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$abcLen = strlen($abc);
		
		do
		{
			// Generate new random name
			$name = "";
			for ($i = 0; $i < 8; $i++)
			{
				$n = mt_rand(0, $abcLen - 1);
				$name .= $abc[$n];
			}
		}
		while ($urlsTable->findUrl($name));	// Check if already exists

		return $name;
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
		
		Logger::info('Redirecting: ' . $entry['full_url']);

		Response::redirect($entry['full_url']);
	}
	
	private function procMain()
	{
		$request = Request::getInstance();
		
		// Check if form was submitted
		if ($request->postParam('btnCreate') !== false)
		{
			// Get request params
			$fullUrl = trim($request->postParam('inputURL'));
			$shortName = trim($request->postParam('inputShort', ''));
				
			// Check for errors
			$errors = $this->validateData($fullUrl, $shortName);
			if (count($errors) == 0)
			{
				// Generate new short name, if necessary
				if ($shortName == '')
					$shortName = $this->generateShortName();
			
				// Insert entry to the table
				$entry = array(
					'short_name' => $shortName,
					'full_url' => $fullUrl
				);
				
				$urlsTable = new UrlsTable();
				$urlsTable->insertEntry($entry);
				
				Logger::info('New short URL: ' . $entry['short_name'] . ' -> ' . $entry['full_url']);
				
				// Finally, show created URL
				Response::renderTemplate('created', array('short_name' => $request->getAppURL() . $shortName));
			}
			else
			{
				// Display errors
				Response::renderTemplate('main', array(
					'short_name' => $shortName,
					'full_url' => $fullUrl,
					'errors' => $errors
				));
			}
		}
		
		Response::renderTemplate('main');
	}
	
	private function validateData($fullUrl, $shortName)
	{
		$errors = array();
		
		// URL validation
		if (!UrlValidator::validate($fullUrl))
		{
			Logger::info('Invalid URL: ' . $fullUrl);
			$errors[] = 'Invalid URL!';
		}
		
		// Check short name
		if ($shortName != '')
		{
			// Check format
			if (!$this->isValidShortName($shortName))
				$errors[] = 'Invalid short name';
			
			// Check uniqueness
			$urlsTable = new UrlsTable();
			if ($urlsTable->findUrl($shortName))
			{
				Logger::info('Already exists: ' . $shortName);
				$errors[] = 'Short name already exists!';
			}
		}
		
		return $errors;
	}
	
	private function procAPI()
	{
		$urlsTable = new UrlsTable();
		
		$request = Request::getInstance();
		$method = $request->getParam('method');
		switch ($method)
		{
			case 'create':
				// Get request params
				$fullUrl = trim($request->getParam('url'));
				$shortName = trim($request->getParam('short', ''));
					
				// Check for errors
				$errors = $this->validateData($fullUrl, $shortName);
				if (count($errors) == 0)
				{
					// Generate new short name, if necessary
					if ($shortName == '')
						$shortName = $this->generateShortName();
				
					// Insert entry to the table
					$urlsTable->insertEntry(array(
						'short_name' => $shortName,
						'full_url' => $fullUrl
					));
					
					Logger::info('API New short URL: ' . $shortName . ' -> ' . $fullUrl);
					
					$response = array('result' => 'ok', 'url' => $request->getAppURL() . $shortName);
				}
				else
				{
					Logger::error('API:create: validation error');
					$response = array('result' => 'error', 'errors' => $errors);
				}
				break;
				
			default:
				Logger::error('API: Unknown method');
				$response = array('result' => 'error', 'errors' => array('Unknown method'));
				break;
		}
		
		Response::json($response);
	}
}