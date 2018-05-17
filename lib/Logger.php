<?php

class Logger
{
	private static $instance = null;
	private $logPath;
	protected $level;		// 0 - Disable log; 1 - Errors only; 2 - Errors & Warnings; 3 - All messages
	
	private function __construct()
	{
		$this->logPath = BASE_DIR . 'log/log.txt';
		$this->level = 3;
	}
	
	private function getInstance()
	{
		if (!self::$instance)
			self::$instance = new Logger();
		
		return self::$instance;
	}

	public static function setVerboseLevel($level)
	{
		$logger = self::getInstance();
		$logger->level = (int)$level;
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
		$logger = self::getInstance();
		if ($logger->level <= 0)
			return;
		
		$logger->writeToLog('ERROR: ' . $msg);
	}
	
	public static function warn($msg)
	{
		$logger = self::getInstance();
		if ($logger->level <= 1)
			return;
		
		$logger->writeToLog('WARN: ' . $msg);
	}
	
	public static function info($msg)
	{
		$logger = self::getInstance();
		if ($logger->level <= 2)
			return;
		
		$logger->writeToLog('INFO: ' . $msg);
	}
}