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

ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRICT);

require_once(CLASSES_PATH ."/sql.php");
sql::connect("localhost;port=49925", "azure", "Iggibib!", "socialnetwork");

?>