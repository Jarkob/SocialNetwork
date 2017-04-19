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
			case 'home':
				$contentFileFullPath = TEMPLATES_PATH . "/home.php";
				break;
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
			case 'profile':
				$contentFileFullPath = TEMPLATES_PATH . "/profile.php";
				break;
			case 'messages':
				$contentFileFullPath = TEMPLATES_PATH . "/messages.php";
				break;
			case 'messageHistory':
				$contentFileFullPath = TEMPLATES_PATH . "/messageHistory.php";
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


function renderProfile($id)
{
	$loggedin = getLoginStatus(session_id());

	if($loggedin) {
		$username = getUserName(session_id());
		
		//wenn es das eigene Profil des Users ist, soll er es bearbeiten können
		if($username == $id) {
			$eigenesProfil = true;
		} else {
			$eigenesProfil = false;
		}

		$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

		$sql = "SELECT * FROM user WHERE username = :username";
		$statement = $pdo->prepare($sql);
		$statement->execute(array(':username' => $id));	
		while($row = $statement->fetch()) {
			?>
				<h3>Profil von <?= $id?></h3>
				<p>Vorname: <?= $row['vorname']?></p>
				<p>Nachname: <?= $row['nachname']?></p>
				<p>Geburtsdatum: <?= $row['gebdat']?></p>
			<?php
		}

	} else {
		?>
		<p>Bitte loggen Sie sich zuerst ein.</p>
		<p><a href="?page=login">Zum Login</a></p>
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



//message functions

function getMessages($id)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "SELECT * FROM messages WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':id' => $id));

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>";
	echo "<messages>";
	while($row = $statement->fetch()) {
		echo "<message><id>".$row['id'];
		echo "</id><name>".$row['sender_id'];
		echo "</name><nachricht>".$row['content'];
		//Formatierung des Timestamps
		echo "</nachricht><date>".date("d.m.Y H:i",$row['zeit']); 
		echo "</date></message>";
	}
	echo "</messages>";

}


function createNewEntry($sender, $empfaenger, $content)
{
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	//HTML Tags entfernen
	$sender = strip_tags($sender, '');
	$empfaenger = strip_tags($empfaenger, '');
	$content = strip_tags($content,''); 

	$sql = "INSERT INTO message (content, sender_id, empfaenger_id) 
	VALUES (:content, :sender, :empfaenger)";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':content' => $content, ':sender' => $sender, ':empfaenger' => $empfaenger));

	/*unnötig ab hier nur Fehlerkontrolle
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>";
	while($row = $statement->fetch()) {
		echo "<createNewEntry>0</createNewEntry>";
	} else {
		echo "<createNewEntry>1</createNewEntry>";
	}
	*/
}


?>