<?php

class message
{
	protected $id;
	protected $content; 
	protected $sender;
	protected $empfaenger;
	protected $verlauf;

	public function getId()
	{
		return $this->id;
	}
	public function getContent()
	{
		return $this->content;
	}
	public function getSender()
	{
		return $this->sender;
	}
	public function getEmpfaenger()
	{
		return $this->empfaenger;
	}
	public function getVerlauf()
	{
		return $this->verlauf;
	}

	public function __construct($id)
	{
		$result = message::findMessageById($id);
		$this->id = $id;
		$this->content = $result[0]['content'];
		$this->sender = $result[0]['sender_id'];
		$this->empfaenger = $result[0]['empfaenger_id'];
		$this->verlauf = $result[0]['verlauf_id'];
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

	public function renderMessage()
	{
		?>
		<div class="message">
			<h4>
				<?= $this->getSender()?>
			</h4>
			<p>
				<?= $this->getContent()?>
			</p>
		</div>
		<?php
	}
}
?>