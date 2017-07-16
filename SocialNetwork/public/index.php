<?php
echo "ab angeanf";
session_start();
require_once(realpath(dirname(__FILE__) ."/../resources/config.php"));
echo "nach requiren von config.php";

require_once(LIBRARY_PATH ."/templateFunctions.php");
echo "nach requiren von tfunctions";
//renderPage();
?>
