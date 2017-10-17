<?php if ($hasArchiveEnabled) { ?>

	<?php if ($multiple == FALSE && $entry->isArchived()) { ?>
		<h3><?= transFull("Archivierung aufheben?");?></h3>

		<div class="alert alert-success" role="alert">
				<h2><?= transFull("Soll dieser Datensatz jetzt aus dem Archiv wieder hergestellt werden?");?></h2>
		</div>

		<div style="float:left;">
			<a href="<?= getLink("*/index"); ?>" class="btn btn-info" style="width:100px;"><?= transFull("Nein");?></a>
		</div>

		<div style="float:left;margin-left:10px;">
			<form method="post" action="<?= getLink("*/unarchive/" . $this->fw->QS[2]); ?>">
				<button class="btn btn-danger" style="width:100px;"><?= transFull("Ja");?></button>
			</form>
		</div>
	<?php } else { ?>

		<h3><?= transFull("Archivieren?");?></h3>

		<div class="alert alert-info" role="alert">
			<?php if ($multiple == 1) { ?>
				<h2><?= transFull("Sollen diese Datensätze jetzt archiviert werden?");?></h2>
			<?php } else { ?>
				<h2><?= transFull("Soll dieser Datensatz jetzt archiviert werden?");?></h2>
			<?php } ?>
		</div>

		<div style="float:left;">
			<a href="<?= getLink("*/index"); ?>" class="btn btn-info" style="width:100px;"><?= transFull("Nein");?></a>
		</div>

		<div style="float:left;margin-left:10px;">
			<form method="post" action="<?= getLink("*/archive/" . $this->fw->QS[2]); ?>">
				<button class="btn btn-danger" style="width:100px;"><?= transFull("Ja");?></button>
			</form>
		</div>
	<?php } ?>

	<div style="clear:both;"></div>
	<br><br>
	<h3><?= transFull("oder Löschen?");?></h3>
	<br>
<?php } ?>

<div class="alert alert-danger" role="alert">
	<?php if ($multiple == 1) { ?>
		<h2><?= transFull("Sollen diese Datensätze wirklich gelöscht werden?");?></h2>
	<?php } else { ?>
		<h2><?= transFull("Soll dieser Datensatz wirklich gelöscht werden?");?></h2>
	<?php } ?>
</div>

<div style="float:left;">
	<a href="<?= getLink("*/index"); ?>" class="btn btn-info" style="width:100px;"><?= transFull("Nein");?></a>
</div>

<div style="float:left;margin-left:10px;">
	<form method="post" action="<?= getLink("*/delete/" . $this->fw->QS[2]); ?>">
		<button class="btn btn-danger" style="width:100px;"><?= transFull("Ja");?></button>
	</form>
</div>

<div style="clear:both;"></div>