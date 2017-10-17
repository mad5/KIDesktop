<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3" style="text-align: center;">
			<br><br>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?= trans("login|Registrierung");?></h3>
				</div>
				<div class="panel-body">
					<div style="margin-bottom:20px;text-align:center;">
					</div>
					<form role="form" class="form-horizontal" action="<?= getLink("Backenduser/Register");?>" method="post">
						<fieldset>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?= trans("login|Benutzername");?></label>
								<div class="col-sm-10">
									<input class="form-control" placeholder="<?= trans("login|Benutzername");?>" name="backenduser[bu_username]" type="text" value="" required autofocus >
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?= trans("login|Passwort");?></label>
								<div class="col-sm-10">
									<input class="form-control" placeholder="<?= trans("login|Passwort");?>" name="backenduser[bu_password]" type="password" required value="" >
								</div>
							</div>
							<div class="form-group">
								<label for="inputfirstname" class="col-sm-2 control-label"><?= trans("login|Vorname");?></label>
								<div class="col-sm-10">
									<input class="form-control" id="inputfirstname" placeholder="<?= trans("login|Vorname");?>" name="backenduser[bu_firstname]" type="text" value="" autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<label for="inputlastname" class="col-sm-2 control-label"><?= trans("login|Nachname");?></label>
								<div class="col-sm-10">
									<input class="form-control" id="inputlastname" placeholder="<?= trans("login|Nachname");?>" name="backenduser[bu_lastname]" type="text" value="" autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="col-sm-2 control-label"><?= trans("login|E-Mail");?></label>
								<div class="col-sm-10">
									<input class="form-control" id="inputEmail" placeholder="<?= trans("login|E-Mail");?> (optional)" name="backenduser[bu_email]" type="email" value="" autocomplete="off">
								</div>
							</div>
							<input type="submit" class="btn btn-lg btn-success btn-block" value="<?= trans("login|Registrierung_abschliessen");?>">
						</fieldset>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>