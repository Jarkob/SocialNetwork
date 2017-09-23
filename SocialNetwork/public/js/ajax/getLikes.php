<?php

$sql = "SELECT * FROM entry_gefaelltMir WHERE gefallender_entry = :entryId";
$params = array(":entryId" => $_GET['id']);

require_once("../../../resources/config.php");
require_once(CLASSES_PATH ."/sql.php");
$results = sql::exe($sql, $params);

if($results != null) {
	foreach($results as $result) {
		echo "\n". $result['autor_user']. " gefällt das";
	}
}
?>