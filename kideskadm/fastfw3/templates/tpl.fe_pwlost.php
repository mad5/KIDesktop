<b>Passwort vergessen</b><br/>
<br/><br/>

<?php if($VARS->get('pwlostsend')=='1') { ?>
	Vielen Dank.<br/>
	Sie erhalten in K&uuml;rze ein neues Passwort mit dem Sie sich in Ihren Zugang einloggen k&ouml;nnen.
	<br/><br/>
	<a href='<?= getLink('fe_login');?>'>weiter zum Login</a>
<?php } else if($VARS->get('pwlostsend')=='-1') { ?>
	Leider existiert kein Eintrag mit diesem Loginnamen oder dieser E-Mailadresse.<br>
	<br/><br/>
	<a href='<?= getLink('fe_login');?>'>weiter zum Login</a>
<?php } else { ?>

<script type="text/javascript">
function isReady() {
	// {{{
	var err = '';
	if($('fe_pwlost').value=='') err += '- Loginname oder E-Mailadresse\n';
	if(err!='') {
		alert(err);
		return(false);
	}
	return(true);
	// }}}
}
</script>
<form method="post" action="">
<input type='hidden' name="send_fe_pwlost" value="1" />
<table summary="">
<tr>
	<td>Loginname oder E-Mailadresse</td>
	<td><input type='text' size=30 name='fe_pwlost' id='fe_pwlost' /></td>
</tr>
<tr>
	<td></td>
	<td><br/><input type=submit value='Neues Passwort anfordern' /></td>
</tr>
</table>
</form>
<?php } ?>
