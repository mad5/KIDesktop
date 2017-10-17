<div class="panel panel-default">
	<div class="panel-heading" style="padding-top:5px;padding-bottom:0px;">

		<div class="btn-group pull-right">
			<form method="post" action="<?= getLink("*/*"); ?>&crudListPage=0">
				<table>
					<tr>
						<td height=35>&nbsp;</td>
						
						<?php if (!$disablePerPageSelect) { ?>
						<td>
							<div id="dataTables-example_length" class="dataTables_length">
								<label>
									<select class="form-control input-sm crudListPerPage" name="crudListPerPage" aria-controls="dataTables-example" onchange="submit();">
										<?php $values = \classes\CrudService::$elementsPerPage; ?>
										<?php foreach ($values as $val) { ?>
											<option value="<?= $val; ?>" <?= ($perPage == $val ? 'selected' : ''); ?>><?= $val; ?></option>
										<?php } ?>

									</select>

								</label>
							</div>
						</td>
						
						<td style="padding-left:5px;"><?= trans("crud|Eintraege_pro_Seite");?></td>						
						<td width="50"></td>
						<?php } ?>
						
						<?php if (!$disableQuicksearch) { ?>
						<td><?= trans("crud|Schnellsuche");?>:&nbsp;</td>
						<td>
							<div id="dataTables-example_filter" class="dataTables_filter">
								<label>
									<div class="btn-group">
										<input class="form-control input-sm searchinput crudListSearch<?= ($search != "" ? ' inputFilled' : ''); ?>" type="search" name="crudListSearch" value="<?= htmlspecialchars($search); ?>" aria-controls="dataTables-example" autocomplete="off">
										<span id="searchclear" class="glyphicon glyphicon-remove-circle"></span>
									</div>

								</label>
							</div>
						</td>
						<?php } ?>
					</tr>
				</table>
			</form>
		</div>

		<div style="padding-top:5px;margin-right:20px;" class="pull-left">
			<?php if (!$disableNewButton) { ?>
				<a href="<?php $actionNewDefault = '*/new'; if ($actionNew != '') { $actionNewDefault = $actionNew; } echo getLink($actionNewDefault); ?>">
					<i class="glyphicon glyphicon-plus-sign"></i>
					<?php $actionNewTitleDefault = trans("crud|Neuer_Eintrag"); if ($actionNewTitle != '') { $actionNewTitleDefault = $actionNewTitle; } echo $actionNewTitleDefault; ?>
				</a>
			<?php } ?>
		</div>

		<?php foreach($headButtons as $headButton) { ?>
			<div style="padding-top:5px;margin-right:20px;" class="pull-left">
					<a href="<?= getLink($headButton["link"]); ?>">
						<?php if($headButton["symbol"]!="") { ?><i class="<?= $headButton["symbol"];?>"></i><?php } ?>
						<?= $headButton["title"];?>
					</a>
			</div>
		<?php } ?>

		<div style="clear:both;"></div>
	</div>

	<div class="panel-body">
		<div class="table">
			<div id="dataTable_wrapper" class="dataTables_wrapper form-inline" role="grid">

				<?= $listContent; ?>

				<div class="row">
					<?php if ($foundRows > 0 && !$disableGroupCommand) { ?>
						<form method="post" action="<?= getLink("*/groupCommand"); ?>" onsubmit="return collectCrudCheckboxes();">
							<input type="hidden" name="pks" id="multiPks" value="">
							<div class="col-sm-3">
								<select name="groupAction" class="form-control" id="groupCommand">
									<option value=""><?= transFull("crud|Aktion auswählen");?></option>
									<?php foreach ($groupActions as $groupActionIndex => $groupActionLabel) { ?>
										<option value="<?= $groupActionIndex; ?>"><?= transFull("crud|".$groupActionLabel); ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-sm-1">
								<button class="form-control"><i class="glyphicon glyphicon-arrow-right"></i></button>
							</div>
						</form>
					<?php } else { ?>
						<div class="col-sm-4"> </div>
					<?php } ?>
					<div class="col-sm-2">
					</div>
					<div class="col-sm-6">
						<?php
						$pages = ceil($foundRows / max(\classes\CrudService::MIN_PER_PAGE, (int)$perPage));
						?>
						<?php if ($foundRows > 0 && $pages>1) { ?>
							<div id="dataTable_paginate" class="dataTables_paginate paging_full_numbers">
								<ul class="pagination">
									<li id="dataTable_first" class="paginate_button first <?= ((int)$page == 0 ? "disabled" : ""); ?>"
										tabindex="0">
										<a href="<?= getLink('*/*'); ?>&crudListPage=0" <?= ((int)$page == 0 ? "onclick='return false;'" : ""); ?>>|&lt;</a>
									</li>
									<li id="dataTable_previous"
										class="paginate_button previous <?= ((int)$page == 0 ? "disabled" : ""); ?>" tabindex="0">
										<a href="<?= getLink('*/*'); ?>&crudListPage=<?= ($page - 1); ?>" <?= ((int)$page == 0 ? "onclick='return false;'" : ""); ?>>&lt;</a>
									</li>
									
									<?php for ($i = 0; $i < $pages; $i++) { ?>
										<li class="paginate_button <?= ($i == (int)$page ? "active" : ""); ?>" tabindex="0">
											<a href="<?= getLink('*/*'); ?>&crudListPage=<?= $i; ?>"><?= ($i + 1); ?></a>
										</li>
									<?php } ?>
									<li id="dataTable_next"
										class="paginate_button next <?= ((int)$page >= $pages - 1 ? "disabled" : ""); ?>" tabindex="0">
										<a href="<?= getLink('*/*'); ?>&crudListPage=<?= ($page + 1); ?>" <?= ((int)$page >= $pages - 1 ? "onclick='return false;'" : ""); ?>>&gt;</a>
									</li>
									<li id="dataTable_last"
										class="paginate_button last <?= ((int)$page >= $pages - 1 ? "disabled" : ""); ?>" tabindex="0">
										<a href="<?= getLink('*/*'); ?>&crudListPage=<?= ($pages - 1); ?>" <?= ((int)$page >= $pages - 1 ? "onclick='return false;'" : ""); ?>>&gt;|</a>
									</li>
								</ul>
							</div>
							<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function collectCrudCheckboxes() {

	if($('#groupCommand').val()=="") {
		alert("Bitte wählen Sie eine Aktion!");
		return false;
	}

	var pks = [];
	$('.crudMulti:checked').each(function() {
		pks.push($(this).val());
	});
	if(pks.length==0) {
		alert("Bitte markieren Sie mindestens einen Eintrag!");
		return false;
	}
	//cl(pks);

	$('#multiPks').val(pks.join(','));

	return true;
}
</script>

