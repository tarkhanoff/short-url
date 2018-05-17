<?php

class Request
{
	private static $instance = null;
	private $uri;
	
	private function __construct()
	{
		//var_dump($_SERVER);
		
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
}