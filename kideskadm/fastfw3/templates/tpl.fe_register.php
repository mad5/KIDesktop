<b>Registrieren</b><br/>
<br/><br/>

<?php if($VAR['registered']==1) { ?>
	Vielen Dank f&uuml;r Ihre Anmeldung.<br/>
	Sie erhalten in K&uuml;rze eine E-mail mit der Sie Ihren Zugang freischalten k&ouml;nnen.
	
<?php } else { ?>

<script type="text/javascript">
function isReady() {
	// {{{
	var err = '';
	if($('fe_user').value=='') err += '- Loginname\n';
	if($('fe_pass').value=='') err += '- Passwort\n';
	if($('fe_email').value=='') err += '- E-Mail\n';
	if($('fe_pass').value!=$('fe_pass2').value) err += '- Passworte stimmen nicht ueberein.\n';
	if(err!='') {
		alert(err);
		return(false);
	}
	return(true);
	// }}}
}
</script>
<form method="post" action="">
<input type='hidden' name="send_fe_register" value="1"/>
<table summary="">
<tr>
	<td>Loginname</td>
	<td><input type='text' size=30 name='fe_user' id='fe_user'/></td>
</tr>
<tr>
	<td>Passwort</td>
	<td><input type='password' size=30 name='fe_pass' id='fe_pass'/></td>
</tr>
<tr>
	<td>Wiederholung</td>
	<td><input type='password' size=30 name='fe_pass2' id='fe_pass2'/></td>
</tr>
<tr>
	<td>E-Mail</td>
	<td><input type='text' size=30 name='fe_email' id='fe_email'/></td>
</tr>
<tr>
	<td></td>
	<td><br/><input type=submit value='Anmelden'/></td>
</tr>
</table>
</form>
<?php } ?>
