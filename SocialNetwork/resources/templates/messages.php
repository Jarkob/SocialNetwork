<div id="messages">
<?php
//zeige alle Konversationen an, an denen die Person die die Seite aufruft beteiligt ist

$loggedin = getLoginStatus(session_id());
if($loggedin) {
	$username = getUserName(session_id());

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT * FROM message";
}
?>
</div>