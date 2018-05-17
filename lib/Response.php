<?php

class Response
{
	static function error404()
	{
		header('HTTP/1.1 404 Not Found');
		@include BASE_DIR . 'www/404.html';
		die();
	}
}