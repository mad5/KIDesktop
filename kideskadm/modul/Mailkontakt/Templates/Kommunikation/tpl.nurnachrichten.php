<?php
foreach($nachrichten as $nachricht) {
	
	?>
	<tr class='<?= ($nachricht->getSender()=="rechner" && $nachricht->getGelesen()==_DATE0 ? 'ungelesen' : '');?>'>
		<td valign=top width=50>
				<?php if($nachricht->getSender()=="rechner") { ?>
					<img src='uploads/<?= $nachricht->getRechner()->getBild();?>' width=50 style="border-radius: 50%;">
				<?php } else { ?>
					<img src='uploads/<?= $nachricht->getMailkontakt()->getBild();?>' width=50 style="border-radius: 50%;">
				<?php } ?>
		</td>
		<td valign=top style="border-bottom: dotted 1px gray;padding: 5px;" nowrap width=100>
			<b>
				<?php if($nachricht->getSender()=="rechner") { ?>
					<?= $nachricht->getRechner()->getKind();?>
				<?php } else { ?>
					<?= $nachricht->getMailkontakt()->getName();?>
				<?php } ?>
				:</b>
				
		</td>
		<td valign=top style="border-bottom: dotted 1px gray;padding: 5px;"><?= nl2br($nachricht->getNachricht()); ?></td>
		
		<td valign=top align=right style="border-bottom: dotted 1px gray;padding: 5px;">
			<?= formatDateHuman($nachricht->getCreatedate());?>
			<?php if($nachricht->getGelesen()!=_DATE0) echo "<i class='glyphicon glyphicon-ok'></i>"; ?>
		</td>
		</tr>
	
<?php } ?>
