<?php
require_once(realpath(dirname(__FILE__) ."/../config2.php"));
require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/entry.php");
require_once(CLASSES_PATH ."/login.php");

function renderPage()
{
	//hier kommt das skript an
	require_once(VIEWS_PATH ."/header.view.php");

	// Neuer Versuch
	$view = VIEWS_PATH;
	if(isset($_GET('page'))) {
		if(login::loggedIn(session_id())) {
			switch($_GET['page']) {
				case 'home':
					$view .= "/home.view.php";
				case 'logout':
					$view .= "/logout.view.php";
					break;
				default:
					$view .= "/error.view.php";
					break;
			}
		} else {
			switch($_GET['page']) {
				case 'login':
					$view .= "/login.view.php";
					break;
				case 'registration':
					$view .= "/registration.view.php";
					break;
				default:
					?>
					<p>Bitte loggen Sie sich zuerst ein.</p>
					<p><a href="?page=login">Zum Login</a></p>
					<?php
					break;
			}
		}

		require_once($view);

	} else {
		if(login::isLoggedIn(session_id())) {
			$view = $view . '/home.view.php';
			require_once($view);
		} else {
			?>
			<p>Bitte loggen Sie sich zuerst ein.</p>
			<p><a href="?page=login">Zum Login</a></p>
			<?php
		}
	}


		
	require_once(VIEWS_PATH ."/footer.view.php");
}


function renderHome()
{
	require_once(VIEWS_PATH ."/newEntry.view.php");

	$username = user::findUserBySid(session_id());
	$user = new user($username);
	$friends = $user->getFriends();


	// Einträge zählen
	$sql = "SELECT * FROM entry WHERE autor = '" .$username;
	foreach($friends as $friend) {
		$sql .= "' OR autor = '". $friend;
	}
	$sql .= "' ORDER BY zeit DESC";

	$alleEintraege = sql::exe($sql);

	$anzahlEintraege = sizeof($alleEintraege);
	$anzahlProSeite = 10;
	$anzahlSeiten = $anzahlEintraege / $anzahlProSeite;

	if(empty($_GET['nr'])) {
		$seite = 1;
	} else {
		$seite = $_GET['nr'];
		if($seite > $anzahlSeiten) {
			$seite = 1;
		}
	}

	$start = ($seite * $anzahlProSeite) - $anzahlProSeite;
	$limit = /*$start + */10;

	$sql = "SELECT * FROM entry WHERE autor = '" .$username;
	foreach($friends as $friend) {
		$sql .= "' OR autor = '". $friend;
	}
	$sql .= "' ORDER BY zeit DESC LIMIT ". $limit ." OFFSET ". $start;

	$result = sql::exe($sql);


	$entries = array();
	
	for($i = 0; $i < sizeof($result); $i++) {
		$entries[] = new entry($result[$i]['id'], $result[$i]['autor'], $result[$i]['content']);
	}

	foreach($entries as $entry) {
		$entry->renderEntry();
	}

	?>
	<ul id="seiten">
		<?php
		for($i = 1; $i <= $anzahlSeiten; $i++) {
			if($seite == $i) {
				?>
				<li>
					<a href="?page=home&nr=<?= $i?>"><b>Seite <?= $i?></b></a>
				</li>
				<?php
			} else {
				?>
				<li>
					<a href="?page=home&nr=<?= $i?>">Seite <?= $i?></a>
				</li>
				<?php
			}
		}
		?>
	</ul>
	<?php
}

?>