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

	public function createNewComment()
	{
		if($this->getId() == null) {
			$sql = "INSERT INTO comment";// comment muss noch erstellt werden
		}
	}
}
?>