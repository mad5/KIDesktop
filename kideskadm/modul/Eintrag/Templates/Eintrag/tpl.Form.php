<?php
/**
* @var $eintrag \Eintrag\Model\EintragModel
*/

?>
<form data-toggle="validator" class="form-horizontal" role="form" method="post"
	  enctype="multipart/form-data"
	  action="<?= getLink('*/' . $formAction . '/' . $eintrag->getPk()); ?>">


	<div class="form-group">
		<label for="inputname" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("eintrag", "name") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="eintrag[ei_name]" id="inputname" placeholder="" value="<?= $eintrag->getName(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("eintrag", "name"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputicon" class="col-sm-2 control-label">Icon</label>
			<div class="col-sm-5">					<input type="file" class="" name="eintrag[ei_icon]" id="inputicon"						   placeholder="Datei" value="">					<?= \classes\FormViewHelper::showErrorMessage("eintrag", "icon"); ?>			</div>			<div class="col-sm-5">				<?php if($eintrag->getIcon()!="") { ?>					Datei vorhanden: <a href="<?= \classes\FileUtils::getFileDownloadLink($eintrag->getIcon()); ?>">Download</a><br>					Datei entfernen: <input type="checkbox" value="1" name="eintrag[ei_icon_remove]">				<?php } ?>			</div>			<?= \classes\FormViewHelper::showErrorMessage("eintrag", "icon"); ?>
	</div>

	<!--
<div class="form-group">
		<label for="inputkategorie" class="col-sm-2 control-label">Kategorie</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("eintrag", "kategorie") ? "errorForm" : ""); ?>">
			<?php foreach ($eintrag->getAllPossibleKategorie() as $kategorie) { ?>
				<input type="checkbox" name="eintrag[ei_kategorie][]" value="<?= $kategorie->getPk(); ?>" <?= ($eintrag->isInkategorie($kategorie->getPk()) ? "checked" : ""); ?> ><?= $kategorie->getName(); ?><br>
			<?php } ?>
			<?= \classes\FormViewHelper::showErrorMessage("eintrag", "kategorie"); ?>
		</div>
	</div>
	-->

<div class="form-group">
		<label for="inputbereich" class="col-sm-2 control-label">Bereich</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("eintrag", "bereich") ? "errorForm" : ""); ?>">
			<select name="eintrag[ei_bereich]" class="form-control">
			<option></option>
			<?php foreach ($eintrag->getAllPossibleBereich() as $bereich) { ?>
				<option value="<?= $bereich->getPk(); ?>" <?= ((int)$bereich->getPk()===(int)$eintrag->getBereich()->getPk() ? "selected" : ""); ?> ><?= $bereich->getName(); ?></option>
			<?php } ?>
			</select>
			<?= \classes\FormViewHelper::showErrorMessage("eintrag", "bereich"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputrechner" class="col-sm-2 control-label">Rechner</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("eintrag", "rechner") ? "errorForm" : ""); ?>">
			<?php foreach ($eintrag->getAllPossibleRechner() as $rechner) { ?>
				<input type="checkbox" name="eintrag[ei_rechner][]" value="<?= $rechner->getPk(); ?>" <?= ($eintrag->isInrechner($rechner->getPk()) ? "checked" : ""); ?> ><?= $rechner->getKind(); ?><br>
			<?php } ?>
			<?= \classes\FormViewHelper::showErrorMessage("eintrag", "rechner"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputtyp" class="col-sm-2 control-label">Typ</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("eintrag", "typ") ? "errorForm" : ""); ?>">
			<select class="form-control" name="eintrag[ei_typ]" id="inputtyp" onchange="setLabel();">
				<option value="webseite" <?= $eintrag->getTyp()=='webseite' ? 'selected' : ''; ?>>Webseite</option>
				<option value="programm" <?= $eintrag->getTyp()=='programm' ? 'selected' : ''; ?>>Programm</option>
				<!--
				<option value="bilder" <?= $eintrag->getTyp()=='bilder' ? 'selected' : ''; ?>>Bilderordner</option>
				<option value="mp3" <?= $eintrag->getTyp()=='mp3' ? 'selected' : ''; ?>>mp3</option>
				<option value="videos" <?= $eintrag->getTyp()=='videos' ? 'selected' : ''; ?>>Videos</option>
				-->
				<option value="app" <?= $eintrag->getTyp()=='app' ? 'selected' : ''; ?>>App</option>
				</select>
			<?= \classes\FormViewHelper::showErrorMessage("eintrag", "typ"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputbefehl" class="col-sm-2 control-label" id="labelbefehl">Befehl</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("eintrag", "befehl") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="eintrag[ei_befehl]" id="inputbefehl" placeholder="" value="<?= $eintrag->getBefehl(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("eintrag", "befehl"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputbefehl" class="col-sm-2 control-label" id="labelbefehl">Hosts</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("eintrag", "hosts") ? "errorForm" : ""); ?>">
			<textarea class="form-control" name="eintrag[ei_hosts]" id="inputhosts" placeholder=""><?= $eintrag->getHosts(); ?></textarea>
			<?= \classes\FormViewHelper::showErrorMessage("eintrag", "hosts"); ?>
		</div>
	</div>
	
	
<script>
	function setLabel() {
		var typ = $('#inputtyp').val();
		if(typ=="webseite") $('#labelbefehl').html("URL / http-Adresse");
		if(typ=="programm") $('#labelbefehl').html("Befehlszeile");
		if(typ=="bilder") $('#labelbefehl').html("Pfad zu den Bildern");
		if(typ=="mp3") $('#labelbefehl').html("Pfad zu den mp3s");
		if(typ=="app") $('#labelbefehl').html("App");
	}
	$(function() {
		setLabel();
	});
</script>


	<?= \classes\CrudViewHelper::getFormButtons(); ?>

</form>