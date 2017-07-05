<?php
//untragbar

$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');

echo $ip ."<br>";
echo $userAgent ."<br>";
echo $referrer ."<br>";
echo $query;

//$sql = "INSERT INTO access_log (ip, browser) VALUES (:ip, :browser)";

/*
class log
{

}
*/
?>