<div id="newEntry">
<h3>Neuer Post</h3>
<form id="entryForm" action="index.php?page=home" method="post">
	<textarea name="content"></textarea>
	<button>Posten</button>
</form>

</div>

<?php

if(array_key_exists('content', $_POST)) {

	$username = getUserName(session_id());

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');

	$sql = "INSERT INTO entry (content, autor) VALUES (:content, :autor";
	$statement = $pdo->prepare($sql);
	$statement->execute(array(':content' => $_POST['content'], ':autor' => $username));
}

?>