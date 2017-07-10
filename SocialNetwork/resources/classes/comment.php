<?php
require_once(CLASSES_PATH ."/entry.php");

class comment extends entry
{
	protected $parentId;

	public function getParentId()
	{
		return $this->parentId;
	}

	public function __construct($author, $content, $parentId, $id=null)
	{
		if($id != null) {
			$this->id = $id;
		}
		$this->author = $author;
		$this->content = $content;
		$this->parentId = $parentId;
	}

	// Gibt comment Objekt zurück
	public static function findCommentById($id)
	{
		$sql = "SELECT * FROM entry WHERE id = :id";
		$params = array(":id" => $id);
		$result = sql::exe($sql, $params);
		$comment = new comment($result[0]['autor'], $result[0]['content'], $result[0]['parent_id'], $result[0]['id']);
		return $comment;
	}

	public function createNewComment()
	{
		if($this->getId() == null) {
			$sql = "INSERT INTO comment (parent_id, content, autor) VALUES (:parent_id, :content, :autor)";
			$params = array(":parent_id" => $this->getParentId(), ":content" => $this->getContent(), ":autor" => $this->getAuthor());
			sql::exe($sql, $params);
		} else {
			?>
			<p>Der Eintrag existiert bereits.</p>
			<?php
		}
	}

	// In den Rendermethoden von Comment und entry geht noch einiges schief
	public function renderComment()
	{
		$sql = "SELECT * FROM comment WHERE id = :id";
		$params = array(":id" => $this->getId());
		$result = sql::exe($sql, $params);
		?>
		<div class="comment">
			<p>
				<i><?= $result['zeit']?></i>
			</p>
			<h5>
				<a href="?page=profile&owner=<?= $result[0]['autor']?>"><?= $result[0]['autor']?></a>
			</h5>
			<p>
				<?= $result[0]['content']?>
			</p>
		</div>
		<?php
	}

	public function deleteComment()
	{
		$sql = "DELETE FROM entry WHERE id = :id";
		$params = array(":id" => $this->getId());
	}
}
?>