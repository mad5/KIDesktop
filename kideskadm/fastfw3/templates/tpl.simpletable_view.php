<?php if($VARS->get('hideEditLinks')==false) { ?>
<table summary="tabelle"><tr>
<td>
	<a href="<?= getLink($VARS->get('defaultLink'));?>">&laquo; zur&uuml;ck</a>
</td>
</tr></table>
<?php } ?>

<?php if($VARS->get('errors')->count()>0) { ?>
<div class="error">
<?php for($i=0;$i<$VARS->get('errors')->count();$i++) { ?>
    &bull;&nbsp;<?= $VARS->get('errors/'.$i.'/text');?><br/>
<?php } ?>
</div>
<?php } ?>


<!-- <h2><?= $VARS->get('title');?></h2> -->
<form method="post" action="<?= getLink($VARS->get('defaultLink'));?>" enctype="multipart/form-data">
<?php if($VARS->get('data')->get($VARS->get('primaryKey'))=='') { ?><input type="hidden" name="createEntry" value="1" />
<?php } else { ?><input type="hidden" name="updateEntry" value="<?= $VARS->get('data')->get($VARS->get('primaryKey'));?>" /><?php } ?>
<table summary="tabelle" class="simpletableedit" cellspacing="5" cellpadding="0">
<?php for($i=0;$i<$VARS->get('fieldlist')->count();$i++) { ?>
	<?php if($VARS->get('fieldlist')->get($i)->get('type')!='hidden') { ?>
	<tr id="tableline_<?= $VARS->get('fieldlist')->get($i)->get('field');?>">
		<th <?= ($VARS->get('fieldlist')->get($i)->get('longcaption')===true ? 'colspan="2"' : '');?>>
		    <?= $VARS->get('fieldlist')->get($i)->get('caption');?>
		</th>
		<?= ($VARS->get('fieldlist')->get($i)->get('longcaption')===true ? '</tr><tr><td></td>' : '');?>
		<td>
			<?php if($VARS->get('fieldlist')->get($i)->get('type')=='checkbox') { ?>
				<?php for($j=0;$j<$VARS->get('fieldlist')->get($i)->get('values')->count();$j++) { ?>
					<input type=checkbox name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>][]" disabled value='<?= $VARS->get('fieldlist')->get($i)->get('values')->get($j);?>' <?= (stristr('|'.$VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field')).'|','|'.$VARS->get('fieldlist')->get($i)->get('values')->get($j).'|') ? 'checked' : ''); ?> /><?= $VARS->get('fieldlist')->get($i)->get('texts')->get($j);?><br>
				<?php } ?>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='radio') { ?>
				<?php for($j=0;$j<$VARS->get('fieldlist')->get($i)->get('values')->count();$j++) { ?>
					<input type=radio name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" disabled value='<?= $VARS->get('fieldlist')->get($i)->get('values')->get($j);?>' <?= (stristr('|'.$VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field')).'|','|'.$VARS->get('fieldlist')->get($i)->get('values')->get($j).'|') ? 'checked' : ''); ?> /><?= $VARS->get('fieldlist')->get($i)->get('texts')->get($j);?><br>
				<?php } ?>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='select') { ?>

					
				<select name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" disabled id="input_<?= $VARS->get('fieldlist')->get($i)->get('field');?>">
					<option value=''>&nbsp;</option>
					<?php for($j=0;$j<$VARS->get('fieldlist')->get($i)->get('values')->count();$j++) { ?>
						<option value='<?= $VARS->get('fieldlist')->get($i)->get('values')->get($j);?>' 
						<?= ($VARS->get('data')->is_set($VARS->get('fieldlist')->get($i)->get('field')) &&
							$VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'))==$VARS->get('fieldlist')->get($i)->get('values')->get($j) ? ' selected="selected" ' : ''); ?>
						><?= $VARS->get('fieldlist')->get($i)->get('texts')->get($j);?></option>
					<?php } ?>
				</select>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='html') { ?>
				<?= $this->tplParse($VARS->get('fieldlist')->get($i)->get('html')->get('edit'));?>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='multiselect') {  ?>
				<select name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>][]" disabled multiple="multiple" class="multiselect">
					<?php for($j=0;$j<$VARS->get('fieldlist')->get($i)->get('values')->count();$j++) { ?>
						<option value='<?= $VARS->get('fieldlist')->get($i)->get('values')->get($j);?>' <?= (stristr('|'.$VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field')).'|','|'.$VARS->get('fieldlist')->get($i)->get('values')->get($j).'|') ? ' selected="selected" ' : ''); ?> ><?= $VARS->get('fieldlist')->get($i)->get('texts')->get($j);?></option>
					<?php } ?>
				</select>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='textarea') { ?>
				<textarea name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" disabled id="input_<?= $VARS->get('fieldlist')->get($i)->get('field');?>" rows=5 cols=50 class="<?= $VARS->get('fieldlist')->get($i)->get('class'); ?>" ><?= htmlspecialchars($VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field')));?></textarea>
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='file') { ?>
				<?php if($VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'))!='') { ?>Datei: <?= $VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'));?><br><?php } ?>
				<input type="file" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" disabled />
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='image') { ?>
				<?php if($VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'))!='') { ?><img src="<?= getLink('image/thumb');?>&amp;img=uploads/<?= $VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'));?>&amp;width=80&amp;height=200" alt="Bild" /><br/><?php } ?>
				<input type="file" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" disabled />
			<?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='password') { ?>
				<input type="password" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" disabled value="" />
                        <?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='date') { ?>
                                <input type="text" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" disabled class="<?= $VARS->get('fieldlist')->get($i)->get('class'); ?>"  id="input_<?= $VARS->get('fieldlist')->get($i)->get('field');?>" value="<?= formatDate($VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field')));?>" />
                        <?php } else if($VARS->get('fieldlist')->get($i)->get('type')=='time') { ?>
                                <input type="text" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" disabled id="input_<?= $VARS->get('fieldlist')->get($i)->get('field');?>" value="<?= formatTime($VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field')));?>" />
			<?php } else { ?>
				<input type="<?= ($VARS->get('fieldlist')->get($i)->get('texttype')=='password' ? 'password' : 'text'); ?>" disabled id="input_<?= $VARS->get('fieldlist')->get($i)->get('field');?>" name="FORM[<?= $VARS->get('fieldlist')->get($i)->get('field');?>]" value="<?= $VARS->get('data')->get($VARS->get('fieldlist')->get($i)->get('field'));?>" />
			<?php } ?>
		</td>
		<td>
		    <?= infoicon($VARS->get('fieldlist')->get($i)->get('info'));?>
		</td>
	</tr>
	<?php } ?>
<?php } ?>

</table>

</form>


