<?php
require_once(realpath(dirname(__FILE__) ."/../config.php"));
require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/entry.php");
require_once(CLASSES_PATH ."/login.php");

function renderPage()
{
	// Zugriff speichern
	require_once(CLASSES_PATH ."/log.php");
	log::logAccess();
	
	require_once(VIEWS_PATH ."/header.view.php");

	// Neuer Versuch
	$view = VIEWS_PATH;
	if(isset($_GET['page'])) {
		if(login::isLoggedIn(session_id())) {
			switch($_GET['page']) {
				case 'home':
					$view .= "/home.view.php";
					$username = user::findUserBySid(session_id());
					$user = new user($username);

					if(isset($_GET['delete'])) {
						$entry = entry::findEntryById($_GET['delete']);
						if($entry->getAuthor() == $username) {
							$entry->deleteEntry();
						}
					} else if(isset($_GET['like'])) {
						$entry = entry::findEntryById($_GET['like']);
						if(!$entry->hasUserLiked($username)) {
							$user->likeEntry($_GET['like']);
						}
					} else if(isset($_GET['dislike'])) {
						$entry = entry::findEntryById($_GET['dislike']);
						if($entry->hasUserLiked($username)) {
							$user->dislikeEntry($_GET['dislike']);
						}
					}

					break;
				case 'profile':
					$view .= "/profile.view.php";
					break;
				case 'search':
					$view .= "/search.view.php";
					break;
				case 'sendFriendrequest':
					$view .= "/sendFriendrequest.view.php";
					break;
				case 'friendrequests':
					$view .= "/friendrequests.view.php";
					break;
				case 'acceptFriendrequest':
					$view .= "/acceptFriendrequest.view.php";
					break;
				case 'declineFriendrequest':
					$view .= "/declineFriendrequest.view.php";
					break;
				case 'editEntry':
					$view .= "/editEntry.view.php";
					break;
				case 'editProfile':
					$view .= "/editProfile.view.php";
					break;
				case 'logout':
					$view .= "/logout.view.php";
					break;
				case 'impressum':
					$view .= "/impressum.view.php";
					break;
				case 'notifications':
					$view .= "/notifications.view.php";
					break;
				default:
					$view .= "/error.view.php";
					break;
			}
			
			require_once($view);

		} else {
			switch($_GET['page']) {
				case 'login':
					$view .= "/login.view.php";
					require_once($view);
					break;
				case 'registration':
					$view .= "/registration.view.php";
					require_once($view);
					break;
				default:
					?>
					<p>Bitte loggen Sie sich zuerst ein.</p>
					<p><a href="?page=login">Zum Login</a></p>
					<?php
					break;
			}
		}

	} else {
		if(login::isLoggedIn(session_id())) {
			$view .= '/home.view.php';

			// Möglicherweise ist diese Deletung obsolet
			if(isset($_GET['delete'])) {
				echo "kurz vorm dleeten";
				$entry = entry::findEntryById($_GET['delete']);
				if($entry->getAuthor() == user::getUserBySid(session_id())) {
					$entry->deleteEntry();
				}
			}

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
	require_once(VIEWS_PATH ."/newContent.view.php");

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
		$entries[] = new entry($result[$i]['autor'], $result[$i]['content'], $result[$i]['id']);
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