<?php
/**
* @var $bereich \Bereiche\Model\BereichModel
*/
?>
<form data-toggle="validator" class="form-horizontal" role="form" method="post"
	  enctype="multipart/form-data"
	  action="<?= getLink('*/' . $formAction . '/' . $bereich->getPk()); ?>">


	<div class="form-group">
		<label for="inputname" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("bereich", "name") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="bereich[be_name]" id="inputname" placeholder="" value="<?= $bereich->getName(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("bereich", "name"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputicon" class="col-sm-2 control-label">Icon</label>
			<div class="col-sm-5">					<input type="file" class="" name="bereich[be_icon]" id="inputicon"						   placeholder="Datei" value="">					<?= \classes\FormViewHelper::showErrorMessage("bereich", "icon"); ?>			</div>			<div class="col-sm-5">				<?php if($bereich->getIcon()!="") { ?>					Datei vorhanden: <a href="<?= \classes\FileUtils::getFileDownloadLink($bereich->getIcon()); ?>">Download</a><br>					Datei entfernen: <input type="checkbox" value="1" name="bereich[be_icon_remove]">				<?php } ?>			</div>			<?= \classes\FormViewHelper::showErrorMessage("bereich", "icon"); ?>
	</div>

<div class="form-group">
		<label for="inputreihenfolge" class="col-sm-2 control-label">Reihenfolge</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("bereich", "reihenfolge") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="bereich[be_reihenfolge]" id="inputreihenfolge" placeholder="" value="<?= $bereich->getReihenfolge(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("bereich", "reihenfolge"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputfreigegeben" class="col-sm-2 control-label">Freigegeben</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("bereich", "freigegeben") ? "errorForm" : ""); ?>">
			<input type="checkbox" name="bereich[be_freigegeben]" id="inputfreigegeben" value="1" <?= $bereich->getFreigegeben()==1 ? 'checked' : ''; ?> > ja
			<?= \classes\FormViewHelper::showErrorMessage("bereich", "freigegeben"); ?>
		</div>
	</div>




	<?= \classes\CrudViewHelper::getFormButtons(); ?>

</form>