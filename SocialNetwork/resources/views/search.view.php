<?php
require_once(CLASSES_PATH ."/search.php");
if(array_key_exists('search', $_GET)) {
	$results = search::suchen($_GET['search']);
	//zeige Suchergebnisse an
	?>
	<ul class="list-group">
	<?php
	if(count($results) > 0) {
		foreach($results as $person) {
			//zeige Person an
			?>
			<li class="list-group-item">
				<a href="?page=profile&owner=<?= $person['username']?>"><?= $person['username']?></a>
			</li>
			<?php
		}
	} else {
		?>
		<li class="list-group-item">Ihre Suche hatte leider keine Ergebnisse</li>
		<?php
	}
	?>
	</ul>
	<?php
}

?>