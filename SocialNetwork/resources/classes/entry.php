<?php

require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");

class entry
{
	protected $author;
	protected $content;
	protected $id;

	public function __construct($author, $content, $id=null)
	{
		if($id != null) {
			$this->id = $id;
		}
		$this->author = $author;
		$this->content = $content;
	}

	public static function getEntries(user $user)
	{
		
	}

	public function createNewEntry()
	{
		if($this->id == null) {
			$sql = "INSERT INTO entry (content, autor) VALUES (:content, :autor)";
			$params = array(':content' => $this->content, ':autor' => $this->author);

			$sql::exe($sql, $params);
		} else {
			?>
			<p>Der Eintrag existiert bereits.</p>
			<?php
		}
	}
	
	public function renderEntry()
	{
		$sql = "SELECT * FROM entry WHERE id = :id";
		$params = array(":id" => $this->id);
		$result = sql::exe($sql, $params);
		?>
		<div class="entry">
			<p>
				<i><?= $result[0]['zeit']?></i>
			</p>
			<p>
				<a href="?page=profile&owner=<?= $result[0]['autor']?>"><b><?= $result[0]['autor']?></b></a>
			</p>
			<p>
				<?= $result[0]['content']?>
			</p>
		</div>
		<?php
	}
}

?>
