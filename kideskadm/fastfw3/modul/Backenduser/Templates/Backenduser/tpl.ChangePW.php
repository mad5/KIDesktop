<h1>Kennwort ändern</h1>

<form action="<?= getLink("*/changePWRun");?>" method="post" class="form-horizontal" role="form" data-toggle="validator">

	<div class="form-group">
		<label for="old_pw" class="col-sm-2 control-label">Bisheriges Kennwort</label>
		<div class="col-sm-10">
			<input type="password" class="form-control dis" name="my_old_pw" id="my_old_pw" value="" required onmouseover="$(this).removeAttr('disabled');" disabled placeholder="Bisher verwendetes Kennwort">
		</div>
	</div>

	<div class="form-group">
		<label for="old_pw" class="col-sm-2 control-label">Neues Kennwort</label>
		<div class="col-sm-10">
			<input type="password" class="form-control dis" name="my_new_pw" id="my_new_pw" value="" required onmouseover="$(this).removeAttr('disabled');" disabled placeholder="Das Neue zu verwendende Kennwort">
		</div>
	</div>

	<div class="form-group">
		<label for="old_pw" class="col-sm-2 control-label">Bisheriges Kennwort</label>
		<div class="col-sm-10">
			<input type="password" class="form-control dis" name="my_new_pw2" id="my_new_pw2" value="" required onmouseover="$(this).removeAttr('disabled');" disabled placeholder="Bitte wiederholen Sie das neue Kennwort">
		</div>
	</div>


	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name="send" value="1" class="btn btn-info"><?= trans("crud|speichern");?></button>
			<div style="float:right;"><a class="btn btn-danger" href="<?= getLink('Dashboard'); ?>"><?= trans("crud|abbrechen");?></a></div>
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