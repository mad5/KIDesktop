<?php
$html .= '<div class="form-group">'."\n";
$html .= '		<label for="input'.$data[0].'" class="col-sm-2 control-label">'.ucfirst($data[0]).'</label>'."\n";

$html .= '			<div class="col-sm-5">'."\n";
$html .= '					<input type="file" class="" name="'.$feldName.'" id="input'.$data[0].'"'."\n";
$html .= '						   placeholder="Datei" value="">'."\n";
$html .= '					<'.'?= \classes\FormViewHelper::showErrorMessage("'.$model.'", "'.$data[0].'"); ?'.'>'."\n";
$html .= '			</div>'."\n";
$html .= '			<div class="col-sm-5">'."\n";
$html .= '				<'.'?php if($'.$model.'->'.($isLangDistinct ? 'getLangDistinction()->' : '').'get'.ucfirst($data[0]).'()!="") { ?'.'>'."\n";
$html .= '					Datei vorhanden: <a href="<?= \classes\FileUtils::getFileDownloadLink($'.$model.'->'.($isLangDistinct ? 'getLangDistinction()->' : '').'get'.ucfirst($data[0]).'()); ?>">Download</a><br>'."\n";
$html .= '					Datei entfernen: <input type="checkbox" value="1" name="'.$model.'['.$prefix.'_'.$data[0].'_remove]">'."\n";
$html .= '				<?php } ?>'."\n";
$html .= '			</div>'."\n";

$html .= '			<'.'?= \classes\FormViewHelper::showErrorMessage("'.$model.'", "'.$data[0].'"); ?>'."\n";
$html .= '	</div>'."\n";
$html .= "\n";


?>