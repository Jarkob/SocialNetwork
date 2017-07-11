<?php

class sql
{
	protected static $pdo;

	public static function connect($host, $username, $password, $dbname)
	{
		//Verbindung herstellen
		try{
			//wenn $database übergeben wird, wird zu der Datenbank eine Verbindung hergestellt
			$database = ';dbname='. $dbname;
			
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
		$statement = self::$pdo->prepare($sql);
		
		//bind_para ist ein Platzhalter für z.b. Limit Sachen,
		//die brauchen nämlich einen Integer es werden aber defaultmäßig
		//nur strings übergeben
		if(($params !== null) && (strpos($sql, ' LIMIT:') !== false) || (strpos($sql, ' limit:') !== false)) {
			$bind_para = true;
		} else {
			$bind_para = false;
		}

		if($bind_para && is_array($params)) {
			foreach($params as $key => &$val){
				if(is_string($val)){
					$statement->bindParam($key, $val, PDO::PARAM_STR);
				}
				elseif(is_bool($val)){
					$statement->bindParam($key, $val, PDO::PARAM_BOOL);
				}
				elseif(is_null($val)){
					$statement->bindParam($key, $val, PDO::PARAM_NULL);
				}
				elseif(is_numeric($val)){
					$statement->bindParam($key, $val, PDO::PARAM_INT);
				}
				else {
					$statement->bindParam($key, (string)$val, PDO::PARAM_STR);
				}
			}
			$params = null;
		}

		//error Kram
		if(!$statement->execute($params)){
			$err_info   = $statement->errorInfo();
			$sql_state  = $err_info[0];
			$ecode	  = $err_info[1];
			$emsg	   = $err_info[2];
			// um mit der meldung am ende etwas anfangen zu können,
			// bezeichen wir diese werte in dem zu erstellenden string:
			$sql_state  = '(SQLSTATE: ' . $sql_state . ')';
			$ecode	  = '(eCode: ' . $ecode . ')';
			$emsg	   = 'eMessage: ' . $emsg;
			// alles zusammen gefügt:
			$error = $sql_state . ' ' . $emsg . ' ' . $ecode;
			// und um später auch zu wissen bei welcher query dieser fehler auftrat setzen wir -
			// die $sql und $para_copy (hier kommt die kopie von oben zum einsatz) hinten dran:
			// überflüssige leerzeichen auf $sql entfernen
			$sql = preg_replace('/\s+/', ' ', $sql);
			// aus dem array $para_copy einen string erstellen:
			$para_sring = '';
			if($para_copy){
				foreach($para_copy as $k => $v){
					$para_sring .= ($para_sring === '') ? '' : '; ';
					$para_sring .= ((strpos($k, ':') !== false) ? '' : ':') . $k . ' => ' . $v;
				}
			}
			$error .= 'query: ' . $sql . ' para: ' . $para_sring;
			// jetzt werfen wir den fehler nach "draußen"
			// ! achtung: in den parametern $para könnten benutzerdaten wie zB passwörter enthalten sein!
			// daher sollte der hier erstellte und nach draußßen geworfene error-string nicht ausgegeben werden!

			// nur für den Notfall
			//echo $error;
			
			throw new Exception($error);
		}
		// beim ausführen (->execute) trat kein fehler auf.
		// jetzt holen wir uns alle datensätze (zeilen) die wir bekommen können.
		// dabei setzen wit $result ersteinmal auf NULL.
		// wenn nichts "zu holen" gab (wir waren nicht einmal in der while-schleife),
		// dann gibt es kein ergebnis (also NULL)
		$result = null;
		while($row = $statement->fetch(PDO::FETCH_ASSOC)){
			// beim ersten durchlauf setzen wir $result als array
			if($result === null){
				$result = array();
			}
			// sammeln der daten im array $result
			$result[] = $row;
		}
		// hier setzen wir $stmt auf null (nicht unbedingt notwendig)
		$statement = null;
		// fertig: ergebnis ($result) zurückgeben
		return $result;
	}


	public static function lastInsertId()
	{
		//lastInsertId prüfen
		return self::$pdo->lastInsertId();
	}
}

?>
