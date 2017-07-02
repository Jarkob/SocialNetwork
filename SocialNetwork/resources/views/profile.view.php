<?php
echo "in der profile.view";
require_once(CLASSES_PATH ."/user.php");
echo "nach requiren von user.php";
require_once(CLASSES_PATH ."/profile.php");
echo "nach dem requiren";
if(isset($_GET['owner'])) {
	$owner = $_GET['owner'];
} else {
	$owner = user::findUserBySid(session_id());
}
echo "der owner ist ". $owner;

$profile = new profile(new user($owner));
echo "nach profilinitialisierung";
$profile->renderProfile();
echo "nach profilladen";
?>