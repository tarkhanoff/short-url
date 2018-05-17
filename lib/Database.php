<?php

class Database
{
	private static $instance = null;
	private $connection = null;
	
	private function __construct()
	{
	}
	
	public function getInstance()
	{
		if (!self::$instance)
			self::$instance = new Database();
		
		return self::$instance;
	}
	
	public function connection()
	{
		return $connection;
	}
	
	public function connect($server, $login, $pass, $dbname)
	{
		$this->connection = @mysqli_connect($server, $login, $pass, $dbname);
		if (!$this->connection)
		{
			throw new Exception('error connecting to DB');
		}
		
		mysqli_set_charset($this->connection, 'UTF-8');
	}
	
	public function escapeString($str)
	{
		return mysqli_real_escape_string($this->connection, $str);
	}
	
	public function fetch($q)
	{
		$r = mysqli_query($this->connection, $q);
		if (!$r || !mysqli_num_rows($r))
			return false;
		
		return mysqli_fetch_assoc($r);
	}
	
	public function fetchAll($q)
	{
		$r = mysqli_query($this->connection, $q);
		
		$res = [];
		
		// ..
		
		return $res;
	}
	
	public function exec($q)
	{
		return !!mysqli_query($this->connection, $q);
	}
}