<?php
/**
* @var $list \Nachrichten\Model\NachrichtModel[]
*/
?>

<table class="table table-striped table-bordered table-hover dataTable no-footer crudTable" id="dataTable" aria-describedby="dataTable_info">

	<?= \classes\CrudViewHelper::getTableHead($listColumns, $sortableColumns, $sort, $order); ?>

	<tbody>
	<?php if ($foundRows > 0) {
		foreach ($list as $key => $entry) { ?>
			<tr <?php if($entry->getPk()==getS('highlightCrudLine')) {setS('highlightCrudLine',"");echo 'class="highlightLine"';} ?>>
				<td style="width:20px;">
					<input type="checkbox" value="<?= $entry->getPk(); ?>" name="multi[]" class="crudMulti"></td>

<td><?= $entry->getSender(); ?></td>
<td><?= $entry->getMailkontaktName(); ?></td>
<td><?= $entry->getRechnerKind(); ?></td>
<td><?= $entry->getNachricht(); ?></td>
<td><?= $entry->getUebertragen(); ?></td>
<td><?= $entry->getGelesen(); ?></td>


				<?= \classes\CrudViewHelper::getDefaultActions($entry); ?>
			</tr>
		<?php }
		//$entry->getZ();
	} else {
		echo $this->get("Helper/tpl.crudNoEntries.php");
	} ?>
	</tbody>
</table>
