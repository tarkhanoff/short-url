<?php

class Logger
{
	private static $instance = null;
	private $logPath;
	
	private function __construct()
	{
		$this->logPath = BASE_DIR . 'log/log.txt';
	}
	
	private function getInstance()
	{
		if (!self::$instance)
			self::$instance = new Logger();
		
		return self::$instance;
	}
	
	private function writeToLog($msg)
	{
		$f = @fopen($this->logPath, "a+");
		if ($f)
		{
			fwrite($f, '[' . date('Y-m-d H:i:s') . '] ' . $msg . "\n");
			fclose($f);
		}
	}
	
	public static function error($msg)
	{
		// TODO: Filter
		
		$logger = self::getInstance();
		$logger->writeToLog('ERROR: ' . $msg);
	}
	
	public static function warn($msg)
	{
		// TODO: Filter
		
		$logger = self::getInstance();
		$logger->writeToLog('WARN: ' . $msg);
	}
	
	public static function info($msg)
	{
		// TODO: Filter
		
		$logger = self::getInstance();
		$logger->writeToLog('INFO: ' . $msg);
	}
}