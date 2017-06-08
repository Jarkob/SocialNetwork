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
		self::$pdo = null;
	}

	//$sql ist der Befehl(keine null queries!?), $params ein assoziatives array mit den Parametern
	public static function exe($sql, $params=null)
	{
		//queries ausführen
		$statement = self::$pdo>prepare($sql);
		
		//bind_para ist ein Platzhalter für z.b. Limit Sachen,
		//die brauchen nämlich einen Integer es werden aber defaultmäßig
		//nur strings übergeben
		if(($params !== null) && (strpos($sql, ' LIMIT:') !== false) || (strpos($sql, ' limit:') !== false) {
			$bind_para = true;
		} else {
			$bind_para = false;
		}
	}

	public function lastInsertId()
	{
		//lastInsertId prüfen
	}
}

?>
