<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="css/default.css">
	<title><?=SITE_NAME?></title>
</head>

<body>
<div id="header">
	
	<ul class="topmenu">
		<li><a href="index.php">Home</a></li>
		<li><a href="?page=search">Search</a></li>
		<?php

		$loggedin = getLoginStatus(session_id());
		if($loggedin) {
			$username = getUserName(session_id());
			?>
			<li><a href="?page=profile&owner=<?= $username?>">Profile</a></li>
			<li><a href="?page=messages">Messages</a></li>
			<li><a href="?page=logout">Logout</a></li>
			<?php
		} else {
			?>
			<li><a href="?page=login">Login</a></li>
			<?php
		}

		?>
	</ul>

	<h1><?=SITE_NAME?></h1>
</div>

<div id="content">