<div id="login">
<?php
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/login.php");
if(login::isLoggedIn(session_id())) {
?>
	<p>Sie sind bereits eingeloggt.</p>
<?php
} else {
	if(array_key_exists('username', $_POST) && array_key_exists('password', $_POST)) {
		$username = $_POST['username'];
		$password = $_POST['password'];

		if(login::loginUser($username, $password)) {
		?>
			<p>Sie sind nun eingeloggt.</p>
			<script type="text/javascript">
				document.location.href = "index2.php";
			</script>
			<p><a href="index2.php">Zurück zur Startseite</a></p>
		<?php
		} else {
		?>
			<p>Ihre Logindaten waren nicht korrekt.</p>
			<p><a href="?page=login">Nochmal versuchen</a></p>
		<?php	
		}
	} else {
	?>
		<form action="" method="post">
			<div>
				<p><label for="username">Username</label></p>
				<p><input id="username" name="username"></p>
			</div>
			<div>
				<p><label for="password">Passwort</label></p>
				<p><input id="password" name="username" type="password"></p>
			</div>
			<p><button>Anmelden</button></p>
		</form>
		<p>Besitzen Sie noch keinen Account?</p>
		<p>Dann können Sie sich <a href="#">hier</a> registrieren.</p>

	<?php
	}
}
?>
</div>