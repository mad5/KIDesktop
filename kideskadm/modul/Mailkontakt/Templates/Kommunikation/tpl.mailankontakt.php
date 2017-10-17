<h2>Neue Nachricht von: <?= $rechner->getKind();?></h2>

Dir wurde eine Nachricht gesendet!<br>
Klick auf folgenden Link um die Nachricht zu öffnen:<br>
<br>
<a href='<?= fullLink("Mailkontakt/lesen/".$rechner->getHash()."/".$mailkontakt->getHash());?>'>Nachricht lesen und beantworten</a>

<hr>

<a href='<?= fullLink("Mailkontakt/lesen/".$rechner->getHash()."/".$mailkontakt->getHash());?>'><img src='cid:<?= $cid;?>' border=0></a>