<?php

$sql = "SELECT * FROM entry_gefaelltMir WHERE gefallender_entry = :entryId";
$params = array(":entryId" => $_GET['id']);

require_once("../../../resources/config.php");
require_once(CLASSES_PATH ."/sql.php");
$results = sql::exe($sql, $params);

echo "<ul>";

foreach($results as $result) {
	echo "<li>";
	echo $result['autor_user']. " gefällt das";
	echo "</li>";
}

echo "</ul>";

?>