<?php

class sql
{
	protected static $pdo;

	public static function connect($host, $username, $password, $dbname=null)
	{
		//Verbindung herstellen
		try{
			//wenn $database übergeben wird, wird zu der Datenbank eine Verbindung hergestellt
			$database = ';dbname=';
			if($database != null) {
				$database += $dbname;
			}
			
			self::$pdo = new PDO('mysql:host='. $host . $database, $username, $password);
			return;
		}catch(PDOException $e){
			throw new Exception($e->getMessage());
		}
	}

	public static function close()
	{
		//Verbindung schließen
		  
	}

	public static function exe()
	{
		//queries ausführen
	}

	public function lastInsertId()
	{
		//lastInsertId prüfen
	}
}

?>
