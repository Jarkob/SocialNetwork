<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?=SITE_NAME?></title>
</head>

<body>
<div id="header">
	<h1><?=SITE_NAME?></h1>
	<ul>
		<li><a href="index.php">Home</a></li>
		<li><a href="#">Profile</a></li>
		<li><a href="?page=search">Search</a></li>
		<?php
		$loggedin = getLoginStatus(session_id());
		if($loggedin) {
			?>
			<li><a href="?page=logout">Logout</a></li>
			<?php
		} else {
			?>
			<li><a href="?page=login">Login</a></li>
			<?php
		}
		?>
	</ul>
</div>

<div id="content">