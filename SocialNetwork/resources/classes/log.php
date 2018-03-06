<?php

class log
{
	public static function logAccess()
	{
		// in einem dieser Dinger ist ein Problem
		$ip = getenv('REMOTE_ADDR');
		$userAgent = getenv('HTTP_USER_AGENT');
		$referrer = getenv('HTTP_REFERER');
		$query = getenv('QUERY_STRING');

		$sql = "INSERT INTO access_log (ip, browser, referrer, query) VALUES (:ip, :userAgent, :referrer, :query)";
		$params = array(":ip" => $ip, ":userAgent" => $userAgent, ":referrer" => $referrer, ":query" => $query);
		sql::exe($sql, $params);
	}
}
?>