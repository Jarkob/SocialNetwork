<?php
// Achtung: dient zur Erstellung neuer entries und comments
?>

<div id="newEntry">
	<h3>Neuer Post</h3>
	<form action="index.php" method="post">
		<textarea name="content"></textarea>
		<button type="submit">Posten</button>
	</form>
</div>

<?php

require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/entry.php");
require_once(CLASSES_PATH ."/comment.php");

$username = user::findUserBySid(session_id());

if(array_key_exists('content', $_POST) && !array_key_exists('entry', $_GET)) {
	$entry = new entry($username, $_POST['content']);
	$entry->createNewEntry();
} elseif(array_key_exists('content', $_POST) && array_key_exists('entry', $_GET)) {
	$comment = new comment($username, $_POST['content'], $_GET['entry']);
	$comment->createNewComment();
}

?>