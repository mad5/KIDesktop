<form class="form-horizontal" role="form" method="post" action="<?= getLink('*/' . $formAction . '/' . $role->getPk()); ?>">

	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has('role', 'br_name') ? 'errorForm' : ''); ?>">
			<input type="text" class="form-control" name="role[br_name]" id="inputName"
				   placeholder="" value="<?= $role->getName(); ?>">
			<?= \classes\FormViewHelper::showErrorMessage('role', 'br_name'); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">Rollentyp</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has('role', 'br_roletype_fk') ? 'errorForm' : ''); ?>">
			<?php foreach ($roletypes as $roletype) { ?>
				<input type="radio" class="" name="role[br_roletype_fk]" id="br_roletype_fk_<?php echo $roletype->getPk(); ?>" value="<?php echo $roletype->getPk(); ?>"<?php echo ($role->getRoletype() && $role->getRoletype()->getPk() == $roletype->getPk() ? ' checked="checked"' : ''); ?> />
				<label for="br_roletype_fk_<?php echo $roletype->getPk(); ?>"><?php echo $roletype->getName(); ?></label><br />
			<?php } ?>
		</div>
	</div>

	<div class="form-group">
		<label for="inputDesc" class="col-sm-2 control-label">Beschreibung</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has('role', 'br_description') ? 'errorForm' : ''); ?>">
			<input type="text" class="form-control" name="role[br_description]" id="inputDesc"
				   placeholder="" value="<?= $role->getDescription(); ?>">
			<?= \classes\FormViewHelper::showErrorMessage('role', 'br_description'); ?>
		</div>
	</div>

	<div class="form-group">
		<label for="inputDesc" class="col-sm-2 control-label">Rechte</label>
		<div class="col-sm-10">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th colspan="3" style="text-align:center;">eigene</th>
						<!-- <th colspan="3" style="text-align:center;">andere</th> -->
					</tr>
					<tr class="success">
						<th>Bereich</th>
						<th style="text-align:center;">kein Zugriff</th>
						<th style="text-align:center;">lesen</th>
						<th style="text-align:center;">lesen+schreiben</th>

						<th style="text-align:center;">kein Zugriff</th>
						<th style="text-align:center;">lesen</th>
						<th style="text-align:center;">lesen+schreiben</th>

					</tr>
				</thead>
				<tbody>
					<?php $lastRoleGroupPk = 0;
					foreach ($areas as $key => $area) {
						#if($area->getId()!="event") continue;
						if ($area->getGroup()->getPk() != $lastRoleGroupPk) { ?>
							<tr>
								<td colspan="7"><strong><?= $area->getGroup()->getName(); ?></strong></td>
							</tr>
						<?php }
						$lastRoleGroupPk = $area->getGroup()->getPk(); ?>
						<tr>
							<td style="border-right:solid 1px silver;">&nbsp;&nbsp;&nbsp;<?= $area->getName(); ?></td>
							<td style="text-align:center;">
								<input type="radio" name="role[rightown][<?= $area->getId();?>]" value="" <?= ($selectedAreas[$area->getId()]['own']=='' ? 'checked="checked"' : '');?>>
							</td>
							<td style="text-align:center;">
								<input type="radio" name="role[rightown][<?= $area->getId();?>]" value="r" <?= ($selectedAreas[$area->getId()]['own']=='r' ? 'checked="checked"' : '');?>>
							</td>
							<td style="text-align:center;border-right:solid 1px silver;">
								<input type="radio" name="role[rightown][<?= $area->getId();?>]" value="w" <?= ($selectedAreas[$area->getId()]['own']=='w' ? 'checked="checked"' : '');?>>
							</td>

							<td style="text-align:center;">
								<input type="radio" name="role[rightother][<?= $area->getId();?>]" value="" <?= ($selectedAreas[$area->getId()]['other']=='' ? 'checked="checked"' : '');?>>
							</td>
							<td style="text-align:center;">
								<input type="radio" name="role[rightother][<?= $area->getId();?>]" value="r" <?= ($selectedAreas[$area->getId()]['other']=='r' ? 'checked="checked"' : '');?>>
							</td>
							<td style="text-align:center;">
								<input type="radio" name="role[rightother][<?= $area->getId();?>]" value="w" <?= ($selectedAreas[$area->getId()]['other']=='w' ? 'checked="checked"' : '');?>>
							</td>

						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

	<?= \classes\CrudViewHelper::getFormButtons(); ?>

</form>