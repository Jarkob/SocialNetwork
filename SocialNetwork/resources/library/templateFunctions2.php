<?php

function renderPage()
{
	//hier kommt das skript an
	require_once(VIEWS_PATH ."header.view.php");

	$view = VIEWS_PATH;
echo "nutten";
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