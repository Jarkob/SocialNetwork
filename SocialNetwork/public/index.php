<?php
session_start();

require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

require_once(LIBRARY_PATH . "/templateFunctions.php");

renderLayoutWithContentFile();

echo "This is the index.php file.";

?>