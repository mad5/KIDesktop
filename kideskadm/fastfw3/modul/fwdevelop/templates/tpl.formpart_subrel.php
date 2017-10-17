<?php
$mm = explode("/", $data[2]);


$html .= '<div class="form-group">'."\n";
	$html .= '		<label for="input'.$mm[1].'" class="col-sm-2 control-label">'.$mm[1]."\n";
		$html .= '			<a href="#" onclick="$(this).closest(\'.form-group\').append($(\'#'.ucfirst($mm[1]).'PartialTpl\').html().replaceAll(\''.$mm[1].'[]\', \''.$mm[1].'[-\'+(new Date()).getTime()+\']\'));return false;">[+]</a>'."\n";
		$html .= '		</label>'."\n";
	$html .= '		<?php $first = true;'."\n";
						$html .= '		$list'.ucfirst($mm[1]).' = $'.$model.'->getAllRelated'.ucfirst($mm[1]).'();'."\n";
						$html .= '		if(count($list'.ucfirst($mm[1]).')==0) $list'.ucfirst($mm[1]).' = array(new \\classes\\NullObj());'."\n";
						$html .= '		foreach($list'.ucfirst($mm[1]).' as $'.$mm[1].') { ?>'."\n";
		$html .= '			<div class="col-sm-9 <?= (!$first ? "col-md-offset-2" : "");?>">'."\n";
			$html .= '				<?php include dirname(__FILE__)."/tpl.'.ucfirst($mm[1]).'Partial.php"; ?>'."\n";
			$html .= '			</div>'."\n";
										$html .= '			<div class="col-sm-1">'."\n";
			$html .= '				<?php if(!$first) { ?>'."\n";
				$html .= '					<a href="#" onclick="$(this).closest(\'div\').after(\'<input type=hidden name='.$mm[1].'[<?= $'.$mm[1].'->getPk();?>] value=-1>\');$(this).closest(\'div\').prev().remove();$(this).closest(\'div\').remove();return false;">'."\n";
					$html .= '						[-]'."\n";
					$html .= '					</a>'."\n";
				$html .= '				<?php } ?>'."\n";
			$html .= '			</div>'."\n";
		$html .= '			<?php $first = false;?>'."\n";
		$html .= '		<?php } ?>'."\n";
	$html .= '</div>'."\n";
$html .= '<script type="text/plain" id="'.ucfirst($mm[1]).'PartialTpl">'."\n";
						$html .= '		<div class="col-sm-9 col-md-offset-2">'."\n";
						$html .= '		<?php'."\n";
						$html .= '	$'.$mm[1].' = new \\classes\\NullObj();'."\n";
						$html .= '	include dirname(__FILE__)."/tpl.'.ucfirst($mm[1]).'Partial.php";'."\n";
						$html .= '	?>'."\n";
						$html .= '		</div>'."\n";
						$html .= '		<div class="col-sm-1">'."\n";
						$html .= '			<a href="#" onclick="$(this).closest(\'div\').prev().remove();$(this).closest(\'div\').remove();return false;">[-]</a>'."\n";
						$html .= '		</div>'."\n";
						$html .= '</script>'."\n\n";


$otherFields = explode("|", $data[4]);

$partial = "";
foreach($otherFields as $oField) {
$partial .= '<div class="form-group">' . "\n";
	$partial .= '	<label for="input' . $oField . '" class="col-sm-2 control-label">' . ucfirst($oField) . '</label>' . "\n";
	$partial .= '	<div class="col-sm-10">' . "\n";
		$partial .= '		<input type="text" class="form-control" name="' . $mm[1] . '[<?= (isNullObj($' . $mm[1] . ') ? -1 : $' . $mm[1] . '->getPk());?>]['.$mm[2].'_'.$oField.']" id="input' . $oField . '" placeholder="" value="<?= $'.$mm[1].'->get'.ucfirst($oField).'(); ?>" >' . "\n";
		$partial .= '	</div>' . "\n";
	$partial .= '</div>' . "\n";
}

file_put_contents(dirname($fn)."/tpl.".ucfirst($mm[1])."Partial.php", $partial);
?>