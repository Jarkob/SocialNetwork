<?php

require_once(realpath(dirname(__FILE_) ."/../config2.php"));
require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");

class login
{

	public static function loginUser($username, $password)
	{
		$sql = "SELECT * FROM user
			WHERE username = :username";
		$params = array(":username" => $username);
		$result = sql::exe($sql, $params);
		if(password_verify($password, $result[0]['passwort'])) {
			require_once(CLASSES_PATH ."/user.php");
			$userData = user::findUserByName($username);
			$params = array("username" => $userData[0]["username"], 
				"vorname" => $userData[0]["vorname"], 
				"nachname" => $userData[0]["nachname"], 
				"gebdatum" => $userData[0]["gebdatum"]);
			$user = new user($params);
			$user->changeSid();
			return true;
		} else {
			return false;
		}
	}

	public static function isLoggedIn($sid)
	{
		$sql = "SELECT * FROM user
			WHERE sid = :sid";
		$params = array(":sid" => $sid);
		$result = sql::exe($sql, $params);
		if(sizeof($result) > 0) {
			return true;
		} else {
			return false;
		}
	}

}

?>