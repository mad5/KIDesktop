<form class="form-horizontal" role="form" method="post" action="<?= getLink('*/' . $formAction . '/' . $backenduser->getPk()); ?>">

	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">Username</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has('backenduser', 'bu_username') ? 'errorForm' : ''); ?>">
			<input type="text" class="form-control" name="backenduser[bu_username]" id="inputName"
				   placeholder="" value="<?= $backenduser->getUsername(); ?>">
			<?= \classes\FormViewHelper::showErrorMessage('backenduser', 'bu_username'); ?>
		</div>
	</div>

	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">Vorname</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has('backenduser', 'bu_firstname') ? 'errorForm' : ''); ?>">
			<input type="text" class="form-control" name="backenduser[bu_firstname]" id="inputName"
				   placeholder="" value="<?= $backenduser->getFirstname(); ?>">
			<?= \classes\FormViewHelper::showErrorMessage('backenduser', 'bu_firstname'); ?>
		</div>
	</div>

	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">Nachname</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has('backenduser', 'bu_lastname') ? 'errorForm' : ''); ?>">
			<input type="text" class="form-control" name="backenduser[bu_lastname]" id="inputName"
				   placeholder="" value="<?= $backenduser->getLastname(); ?>">
			<?= \classes\FormViewHelper::showErrorMessage('backenduser', 'bu_lastname'); ?>
		</div>
	</div>

	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">E-Mailadresse</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has('backenduser', 'bu_email') ? 'errorForm' : ''); ?>">
			<input type="text" class="form-control" name="backenduser[bu_email]" id="inputName"
				   placeholder="" value="<?= $backenduser->getEmail(); ?>">
			<?= \classes\FormViewHelper::showErrorMessage('backenduser', 'bu_email'); ?>
		</div>
	</div>


	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">Rollen</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has('role', 'bu_roles') ? 'errorForm' : ''); ?>">
			<?php foreach ($roles as $role) { ?>
				<input type="checkbox" class="" name="backenduser[bu_roles][]" id="bu_roles_<?php echo $role->getPk(); ?>" value="<?php echo $role->getPk(); ?>"<?= ($backenduser->hasRole($role) ? ' checked="" ' : ''); ?>/>
				<label for="bu_roles_<?php echo $role->getPk(); ?>"><?php echo $role->getName(); ?></label><br />
			<?php } ?>
		</div>
	</div>

	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">Administrator</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has('backenduser', 'bu_admin') ? 'errorForm' : ''); ?>">
			<input type="checkbox" name="backenduser[bu_admin]" value="1" id="inputName"  <?= ($backenduser->isAdmin() ? ' checked="" ' : ''); ?>>
			Nutzer die als Administratoren markiert sind, dürfen unabhängig von Rollen auf alle Teile des Systems zugreifen.
			<?= \classes\FormViewHelper::showErrorMessage('backenduser', 'bu_admin'); ?>
		</div>
	</div>

	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">Gesperrt</label>
		<div class="col-sm-10 <?= (\classes\FormErrorHandler::has('backenduser', 'bu_disable') ? 'errorForm' : ''); ?>">
			<input type="checkbox" name="backenduser[bu_disable]" value="1" id="inputName"  <?= ($backenduser->getDisable()==1 ? ' checked="" ' : ''); ?>>
			Benutzer kann sich nicht einloggen, solange er gesperrt ist.
			<?= \classes\FormViewHelper::showErrorMessage('backenduser', 'bu_disable'); ?>
		</div>
	</div>

	<?= \classes\CrudViewHelper::getFormButtons(); ?>

</form>