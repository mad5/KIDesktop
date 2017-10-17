<?php if(!isset($entry->controller) || $entry->controller->hideCrudListActionView!=true) { ?>
<td style="width:20px;">

		<a href="<?= getLink("*/view/".$entry->getPk()); ?>" title="<?= trans("crud|Detailansicht"); ?>" class="glyphicon glyphicon-eye-open crudViewAction"></a>

</td>
<?php } ?>
<?php if(!isset($entry->controller) || $entry->controller->hideCrudListActionTrash!=true) { ?>
<td style="width:20px;">

		<a href="<?= getLink("*/deleteConfirm/".$entry->getPk()); ?>" title="<?= transFull("crud|Löschen"); ?>" class="glyphicon glyphicon-trash"></a>

</td>
<?php } ?>
<?php if(!isset($entry->controller) || $entry->controller->hideCrudListActionEdit!=true) { ?>
<td style="width:20px;">
		<a href="<?= getLink("*/edit/".$entry->getPk()); ?>" title="<?= trans("crud|Bearbeiten"); ?>" class="glyphicon glyphicon-pencil"></a>

</td>
<?php } ?>
