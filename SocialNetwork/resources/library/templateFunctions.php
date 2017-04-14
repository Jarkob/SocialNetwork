<?php

require_once(realpath(dirname(__FILE__) . "/../config.php"));

function renderLayoutWithContentFile()
{
	$loggedin = getLoginStatus(session_id());

	if($loggedin) {
		$username = getUserName(session_id());
	}

	require_once(TEMPLATES_PATH . "/header.php");

	if(isset($_GET['page'])) {
		switch($_GET['page']) {
			case 'login':
				$contentFileFullPath = TEMPLATES_PATH . "/login.php";
				break;
			case 'profile':
				$contentFileFullPath = TEMPLATES_PATH . "/profile.php";
				break;
			case 'impressum':
				$contentFileFullPath = TEMPLATES_PATH . "/impressum.php";
				break;
			default:
				$contentFileFullPath = TEMPLATES_PATH . "/error.php";
				break;
		}
	} else {
		$contentFileFullPath = TEMPLATES_PATH . "/home.php";
	}


	if(file_exists($contentFileFullPath)) {
		require_once($contentFileFullPath);
	} else {
		require_once(TEMPLATES_PATH . "/error.php");
	}

	require_once(TEMPLATES_PATH . "/footer.php");
}


function loginFunction($name, $password)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT * FROM user WHERE username = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute($name);
	$username = "test";
	while($row = $statement->fetch()) {
		$id = $row['username'];

		if($password == $row['passwort']) {
			return true;
		}
	}
	return false;
}


function getLoginStatus($sid)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT * FROM user WHERE sid = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute($sid);
	$gefundeneSIDs = $statement->rowCount();
	if($gefundeneSIDs > 0) {
		return true;
	} else {
		return false;
	}
}


function getUserName($sid) 
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT * FROM user WHERE sid = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute($sid);
	$username = "failure";
	while($row = $statement->fetch()) {
		$username = $row['username'];
	}
	return $username;
}


function getUserValue($userid, $column)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT :column FROM user WHERE username = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':column' => $column, ':username' => $userid));
	$value = "empty";
	while($row = $statement->fetch()) {
		$value = $row[$column];
	}
	return $value;
}


function editUserValue($userid, $column, $newValue)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "UPDATE user SET :column WHERE username = :userid";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':column' => $column, ':username' => $userid));
	echo "<p>User Value wurde erfolgreich editiert.";
}

?>