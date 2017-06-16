<?php

session_start();
require_once(realpath(dirname(__FILE__) ."/../resources/config2.php"));
require_once(LIBRARY_PATH ."/templateFunctions2.php");
renderPage();
?>
