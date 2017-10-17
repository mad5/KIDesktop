<b>Login</b><br/>
<br/><br/>
<form method="post" action="" class="form-horizontal" role="form">
<input type='hidden' name="send_fe_login" value="1"/>

<div class="form-group">
    <label for="fe_user" class="col-sm-2 control-label"><?= TRANS("Benutzername"); ?></label>
    <div class="col-sm-10">
    	<input type="text" class="form-control" id="fe_user" name="fe_user" placeholder="<?= TRANS("Benutzername"); ?>">
    </div>
</div>

<div class="form-group">
    <label for="fe_pass" class="col-sm-2 control-label"><?= TRANS("Passwort"); ?></label>
    <div class="col-sm-10">
    	<input type="password" class="form-control" id="fe_pass" name="fe_pass" placeholder="<?= TRANS("Passwort"); ?>">
    </div>
</div>


<table summary="logintable">
<tr>
	<td>Benutzername</td>
	<td><input type='text' size=30 name='fe_user'/></td>
</tr>
<tr>
	<td>Passwort</td>
	<td><input type='password' size=30 name='fe_pass'/></td>
</tr>
<tr>
	<td></td>
	<td><input type='checkbox' value='1' name='fe_angemeldetbleiben' checked /> angemeldet bleiben</td>
</tr>
<tr>
	<td></td>
	<td><br/><input type=submit value='Anmelden'/></td>
</tr>
</table>
</form>
<br/><br/>
Passwort vergessen? <a href='<?= getLink('fe_login/pwlost');?>'>Neues Passwort anfordern.</a>
<?php if(disableRegistration!=true) { ?>
<br/><br/>
Noch keinen Account? <a href='<?= getLink('fe_login/register');?>'>Dann jetzt anmelden.</a>
<?php } ?>


