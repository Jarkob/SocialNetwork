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


function renderHome()
{
	$username = user::findUserBySid(session_id());
	$user = new user($username);
	$friends = $user->getFriends();

	$sql = "SELECT * FROM entry WHERE autor = '" .$username;
	foreach($friends as $friend) {
		$sql .= "' OR autor = '". $friend->getUsername();
	}
	$sql .= "' ORDER BY zeit DESC LIMIT 10";
	echo $sql;

	$result = sql::exe($sql);
	$entries = array();
	
	for($i = 0; $i < sizeof($result); $i++) {
		$entries[] = new entry($result[$i]['id'], $result[$i]['autor'], $result[$i]['content']);
	}

	foreach($entries as $entry) {
		$entry->renderEntry();
	}
}

?>