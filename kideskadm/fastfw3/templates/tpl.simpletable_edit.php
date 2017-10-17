<table summary="tabelle"><tr>
<td>
	<a href="<?= getLink($VARS->get('defaultLink'));?>">&laquo; zur&uuml;ck</a>
</td>
</tr></table>

<hr size=1 />

<form method="post" action="<?= getLink($VARS->get('defaultLink'));?>" enctype="multipart/form-data"  role="form" class="form-horizontal">

<?php if($VARS->get('data')->get($VARS->get('primaryKey'))=='') { ?><input type="hidden" name="createEntry" value="1" />
<?php } else { ?><input type="hidden" name="updateEntry" value="<?= $VARS->get('data')->get($VARS->get('primaryKey'));?>" /><?php } ?>

<?php for($i=0;$i<$VARS->get('fieldlist')->count();$i++) { ?>
	<?php if($VARS->get('fieldlist')->get($i)->get('type')!='hidden') { ?>
		
		<div class="form-group">
			<label class="control-label col-sm-2"><?= $VARS->get('fieldlist')->get($i)->get('caption');?></label>
			
			<div class="col-sm-10">
		
			<?php if($VARS->get('fieldlist')->get($i)->get('type')=='checkbox') { ?>
				<?php for($j=0;$j<$VARS->get('fieldlist')->get($i)->get('values')->count();$j++) { ?>
					<div class="checkbox">
					<label>
					<input type=checkbox class="form-control" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>][]" value='<?= $VARS->get('fieldlist')->get($i)->get('values')->get($j);?>' <?= (stristr('|'.$VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field')).'|','|'.$VARS->get('fieldlist')->get($i)->get('values')->get($j).'|') ? 'checked' : ''); ?> /><?= $VARS->get('fieldlist')->get($i)->get('texts')->get($j);?><br>
					</label>
					</div>
				<?php } ?>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='radio') { ?>
				<?php for($j=0;$j<$VARS->get('fieldlist')->get($i)->get('values')->count();$j++) { ?>
					<input type=radio class="form-control" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" value='<?= $VARS->get('fieldlist')->get($i)->get('values')->get($j);?>' <?= (stristr('|'.$VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field')).'|','|'.$VARS->get('fieldlist')->get($i)->get('values')->get($j).'|') ? 'checked' : ''); ?> /><?= $VARS->get('fieldlist')->get($i)->get('texts')->get($j);?><br>
				<?php } ?>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='select') { ?>
				<select class="form-control" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]">
					<?php if(!$VARS->get('fieldlist')->get($i)->is_set('hideempty') || $VARS->get('fieldlist')->get($i)->getInt('hideempty')!=1) { ?><option value=''>&nbsp;</option><?php } ?>
					<?php for($j=0;$j<$VARS->get('fieldlist')->get($i)->get('values')->count();$j++) { ?>
						<option value='<?= $VARS->get('fieldlist')->get($i)->get('values')->get($j);?>' <?= ($VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'))==$VARS->get('fieldlist')->get($i)->get('values')->get($j) ? ' selected="selected" ' : ''); ?> ><?= $VARS->get('fieldlist')->get($i)->get('texts')->get($j);?></option>
					<?php } ?>
				</select>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='html') { ?>
				<?= $this->tplParse($VARS->get('fieldlist')->get($i)->get('html')->get('edit'));?>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='multiselect') {  ?>
				<select name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>][]" multiple="multiple" class="form-control multiselect">
					<?php for($j=0;$j<$VARS->get('fieldlist')->get($i)->get('values')->count();$j++) { ?>
						<option value='<?= $VARS->get('fieldlist')->get($i)->get('values')->get($j);?>' <?= (stristr('|'.$VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field')).'|','|'.$VARS->get('fieldlist')->get($i)->get('values')->get($j).'|') ? ' selected="selected" ' : ''); ?> ><?= $VARS->get('fieldlist')->get($i)->get('texts')->get($j);?></option>
					<?php } ?>
				</select>
                        <?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='date') { ?>
                                <input type="text" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" class="form-control <?= $VARS->get('fieldlist')->get($i)->get('class'); ?>"  id="input_<?= $VARS->get('fieldlist')->get($i)->get('field');?>" value="<?= formatDate($VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field')));?>" />
                                
                                <?php if($VARS->get('fieldlist')->get($i)->is_set('nextdays') && $VARS->get('fieldlist')->get($i)->get('nextdays')==1 && $VARS->get('data')->get($VARS->get('primaryKey'))=="") { ?><br/>
                                <nobr>
                                    <a href='#' onclick="$('#input_<?= $VARS->get('fieldlist')->get($i)->get('field');?>').val('<?= date("d.m.Y")?>');return false;">[heute]</a>
                                    <a href='#' onclick="$('#input_<?= $VARS->get('fieldlist')->get($i)->get('field');?>').val('<?= date("d.m.Y", time()+60*60*24)?>');return false;">[morgen]</a>
                                    , nächsten 
                                    <?php $wd = 1-date("w");/*echo date("w")."<br>";echo $wd."<br>"; echo date("d.m.Y", time()+60*60*24*($wd+7));*/?>
                                    <?php for($iw=0;$iw<7;$iw++) { ?>
                                        <a href='#' onclick="$('#input_<?= $VARS->get('fieldlist')->get($i)->get('field');?>').val('<?= date("d.m.Y", time()+60*60*24*($wd+7+$iw))?>');return false;">[<?= formatWochentag($iw+1); ?>]</a>
                                    <?php } ?>
                                </nobr>
                                <?php } ?>
                        		
				
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='textarea') { ?>
				<textarea class="form-control" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" rows=5 cols=50 ><?= $VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'));?></textarea>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='file') { ?>
				<?php if($VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'))!='') { ?>Datei: <?= $VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'));?><br><?php } ?>
				<input class="form-control" type="file" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" />
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='image') { ?>
				<?php if($VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'))!='') { ?>Datei: <?= $VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'));?><br><?php } ?>
				<input class="form-control" type="file" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" />
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='password') { ?>
				<input class="form-control" type="password" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" value="" />
			<?php } else { ?>
				<input class="form-control" type="<?= ($VARS->get('fieldlist')->get($i)->get('texttype')=='password' ? 'password' : 'text'); ?>" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" value="<?= $VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'));?>" />
			<?php } ?>
			</div>
			
		</div>
	<?php } ?>
<?php } ?>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default"><?= $VARS->get('saveButtonCaption');?></button>
    </div>
  </div>



</form>


