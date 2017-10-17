<?php
$html .= '<div class="form-group">'."\n";
$html .= '		<label for="input'.$data[0].'" class="col-sm-2 control-label">'.ucfirst($data[0]).'</label>'."\n";
$html .= '		<div class="col-sm-10 <'.'?= (\classes\FormErrorHandler::has("'.$model.'", "'.$data[0].'") ? "errorForm" : ""); ?'.'>">'."\n";
$html .= '			<textarea class="form-control" rows="5" name="'.$feldName.'" id="input'.$data[0].'" placeholder=""><?= $'.$model.'->'.($isLangDistinct ? 'getLangDistinction()->' : '').'get'.ucfirst($data[0]).'(); ?></textarea>'."\n";
$html .= '			<'.'?= \classes\FormViewHelper::showErrorMessage("'.$model.'", "'.$data[0].'"); ?>'."\n";
$html .= '		</div>'."\n";
$html .= '	</div>'."\n";
$html .= "\n";

?>