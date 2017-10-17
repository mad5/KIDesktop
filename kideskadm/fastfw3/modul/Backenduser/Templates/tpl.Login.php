<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3" style="text-align: center;">

			<h1><?= transFull("login|Anmeldung");?></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?= trans("login|Anmeldung");?></h3>
				</div>
				<div class="panel-body">
					<form role="form" action="<?= getLink("Backenduser/checkLogin");?>" method="post">
						<fieldset>
							<div class="form-group">
								<input class="form-control" placeholder="<?= trans("login|Benutzername");?>" name="backenduser[bu_username]" type="text" value="" autocomplete="off" autofocus >
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="<?= trans("login|Kennwort");?>" name="backenduser[bu_password]" type="password" value="" autocomplete="off">
							</div>
							<!--
							<div class="checkbox">
								<label>
									<input name="remember" type="checkbox" value="Remember Me">Remember Me
								</label>
							</div>
							-->
							<input type="submit" class="btn btn-lg btn-success btn-block" value="<?= trans("login|Anmelden");?>">
						</fieldset>
					</form>



				</div>
			</div>
		</div>
	</div>
</div>