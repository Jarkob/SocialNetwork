<?php

class notification
{
	protected $id;
	protected $username;

	// Kann für Nachrichten, oder Freundschaftsanfragen sein, erstmal nur Freundschaftsanfragen
	protected $type;
	protected $typeId;
	protected $seen;

	public function getId()
	{
		return $this->id;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getTypeId()
	{
		return $this->typeId;
	}

	public function getSeen()
	{
		return $this->seen;
	}

	public function __construct($username, $type, $typeId)
	{
		$this->username = $username;
		$this->type = $type;
		$this->typeId = $typeId;
		$this->seen = false;
	}

	public static function findNotificationById($id)
	{
		$sql = "SELECT * FROM notification WHERE id = :id";
		$params = array(":id" => $id);
		$results = sql::exe($sql, $params);
		$result = $results[0];
		$notification; //muss fertig gemacht werden und zurückgegeben
	}
}
?>