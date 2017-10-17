<?php
namespace classes;

/**
 */
class FlashMessage {

	/**
	 * @param string $message
	 * @param string $type (success, danger, info)
	 * @return void
	 */
	public static function add($message, $type="info") {
		$msg = getS('flashMessages');
		if (!is_array($msg)) {
			$msg = array();
		}
		$msg[] = array('message' => (string)$message, 'type' => (string)$type);
		setS('flashMessages', $msg);
	}

	/**
	 * @return string
	 */
	public static function renderHtml() {
		$msg = getS('flashMessages');
		if ($msg == '' || !is_array($msg)) {
			return '';
		}

		$html = '';
		for ($i = 0; $i < count($msg); $i++) {
			if (!isset($msg[$i]['type'])) {
				$msg[$i]['type'] = 'info';
			}
			elseif ($msg[$i]['type'] == 'error') {
				$msg[$i]['type'] = 'danger';
			}
			$html .= '<div class="alert alert-'.$msg[$i]['type'].' alert-dismissable">';
			$html .= '<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>';
			$html .= $msg[$i]['message'];
			$html .= '</div>';
		}
		setS('flashMessages', array());

		return $html;
	}
}

?>