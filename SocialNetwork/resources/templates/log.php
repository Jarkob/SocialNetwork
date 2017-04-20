<?php
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');

$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

$sql = "INSERT INTO access_log (ip, browser) VALUES (:ip, :browser)";
$statement = $pdo->prepare($sql);
$statement->execute(array(':ip' => $ip, ':browser' => $userAgent));
?>