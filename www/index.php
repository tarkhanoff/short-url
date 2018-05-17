<?php

define ('BASE_DIR', __DIR__ . '/../');

require_once BASE_DIR . 'lib/Config.php';
require_once BASE_DIR . 'lib/Database.php';

try
{
	//Config::load('debug.ini');

	$config = Config::getInstance();
	
	$db = Database::getInstance();
	$db->connect($config->get('db.server'), $config->get('db.user'), $config->get('db.pass'), $config->get('db.name'));
	
	var_dump($db);
}
catch (Exception $ex)
{
	// TODO: log error
	
	echo 'Sorry, something goes wrong..<br>';
	echo 'ERROR: ' . $ex->getMessage();
	die();
}
