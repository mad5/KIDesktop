<?php
class fastfw_time extends fastfw_modul{
	
	function model_today($QS) {
		// {{{
		echo "<p>".date("d.m.Y H:i:s")."</p>";
		// }}}
	}
}
?>