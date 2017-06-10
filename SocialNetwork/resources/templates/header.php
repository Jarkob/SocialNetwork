<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="css/default.css">
	<title><?=SITE_NAME?></title>
</head>

<body>

<nav>
	<div class="clearfix">
		<ul class="topmenu">
			<li><a href="index.php">Startseite</a></li>
			<li><a href="?page=search">Suche</a></li>
			<?php
			global $pdo;//unschön
			
			$loggedin = getLoginStatus($pdo, session_id());
			if($loggedin) {
				$username = getUserName($pdo, session_id());
				?>
				<li><a href="?page=profile&owner=<?= $username?>">Profil</a></li>
				<li><a href="?page=messages">Nachrichten</a></li>
				<li><a href="?page=manageFriendrequest">Freundschaftsanfragen</a></li>
				<li><a href="?page=logout">Logout</a></li>
				<li><a href="#">Hallo <?= $username?></a></li>
				<?php
			} else {
				?>
				<li><a href="?page=login">Login</a></li>
				<li><a href="?page=registration">Registrieren</a></li>
				<?php
			}

			?>
		</ul>
	</div>
</nav>

<div id="wrapper">

	<!--
	<header>
		<h1><?=SITE_NAME?></h1>
	</header>
	-->
	<main>
