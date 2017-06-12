<?php
session_start();
echo "vor config";
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
echo "vor tfunktionen";
//require_once(LIBRARY_PATH . "/templateFunctions.php");
global $pdo;
//renderLayoutWithContentFile($pdo);

?>