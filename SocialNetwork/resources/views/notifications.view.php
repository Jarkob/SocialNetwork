<h3>Benachrichtigungen</h3>
<?php
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/notification.php");

$username = user::findUserBySid(session_id());

$sql = "SELECT * FROM notification WHERE user = :username";
$params = array(":username" => $username);
$results = sql::exe($sql, $params);

$notifications = array();

if($results != null) {
	foreach($results as $result) {
		$notifications[] = notification::findNotificationById($result['id']);
	}
}

if(sizeof($notifications) != 0) {
	foreach($notifications as $notification) {
		$notification->renderNotification();
	}
} else {
	?>
	<p>Sie haben derzeit keine Benachrichtigungen.</p>
	<?php
}
?>