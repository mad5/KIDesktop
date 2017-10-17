<?php
/**
* @var $list \Rechner\Model\RechnerModel[]
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

<td align=center width=60><?= ($entry->getBild()!="" ? $entry->getBild()->getImageTag(array("style"=>"max-height:50px;")) : ''); ?></td>
					
<td><?= $entry->getKind(); ?></td>
<td><?= $entry->getOrt(); ?></td>
<td><?= $entry->getBeschreibung(); ?></td>
<!-- <td><?= $entry->getLetzteip(); ?></td> -->
<td><?= $entry->getZuletztonline(); ?></td>
<!-- <td><?= $entry->getOfflineab(); ?></td>
<td><?= $entry->getOfflinebis(); ?></td>
<td><?= $entry->getAusab(); ?></td>
<td><?= $entry->getAusbis(); ?></td>
<td><?= $entry->getNutzungsdauerinsgesamt(); ?></td>
-->
<td><?= $entry->getHash(); ?></td>


				<?= \classes\CrudViewHelper::getDefaultActions($entry); ?>
			</tr>
		<?php }
		//$entry->getZ();
	} else {
		echo $this->get("Helper/tpl.crudNoEntries.php");
	} ?>
	</tbody>
</table>
