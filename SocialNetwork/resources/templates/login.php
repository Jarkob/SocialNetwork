<div class="login">
<?php

if ((array_key_exists('login', $_POST))
	&& (array_key_exists('pass', $_POST))) {
	$username = $_POST['login'];
	$password = $_POST['pass'];

	if(loginFunction($pdo, $username, $password)) {
		editUserSid($username, session_id());//da muss die sid hin
	?>
		<p>Sie sind nun eingeloggt.</p>
		<script type="text/javascript">
			document.location.href = "index.php";
		</script>
		<p><a href='index.php'>Zurück zur Startseite</a></p>
	<?php
	} else {
		?>
		<p>Ihre Logindaten waren leider inkorrekt</p>
		<p><a href='?page=login'>Nochmal versuchen</a></p>

		<?php
	}
} else {
?>

<form action="" method="post">
	<div>
		<p><label for="login">Name</label></p>
		<p><input id="login" name="login"></p>
	</div>
 	<div>
 		<p><label for="pass">Passwort</label></p>
		<p><input id="pass" name="pass" type="password"></p>
	</div>
	<p><button>anmelden</button></p>
</form>
<p>Haben Sie noch keinen Account?</p>
<p>Dann können Sie sich <a href="?page=registration">hier</a> kostenlos registrieren.</p>
<?php
}
?>
</div>