<?php
echo "in friendrequest";
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

	public static function createNewFriendrequest($sender, $empfaenger)
	{
		$sql = "INSERT INTO friendrequest
			(sender_friendrequest, empfaenger_friendrequest)
			VALUES (:sender, :empfaenger)";
		$params = array(":sender" => $sender, ":empfaenger" => $empfaenger);
		sql::exe($sql, $params);
	}

	public static function checkFriendrequest($sender, $empfaenger)
	{
		$sql = "SELECT * FROM friendrequest
			WHERE sender_friendrequest = :sender AND empfaenger_friendrequest = :empfaenger";
		$params = array(":sender" => $sender, ":empfaenger" => $empfaenger);
		$result = sql::exe($sql, $params);
		if(sizeof($result) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public static function getFriendrequestById($id)
	{
		$sql = "SELECT * FROM friendrequest
			WHERE id = :id";
		$params = array(":id" => $id);
		$result = sql::exe($sql, $params);
		return $result;
	}

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
				WHERE sender_friendrequest = :sender AND empfaenger_friendrequest = :empfaengerf";
			$params = array(":sender" => $sender, ":empfaenger" => $empfaenger);
		} else {
			echo "Alarm, Alarm";
			return array("Alarm" => "Alarm");
			break;
		}
		$result = sql::exe($sql, $params);
		return $result;
	}

	public function deleteFriendrequest()
	{
		$sql = "DELETE FROM friendrequest
			WHERE id = :id";
		$params = array(":id" => $this->id);
		sql::exe($sql, $params);
	}
}

?>