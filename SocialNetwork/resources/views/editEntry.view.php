<div id="editEntry">
<?php
require_once(CLASSES_PATH ."/entry.php");

if(isset($_GET['id'])) {
	$entry = entry::findEntryById($_GET['id']);

	if(isset($_POST['content'])) {
		$entry->changeContent($_POST['content']);
		?>
		<p>Der Post wurde bearbeitet.</p>
		<script type="text/javascript">
			document.location.href = "index.php";
		</script>
		<?php
	} else {
		?>
		<form action="?page=editEntry&id=<?= $_GET['id']?>" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="editEntry">Bearbeiten</label>
				<textarea id="editEntry" class="form-control" name="content">
					<?= $entry->getContent()?>
				</textarea>
			</div>
			<button class="btn btn-default" type="submit">Speichern</button>
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