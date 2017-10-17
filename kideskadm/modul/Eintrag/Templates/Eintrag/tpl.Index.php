<?php
/**
* @var $list \Eintrag\Model\EintragModel[]
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

<td><?= $entry->getName(); ?></td>
<td align=center width=60><?= ($entry->getIcon()!="" ? $entry->getIcon()->getImageTag(array("style"=>"max-height:50px;")) : ''); ?></td>
<!-- <td><?= $entry->getKategorieName(); ?></td> -->
<td><?= $entry->getBereichName(); ?></td>
<td><?= $entry->getRechnerKind(); ?></td>
<td><?= $entry->getTyp(); ?></td>
<td><?= $entry->getBefehl(); ?></td>


				<?= \classes\CrudViewHelper::getDefaultActions($entry); ?>
			</tr>
		<?php }
		//$entry->getZ();
	} else {
		echo $this->get("Helper/tpl.crudNoEntries.php");
	} ?>
	</tbody>
</table>
