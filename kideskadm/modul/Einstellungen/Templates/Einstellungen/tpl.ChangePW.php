<h1>Zugangsdaten ändern</h1>
<?php
/**
 * @var $user \Backenduser\Model\BackenduserModel
 */
?>
<form action="<?= getLink("*/changePWRun");?>" method="post" class="form-horizontal" role="form" data-toggle="validator">


	<div class="form-group">
		<label for="old_pw" class="col-sm-2 control-label"><?= transFull("Benutzername");?></label>
		<div class="col-sm-10">
			<input type="text" class="form-control dis" name="username" id="username" value="<?= $user->getUsername()?>"  onmouseover="$(this).removeAttr('disabled');" disabled placeholder="<?= transFull("Benutzername");?>">
		</div>
	</div>

	<div class="form-group">
		<label for="old_pw" class="col-sm-2 control-label"><?= transFull("Vorname");?></label>
		<div class="col-sm-10">
			<input type="text" class="form-control dis" name="firstname" id="firstname" value="<?= $user->getFirstname()?>"  onmouseover="$(this).removeAttr('disabled');" disabled placeholder="<?= transFull("Vorname");?>">
		</div>
	</div>

	<div class="form-group">
		<label for="old_pw" class="col-sm-2 control-label"><?= transFull("Nachname");?></label>
		<div class="col-sm-10">
			<input type="text" class="form-control dis" name="lastname" id="lastname" value="<?= $user->getLastname()?>"  onmouseover="$(this).removeAttr('disabled');" disabled placeholder="<?= transFull("Nachname");?>">
		</div>
	</div>

	<div class="form-group">
		<label for="old_pw" class="col-sm-2 control-label"><?= transFull("Bisheriges Kennwort");?></label>
		<div class="col-sm-10">
			<input type="password" class="form-control dis" name="my_old_pw" id="my_old_pw" value=""  onmouseover="$(this).removeAttr('disabled');" disabled placeholder="<?= transFull("Bisher verwendetes Kennwort");?>">
		</div>
	</div>

	<div class="form-group">
		<label for="old_pw" class="col-sm-2 control-label"><?= transFull("Neues Kennwort");?></label>
		<div class="col-sm-10">
			<input type="password" class="form-control dis" name="my_new_pw" id="my_new_pw" value=""  onmouseover="$(this).removeAttr('disabled');" disabled placeholder="<?= transFull("Das Neue zu verwendende Kennwort");?>">
		</div>
	</div>

	<div class="form-group">
		<label for="old_pw" class="col-sm-2 control-label"><?= transFull("Kennwort bestätigen");?></label>
		<div class="col-sm-10">
			<input type="password" class="form-control dis" name="my_new_pw2" id="my_new_pw2" value=""  onmouseover="$(this).removeAttr('disabled');" disabled placeholder="<?= transFull("Bitte wiederholen Sie das neue Kennwort");?>">
		</div>
	</div>

	<hr>
	
	<div class="form-group">
		<label for="old_pw" class="col-sm-2 control-label"><?= transFull("Haupt-Kennung");?></label>
		<div class="col-sm-3">
			<input type="text" class="form-control" value="<?= $user->getHash();?>" onfocus="this.select();" placeholder="">
		</div>
		<div class="col-sm-7">
			Bitte in die Datei config.json hinter <b>mainkey</b> eintragen.<br>
<pre>
{
	...
	"mainkey": "<?= $user->getHash();?>",
	...
}
</pre>
		</div>
	</div>
	

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name="send" value="1" class="btn btn-info"><?= trans("crud|speichern");?></button>
			
			<div style="clear:both;"></div>
		</div>
	</div>


</form>

<script>
$(function() {
	setTimeout(function() {
		$('.dis').removeAttr("disabled");
	}, 1000);
});
</script>
