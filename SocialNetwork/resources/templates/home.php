<div id="home">

<?php
$loggedIn = getLoginStatus(session_id());

if($loggedIn) {
	$username = getUserName(session_id());

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');


	//neuer Eintrag
	require_once(TEMPLATES_PATH . "/newEntry.php");


	$friends = getFriends($username);


	//zuerst müssen alle einträge gezählt werden, dann auf seiten verteilt werden

	$sql = "SELECT * FROM entry WHERE autor = '". $username;
	foreach($friends as $friend) {
		$sql .= "' OR autor = '". $friend;
	}
	$sql .= "' ORDER BY zeit DESC";

	$statement = $pdo->prepare($sql);
	$statement->execute();
	
	$anzahlEintraege = $statement->rowCount();
	$anzahlProSeite = 10;
	$anzahlSeiten = $anzahlEintraege / $anzahlProSeite;
	

	if (empty($_GET['nr'])) {
	    $seite = 1;
	} else {
	    $seite = $_GET['nr'];
	    if ($seite > $anzahlSeiten) {
	        $seite = 1;
	    }
	}

	$start = ($seite * $anzahlProSeite) - $anzahlProSeite;
	$limit = $start + 10;

	$sql = "SELECT * FROM entry WHERE autor = '". $username;
	foreach($friends as $friend) {
		$sql .= "' OR autor = '". $friend;
	}
	$sql .= "' ORDER BY zeit DESC LIMIT ". $limit ." OFFSET ". $start;

	$statement = $pdo->prepare($sql);
	$statement->execute();
	
	while($row = $statement->fetch()) {
		renderEntry($row['id']);
	}

	echo "<ul>";
	for ($i = 1; $i <= $anzahlSeiten; $i++) {
    	if ($seite == $i) {
        ?>
        <li>
        	<a href="?page=home&nr=<?= $i?>"><b>Seite <?= $i?></b></a>
        </li>
        <?php

    	} else {
    	?>
    	<li>
        	<a href="?page=home&nr=<?= $i?>">Seite <?= $i?></a>
        </li>
        <?php
    	}
    }
    echo "</ul>";
} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}
?>
</div>