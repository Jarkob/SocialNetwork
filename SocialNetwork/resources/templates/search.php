<div id="search">

	<form action="index.php?page=search&" method="get">
		<input type="hidden" name="page" value="<?= $_GET["page"]?>">
		<input name="suche">
		<button>Suchen</button>
	</form>

</div>

<?php

if(array_key_exists('suche', $_GET)) {
	$searchResults = array();

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$suchString = '%'. $_GET['suche'] .'%';
	$sql = "SELECT * FROM user WHERE username LIKE ?";
	$statement = $pdo->prepare($sql);
	$statement->execute(array($suchString));
	while($row = $statement->fetch()) {
		$searchResults[] = $row['username'];
	}

	//zeige Suchergebnisse an
	if(count($searchResults) > 0) {
		foreach($searchResults as $person) {
			//zeige Person an
			?>
			<p><a href="index.php"><?= $person?></a></p>
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