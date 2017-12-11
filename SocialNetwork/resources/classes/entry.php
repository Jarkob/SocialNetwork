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
		$this->content = $this->escapeInput($newContent);
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
		$this->content = $this->escapeInput($content);
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
		$sql = "SELECT * FROM entry_gefaelltMir WHERE gefallender_entry = :entryid";
		$params = array(":entryid" => $this->id);
		$result = sql::exe($sql, $params);
		return sizeof($result);
	}

	public function hasUserLiked($username)
	{
		$sql = "SELECT * FROM entry_gefaelltMir WHERE autor_user = :autor_user AND gefallender_entry = :gefallender_entry";
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
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="thumbnail">
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

					<?php
						// Hier müssen Bilder geladen werden
						$picturePath = "img/content/posts/". $result[0]['autor'] ."/". $result[0]['id'];
						
						$pictureExists = false;

						if(file_exists($picturePath . ".jpg")) {
							$picturePath .= ".jpg";
							$pictureExists = true;
						} else if(file_exists($picturePath . ".png")) {
							$picturePath .= ".png";
							$pictureExists = true;
						} else if(file_exists($picturePath . ".jpeg")) {
							$picturePath .= ".jpeg";
							$pictureExists = true;
						} else if(file_exists($picturePath . ".gif")) {
							$picturePath .= ".gif";
							$pictureExists = true;
						}


						// Videoanzeige
						$videoPath = "img/content/posts/". $result[0]['autor'] ."/". $result[0]['id'];

						$videoExists = false;

						if(file_exists($videoPath .".mp4")) {
							$videoPath .= ".mp4";
							$videoExists = true;
						} else if(file_exists($videoPath .".ogg")) {
							$videoPath .= ".ogg";
							$videoExists = true;
						}


						if($pictureExists) {
							?>
							<img class="img-responsive" title="Weg mit dem Cursor!" src="<?= $picturePath?>" style="width: 300px">
							<?php
						} else if($videoExists) {

							// TODO
							// Momentan geht nur mp4
							// Außerdem müssen videos auch gelöscht werden, wenn der Beitrag gelöscht wird!
							?>
							<video controls="">
						        <source src="<?= $videoPath?>" type="video/mp4">
							</video>
							<?php
						}
					?>

					<div class="caption">
						<div class="media">
							<div class="media-left">

								<?php
								if(file_exists("img/content/profile/". $result[0]['autor'] .".jpg")) {
									?>
									<img class="media-object" title="<?= $row['autor']?>" src="img/content/profile/<?= $result[0]['autor']?>.jpg" style="max-width: 64px">
									<?php
								} else if(file_exists("img/content/profile/". $result[0]['autor'] .".png")) {
									?>
									<img class="media-object" title="<?= $result[0]['autor']?>" src="img/content/profile/<?= $result[0]['autor']?>.png" style="max-width: 64px">
									<?php
								} else {
									?>
									<img class="media-object" title="<?= $result[0]['autor']?>" src="img/content/profile/default.png" style="max-width: 64px">
									<?php
								}
								?>
							</div>

							<div class="media-body">
								<h4>
									<a href="?page=profile&owner=<?= $result[0]['autor']?>">
										<?= $result[0]['autor']?>
									</a>
								</h4>
								<p>
									<?= $result[0]['content']?>
								</p>
							</div>
						</div>
					</div>

					<p>
						<a href="#" class="btn btn-default" id="showLikes<?= $this->getId()?>" onclick="showLikes(<?= $this->getId()?>)">
							<?= $this->getLikes()?> Leuten gefällt das
						</a>
						<?php
						if($this->hasUserLiked(user::findUserBySid(session_id()))) {
							?>
							<a class="btn btn-default" href="?page=home&dislikeEntry=<?= $this->id?>" title="Gefällt mir nicht mehr">
								<span class="glyphicon glyphicon-thumbs-down"></span>
							</a>
							<?php
						} else {
							?>
							<a class="btn btn-default" href="?page=home&likeEntry=<?= $this->id?>" aria-hidden="true">
								<span class="glyphicon glyphicon-thumbs-up"></span>
							</a>
							<?php
						}
						
						if($result[0]['autor'] == user::findUserBySid(session_id())) {
							?>
							<a class="btn btn-default" href="?page=editEntry&id=<?= $this->id?>" title="Bearbeiten">
								<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
							</a>
							
							<a class="btn btn-default" href="?page=home&deleteEntry=<?= $result[0]['id']?>" title="Löschen">
								<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
							</a>
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
				<form action="index.php?entry=<?= $this->getId()?>" method="post">
					<div class="form-group">
						<textarea class="form-control" name="content" placeholder="Kommentar"></textarea>
					</div>
					
					<button class="btn btn-default" type="submit">
						<span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
						Kommentieren
					</button>
				</form>

				<hr>
			</div>
		</div>
		
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
		if($results != null) {
			foreach($results as $result) {
				$comments[] = comment::findCommentById($result['id']);
			}
		}
		return $comments;	
	}

	public function deleteEntry()
	{
		$sql = "DELETE FROM entry WHERE id = :id";
		$params = array(":id" => $this->getId());
		sql::exe($sql, $params);

		// Kommentare löschen, falls vorhanden
		$sql = "DELETE FROM comment WHERE parent_id = :id";
		$params = array("id" => $this->getId());
		sql::exe($sql, $params);

		// Bild löschen, falls vorhanden
		$picturePath = "img/content/posts/". $this->author ."/". $this->getId();
		
		$pictureExists = false;

		if(file_exists($picturePath . ".jpg")) {
			$picturePath .= ".jpg";
			$pictureExists = true;
		} else if(file_exists($picturePath . ".png")) {
			$picturePath .= ".png";
			$pictureExists = true;
		} else if(file_exists($picturePath . ".jpeg")) {
			$picturePath .= ".jpeg";
			$pictureExists = true;
		} else if(file_exists($picturePath . ".gif")) {
			$picturePath .= ".gif";
			$pictureExists = true;
		}

		if($pictureExists) {
			unlink($picturePath);
		}
	}
	
	public function escapeInput($html)
	{
		return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
	}
}

?>
