<?php

require_once(realpath(dirname(__FILE_) ."/../config2.php"));
require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");

class logout
{
	public static function logoutUser(user $user)
	{
		$sql = "UPDATE user SET sid = 'loggedout'
			WHERE username = :username";
		$params = array(":username" => $user.getUsername());
		sql::exe($sql, $params);
	}
}

?>