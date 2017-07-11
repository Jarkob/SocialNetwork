<?php

// Stellt Freundschaftsanfragen dar
class friendrequest
{
	protected $id;
	protected $sender;
	protected $empfaenger;
	public function getId()
	{
		return $this->id;
	}
	public function getSender()
	{
		return $this->sender;
	}
	public function getEmpfaenger()
	{
		return $this->empfaenger;
	}

	public function __construct($id)
	{
		$sql = "SELECT * FROM friendrequest
			WHERE id = :id";
		$params = array(":id" => $id);
		$result = sql::exe($sql, $params);
		$this->id = $id;
		$this->sender = $result[0]['sender_friendrequest'];
		$this->empfaenger = $result[0]['empfaenger_friendrequest'];
	}

	// Erstellt eine neue Freundschaftsanfrage in der Datenbank anhand der Benutzernamen des Senders und des Empfängers
	public static function createNewFriendrequest($sender, $empfaenger)
	{
		$sql = "INSERT INTO friendrequest
			(sender_friendrequest, empfaenger_friendrequest)
			VALUES (:sender, :empfaenger)";
		$params = array(":sender" => $sender, ":empfaenger" => $empfaenger);
		sql::exe($sql, $params);
	}

	// Prüft, ob in der Datenbank bereits eine Freundschaftsanfrage von Sender an Empfänger existiert und gibt das Ergebnis als Boolean zurück.
	public static function checkFriendrequest($sender, $empfaenger)
	{
		$sql = "SELECT * FROM friendrequest
			WHERE sender_friendrequest = :sender AND empfaenger_friendrequest = :empfaenger";
		$params = array(":sender" => $sender, ":empfaenger" => $empfaenger);
		$result = sql::exe($sql, $params);
		if(sizeof($result) != 0) {
			return true;
		} else {
			return false;
		}
	}

	// Gibt die Rohdaten aus der Datenbank zu einem friendrequest Datensatz anhand der id zurück.
	public static function getFriendrequestById($id)
	{
		$sql = "SELECT * FROM friendrequest
			WHERE id = :id";
		$params = array(":id" => $id);
		$result = sql::exe($sql, $params);
		return $result;
	}

	// Gibt die Rohdaten aus der Datenbank zu einem friendrequest Datensatz zurück.
	// Die optionalen Parameter dabei sind der Sender und der Empfänger.
	public static function getFriendrequestByParticipating($sender=null, $empfaenger=null)
	{
		if($sender == null) {
			$sql = "SELECT * FROM friendrequest WHERE empfaenger_friendrequest = :empfaenger";
			$params = array(":empfaenger" => $empfaenger);
		} else if($empfaenger == null) {
			$sql = "SELECT * FROM friendrequest WHERE sender_friendrequest = :sender";
			$params = array(":sender" => $sender);
		} else if($empfaenger != null && $sender != null) {
			$sql = "SELECT * FROM friendrequest
				WHERE sender_friendrequest = :sender AND empfaenger_friendrequest = :empfaenger";
			$params = array(":sender" => $sender, ":empfaenger" => $empfaenger);
		} else {
			echo "Alarm, Alarm";
			$sql = "SELECT * FROM friendrequest";
			$params = array();
		}
		$result = sql::exe($sql, $params);
		return $result;
	}

	// Löscht einen friendrequest Datensatz anhand eines friendrequest Objekts.
	public function deleteFriendrequest()
	{
		$sql = "DELETE FROM friendrequest
			WHERE id = :id";
		$params = array(":id" => $this->id);
		sql::exe($sql, $params);
	}
}

?>