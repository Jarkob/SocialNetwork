<?php

function renderPage()
{
	$view = TEMPLATES_PATH;

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
}

?>