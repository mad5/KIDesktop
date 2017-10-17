<?php
class fastfw_index extends fastfw_modul{
	
	function model_today($QS) {
		// {{{
		echo "<p>".date("d.m.Y H:i:s")."</p>";
		// }}}
	}
}
?>