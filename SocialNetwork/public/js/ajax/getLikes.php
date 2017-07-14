<?php
echo "Bis hierhin funktioniert es";


$sql = "SELECT * FROM entry_gefaelltMir WHERE gefallender_entry = :entryId";
$params = array(":entryId" => $_GET['id']);
echo "nach initialisierung";

require_once("../../../resources/config.php");
require_once(CLASSES_PATH ."/sql.php");
echo "nach requiren";
$results = sql::exe($sql, $params);
echo "nach datenbankung";
/*
echo "<ul>";
foreach($results as $result) {
	?>
	<li>
		<?= $result['autor_user']?> gefällt das
	</li>
	<?php
}
echo "</ul>";
*/
?>