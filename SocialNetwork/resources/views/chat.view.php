<?php
require_once(CLASSES_PATH ."/user.php");

$username = user::findUserBySid(session_id());
$user = new user($username);
?>