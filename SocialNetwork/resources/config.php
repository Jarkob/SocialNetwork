<?php

defined("LIBRARY_PATH")
	or define("LIBRARY_PATH", realpath(dirname(__FILE__) ."/library"));

defined("CLASSES_PATH")
	or define("CLASSES_PATH", realpath(dirname(__FILE__) ."/classes"));

defined("VIEWS_PATH")
	or define("VIEWS_PATH", realpath(dirname(__FILE__) ."/views"));

defined("TEMPLATES_PATH")
	or define("TEMPLATES_PATH", realpath(dirname(__FILE__) ."/templates"));

defined("SITE_NAME")
	or define("SITE_NAME", "youwho");


$dbconfig = array("local" => array(
						"host" => "localhost",
						"username" => "root",
						"password" => "root",
						"dbname" => "socialnetwork"
					),
					"azure" => array(
						// "host" => "localhost;port=49925",
						"host" => "127.0.0.1;port=49925",
						"port" => "49925",
						"username" => "azure",
						"password" => "Iggibib!",
						"dbname" => "socialnetwork"
					)
				);

require_once(CLASSES_PATH ."/sql.php");

// Wenn es eine lokale Verbindung ist, soll die lokale Datenbankverbindung genutzt werden, sonst die azure
if($_SERVER['REMOTE_ADDR']=='127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {
	define('ENV', 'local');
}
echo $_SERVER['REMOTE_ADDR'];
//if(ENV == 'local') {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	// sql::connect($dbconfig["local"]["host"], $dbconfig["local"]["username"], $dbconfig["local"]["password"], $dbconfig["local"]["dbname"]);
//} else {
// 	ini_set("error_reporting", "true");
// 	error_reporting(E_ALL|E_STRICT);
	sql::connect($dbconfig["azure"]["host"], $dbconfig["azure"]["username"], $dbconfig["azure"]["password"], $dbconfig["azure"]["dbname"]);
// }

?>