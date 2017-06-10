<div id="home">

<?php
$loggedIn = getLoginStatus($pdo, session_id());

if($loggedIn) {
	$username = getUserName($pdo, session_id());

	//hier werden die Likes ausgewertet
	if(isset($_GET['like'])) {
		likeEntry($pdo, $username, $_GET['like']);
	}

	//neuer Eintrag
	require_once(TEMPLATES_PATH . "/newEntry.php");


	$friends = getFriends($pdo, $username);


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
	
	$anyEntrys = false;
	while($row = $statement->fetch()) {
		renderEntry($pdo, $row['id']);
		$anyEntrys = true;
	}

	if(!$anyEntrys) {
		?>
		<p>Tipp: Adde Freunde um ihre Beiträge zu sehen. Du kannst Freunde über die Suche in der Navigationsleiste finden.</p>
		<?php
	}

	?>
	<ul id="seiten">
	<?php
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