<?php
/**
* @var $kategorie \Kategorien\Model\KategorieModel
*/
?>
<form data-toggle="validator" class="form-horizontal" role="form" method="post"
	  enctype="multipart/form-data"
	  action="<?= getLink('*/' . $formAction . '/' . $kategorie->getPk()); ?>">


	<div class="form-group">
		<label for="inputname" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has("kategorie", "name") ? "errorForm" : ""); ?>">
			<input type="text" class="form-control" name="kategorie[ka_name]" id="inputname" placeholder="" value="<?= $kategorie->getName(); ?>" >
			<?= \classes\FormViewHelper::showErrorMessage("kategorie", "name"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputbereich" class="col-sm-2 control-label">Bereich</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("kategorie", "bereich") ? "errorForm" : ""); ?>">
			<select name="kategorie[ka_bereich]" class="form-control">
			<option></option>
			<?php foreach ($kategorie->getAllPossibleBereich() as $bereich) { ?>
				<option value="<?= $bereich->getPk(); ?>" <?= ((int)$bereich->getPk()===(int)$kategorie->getBereich()->getPk() ? "selected" : ""); ?> ><?= $bereich->getName(); ?></option>
			<?php } ?>
			</select>
			<?= \classes\FormViewHelper::showErrorMessage("kategorie", "bereich"); ?>
		</div>
	</div>

<div class="form-group">
		<label for="inputrechner" class="col-sm-2 control-label">Rechner</label>
		<div class="col-sm-2 <?= (\classes\FormErrorHandler::has("kategorie", "rechner") ? "errorForm" : ""); ?>">
			<?php foreach ($kategorie->getAllPossibleRechner() as $rechner) { ?>
				<input type="checkbox" name="kategorie[ka_rechner][]" value="<?= $rechner->getPk(); ?>" <?= ($kategorie->isInrechner($rechner->getPk()) ? "checked" : ""); ?> ><?= $rechner->getKind(); ?><br>
			<?php } ?>
			<?= \classes\FormViewHelper::showErrorMessage("kategorie", "rechner"); ?>
		</div>
	</div>




	<?= \classes\CrudViewHelper::getFormButtons(); ?>

</form>