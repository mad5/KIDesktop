<?php
$html .= '<div class="form-group">'."\n";
	$html .= '		<label for="input'.$data[0].'" class="col-sm-2 control-label">'.ucfirst($data[0]).'</label>'."\n";
	$html .= '		<div class="col-sm-2 <'.'?= (\classes\FormErrorHandler::has("'.$model.'", "'.$data[0].'") ? "errorForm" : ""); ?'.'>">'."\n";
	if ($data[4] == "m") {
	$html .= '			<?php foreach ($' . $model . '->getAllPossible' . ucfirst($data[0]) . '() as $' . $data[0] . ') { ?>' . "\n";
		$html .= '				<input type="checkbox" name="' . $model . '[' . $prefix . '_' . $data[0] . '][]" value="<?= $' . $data[0] . '->getPk(); ?>" <?= ($'.$model.'->isIn'.$data[0].'($' . $data[0] . '->getPk()) ? "checked" : ""); ?> ><?= $' . $data[0] . '->get' . ucfirst($data[3]) . '(); ?><br>' . "\n";
		$html .= '			<?php } ?>' . "\n";
	} else {
	$html .= '			<select name="' . $model . '[' . $prefix . '_' . $data[0] . ']" class="form-control">' . "\n";
		$html .= '			<option></option>' . "\n";
		$html .= '			<?php foreach ($' . $model . '->getAllPossible' . ucfirst($data[0]) . '() as $' . $data[0] . ') { ?>' . "\n";
			$html .= '				<option value="<?= $' . $data[0] . '->getPk(); ?>" <?= ((int)$' . $data[0] . '->getPk()===(int)$' . $model . '->get' . ucfirst($data[0]) . '()->getPk() ? "selected" : ""); ?> ><?= $' . $data[0] . '->get' . ucfirst($data[3]) . '(); ?></option>' . "\n";
			$html .= '			<?php } ?>' . "\n";
		$html .= '			</select>' . "\n";
	}


	$html .= '			<'.'?= \classes\FormViewHelper::showErrorMessage("'.$model.'", "'.$data[0].'"); ?>'."\n";
	$html .= '		</div>'."\n";
$html .= '	</div>'."\n";
$html .= "\n";
?>