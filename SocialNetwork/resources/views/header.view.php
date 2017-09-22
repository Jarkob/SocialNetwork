<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">

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
					<a href="index.php">Startseite</a>
				</li>

			</ul>

			<form class="navbar-form navbar-left" action="index.php?page=search&" method="get">
				<div class="form-group">
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
						<a href="?page=profile">Profil</a>
					</li>
					<li>
						<a href="?page=histories">Nachrichten</a>
					</li>
					<li>
						<a href="?page=friendrequests">Freundschaftsanfragen</a>
					</li>
					<li>
						<a href="?page=notifications" id="notificationsIcon">Benachrichtigungen
						<?php
						$username = user::findUserBySid(session_id());
						$user = new user($username);
						$anzahlBenachrichtigungen = $user->getNotifications();
						if(sizeof($anzahlBenachrichtigungen) != 0) {
							?>
							<span style="color: #f00">
								<?= sizeof($anzahlBenachrichtigungen)?>
							</span>
							<?php
						}
						?>
						</a>
					</li>
					<li>
						<a href="?page=logout">Logout</a>
					</li>
					<li>
						<a href="#">Hallo, <?= $username?></a>
					</li>
					<?php
				} else {
					?>
					<li>
						<a href="?page=login">Login</a>
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
