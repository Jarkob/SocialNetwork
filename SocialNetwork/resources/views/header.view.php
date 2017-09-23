<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- jQuery einbinden-->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<!-- Bootstrap einbinden-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="css/default.css">
	<script type="text/javascript" src="js/functions.js"></script>
	<title><?= SITE_NAME?></title>
</head>
<body>

<!-- Ab hier neue Struktur-->

<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-which-will-collapse" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
        		<span class="icon-bar"></span>
       			<span class="icon-bar"></span>
       			<span class="icon-bar"></span>
			</button>
		</div>

		<!-- Dies ist der Teil der eingeklappt wird-->
		<div class="collapse navbar-collapse" id="navbar-which-will-collapse">
			<ul class="nav navbar-nav">
				<li>
					<a href="index.php">
						<span class="glyphicon glyphicon-home" aria-hidden="true" title="Startseite"></span>
					</a>
				</li>

			</ul>

			<form class="navbar-form navbar-left" action="index.php?page=search&" method="get">
				<div class="form-group">
					<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
					<input type="hidden" name="page" value="search">
					<input name="search">
				</div>
				<button type="submit" class="btn btn-default">Suchen</button>
			</form>

			<ul class="nav navbar-nav navbar-right">

				<?php
				require_once(CLASSES_PATH ."/login.php");
				require_once(CLASSES_PATH ."/user.php");

				if(login::isLoggedin(session_id()))
				{
					?>
					<li>
						<a href="?page=profile" title="Profil">
							<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
						</a>
					</li>
					<li>
						<a href="?page=histories" title="Nachrichten">
							<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
						</a>
					</li>
					<li>
						<a href="?page=friendrequests" title="Freundschaftsanfragen">
							<span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
						</a>
					</li>
					<li>
						<a href="?page=notifications" id="notificationsIcon" title="Benachrichtigungen">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<?php
							$username = user::findUserBySid(session_id());
							$user = new user($username);
							$anzahlBenachrichtigungen = $user->getNotifications();
							?>
							<span class="badge">
								<?= sizeof($anzahlBenachrichtigungen)?>
							</span>
						</a>
					</li>
					<li>
						<a href="?page=logout" title="logout">
							<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
						</a>
					</li>
					<li>
						<a href="#">Hallo, <?= $username?></a>
					</li>
					<?php
				} else {
					?>
					<li>
						<a href="?page=login" title="login">
							<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
						</a>
					</li>
					<li>
						<a href="?page=registration">Registrieren</a>
					</li>
					<?php
				}
				
				?>
			</ul>
		</div>
	</div>
</nav>

<!-- -->


<div class="container" id="wrapper">
	<div class="row">
		<div class="col-xs-0 col-sm-1 col-md-2 col-ld-3">
			
		</div>

		<div class="col-xs-12 col-sm-10 col-md-8 col-ld-6">
