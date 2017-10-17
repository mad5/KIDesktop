<style>
body {
margin:0;
}
.ungelesen td {
	background-color: silver;
	font-weight: bold;
}
</style>
<h2>Deine Nachrichten mit: <?= $mailkontakt->getName();?></h2>
<div>
<table width=100% cellspacing=0 cellpadding=5 id="nachrichtenliste"><tr>
<?php
$lastNPk=0;
foreach($nachrichten as $nachricht) { 
	$lastNPk = $nachricht->getPk();
	?>
	<tr class='<?= ($nachricht->getSender()=="kontakt" && $nachricht->getGelesen()==_DATE0 ? 'ungelesen' : '');?>'>
		<td valign=top width=50>
				<?php if($nachricht->getSender()=="rechner") { ?>
					<img src='uploads/<?= $nachricht->getRechner()->getBild();?>' width=50 style="border-radius: 50%;">
				<?php } else { ?>
					<img src='uploads/<?= $nachricht->getMailkontakt()->getBild();?>' width=50 style="border-radius: 50%;">
				<?php } ?>
		</td>
		<td valign=top style="border-bottom: dotted 1px gray;padding: 5px;" nowrap width=100>
			<b>
				<?php if($nachricht->getSender()=="rechner") { ?>
					<?= $nachricht->getRechner()->getKind();?>
				<?php } else { ?>
					<?= $nachricht->getMailkontakt()->getName();?>
				<?php } ?>
				:</b>
				
		</td>
		<td valign=top style="border-bottom: dotted 1px gray;padding: 5px;"><?= nl2br($nachricht->getNachricht()); ?></td>
		
		<td valign=top align=right style="border-bottom: dotted 1px gray;padding: 5px;">
			<?= formatDateHuman($nachricht->getCreatedate());?>
			<?php if($nachricht->getGelesen()!=_DATE0) echo "<i class='glyphicon glyphicon-ok'></i>"; ?>
		</td>
		</tr>
	
<?php } ?>
</table>
</div>

<form onsubmit="return false;">
<h3>Nachricht senden an <?= $mailkontakt->getName();?>:</h3>
<textarea class="form-control" id="nachricht" rows=3 style="width: 100%;" autofocus></textarea>
<button id="delbutton" class="btn btn-default" onclick="$('#nachricht').val('');">löschen</button>
<button class="btn btn-success pull-right" onclick="sendMsg();"><i class="glyphicon glyphicon-envelope"></i> Nachricht absenden</button>
</form>
<script>
var lastNPk = <?= $lastNPk;?>;
function sendMsg() {
	if($("#nachricht").val().trim()=="") return;
	$.ajax({
			"url": "<?= getLink("*/doantwort/".$rechner->getHash()."/".$mailkontakt->getHash());?>",
			"type": "post",
			"data": {"nachricht": $("#nachricht").val(), "wer": "rechner"},
			"dataType": "json",
			"success": function(data) {
				window.location = '<?= getLink("*/*/*/*");?>&dat=<?= time();?>';
			}
	});
	$("#nachricht").val("");
	
}
$(function() {
		document.getElementById("delbutton").scrollIntoView();
		setInterval(function() {
				checkForNewMsg();
		}, 5000);
});



function checkForNewMsg() {
	$.ajax({
			"url": "<?= getLink("*/newmsgs/".$rechner->getHash()."/".$mailkontakt->getHash());?>",
			"type": "post",
			"data": {"lastNPk": lastNPk},
			"dataType": "json",
			"success": function(data) {
				if(data.found>0) {
					$('#nachrichtenliste').append(data.html);
					lastNPk = data.lastNPk;
					document.getElementById("delbutton").scrollIntoView();
				}
			}
	});
}
</script>