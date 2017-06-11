<?php

class login
{
	
	public static function loginUser($username, $password)
	{
		$sql = "SELECT * FROM user
			WHERE username = :username";
		$params = {":username" => $username}
		$result = sql::exe($sql, $params);
		/*if(password_verify($password, $result[0['passwort']])) {
			require_once(CLASSES_PATH ."/user.php");
			$user = new user($username);
			$user->changeSid();
			return true;
		} else {
			return false;
		}*/
	}

	public static function isLoggedIn($sid)
	{
		$sql = "SELECT * FROM user
			WHERE sid = :sid";
		$params = {":sid" => $sid}
		$result = sql::exe($sql, $params);
		if(sizeof($result) > 0) {
			return true;
		} else {
			return false;
		}
	}

}

?>