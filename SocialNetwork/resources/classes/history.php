<?php
require_once(CLASSES_PATH ."/message.php");

class history
{
	// Array mit den Teilnehmern, so sind auch Gruppenchats möglich, nur noch nicht in der Datenbank
	protected $teilnehmer;
	protected $id;

	public function getTeilnehmer()
	{
		return $this->teilnehmer;
	}

	public function getId()
	{
		return $this->id;
	}

	public function __construct($id)
	{
		$result = history::findHistoryById($id);
		$this->teilnehmer = array();
		$this->teilnehmer[] = $result[0]['teilnehmer1'];
		$this->teilnehmer[] = $result[0]['teilnehmer2'];
		$this->id = $id;
	}

	// Hilfsfunktion, gibt nur das result zurück
	public static function findHistoryById($id)
	{
		$sql = "SELECT * FROM verlauf WHERE id = :id";
		$params = array(":id" => $id);
		return sql::exe($sql, $params);
	}

	public static function doesHistoryExist($teilnehmer1, $teilnehmer2)
	{
		$sql = "SELECT * FROM verlauf WHERE (teilnehmer1 = :teilnehmer1 AND teilnehmer2 = :teilnehmer2) OR (teilnehmer1 = :teilnehmer2 AND teilnehmer2 = :teilnehmer1)";
		$params = array(":teilnehmer1" => $teilnehmer1, ":teilnehmer2" => $teilnehmer2);
		$result = sql::exe($sql, $params);
		if($result != null) {
			return true;
		} else {
			return false;
		}
	}

	// Gibt id eines Verlaufs anhand der Teilnehmer zurück
	public static function findHistoryByParticipating($teilnehmer1, $teilnehmer2)
	{
		$sql = "SELECT * FROM verlauf WHERE (teilnehmer1 = :teilnehmer1 AND teilnehmer2 = :teilnehmer2) OR (teilnehmer1 = :teilnehmer2 AND teilnehmer2 = :teilnehmer1)";
		$params = array(":teilnehmer1" => $teilnehmer1, ":teilnehmer2" => $teilnehmer2);
		$result = sql::exe($sql, $params);
		return $result[0]['id'];
	}

	public function getOtherParticipant($teilnehmer)
	{
		if($this->$teilnehmer[0] == $teilnehmer) {
			return $this->teilnehmer[1];
		} else {
			return $this->teilnehmer[0];
		}
	}

	public static function getHistories($username)
	{
		$sql = "SELECT * FROM verlauf WHERE teilnehmer1 = :username OR teilnehmer2 = :username";
		$params = array(":username" => $username);
		return sql::exe($sql, $params);
	}

	// Gibt alle zugehörigen message Objekte zurück
	public function getMessages()
	{
		$sql = "SELECT * FROM message WHERE verlauf_id = :id";
		$params = array(":id" => $this->getId());
		$results = sql::exe($sql, $params);

		$messages = array();
		foreach($results as $result) {
			$messages = new message($result['id']);
		}
		return $messages;
		// Möglicherweise sind die Nachrichten noch falsch herum geordnet
	}
}
?>
