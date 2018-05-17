<?php

define ('BASE_DIR', __DIR__ . '/../');

require_once BASE_DIR . 'lib/Config.php';
require_once BASE_DIR . 'lib/Database.php';
require_once BASE_DIR . 'lib/Logger.php';

try
{
	//Logger::error('Sample error text');
	//Logger::warn('Sample warning text');
	//Logger::info('Sample info text');
	
	//Config::load('debug.ini');

	$config = Config::getInstance();
	
	$db = Database::getInstance();
	$db->connect($config->get('db.server'), $config->get('db.user'), $config->get('db.pass'), $config->get('db.name'));
	
	var_dump($_SERVER['REQUEST_URI']);
}
catch (Exception $ex)
{
	echo 'Sorry, something goes wrong..<br>';
	Logger::error('Exception: ' . $ex->getMessage());
	die();
}
