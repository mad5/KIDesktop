<script>
function toggleCRUDCheck(obj) {
	if (obj.checked) {
		var checks = $(obj).closest("table").find(".crudMulti:not(:checked)").click();
	} else {
		var checks = $(obj).closest("table").find(".crudMulti:checked").click();
	}
}
</script>
<thead>
<tr role="row">
	<?php if(!$disableGroupCommand) { ?>
	<th>
		<input type="checkbox" value="1" onclick="toggleCRUDCheck(this);">

	</th>
	<?php } ?>
	<?php foreach($listColumns as $key => $column) { ?>
		<th <?php if(in_array($column["field"], $sortableColumns)) { ?>
				onclick="window.location='<?= getLink('*/*');?>&crudListPage=0&crudListSort=<?= $column["field"];?>';return false;"
				title="<?= $column["title"].': '.trans("crud|Zum_Sortieren_anklicken");?>"
				class="<?= $column["class"];?>
				<?php if($sort == $column["field"] && ($order == 'asc' || $order == 'desc')) { ?>
					sorting_<?= $order;?>
				<?php } ?>
				"
			<?php } else { ?>
				class="<?= $column["class"];?>"
			<?php } ?>
		>
			<?php if(in_array($column["field"], $sortableColumns)) { ?>
				<a href="<?= getLink('*/*');?>&crudListPage=0&crudListSort=<?= $column["field"];?>">
			<?php } ?>
			<?= $column["title"];?>
			<?php if(in_array($column["field"], $sortableColumns)) { ?>
				</a>
			<?php } ?>
		</th>
	<?php } ?>
	<?php if(!$disableLineButtons) { ?>
	<th colspan="10"></th>
	<?php } ?>
</tr>
</thead>