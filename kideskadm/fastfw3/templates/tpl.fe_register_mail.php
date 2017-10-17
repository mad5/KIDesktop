Sie haben sich auf der Seite <?= $VAR['site']; ?> mit dem Benutzernamen <?= $VAR['username'];?> registriert<br>
Klicken Sie auf folgenden Link um Ihre Registrierung zu best&auml;tigen.<br>
<br>
<a href='<?= getLink('fe_login/confirm/'.$VAR['md5']);?>'>Registrierung best&auml;tigen</a>
