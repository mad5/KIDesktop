<table class="table table-striped table-bordered table-hover dataTable no-footer crudTable" id="dataTable" aria-describedby="dataTable_info">
	<?= \classes\CrudViewHelper::getTableHead($listColumns, $sortableColumns, $sort, $order); ?>
	<tbody>
		<?php if ($foundRows > 0) {
			foreach ($list as $key => $entry) { ?>
				<tr <?php if($entry->getPk()==getS('highlightCrudLine')) {setS('highlightCrudLine',"");echo 'class="highlightLine"';} ?>>
					<td style="width:20px;"><input type="checkbox" value="<?= $entry->getPk(); ?>" name="multi[]" class="crudMulti"></td>
					<td><?= $entry->getName(); ?></td>
					<td><?= ($entry->getRoletype() ? $entry->getRoletype()->getName() : '&nbsp;'); ?></td>
					<td style="width:20px;"><?php if (\Backenduser\Service\AccessService::hasWriteAccessByModel($entry)) { ?><a href="<?= getLink("*/deleteConfirm/".$entry->getPk());?>" title="Löschen" class="fa fa-trash-o"></a><?php } ?></td>
					<td style="width:20px;"><?php if (\Backenduser\Service\AccessService::hasWriteAccessByModel($entry)) { ?><a href="<?= getLink("*/edit/".$entry->getPk());?>" title="Bearbeiten" class="fa fa-edit crudViewAction"></a><?php } ?></td>
				</tr>
			<?php }
		}
		else {
			include projectPath.'/templates/helper/tpl.crudNoEntries.php';
		} ?>
	</tbody>
</table>

