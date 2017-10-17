<?php
$html .= '<div class="form-group">'."\n";
	$html .= '		<label for="input'.$data[0].'" class="col-sm-2 control-label">'.ucfirst($data[0]).'</label>'."\n";
	$html .= '		<div class="col-sm-2 <'.'?= (\classes\FormErrorHandler::has("'.$model.'", "'.$data[0].'") ? "errorForm" : ""); ?'.'>">'."\n";
	$html .= '			<input type="text" class="form-control" name="'.$feldName.'" id="input'.$data[0].'" placeholder="" value="<?= $'.$model.'->'.($isLangDistinct ? 'getLangDistinction()->' : '').'get'.ucfirst($data[0]).'(); ?>" >'."\n";
	$html .= '			<'.'?= \classes\FormViewHelper::showErrorMessage("'.$model.'", "'.$data[0].'"); ?>'."\n";
	$html .= '		</div>'."\n";
$html .= '	</div>'."\n";
$html .= "\n";
?>