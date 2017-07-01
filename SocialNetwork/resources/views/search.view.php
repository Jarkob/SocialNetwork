<?php
echo "ind der search.view.php";
require_once(CLASSES_PATH ."/search.php");
echo "requiren klappt";
if(array_key_exists('search', $_GET)) {
	echo "in der if";
	$results = search::suchen($_GET['search']);
	echo "nach der suche";
	//zeige Suchergebnisse an
	if(count($results) > 0) {
		foreach($results as $person) {
			//zeige Person an
			?>
			<p><a href="?page=profile&owner=<?= $person['username']?>"><?= $person['username']?></a></p>
			<?php
			//Achtung hier muss noch ein Link zum Profil angegeben werden
		}
	} else {
		?>
		<p>Ihre Suche hatte leider keine Ergebnisse</p>
		<?php
	}
	
}

?>