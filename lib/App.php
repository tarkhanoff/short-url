<?php

require_once BASE_DIR . 'lib/Config.php';
require_once BASE_DIR . 'lib/Database.php';

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
		
		echo 'done';
	}
	
}