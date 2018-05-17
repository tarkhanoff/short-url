<?php

define ('BASE_DIR', __DIR__ . '/../');

require_once BASE_DIR . 'lib/Config.php';

Config::load('default.ini');

$config = Config::getInstance();

var_dump($config);