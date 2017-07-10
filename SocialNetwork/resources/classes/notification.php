<?php

class notification
{
	protected $id;
	protected $username;

	// Kann für Nachrichten, oder Kommentare, oder Freundschaftsanfragen sein, erstmal nur Freundschaftsanfragen
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

	public function renderNotification()
	{
		switch($this->getType) {
			case "friendrequest":
				?>
				<p>
					<a href="?page=friendrequests">Neue Freundschaftsanfrage</a>
				</p>
				<?php
				break;
			case "comment":
				break;
			case "message":
				break;
			default:
				break;
		}
	}

	public function createNewNotification()
	{
		$sql = "INSERT INTO notification (user, type, typeid) VALUES (:user, :type, :typeid)";
		$params = array(":user" => $this->getUsername(), ":type" => $this->getType(), ":typeid" => $this->getTypeId());
		sql::exe($sql, $params);
	}
}
?>