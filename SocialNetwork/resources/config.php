<?php
$config = array(
	"db" => array(
		"db1" => array(
			"dbname" => "socialnetwork",
			"username" => "root",
			"password" => "root",
			"host" => "localhost"
		),
		"db2" => array(
			"dbname" => "socialnetwork",
			"username" => "root",
			"password" => "root",
			"host" => "192.168.178.20",
			"port" => "8888"
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

defined("LIBRARY_PATH")
	or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("TEMPLATES_PATH")
	or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

defined("SITE_NAME")
	or define("SITE_NAME", "Freundeverzeichnis");


ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);


try {
  $pdo = new PDO("mysql:host=localhost;dbname=socialnetwork", 'root', 'root', array(
  PDO::ATTR_PERSISTENT => true
));
} catch(PDOException $e) {
  echo $e->getMessage();
}
?>