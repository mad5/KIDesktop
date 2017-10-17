<script>
$(function() {
		setTimeout(function() {
				window.location='<?= getLink("*/*/*");?>';
		}, 1000*60*1);
});
</script>
<style>
body {
margin:0;
}
</style>
<h2>Deine Kontakte</h2>
<table class="table table-striped" style="">
<?php foreach($mailkontakte as $kontakt) { ?>
	<tr class='kontakte' rel='<?= $kontakt->getHash();?>'>
		<td width=50><img src="uploads/<?= $kontakt->getBild();?>" height=50 style="border-radius:50%;"></td>
		<td style="font-size: 2em;"><?= $kontakt->getName();?></td>
		<td style="font-size: 2em;color: #008000;">
			<?php $C = $kontakt->anzahlUngelesene($rechner); ?>
			<?php if($C>0) { ?>
				<?= $C; ?> ungelesene Nachricht<?= ($C>1 ? 'en' : ''); ?>
			<?php } ?>
		</td>
		<td>
			<?php if($C>0) { ?>
			<?php } ?>
		</td>
	</tr>
<?php } ?>
</table>

<style>
.kontakte td {
	cursor: pointer;
}
</style>
<script>
$(function() {
		$('.kontakte td').on("click", function() {
				var mk_hash = $(this).closest("tr").attr("rel");
				window.open("index.php?fw_goto=Mailkontakt/schreiben/<?= $rechner->getHash();?>/"+mk_hash, "nachrichtmit"+mk_hash	, "width=500,height=600,resizable=yes,scrollbars=yes");
				setTimeout(function() {
						window.location = "<?= getLink("*/*/*/*");?>";
				}, 3000);
		});
});
</script>