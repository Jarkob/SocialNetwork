<?php

session_start();

require_once(realpath(dirname(__FILE__) ."/../resources/config2.php"));
echo "Hallo Welt";
require_once(LIBRARY_PATH ."/templateFunctions2.php");
echo "vor dem Aufruf der rendepagefunktion"
renderPage();
?>
