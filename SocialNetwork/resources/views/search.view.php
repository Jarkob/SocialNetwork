<?php
require_once(CLASSES_PATH ."/search.php");

if(array_key_exists('search', $_GET)) {
	$results = search::Search($_GET['search']);

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