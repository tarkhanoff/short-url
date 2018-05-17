<?php

class Request
{
	private static $instance = null;
	private $uri;
	
	private function __construct()
	{
		$full_uri = $_SERVER['REQUEST_URI'];
		$parts = explode('?', $full_uri);
		$this->uri = $parts[0];
	}
	
	public function getInstance()
	{
		if (!self::$instance)
			self::$instance = new Request();
		
		return self::$instance;
	}
	
	public function getURI()
	{
		return $this->uri;
	}
	
	/**
	 * Returns base URL of the application, i.e. "https://domain.com/"
	 */
	public function getAppURL()
	{
		$url = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
		$url .= $_SERVER['SERVER_NAME'];
		
		$defPort = isset($_SERVER['HTTPS']) ? 443 : 80;
		if ((int)$_SERVER['SERVER_PORT'] != $defPort)
			$url .= ':' . (int)$_SERVER['SERVER_PORT'];
		
		return $url . '/';
	}
	
	/**
	 * Get GET param
	 */
	public function getParam($name, $default = false)
	{
		return (isset($_GET[$name])) ? $_GET[$name] : $default;
	}
	
	/**
	 * Get POST param
	 */
	public function postParam($name, $default = false)
	{
		return (isset($_POST[$name])) ? $_POST[$name] : $default;
	}
}