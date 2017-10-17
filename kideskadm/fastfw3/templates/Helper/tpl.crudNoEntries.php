<tr>
	<td colspan="20">
		<i><?= transFull("Keine Einträge gefunden");?></i><br>
		<?php if(isset($quickSearch) && $quickSearch!='') { ?>
			<i><?= transFull("crud|Sie haben einen Filter (<b>%0</b>) gesetzt.", array(htmlspecialchars($quickSearch)));?></i><br>
			<i><?= transFull("crud|Eventuell müssen Sie diesen Anpassen um ein Ergebnis angezeigt zu bekommen.");?></i>
		<?php } ?>

	</td>
</tr>