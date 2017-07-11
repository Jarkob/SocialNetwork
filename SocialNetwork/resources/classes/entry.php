<?php

require_once(CLASSES_PATH ."/sql.php");
require_once(CLASSES_PATH ."/user.php");
require_once(CLASSES_PATH ."/comment.php");

class entry
{
	protected $author;
	protected $content;
	protected $id;

	public function getAuthor()
	{
		return $this->author;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function changeContent($newContent)
	{
		$this->content = $newContent;
		$sql = "UPDATE entry SET content = :newContent WHERE id = :id";
		$params = array(":newContent" => $newContent, ":id" => $this->id);
		sql::exe($sql, $params);
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function __construct($author, $content, $id=null)
	{
		if($id != null) {
			$this->id = $id;
		}
		$this->author = $author;
		$this->content = $content;
	}

	// Gibt entry Objekt zurück
	public static function findEntryById($id)
	{
		$sql = "SELECT * FROM entry WHERE id = :id";
		$params = array(":id" => $id);
		$result = sql::exe($sql, $params);
		$entry = new entry($result[0]['autor'], $result[0]['content'], $result[0]['id']);
		return $entry;
	}

	public static function getEntries(user $user)
	{
		
	}

	public function getLikes()
	{
		$sql = "SELECT * FROM gefaelltMir WHERE gefallender_entry = :entryid";
		$params = array(":entryid" => $this->id);
		$result = sql::exe($sql, $params);
		return sizeof($result);
	}

	public function hasUserLiked($username)
	{
		$sql = "SELECT * FROM gefaelltMir WHERE autor_user = :autor_user AND gefallender_entry = :gefallender_entry";
		$params = array(":autor_user" => $username, ":gefallender_entry" => $this->id);
		$result = sql::exe($sql, $params);
		if(sizeof($result) != 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createNewEntry()
	{
		if($this->getId() == null) {
			$sql = "INSERT INTO entry (content, autor) VALUES (:content, :autor)";
			$params = array(':content' => $this->getContent(), ':autor' => $this->getAuthor());

			sql::exe($sql, $params);// argh $sql
			// es muss noch die id an das Objekt zurückgegeben werden
			$this->setId(sql::lastInsertId());
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

				<?= $result[0]['autor']?></a>
			</h4>

			<?php
			// Hier müssen Bilder geladen werden
			$picturePath = "img/content/posts/". $result[0]['autor'] ."/". $result[0]['id'];
			$extension = pathinfo($picturePath);
			$picturePath .= $extension;
			if(file_exists($picturePath)) {
				?>
				<img class="contentPicture" title="Weg mit dem Cursor!" src="<?= $picturePath?>" style="width: 300px">
				<?php
			}
			?>

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
						<a href="?page=home&dislike=<?= $this->id?>">Gefällt mir nicht mehr</a>
						<?php
					} else {
						?>
						<a href="?page=home&like=<?= $this->id?>">Gefällt mir</a>
						<?php
					}
						?>
				</span>
				<?php
				if($result[0]['autor'] == user::findUserBySid(session_id())) {
					?>
					|
					<span>
						<a href="?page=editEntry&id=<?= $this->id?>">Bearbeiten</a>
					</span>
					|
					<span>
						<a href="?page=home&delete=<?= $result[0]['id']?>">Löschen</a>
					</span>
					<?php
				}
				?>
			</p>
		</div>

		<div id="<?= $this->getId()?>" class="commentSection">
		<?php

		// Hier müssen die zugehörigen Kommentare gerendert werden
		$allComments = $this->getComments();
		$comments = $this->getComments(5);

		// Wenn es mehr als 5 Kommentare gibt, sollen diese eingeklappt sein
		// Klappt noch nicht
		/*
		if(sizeof($allComments) > 5) {
			?>
			<a href="#" class="moreComments" onclick="showMoreComments(<?= $this->getId()?>)">Mehr Kommentare anzeigen</a>
			<?php
		}
		*/

		foreach($comments as $comment) {
			$comment->renderComment();
		}

		// Das wird leider nichts
		//require_once(VIEWS_PATH ."/newComment.view.php");
		// Achtung: Kommentarerstellung wird in der newContent.view.php gelöst
		?>
		</div>
		<div class="newComment">
			<form action="index.php?entry=<?= $this->getId()?>" method="post">
				<textarea name="content"></textarea>
				<button type="submit">Kommentieren</button>
			</form>
		</div>
		<hr>
		<?php
	}

	public function getComments($limit=null)
	{
		$sql = "SELECT * FROM comment WHERE parent_id = :id";

		if($limit != null) {
			$sql .= " LIMIT ". $limit;
		}

		$params = array(":id" => $this->getId());
		$results = sql::exe($sql, $params);

		$comments = array();
		foreach($results as $result) {
			$comments[] = comment::findCommentById($result['id']);
		}
		return $comments;	
	}

	public function deleteEntry()
	{
		$sql = "DELETE FROM entry WHERE id = :id";
		$params = array(":id" => $this->getId());
		sql::exe($sql, $params);
	}
}

?>
