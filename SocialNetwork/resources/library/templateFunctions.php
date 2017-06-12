<?php
//note: bei allen Funktionen die etwas an der Datenbank machen sollen, muss das pdo Objekt im Aufruf übergeben werden und dementsprechend auch die Deklaration angepasst werden

require_once(realpath(dirname(__FILE__) . "/../config.php"));

//Diese Funktion springt immer an, ist sozusagen die Hauptlaufzeitumgebung. Sie lödt die entsprechenden Seiten oder Funktionen
function renderLayoutWithContentFile($pdo)
{
	//loggt jeden Besucher in der Datenbank
	//require_once(TEMPLATES_PATH . "/log.php");
	//prüft ob Besucher eingeloggt ist, wenn ja gibt es einige tolle Zusatzfunktionen
	
	$loggedin = getLoginStatus($pdo, session_id());
	if($loggedin) {
		$username = getUserName($pdo, session_id());
	}
	
	//header laden
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
			case 'registration':
				$contentFileFullPath = TEMPLATES_PATH . "/registration.php";
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
			case 'sendFriendrequest':
				$contentFileFullPath = TEMPLATES_PATH . "/sendFriendrequest.php";
				break;
			case 'acceptFriendrequest':
				$contentFileFullPath = TEMPLATES_PATH . "/acceptFriendrequest.php";
				break;
			case 'declineFriendrequest':
				$contentFileFullPath = TEMPLATES_PATH . "/declineFriendrequest.php";
				break;
			case 'manageFriendrequest':
				$contentFileFullPath = TEMPLATES_PATH . "/manageFriendrequest.php";
				break;
			case 'messages':
				$contentFileFullPath = TEMPLATES_PATH . "/messages.php";
				break;
			case 'messageHistory':
				$contentFileFullPath = TEMPLATES_PATH . "/messageHistory.php";
				break;
			case 'newHistory':
				$contentFileFullPath = TEMPLATES_PATH . "/newHistory.php";
				break;
			case 'editProfile':
				$contentFileFullPath = TEMPLATES_PATH . "/editProfile.php";
				break;
			default:
				$contentFileFullPath = TEMPLATES_PATH . "/error.php";
				break;
		}
	} else {
		//wenn die gewünschte Seite nicht gefunden wird/existiert kommt man zur Fehlerseite
		$contentFileFullPath = TEMPLATES_PATH . "/home.php";
	}

	//Hauptfunktion wird geladen
	if(file_exists($contentFileFullPath)) {
		require_once($contentFileFullPath);
	} else {
		require_once(TEMPLATES_PATH . "/error.php");
	}

	//footer laden
	require_once(TEMPLATES_PATH . "/footer.php");
}


//lädt den Eintrag mit der übergebenen id und druckt ihn direkt auf die Seite(sinnvolle Funktion)
function renderEntry(PDO $pdo, $id)
{
	$sql = "SELECT * FROM entry WHERE id = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($id));
	while($row = $statement->fetch()) {
		?>
		<div class="entry">
			<p><?= $row['zeit']?></p>
			<a href="?page=profile&owner=<?= $row['autor']?>">
				<h3>
					<?php
					if(file_exists("img/content/profile/". $row['autor'] .".jpg")) {
					?>
						<img id="profileIcon" title="<?= $row['autor']?>" src="img/content/profile/<?= $row['autor']?>.jpg" style="width: 20px;">
					<?php
					}
					echo $row['autor'];
					?>
				</h3>
			</a>
			<div>
				<?= $row['content']?>
			</div>
			<p>
				<?= getLikes($pdo, $id)?> Leuten gefällt das | 

				<a href="?page=home&like=<?= $id?>">Gefällt mir</a>
			
			</p>
			<hr>
		</div>
		<?php
	}
}


//erstellt ein Like-Objekt zwischen dem übergebenen User und dem Eintrag mit der übergebenen ID
function likeEntry(PDO $pdo, $userid, $entryid)
{
	if(!(hasUserLiked($pdo, $userid, $entryid))) {
		$sql = "INSERT INTO gefaelltMir (autor_user, gefallender_entry) VALUES (:autor_user, :gefallender_entry)";
		$statement = $pdo->prepare($sql);
		$statement->execute(array(':autor_user' => $userid, ':gefallender_entry' => $entryid));
	}
}


//returns the amount of likes, the entry with the given id has
function getLikes(PDO $pdo, $entryid)
{
	$sql = "SELECT * FROM gefaelltMir WHERE gefallender_entry = :entryid";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':entryid' => $entryid));
	$amountOfLikes = 0;
	while($row = $statement->fetch()) {
		$amountOfLikes++;
	}
	return $amountOfLikes;
}


//returns as a boolean if the user with the given user id has already liked the entry with the given id
//momentan obsolet
//edit: doch nicht
function hasUserLiked(PDO $pdo, $userid, $entryid)
{
	$sql = "SELECT * FROM gefaelltMir WHERE autor_user = :autor_user AND gefallender_entry = :gefallender_entry";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':autor_user' => $userid, ':gefallender_entry' => $entryid));
	while($row = $statement->fetch()) {
		return true;
	}
	return false;
}


//druckt das Profil des Users mit der übergebenen id direkt auf die Seite
function renderProfile(PDO $pdo, $id)
{
	$loggedin = getLoginStatus($pdo, session_id());

	if($loggedin) {
		$username = getUserName($pdo, session_id());

		//wenn es das eigene Profil des Users ist, soll er es bearbeiten können
		if($username == $id) {
			$eigenesProfil = true;
		} else {
			$eigenesProfil = false;
		}

		if($eigenesProfil) {
			?>
			<p><a href="?page=editProfile">Profil bearbeiten</a></p>
			<?php
		} else {
			//getFreundschaft, else biete Freundschaftsanfrage an
			$freundschaft = false;

			$sql = "SELECT * FROM friendship WHERE 
			(freund1 = :username || freund2 = :username) && 
			(freund1 = :otherUser || freund2 = :otherUser)";
			$statement = $pdo->prepare($sql);
			$statement->execute(array(':username' => $username, ':otherUser' => $id));
			while($row = $statement->fetch()) {
				$freundschaft = true;
			}

			if($freundschaft) {
				?>
				<p>Du bist mit <?= $id?> befreundet.</p>
				<p><a href="?page=newHistory&freund=<?= $id?>">Sende eine Nachricht an <?= $id?></a></p>
				<?php
			} else {
				?>
				<p>Willst du <?= $id?> eine Freundschaftsanfrage senden?</p>
				<p>Dann klicke <a href="?page=sendFriendrequest&id=<?= $id?>">hier</a></p>
				<?php
			}
		}

		$sql = "SELECT * FROM user WHERE username = :username";
		$statement = $pdo->prepare($sql);
		$statement->execute(array(':username' => $id));	
		while($row = $statement->fetch()) {
			?>
				<h3>Profil von <?= $id?></h3>

				<?php
				//Profilbild laden
				//hier isst der Wurm (drin)
				//der Wurm war eine Verwechslung mit javascript/c#
				if(file_exists("img/content/profile/". $id .".jpg")) {
				?>
					<img id="profilePicture" title="Profilbild" src="img/content/profile/<?= $id?>.jpg" style="width: 300px">
				<?php
				}
				?>

				<p>Vorname: <?= $row['vorname']?></p>
				<p>Nachname: <?= $row['nachname']?></p>
				<p>Geburtsdatum: <?= $row['gebdatum']?></p>
				<p>Geschlecht: <?= $row['geschlecht']?></p>
				<p>Beziehungsstatus: <?= $row['bezstatus']?></p>
				<p>Interessen: 
			<?php
		}

		$sql = "SELECT * FROM interesse WHERE user_id = :user_id";
		$statement = $pdo->prepare($sql);
		$statement->execute(array(':user_id' => $id));
		while($row = $statement->fetch()) {
			?>
			<?= $row['bezeichnung']?>, 
			<?php
		}
		?>
		</p>
		<h5>Freunde</h5>
		<?php
		
		$friends = getFriends($pdo, $id);
		foreach($friends as $friend) {
			?>
			<p><a href="?page=profile&owner=<?= $friend]?>"><?= $friend?></a></p>
			<?php
		}

	} else {
		?>
		<p>Bitte loggen Sie sich zuerst ein.</p>
		<p><a href="?page=login">Zum Login</a></p>
		<?php
	}
}


//prüft ob das übergebene Passwort zum User passt und gibt boolsche Antwort zurück
function loginFunction(PDO $pdo, $name, $password)
{
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


//logged den User auf extrem hässliche und unsichere Art aus, benötigt dringend noch Arbeit
function logoutFunction(PDO $pdo, $name)
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
	
	$sql = "UPDATE user SET sid = 'loggedout' WHERE username = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($name));

	?>
	<p>Sie wurden ausgeloggt.</p>
	<script type="text/javascript">
		document.location.href = "index.php";
	</script>
	<?php
}


//gibt den Loginstatus des Users mit der übergebenen ID als boolean zurück
function getLoginStatus(PDO $pdo, $sid)
{
	$sql = "SELECT * FROM user WHERE sid = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($sid));
	$gefundeneSIDs = $statement->rowCount();
	if($gefundeneSIDs != 0) {
		return true;
	} else {
		return false;
	}
}


//gibt zu einer session-ID den passenden usernamen zurück (sinnvoll aber fehleranfällig)
function getUserName(PDO $pdo, $sid) 
{
	$sql = "SELECT * FROM user WHERE sid = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($sid));
	$username = "failure";
	while($row = $statement->fetch()) {
		$username = $row['username'];
	}
	return $username;
}


//funktioniert eh nicht siehe unten, aber Idee ist gut
/*
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
*/

//Achtung: table oder column Namen in einer SQL Anweisung können bei prepared statements nicht
//durch Parameter ersetzt werden, daher müssen hier mehrere Funktionen geschrieben werden statt
//nur editUserValue()

function editUserSid($pdo, $userid, $newValue)
{
	$sql = "UPDATE user SET sid = :new WHERE username = :username";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':new' => $newValue, ':username' => $userid));
}


//errror in sql crap
//gibt die Freunde des übergebenen Users als array zurück
function getFriends(PDO $pdo, $userid)
{
	$friends = array();

	$sql = "SELECT * FROM friendship WHERE freund1 = :userid";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(":userid" => $userid));

	while($row = $statement->fetch()) {
		$friends[] = $row['freund2'];
	}

	$sql = "SELECT * FROM friendship WHERE freund2 = :userid";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(":userid" => $userid));

	while($row = $statement->fetch()) {
		$friends[] = $row['freund1'];
	}

	return $friends;
}



//message functions
//funktioniert eh nicht, der Müll
/*
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
	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'azure', 'Iggibib!');

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
	
}
*/

?>
