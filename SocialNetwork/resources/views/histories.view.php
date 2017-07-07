<h2>Nachrichten</h2>
<?php

require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/history.php");

$username = findUserBySid(session_id());
$histories = getHistories($username);
// Fazit: Nachrichten auf diese Weise zu machen ist schlecht.
?>