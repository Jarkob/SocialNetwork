<div id="editEntry">
<?php
echo "vor requiren";
require_once(CLASSES_PATH ."/entry.php");
echo "nach requiren";
if(isset($_GET['id'])) {
	echo "vor entryini";
	$entry = entry::findEntryById($_GET['id']);
	echo "vor if";
	if(isset($_POST['content'])) {
		echo "in if post existiert";
		$entry->changeContent($_POST['content']);
		echo "nach editieren";
		?>
		<p>Der Post wurde bearbeitet.</p>
		<script type="text/javascript">
				document.location.href = "index2.php";
			</script>
		<?php
	} else {
		?>
		<form action="?page=editEntry&id=<?= $_GET['id']?>" method="post" enctype="multipart/form-data">
			<textarea name="content">
				<?= $entry->getContent()?>
			</textarea>
			<button type="submit">Speichern</button>
		</form>
		<?php
	}
} else {
	?>
	<p>Für den zu bearbeitenden Post wurde keine id angegeben.</p>
	<?php
}
?>
</div>