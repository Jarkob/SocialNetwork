<?php
require_once(CLASSES_PATH ."/entry.php");

if(isset($_GET['id'])) {
	$entry = new entry($_GET['id']);

	if(isset($_POST['content'])) {
		$entry->changeContent($_POST['content']);
		?>
		<p>Der Post wurde bearbeitet.</p>
		<script type="text/javascript">
				document.location.href = "index2.php";
			</script>
		<?php
	} else {
		?>
		<form action="?page=editEntry.php&id=<?= $_GET['id']?>" method="post" enctype="multipart/form-data">
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