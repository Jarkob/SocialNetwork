<?php

require_once(realpath(dirname(__FILE__) . "/../config.php"));

function renderLayoutWithContentFile()
{
	$loggedin = getLoginStatus(session_id());

	if($loggedin) {
		$username = getUserName(session_id());
	}

	require_once(TEMPLATES_PATH . "/header.php");

	//jede Seite die über einen Link mit get Parameter aufgerufen werden können soll muss hier hinzugefügt werden
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
			case 'login':
				$contentFileFullPath = TEMPLATES_PATH . "/login.php";
				break;
			case 'logout':
				$contentFileFullPath = TEMPLATES_PATH . "/logout.php";
				break;
			case 'search':
				$contentFileFullPath = TEMPLATES_PATH . "/search.php";
				break;
			default:
				$contentFileFullPath = TEMPLATES_PATH . "/error.php";
				break;
		}
	} else {
		$contentFileFullPath = TEMPLATES_PATH . "/home.php";
	}

	?>
	<div id="content">
	<?php

	if(file_exists($contentFileFullPath)) {
		require_once($contentFileFullPath);
	} else {
		require_once(TEMPLATES_PATH . "/error.php");
	}

	?>
	</div>
	<?php

	require_once(TEMPLATES_PATH . "/footer.php");
}


function renderEntry($id)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT * FROM entry WHERE id = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($id));
	while($row = $statement->fetch()) {
		?>
		<div class="entry">
			<p><?= $row['zeit']?></p>
			<h3><?= $row['autor']?></h3>
			<div>
				<?= $row['content']?>
			</div>
			<hr>
		</div>
		<?php
	}
}


function loginFunction($name, $password)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');
	$sql = "SELECT * FROM user WHERE username = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($name));//Achtung: hier muss immer ein Array sein
	while($row = $statement->fetch()) {
		
		if(password_verify($password, $row['passwort'])) {
			echo "<p>Richtiges Passwort</p>";
			return true;
		}
	}
	return false;
}


function logoutFunction($name)
{
	/* doesn't work
	session_destroy();
	if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"],
        $params["domain"], $params["secure"], $params["httponly"]
    );
	}
	*/
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "UPDATE user SET sid = 'loggedout' WHERE username = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($name));

	?>
	<p>Sie wurden ausgeloggt.</p>
	<?php
}


function getLoginStatus($sid)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT * FROM user WHERE sid = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($sid));
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
	$statement->execute(array($sid));
	$username = "failure";
	while($row = $statement->fetch()) {
		$username = $row['username'];
	}
	return $username;
}

//Achtung muss ersetzt werden
function getUserValue($userid, $column)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT :column FROM user WHERE username = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':column' => $column, ':username' => $userid));
	$value = "empty";
	while($row = $statement->fetch()) {
		$value = $row[$column];//Problem: der Inhalt der eckigen Klammern muss in einfachen Anführungszeichen sein.
	}
	return $value;
}

//Achtung: table oder column Namen in einer SQL Anweisung können bei prepared statements nicht
//durch Parameter ersetzt werden, daher müssen hier mehrere Funktionen geschrieben werden statt
//nur editUserValue()
function editUserSid($userid, $newValue)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "UPDATE user SET sid = :new WHERE username = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':new' => $newValue, ':username' => $userid));
}


//errror in sql crap
function getFriends($userid)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$friends = array();

	$sql = "SELECT * FROM friendship WHERE freund1 = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($userid));

	while($row = $statement->fetch()) {
		$friends[] = $row['freund2'];
	}

	$sql = "SELECT * FROM friendship WHERE freund2 = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($userid));

	while($row = $statement->fetch()) {
		$friends[] = $row['freund1'];
	}

	return $friends;
}

?>