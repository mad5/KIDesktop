<?php
/**
* @var $nachricht \Nachrichten\Model\NachrichtModel
*/
?>
<form data-toggle="validator" class="form-horizontal" role="form" method="post"
	  enctype="multipart/form-data"
	  action="<?= getLink('*/' . $formAction . '/' . $nachricht->getPk()); ?>">


	<div class="form-group">
		<label for="inputsender" class="col-sm-2 control-label">Sender</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("nachricht", "sender") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="nachricht[na_sender]" id="inputsender" placeholder="" value="<?= $nachricht->getSender(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("nachricht", "sender"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputmailkontakt" class="col-sm-2 control-label">Mailkontakt</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("nachricht", "mailkontakt") ? "errorForm" : ""); ?>">
			<select name="nachricht[na_mailkontakt]" class="form-control">
			<option></option>
			<?php foreach ($nachricht->getAllPossibleMailkontakt() as $mailkontakt) { ?>
				<option value="<?= $mailkontakt->getPk(); ?>" <?= ((int)$mailkontakt->getPk()===(int)$nachricht->getMailkontakt()->getPk() ? "selected" : ""); ?> ><?= $mailkontakt->getName(); ?></option>
			<?php } ?>
			</select>
			<?= \classes\FormViewHelper::showErrorMessage("nachricht", "mailkontakt"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputrechner" class="col-sm-2 control-label">Rechner</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("nachricht", "rechner") ? "errorForm" : ""); ?>">
			<select name="nachricht[na_rechner]" class="form-control">
			<option></option>
			<?php foreach ($nachricht->getAllPossibleRechner() as $rechner) { ?>
				<option value="<?= $rechner->getPk(); ?>" <?= ((int)$rechner->getPk()===(int)$nachricht->getRechner()->getPk() ? "selected" : ""); ?> ><?= $rechner->getKind(); ?></option>
			<?php } ?>
			</select>
			<?= \classes\FormViewHelper::showErrorMessage("nachricht", "rechner"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputnachricht" class="col-sm-2 control-label">Nachricht</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("nachricht", "nachricht") ? "errorForm" : ""); ?>">
			<textarea class="form-control" rows="5" name="nachricht[na_nachricht]" id="inputnachricht" placeholder=""><?= $nachricht->getNachricht(); ?></textarea>
			<?= \classes\FormViewHelper::showErrorMessage("nachricht", "nachricht"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputuebertragen" class="col-sm-2 control-label">Uebertragen</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("nachricht", "uebertragen") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="nachricht[na_uebertragen]" id="inputuebertragen" placeholder="" value="<?= $nachricht->getUebertragen(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("nachricht", "uebertragen"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputgelesen" class="col-sm-2 control-label">Gelesen</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("nachricht", "gelesen") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="nachricht[na_gelesen]" id="inputgelesen" placeholder="" value="<?= $nachricht->getGelesen(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("nachricht", "gelesen"); ?>
		</div>
	</div>




	<?= \classes\CrudViewHelper::getFormButtons(); ?>

</form>