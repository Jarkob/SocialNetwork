<div id="profile">
<?php
//wenn der User eingeloggt ist, soll sein Profil angezeigt werden,
//wenn nicht, soll er zu login.php weitergeleitet werden
//
//diese Seite wird ähnlich wie home.php

$profilVon = $_GET['owner'];

renderProfile($pdo, $profilVon);

?>
</div>