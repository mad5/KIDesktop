<?php
/**
* @var $rechner \Rechner\Model\RechnerModel
*/
?>
<form data-toggle="validator" class="form-horizontal" role="form" method="post"
	  enctype="multipart/form-data"
	  action="<?= getLink('*/' . $formAction . '/' . $rechner->getPk()); ?>">


	<div class="form-group">
		<label for="inputkind" class="col-sm-2 control-label">Kind</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("rechner", "kind") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="rechner[re_kind]" id="inputkind" placeholder="" value="<?= $rechner->getKind(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "kind"); ?>
		</div>
	</div>
	
	
<div class="form-group">
		<label for="inputbild" class="col-sm-2 control-label">Bild</label>
			
		<div class="col-sm-5">					
			<input type="file" class="" name="rechner[re_bild]" id="inputbild"  placeholder="Bild" value="">	
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "bild"); ?>	
		</div>	
		<div class="col-sm-5">		
			<?php if($rechner->getBild()!="") { ?>	
			Datei vorhanden: <a href="<?= \classes\FileUtils::getFileDownloadLink($rechner->getBild()); ?>">Download</a><br>	
			Datei entfernen: <input type="checkbox" value="1" name="rechner[re_bild_remove]">	
			<?php } ?>		
		</div>		
		<?= \classes\FormViewHelper::showErrorMessage("rechner", "bild"); ?>
	</div>


<div class="form-group">
		<label for="inputort" class="col-sm-2 control-label">Ort</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("rechner", "ort") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="rechner[re_ort]" id="inputort" placeholder="" value="<?= $rechner->getOrt(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "ort"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputbeschreibung" class="col-sm-2 control-label">Beschreibung</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("rechner", "beschreibung") ? "errorForm" : ""); ?>">
			<textarea class="form-control" rows="5" name="rechner[re_beschreibung]" id="inputbeschreibung" placeholder=""><?= $rechner->getBeschreibung(); ?></textarea>
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "beschreibung"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputletzteip" class="col-sm-2 control-label">Letzteip</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("rechner", "letzteip") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="rechner[re_letzteip]" id="inputletzteip" placeholder="" value="<?= $rechner->getLetzteip(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "letzteip"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputzuletztonline" class="col-sm-2 control-label">Zuletztonline</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("rechner", "zuletztonline") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="rechner[re_zuletztonline]" id="inputzuletztonline" placeholder="" value="<?= $rechner->getZuletztonline(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "zuletztonline"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputofflineab" class="col-sm-2 control-label">Offlineab</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("rechner", "offlineab") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="rechner[re_offlineab]" id="inputofflineab" placeholder="" value="<?= $rechner->getOfflineab(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "offlineab"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputofflinebis" class="col-sm-2 control-label">Offlinebis</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("rechner", "offlinebis") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="rechner[re_offlinebis]" id="inputofflinebis" placeholder="" value="<?= $rechner->getOfflinebis(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "offlinebis"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputausab" class="col-sm-2 control-label">Ausab</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("rechner", "ausab") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="rechner[re_ausab]" id="inputausab" placeholder="" value="<?= $rechner->getAusab(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "ausab"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputausbis" class="col-sm-2 control-label">Ausbis</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("rechner", "ausbis") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="rechner[re_ausbis]" id="inputausbis" placeholder="" value="<?= $rechner->getAusbis(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "ausbis"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputnutzungsdauerinsgesamt" class="col-sm-2 control-label">Nutzungsdauerinsgesamt</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("rechner", "nutzungsdauerinsgesamt") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="rechner[re_nutzungsdauerinsgesamt]" id="inputnutzungsdauerinsgesamt" placeholder="" value="<?= $rechner->getNutzungsdauerinsgesamt(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("rechner", "nutzungsdauerinsgesamt"); ?>
		</div>
	</div>


<div class="form-group">
		<label for="inputnutzungsdauerinsgesamt" class="col-sm-2 control-label">Kennung</label>
		<div class="col-sm-2">
			<?= $rechner->getHash(); ?>
			
		</div>
		<div class="col-sm-7">
		Bitte in die Datei config.json hinter <b>key</b> eintragen.<br>
<pre>
{
	...
	"key": "<?= $rechner->getHash();?>",
	...
}
</pre>
		</div>
	</div>



	<?= \classes\CrudViewHelper::getFormButtons(); ?>

</form>