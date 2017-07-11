<?php
require_once(CLASSES_PATH ."/friendship.php");
require_once(CLASSES_PATH ."/friendrequest.php");
require_once(CLASSES_PATH ."/notification.php");

// Stellt einen Benutzer dar
class user
{
	//alle protected, falls es mal Rollen gibt
	protected $username;
	public function getUsername()
	{
		return $this->username;
	}

	protected $vorname;
	public function getVorname()
	{
		return $this->vorname;
	}

	protected $nachname;
	public function getNachname()
	{
		return $this->nachname;
	}

	protected $gebdatum;
	public function getGebdatum()
	{
		return $this->gebdatum;
	}

	// Erstellt user anhand des usernamens
	public function __construct($username)
	{
		$user = user::findUserByUserName($username);
		$this->username = $username;
		$this->vorname = $user[0]['vorname'];
		$this->nachname = $user[0]['nachname'];
		$this->gebdatum = $user[0]['gebdatum'];
	}

	public static function createNewUser($userdata)//userdata is an associative array with the userinformation
	{
		$sql = "INSERT INTO user 
			(username, vorname, nachname, passwort) 
			VALUES 
			(:username, :vorname, :nachname, :passwort)";
		sql::exe($sql, $userdata);
	}

	// Gibt ein userdaten array zurück
	public static function findUserByUserName($username)
	{
		$sql = "SELECT * FROM user
			WHERE username = :username";
		$params = array(":username" => $username);

		$user = sql::exe($sql, $params);
		return $user;
	}

	// Gibt den usernamen zur sid zurück
	public static function findUserBySid($sid)
	{
		$sql = "SELECT * FROM user
			WHERE sid = :sid";
		$params = array(":sid" => $sid);
		$user = sql::exe($sql, $params);
		return $user[0]['username'];
	}

	// Der User sendet eine Freundschaftsanfrage an jemanden
	public function sendFriendrequest($empfaenger)
	{
		friendrequest::createNewFriendrequest($this->username, $empfaenger);
		// Hier muss noch eine Benachrichtigung erstellt werden
		$result = friendrequest::getFriendrequestByParticipating($this->getUsername(), $empfaenger);
		$friendrequestId = $result[0]['id'];
		$notification = new notification($empfaenger, "friendrequest", $friendrequestId);
		$notification->createNewNotification();
	}

	// Der User akzeptiert eine Freundschaftsanfrage
	public function acceptFriendrequest(friendrequest $friendrequest)
	{
		friendship::createNewFriendship($friendrequest->getSender(), $friendrequest->getEmpfaenger());
		$friendrequest->deleteFriendrequest();

		// Zugehörige Benachrichtung löschen
		if(notification::checkNotificationByTypeAndTypeId("friendrequest", $friendrequest->getId())) {
			$notification = notification::findNotificationByTypeAndTypeId("friendrequest", $friendrequest->getId());
			$notification->deleteNotification();
		}
	}

	// Der User lehnt eine Freundschaftsanfrage ab
	public function declineFriendrequest(friendrequest $friendrequest)
	{
		$friendrequest->deleteFriendrequest();

		// Zugehörige Benachrichtigung löschen
		if(notification::checkNotificationByTypeAndTypeId("friendrequest", $friendrequest->getId())) {
			$notification = notification::findNotificationByTypeAndTypeId("friendrequest", $friendrequest->getId());
			$notification->deleteNotification();
		}
	}

	// Die Session_id des Benutzers wird aktualisiert
	public function changeSid()
	{
		$sql = "UPDATE user SET sid = :sid
			WHERE username = :username";
		$params = array(":sid" => session_id(), ":username" => $this->username);
		sql::exe($sql, $params);
	}

	// Gibt einen array mit Freundenamen zurück
	public function getFriends()
	{
		$sql = "SELECT * FROM friendship WHERE freund1 = :username OR freund2 = :username";
		$params = array(":username" => $this->username);
		$result = sql::exe($sql, $params);
		$friends = array();
		
		for($i = 0; $i < sizeof($result); $i++) {
			if($result[$i]['freund1'] == $this->username) {
				$friends[] = $result[$i]['freund2'];
			} else {
				$friends[] = $result[$i]['freund1'];
			}
		}
		return $friends;
	}

	public function getNotifications()
	{
		$sql = "SELECT * FROM notification WHERE user = :user";
		$params = array(":user" => $this->getUsername());
		$results = sql::exe($sql, $params);

		return $results;
	}

	public function likeEntry($entryid)
	{
		$sql = "INSERT INTO gefaelltMir (autor_user, gefallender_entry) VALUES (:autor_user, :gefallender_entry)";
		$params = array(":autor_user" => $this->username, ":gefallender_entry" => $entryid);
		sql::exe($sql, $params);
	}

	public function dislikeEntry($entryid)
	{
		$sql = "DELETE FROM gefaelltMir WHERE autor_user = :autor_user AND gefallender_entry = :gefallender_entry";
		$params = array(":autor_user" => $this->username, ":gefallender_entry" => $entryid);
		sql::exe($sql, $params);
	}
}

?>