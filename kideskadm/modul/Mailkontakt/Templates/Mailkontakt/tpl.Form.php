<?php
/**
* @var $mailkontakt \Mailkontakt\Model\MailkontaktModel
*/
?>
<form data-toggle="validator" class="form-horizontal" role="form" method="post"
	  enctype="multipart/form-data"
	  action="<?= getLink('*/' . $formAction . '/' . $mailkontakt->getPk()); ?>">


	<div class="form-group">
		<label for="inputname" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("mailkontakt", "name") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="mailkontakt[mk_name]" id="inputname" placeholder="" value="<?= $mailkontakt->getName(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("mailkontakt", "name"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputemail" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("mailkontakt", "email") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="mailkontakt[mk_email]" id="inputemail" placeholder="" value="<?= $mailkontakt->getEmail(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("mailkontakt", "email"); ?>
		</div>
	</div>
	
	
<div class="form-group">
		<label for="inputbild" class="col-sm-2 control-label">Bild</label>
			
		<div class="col-sm-5">					
			<input type="file" class="" name="mailkontakt[mk_bild]" id="inputbild"  placeholder="Bild" value="">	
			<?= \classes\FormViewHelper::showErrorMessage("mailkontakt", "bild"); ?>	
		</div>	
		<div class="col-sm-5">		
			<?php if($mailkontakt->getBild()!="") { ?>	
			Datei vorhanden: <a href="<?= \classes\FileUtils::getFileDownloadLink($mailkontakt->getBild()); ?>">Download</a><br>	
			Datei entfernen: <input type="checkbox" value="1" name="mailkontakt[mk_bild_remove]">	
			<?php } ?>		
		</div>		
		<?= \classes\FormViewHelper::showErrorMessage("mailkontakt", "bild"); ?>
	</div>



<div class="form-group">
		<label for="inputrechner" class="col-sm-2 control-label">Rechner</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("mailkontakt", "rechner") ? "errorForm" : ""); ?>">
			<?php foreach ($mailkontakt->getAllPossibleRechner() as $rechner) { ?>
				<input type="checkbox" name="mailkontakt[mk_rechner][]" value="<?= $rechner->getPk(); ?>" <?= ($mailkontakt->isInrechner($rechner->getPk()) ? "checked" : ""); ?> ><?= $rechner->getKind(); ?><br>
			<?php } ?>
			<?= \classes\FormViewHelper::showErrorMessage("mailkontakt", "rechner"); ?>
		</div>
	</div>




	<?= \classes\CrudViewHelper::getFormButtons(); ?>

</form>