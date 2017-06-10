<?php

function renderPage()
{
	echo "<p>funktion erreicht</p>";
	require_once(VIEWS_PATH ."header.view.php");

	$view = VIEWS_PATH;

	if(isset($_GET['page'])) {
		switch($_GET['page']) {
			case 'nutten':
				break;
			default:
				break;
		}
	} else {
		$view = $view . 'home.view.php';
	}

	require_once($view);

	require_once(VIEWS_PATH ."footer.view.php");
}

?>