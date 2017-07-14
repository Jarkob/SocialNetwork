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

			$this->setId(sql::lastInsertId());
		} else {
			?>
			<p>Der Eintrag existiert bereits.</p>
			<?php
		}
	}

	public function getLikes()
	{
		$sql = "SELECT * FROM comment_gefaelltMir WHERE gefallender_comment = :commentid";
		$params = array(":commentid" => $this->id);
		$result = sql::exe($sql, $params);
		return sizeof($result);
	}

	public function hasUserLiked($username)
	{
		$sql = "SELECT * FROM comment_gefaelltMir WHERE autor_user = :autor_user AND gefallender_comment = :gefallender_comment";
		$params = array(":autor_user" => $username, ":gefallender_comment" => $this->id);
		$result = sql::exe($sql, $params);
		if(sizeof($result) != 0) {
			return true;
		} else {
			return false;
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
			<p class="time">
				<i>
					<?php
					$time = new DateTime($result[0]['zeit']);

					$sql = "SELECT CURRENT_TIMESTAMP";
					$timeresult = sql::exe($sql);
					$actualTime = new DateTime($timeresult[0]['CURRENT_TIMESTAMP']);

					$betweenTime = $time->diff($actualTime);

					// Andersherum
					$difference = $betweenTime->format("%d");
					if($difference > 2) {
						echo $time->format("j. F Y, H:i");
					} else if($difference == 2) {
						echo "Vorgestern";
					} else if($difference == 1) {
						echo "Gestern";
					} else {
						$difference = $betweenTime->format("%h");
						if($difference >= 1) {
							echo "Vor ". $difference ." Stunden";
						} else {
							$difference = $betweenTime->format("%i");
							if($difference >= 1) {
								echo "Vor ". $difference ." Minuten";
							} else {
								echo "Vor wenigen Sekunden";
							}
						}
					}
					?>
				</i>
			</p>
			<h4>
				<a href="?page=profile&owner=<?= $result[0]['autor']?>">
					<?php
					if(file_exists("img/content/profile/". $result[0]['autor'] .".jpg")) {
						?>
						<img id="profileIcon" title="<?= $row['autor']?>" src="img/content/profile/<?= $result[0]['autor']?>.jpg" style="width: 20px;">
						<?php
					} else if(file_exists("img/content/profile/". $result[0]['autor'] .".png")) {
						?>
						<img id="profileIcon" title="<?= $result[0]['autor']?>" src="img/content/profile/<?= $result[0]['autor']?>.png" style="width: 20px;">
						<?php
					} else {
						?>
						<img id="profileIcon" title="<?= $result[0]['autor']?>" src="img/content/profile/default.png" style="width: 20px;">
						<?php
					}
					?>
					<?= $result[0]['autor']?>	
				</a>
			</h4>
			<p>
				<?= $result[0]['content']?>
			</p>
			<p>
				<span>
					<?= $this->getLikes()?> Leuten gefällt das
				</span>
				|
				<span>
					<?php
					if($this->hasUserLiked(user::findUserBySid(session_id()))) {
						?>
						<a href="?page=home&dislikeComment=<?= $this->getId()?>">Gefällt mir nicht mehr</a>
						<?php
					} else {
						?>
						<a href="?page=home&likeComment=<?= $this->getId()?>">Gefällt mir</a>
						<?php
					}
					?>
				</span>
				<?php
				if($this->author == user::findUserBySid(session_id())) {
					?>
					|
					<span>
						<a href="?page=home&deleteComment=<?= $result[0]['id']?>">Löschen</a>
					</span>
					<?php
				}
				?>
			</p>
		</div>
		<?php
	}

	public function deleteComment()
	{
		$sql = "DELETE FROM comment WHERE id = :id";
		$params = array(":id" => $this->getId());
		sql::exe($sql, $params);
	}
}
?>