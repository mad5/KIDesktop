<?php
function vd($X) {
	// {{{
	echo "<div style='background-color: white;color:black;'><pre>";
	print_r($X);
	echo "</pre></div>";
	// }}}
}

function vdr($X) {
	// {{{
	$html .= "<pre>";
	$html .= print_r($X,1);
	$html .= "</pre>";
	return($html);
	// }}}
}

function getTrace() {
	try{ throw new Exception($a_message); } catch(Exception $e) {return $e->getTraceAsString(); }
}
?>
