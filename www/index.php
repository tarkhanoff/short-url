<?php
define ('BASE_DIR', __DIR__ . '/../');

require_once BASE_DIR . 'lib/Logger.php';
require_once BASE_DIR . 'lib/App.php';

try
{
	$app = new App();
	$app->run();
}
catch (Exception $ex)
{
	echo 'Sorry, something goes wrong..<br>';
	Logger::error('Exception: ' . $ex->getMessage());
	die();
}
