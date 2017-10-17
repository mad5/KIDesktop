<?php
/**
* @var $list \Bereiche\Model\BereichModel[]
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

<td width=60 align=center><?= ($entry->getIcon()!="" ? $entry->getIcon()->getImageTag(array("style"=>"max-height:50px;")) : ''); ?></td>
<td><?= $entry->getName(); ?></td>


<td><?= $entry->getReihenfolge(); ?></td>
<td><?= ($entry->getFreigegeben()==1 ? 'Ja' : 'Nein'); ?></td>


				<?= \classes\CrudViewHelper::getDefaultActions($entry); ?>
			</tr>
		<?php }
		//$entry->getZ();
	} else {
		echo $this->get("Helper/tpl.crudNoEntries.php");
	} ?>
	</tbody>
</table>
