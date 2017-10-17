<form onsubmit="return false;">
<h2>Deine Nachricht an <?= $mailkontakt->getName();?>:</h2>
<textarea class="form-control" id="nachricht" rows=5 style="width: 100%;" autofocus></textarea>
<button class="btn btn-default" onclick="window.close();">löschen</button>
<button class="btn btn-success pull-right" onclick="sendMsg();"><i class="glyphicon glyphicon-envelope"></i> Nachricht absenden</button>
</form>
<script>
function sendMsg() {
	$.ajax({
			"url": "<?= getLink("*/dosend/".$rechner->getHash()."/".$mailkontakt->getHash());?>",
			"type": "post",
			"data": {"nachricht": $("#nachricht").val()},
			"dataType": "json",
			"success": function(data) {
				window.close();
			}
	});
	$("#nachricht").val("");
	
}
</script>