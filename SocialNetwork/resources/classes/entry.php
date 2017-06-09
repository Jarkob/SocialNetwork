<?php

class entry
{
	public static createNewEntry($autor, $content)
	{
		$sql = "INSERT INTO entry
			(content, autor)
			VALUES(:content, :autor)";
		$params = {"content" => $content, "autor" => $autor}
		sql::exe($sql, $params);
	}

	public static getEntryById($id)
	{
		$sql = "SELECT * FROM entry
			WHERE id = :id";
		$params = {"id" => $id}
		$entry = sql::exe($sql, $params);
		return $entry;
	}
}

?>