<?php

require_once(CLASSES_PATH ."/sql.php");

class entry
{
	public static function getEntries(user $user)
	{
		
	}
	
	public static function renderEntry($id)
	{
		$sql = "SELECT * FROM entry WHERE id = :id";
		$params = array(":id" => $id);
		$result = sql::exe($sql, $params);
		?>
		<div class="entry">
			<p><?= $result[0]['zeit']?></p>
			<p><a href="?page=profile&owner=<?= $result[0]['username']?>><?= $result[0]['username']?></a></p>
			<p>
				<?= $result[0]['content']?>
			</p>
		</div>
		<?php
	}
}

?>
