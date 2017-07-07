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

	}

	public static function createNewMessage($content, $sender, $empfaenger)
	{
		$sql = "SELECT * FROM verlauf WHERE ";//ka
	}
}
?>