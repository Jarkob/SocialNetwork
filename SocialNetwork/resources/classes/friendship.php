<?php

// Stellt die Freundschaft zwischen zwei Usern dar
class friendship
{
	protected $id;
	protected $freund1;
	protected $freund2;

	public function __construct($id)
	{

	}

	public static function createNewFriendship($freund1, $freund2)
	{
		$sql = "INSERT INTO friendship
			(freund1, freund2)
			VALUES (:freund1, :freund2)";
		$params = array(":freund1" => $freund1, ":freund2" => $freund2);
		sql::exe($sql, $params);
	}

	public static function checkFriendship($freund1, $freund2)
	{
		$sql = "SELECT * FROM friendship
			WHERE (freund1 = :freund1 AND freund2 = :freund2)
			OR (freund1 = :freund2 AND freund2 = :freund1)";
		$params = array(":freund1" => $freund1, ":freund2" => $freund2);
		$result = sql::exe($sql, $params);//tricky, könnte falsch sein
		if(sizeof($result) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function deleteFriendship()
	{
		$sql = "DELETE FROM friendship
			WHERE id = :id";
		$params = array(":id" => $this->id);
		sql::exe($sql, $params);
		//settype(&$this, 'null');
	}
}

?>