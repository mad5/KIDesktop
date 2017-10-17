<?php
/**
 * @var $list \Backenduser\Model\BackenduserModel[]
 */
?>
<!--
<table>
	<td style="<?= (getS("onlyIntern")==0 && getS("onlyExtern")==0 && getS("onlyNew")==0 ? 'background-color: silver;' : '');?>">
		<a href="<?= getLink('*/index/alle');?>">Alle</a>
	</td>
	<td style="<?= (getS("onlyNew")==1 ? 'background-color: silver;' : '');?>">
		<a href="<?= getLink('*/index/neue');?>">nur Neue</a>
	</td>

</table>-->
<table class="table table-striped table-bordered table-hover dataTable no-footer crudTable" id="dataTable" aria-describedby="dataTable_info">
	<?= \classes\CrudViewHelper::getTableHead($listColumns, $sortableColumns, $sort, $order); ?>
	<tbody>
		<?php if ($foundRows > 0) {
			foreach ($list as $key => $entry) { ?>
				<tr <?= ($entry->getDisable()==1 ? '' : '');?>  <?php if($entry->getPk()==getS('highlightCrudLine')) {setS('highlightCrudLine',"");echo 'class="highlightLine"';} ?>>
					<td style="width:20px;"><input type="checkbox" value="<?= $entry->getPk(); ?>" name="multi[]" class="crudMulti"></td>
					<td style="<?= ($entry->getDisable()==1 ? 'text-decoration: line-through;' : ''); ?>"><?= $entry->getUsername(); ?></td>
					<td><?= $entry->getFirstname(); ?></td>
					<td><?= $entry->getLastname(); ?></td>
					<td><?= $entry->getEmail(); ?></td>
					<td style="<?= ($entry->getNoRights()==1 ? 'background-color:yellow;' : ''); ?>">
						<?php
							$roles = array();
							foreach ($entry->getRoles() as $role) {
								$roles[] = $role->getName();
							}
							echo implode('<br />', $roles);
							if($entry->getNoRights()==1) {
								echo "<b>Bisher keine Rechte zugewiesen</b>";
							}
						?>
					</td>
					<td style="text-align:center;"><?= ($entry->isAdmin() ? 'X' : ''); ?></td>
					<td style="width:20px;"><?php if (1==1 || \Backenduser\Service\AccessService::hasWriteAccessByModel($entry)) { ?><a href="<?= getLink("*/edit/" . $entry->getPk()); ?>" class="glyphicon glyphicon-pencil crudViewAction"></a><?php } ?></td>
				</tr>
			<?php }
		}
		else {
			echo $this->get("Helper/tpl.crudNoEntries.php");
		} ?>
	</tbody>
</table>

