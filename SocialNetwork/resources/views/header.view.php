<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/default.css">
	<title><?= SITE_NAME?></title>
</head>
<body>

<nav>
	<div class="clearfix">
		<ul class="topmenu">
			<li>
				<a href="index2.php">Startseite</a>
			</li>
			<li>
				<a href="#">Suche</a>
			</li>
			<?php
			require_once(CLASSES_PATH ."/login.php");

			if(login::isLoggedin(session_id()))
			{
				?>
				<li>
					<a href="#">Profil</a>
				</li>
				<li>
					<a href="#">Nachrichten</a>
				</li>
				<li>
					<a href="#">Freundschaftsanfragen</a>
				</li>
				<li>
					<a href="?page=logout">Logout</a>
				</li>
				<li>
					<p>Hallo username</p>
				</li>
				<?php
			} else {
				?>
				<li>
					<a href="?page=login">Login</a>
				</li>
				<li>
					<a href="#">Registrieren</a>
				</li>
				<?php
			}
			
			?>
		</ul>
	</div>
</nav>

<div id="wrapper">
<main>
