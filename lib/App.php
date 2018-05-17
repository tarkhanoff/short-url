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
		
		Logger::info('Redirecting: ' . $entry['full_url']);
		
		// Redirect
		header('Location: ' . $entry['full_url']);
		die();
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
			
			$errors = array();
			
			// URL validation
			if (!UrlValidator::validate($fullUrl))
			{
				Logger::info('Invalid URL: ' . $fullUrl);
				$errors[] = 'Invalid URL!';
			}
			
			$urlsTable = new UrlsTable();
			
			// Check short name
			// Should not generate new short name if URL is invalid
			if (($shortName == '') && (count($errors) == 0))
			{
				// TODO: Generate new short name
				$shortName = 'test' . date('YmdHis');
			}
			else
			{
				// Check uniqueness
				if ($urlsTable->findUrl($shortName))
				{
					Logger::info('Already exists: ' . $shortName);
					$errors[] = 'Short name already exists!';
				}
			}

			// Check for errors
			if (count($errors) == 0)
			{
				// Insert entry to the table
				$entry = array(
					'short_name' => $shortName,
					'full_url' => $fullUrl
				);
				
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
}