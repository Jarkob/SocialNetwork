<?php

// Benachrichtigungsklasse
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

	public function setId($id)
	{
		$this->id = $id;
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

	// Erstellt ein notification Objekt anhand einer id und gibt es zurück
	public static function findNotificationById($id)
	{
		$sql = "SELECT * FROM notification WHERE id = :id";
		$params = array(":id" => $id);
		$results = sql::exe($sql, $params);
		$result = $results[0];
		$notification = new notification($result['user'], $result['type'], $result['typeid']);
		$notification->setId($id);
		return $notification;
	}

	// Erstellt ein notification Objekt anhand eines types und einer typeId und gibt es zurück
	public static function findNotificationByTypeAndTypeId($type, $typeId)
	{
		$sql = "SELECT * FROM notification WHERE type = :type AND typeid = :typeid";
		$params = array(":type" => $type, ":typeid" => $typeId);
		$results = sql::exe($sql, $params);
		$result = $results[0];
		$notification = new notification($result['user'], $type, $typeId);
		$notification->setId($result['id']);
		return $notification;
	}

	// Gibt als Boolean zurück ob eine Benachrichtigunh zu einem Objekt existiert
	public static function checkNotificationByTypeAndTypeId($type, $typeId)
	{
		$sql = "SELECT * FROM notification WHERE type = :type AND typeid = :typeid";
		$params = array(":type" => $type, ":typeid" => $typeId);
		$results = sql::exe($sql, $params);

		if(sizeof($results) != 0) {
			return true;
		} else {
			return false;
		}
	}

	public function renderNotification()
	{
		switch($this->getType()) {
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

	// Schreibt Daten aus einem notification Objekt in die Datenbank
	public function createNewNotification()
	{
		$sql = "INSERT INTO notification (user, type, typeid) VALUES (:user, :type, :typeid)";
		$params = array(":user" => $this->getUsername(), ":type" => $this->getType(), ":typeid" => $this->getTypeId());
		sql::exe($sql, $params);
	}

	// Löscht Datensatz aus der Datenbank anhand eines bestehenden notification Objektes
	public function deleteNotification()
	{
		$sql = "DELETE FROM notification WHERE id = :id";
		$params = array(":id" => $this->getId());
		sql::exe($sql, $params);
	}
}
?>