<?php
$username = getUsername($pdo, session_id());
logoutFunction($pdo, $username);
?>