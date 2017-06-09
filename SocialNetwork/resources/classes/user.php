<?php

class user
{
	//alle protected, falls es mal Rollen gibt
	protected $username;
	public function getUsername()
	{
		return $this->username;
	}

	protected $vorname;
	public function getVorname()
	{
		return $this->vorname;
	}

	protected $nachname;
	public function getNachname()
	{
		return $this->nachname;
	}

	protected $gebdatum;
	public function getGebdatum()
	{
		return $this->gebdatum;
	}

	public function __construct($username)
	{
		$params = {"username" => $username}
		$uservalues = exe("SELECT * FROM user WHERE username = ?", $username);
		printf($uservalues);
	}
}

?>