<?php if($VAR['error']==1) { ?>
Dieser Link ist leider ung&uuml;ltig.<br>
Bitte pr&uuml;fen Sie den Link in Ihrer E-Mail noch einmal, oder melden Sie sich erneut an.<br>
<br>
<a href='<?= getLink('fe_login/register');?>'>neu Registrieren.</a>
<?php } else { ?>
Vielen Dank.<br>
Ihr Zugang ist nun freigeschaltet.<br>
<br>
<a href='<?= getLink('fe_login');?>'>Um sich nun anzumelden, folgende Sie bitte diesem Link.</a>
<?php } ?>
