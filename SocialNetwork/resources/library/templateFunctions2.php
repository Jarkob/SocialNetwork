<?php
require_once(realpath(dirname(__FILE__) ."/../config2.php"));
require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/entry.php");

function renderPage()
{
	//hier kommt das skript an
	require_once(VIEWS_PATH ."/header.view.php");

	$view = VIEWS_PATH;

	if(isset($_GET['page'])) {
		switch($_GET['page']) {
			case 'login':
			$view .= "/login.view.php";
				break;
			case 'logout':
				$view .= "/logout.view.php";
				break;
			default:
				break;
		}
	} else {
		$view = $view . '/home.view.php';
	}

	require_once($view);

	require_once(VIEWS_PATH ."/footer.view.php");
}

/*
function renderHome()
{
	$username = user::findUserBySid(session_id());
	$user = user::findUserByUserName($username);
	$friends = $user->getFriends();

	$sql = "SELECT * FROM entry WHERE autor = '" .$username;
	foreach($friends as $friend) {
		$sql .= "' OR autor = '". $friend->username;
	}
	$sql .= "' ORDER BY zeit DESC";

	$result = sql::exe($sql);
	$entries = array();
	foreach($result as $entry) {
		$entries[] = new entry($entry['id'], $entry['autor'], $entry['content']);
	}

	foreach($entries as $entry) {
		$entry->renderEntry();
	}
}
*/
?>