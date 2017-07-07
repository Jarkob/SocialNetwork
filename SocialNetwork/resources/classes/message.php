<?php

class message
{
	protected $id;
	protected $content; 
	protected $sender;
	protected $empfaenger;
	protected $verlauf;

	public getId()
	{
		return $this->id;
	}
	public getContent()
	{
		return $this->content;
	}
	public getSender()
	{
		return $this->sender;
	}
	public getEmpfaenger()
	{
		return $this->empfaenger;
	}
	public getVerlauf()
	{
		return $this->verlauf;
	}

	public function __construct($id)
	{
		$result = findMessageById($id);
		$this->id = $id;
		$this->content = $result[0]['content'];
		$this->sender = $result[0]['sender_id'];
		$this->empfaenger = $result[0]['empfaenger_id'];
		$this->verlauf = $result[0]['verlauf_id']
	}

	public static function createNewMessage($content, $sender, $empfaenger, $verlauf)
	{
		$sql = "INSERT INTO message (content, sender_id, empfaenger_id, verlauf_id) VALUES (:content, :sender, :empfaenger, :verlauf)";
		$params = array(":content" => $content, ":sender" => $sender, ":empfaenger" => $empfaenger, ":verlauf" => $verlauf);
		sql::exe($sql, $params);
	}

	public static function findMessageById($id)
	{
		$sql = "SELECT * FROM message WHERE id = :id";
		$params = array(":id" => $id);
		$result = sql::exe($sql, $params);
		return $result;
	}
}
?>