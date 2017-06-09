<?php
//unnötiges Konfigurationsarray, welches nie benutzt wird, obwohl es benutzt werden sollte
$config = array(
	"db" => array(
		"local" => array(
			"dbname" => "socialnetwork",
			"username" => "root",
			"password" => "root",
			"host" => "localhost"
		),
		"azure" => array(
			"dbname" => "socialnetwork",
			"username" => "azure",
			"password" => "Iggibib!",
			"host" => "localhost",
			"port" => "49925"
		)
	),
	"urls" => array(
		"baseUrl" => "http://example.com"
	),
	"paths" => array(
		"resources" => "/path/to/resources",
		"images" => array(
			"content" => $_SERVER["DOCUMENT_ROOT"] . "images/content",
			"layout" => $_SERVER["DOCUMENT_ROOT"] . "images/layout"
		)
	)
);


//wichtige Pfade
defined("LIBRARY_PATH")
	or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("TEMPLATES_PATH")
	or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

defined("CLASSES_PATH")
	or define("CLASSES_PATH", realpath(dirname(__FILE__) . '/classes'));

defined("SITE_NAME")
	or define("SITE_NAME", "youwho");


//Keine Ahnung irgendwas mit errors
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);


//Datenbankverbindung
//lokal: mysql:host=localhost;dbname=socialnetwork", "root", "root"
//azure: "mysql:host=localhost;port=49925;dbname=socialnetwork", "azure", "Iggibib!"
//$databaseType = 'azure';
try {
  $pdo = new PDO("mysql:host=localhost;port=49925;dbname=socialnetwork", 'azure', 'Iggibib!', array(
  PDO::ATTR_PERSISTENT => true
	));
	global $pdo;
} catch(PDOException $e) {
  echo $e->getMessage();
}

//fortschrittliche Alternative
require_once(CLASSES_PATH . '/sql.php');
//sql::connect("localhost;port=49925", "azure", "Iggibib!", "socialnetwork");
?>
