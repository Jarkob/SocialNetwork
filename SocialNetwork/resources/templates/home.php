<div id="home">

<p>Hallo Welt</p>
<p>Hier sollen die letzten 10 Posts von Freunden angezeigt werden</p>

<?php
$loggedin = getLoginStatus(session_id());

if($loggedin) {
	$username = getUserName(session_id());
} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}
?>
</div>