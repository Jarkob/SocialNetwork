<?php
echo "Bis hierhin funktioniert es";

$sql = "SELECT * FROM entry_gefaelltMir WHERE gefallender_entry = :entryId";
$params = array(":entryId" => $_GET['id']);
$results = sql::exe($sql, $params);

echo "<ul>";
foreach($results as $result) {
	?>
	<li>
		<?= $result['autor_user']?> gefällt das
	</li>
	<?php
}
echo "</ul>";
?>