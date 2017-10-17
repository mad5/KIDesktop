
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button type="submit" name="sendback" value="1" class="btn btn-success"><?= transFull("crud|speichern und zurück");?></button>
		<button type="submit" name="send" value="1" class="btn btn-info"><?= trans("crud|speichern");?></button>
		<?php if($entry=="questionsave") { ?>
			<button type="submit" name="sendandnext" style="margin-left: 20px;" onclick="$(this).closest('form').attr('target', '_blank');function(obj) { $(obj).closest('form').attr('target', ''); }(this);" value="*/previewpdfConfirm" class="btn btn-info"><?= transFull("crud|Speichern und Vorschau");?></button>
		<?php } ?>
		<div style="float:right;"><a class="btn btn-danger" href="<?= getLink('*/index'); ?>"><?= trans("crud|abbrechen");?></a></div>
		<div style="clear:both;"></div>
	</div>
</div>
