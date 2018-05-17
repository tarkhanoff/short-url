<?php

class Response
{
	static function error404()
	{
		header('HTTP/1.1 404 Not Found');
		@include BASE_DIR . 'www/404.html';
		die();
	}
	
	static function renderTemplate($templateName, $data = array())
	{
		$fullPath = BASE_DIR . 'templates/' . $templateName . '.php';
		if (!@file_exists($fullPath))
		{
			throw new Exception('Template not found: ' . $templateName);
		}
		
		// Render template to string
		ob_start();
		@include $fullPath;
		$content = ob_get_contents();
		ob_end_clean();
		
		// Render layout
		// simplified as much as possible
		// layout should display value of $content in apropriate place
		@require BASE_DIR . 'templates/layout.php';
		die();
	}
	
	static function json($data)
	{
		header('Content-type: application/json');
		echo json_encode($data);
		die();
	}
}