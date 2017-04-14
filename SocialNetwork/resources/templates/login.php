<?php
//login kann evtl aus bib_Seite übernommen werden
?>


<div class="login">
<?php

if ((array_key_exists('login', $_POST))
	&& (array_key_exists('pass', $_POST))) {
	$username = $_POST['login'];
	$password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

	loginFunction($username, $password);
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

<?php
$passwort = password_hash('password', PASSWORD_DEFAULT);

}
?>

</div>