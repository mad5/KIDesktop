<div class="alert alert-danger" role="alert">
	<?php if ($multiple == 1) { ?>
		<h2>Sollen diese Datensätze wirklich kopiert werden?</h2>
	<?php } else { ?>
		<h2>Soll dieser Datensatz wirklich kopiert werden?</h2>
	<?php } ?>
</div>

<div style="float:left;">
	<a href="<?= getLink("*/index");?>" class="btn btn-info" style="width:100px;">Nein</a>
</div>

<div style="float:left;margin-left:10px;">
	<form method="post" action="<?= getLink("*/copy/".$this->fw->QS[2]);?>">
		<button  class="btn btn-danger" style="width:100px;">Ja</button>
	</form>
</div>

<div style="clear:both;"></div>