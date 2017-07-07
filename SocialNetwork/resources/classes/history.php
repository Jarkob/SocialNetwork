<?php

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

	public function __construct($teilnehmer1, $teilnehmer2)
	{
		$this->teilnehmer = array();
		$this->teilnehmer[] = $teilnehmer1;
		$this->teilnehmer[] = $teilnehmer2;

		$sql = "SELECT * FROM verlauf WHERE (teilnehmer1 = :teilnehmer1 AND teilnehmer2 = :teilnehmer2) OR (teilnehmer1 = :teilnehmer2 AND teilnehmer2 = :teilnehmer1)";
		$params = array(":teilnehmer1" => $teilnehmer1, ":teilnehmer2" => $teilnehmer2);
		$result = sql::exe($sql, $params);
		$this->id = $result[0]['id'];
	}


}
?>
