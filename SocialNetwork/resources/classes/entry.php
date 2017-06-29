<?php

require_once(CLASSES_PATH ."/sql.php");

class entry
{
	protected $author;
	protected $content;
	protected $id;

	public function __construct($id, $author, $content)
	{
		$this->id = $id;
		$this->author = $author;
		$this->content = $content;
	}

	public static function getEntries(user $user)
	{
		
	}
	
	public function renderEntry()
	{
		$sql = "SELECT * FROM entry WHERE id = :id";
		$params = array(":id" => $this->id);
		$result = sql::exe($sql, $params);
		?>
		<div class="entry">
			<p><?= $result[0]['zeit']?></p>
			<p><a href="?page=profile&owner=<?= $result[0]['username']?>"><?= $result[0]['username']?></a></p>
			<p>
				<?= $result[0]['content']?>
			</p>
		</div>
		<?php
	}
}

?>
