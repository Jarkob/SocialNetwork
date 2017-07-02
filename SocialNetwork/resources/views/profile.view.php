<?php
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/profile.php");

if(isset($_GET['owner'])) {
	$owner = $_GET['owner'];
} else {
	$owner = user::findUserBySid(session_id());
}

$profile = new profile(user::findUserByUsername($owner));
$profile->renderProfile();
?>