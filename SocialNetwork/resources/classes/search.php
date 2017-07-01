<?php
require_once(CLASSES_PATH ."/sql.php");

class search
{
	public static function suchen($q)
	{
		$searchString = '%'. $_GET['search'] .'%';
		$sql = "SELECT * FROM user WHERE username LIKE :searchString";
		$params = array(':searchString' => $searchString);
		$results = sql::exe($sql, $params);
		return $results;
	}
}
?>