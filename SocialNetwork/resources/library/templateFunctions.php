<?php

require_once(realpath(dirname(__FILE__) . "/../config.php"));

function renderLayoutWithContentFile()
{
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
	}
}

?>