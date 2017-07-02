<?php
echo "in der profile.view";
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/profile.php");
echo "nach dem requiren";
if(isset($_GET['owner'])) {
	$owner = $_GET['owner'];
} else {
	$owner = user::findUserBySid(session_id());
}
echo "der owner ist ". $owner;

$profile = new profile(user::findUserByUsername($owner));
echo "nach profilinitialisierung";
$profile->renderProfile();
echo "nach profilladen";
?>