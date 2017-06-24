<?php

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

	public function sendFriendrequest($empfaenger)
	{
		friendrequest::createNewFriendrequest($this->username, $empfaenger);
	}

	public function acceptFriendrequest(friendrequest $friendrequest)
	{
		friendship::createNewFriendship($friendrequest->getSender(), $friendrequest->getEmpfaenger());
		$friendrequest->deleteFriendrequest();
	}

	public function declineFriendrequest(friendrequest $friendrequest)
	{
		$friendrequest->deleteFriendrequest();
	}

	public function changeSid()
	{
		$sql = "UPDATE user SET sid = :sid
			WHERE username = :username";
		$params = array(":sid" => session_id(), ":username" => $this->username);
		sql::exe($sql, $params);
	}

	// Gibt einen array mit Freunden zurück
	public function getFriends()
	{
		$sql = "SELECT * FROM friendship WHERE freund1 = :username OR freund2 = :username";
		$params = array(":username" => $this->username);
		$result = sql::exe($sql, $params);
		$friends = array();
		for($i = 0; $i < sizeof($result); $i++) {
			$friends[] = new user($result[$i]['username']);
		}

		return $friends;
	}
}

?>