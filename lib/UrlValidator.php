<?php

class UrlValidator
{
	public static function validate($url)
	{
		if (!filter_var($url, FILTER_VALIDATE_URL))
			return false;
		
		$page = self::download($url);
		if (!$page)
			return false;
		
		if ($page['code'] != 200)
			return false;
		
		return true;
	}
	
	private function download($url)
	{
		$ch = @curl_init();
		if ($ch)
		{
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, true);
			//curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);

			$output = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);
			
			return array('code' => $httpcode, 'content' => $output);
		}
		else
		{
			Logger::error('Unable to initialize CURL');
		}
		
		return false;
	}
}