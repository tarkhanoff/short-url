<?php

class Config
{
	private static $instance = null;
	private $data;
	
	private function __construct($filename = 'default.ini')
	{
		$fullPath = BASE_DIR . 'config/' . $filename;
		if (!file_exists($fullPath))
		{
			throw new Exception('config file doesn\'t exist: ' . $filename);
		}
		
		$this->data = parse_ini_file($fullPath);
		if (!$this->data)
		{
			throw new Exception('could not parse config file: ' . $filename);
		}
	}
	
	public static function getInstance()
	{
		if (!self::$instance)
			self::$instance = new Config();
		
		if (!self::$instance)
		{
			throw new Exception('could not load default config');
		}
		
		return self::$instance;
	}
	
	public static function load($filename)
	{
		self::$instance = new Config($filename);
		if (!self::$instance)
		{
			throw new Exception('could not load config: ' . $filename);
		}
	}

	public function get($name, $default = false)
	{
		if (isset($this->data[$name]))
			return $this->data[$name];
		
		return $default;
	}
}