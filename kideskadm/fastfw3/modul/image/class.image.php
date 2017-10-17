<?php

class fastfw_image extends \classes\fastfw_modul {

	public $image;

	public function __construct() {
		// {{{

		$this->fw = $GLOBALS["FastFW"];
		#$this->fw->fw_useClass('fe_user'); // , array('requirelogin' => true) // , array('checklogin' => true)
		// }}}
	}

	/**
	 * Index
	 */
	public function view_index($QS) {
		// {{{
		$tpl = $this->newTpl();
		$tpl->setVariable('CONTENT', date('H:i:s'));

		$this->fw->setContentBody('CONTENT', $tpl->get('tpl.Index.php'));
		// }}}
	}

	/**
	 * thumb
	 */
	public function view_thumb($QS) {
		// {{{
		$fn = projectPath . '/' . $_GET['img'];

		if (!file_exists($fn) || !is_file($fn)) {
			#vd($fn);
			echo rawurldecode("%89PNG%0D%0A%1A%0A%00%00%00%0DIHDR%00%00%00%0A%00%00%00%0A%08%06%00%00%00%8D2%CF%BD%00%00%00%01sRGB%00%AE%CE%1C%E9%00%00%00%06bKGD%00%FF%00%FF%00%FF%A0%BD%A7%93%00%00%00%09pHYs%00%00%0B%13%00%00%0B%13%01%00%9A%9C%18%00%00%00%07tIME%07%DC%08%1D%0D4%22%5D%B9%937%00%00%00%17IDAT%18%D3c%FC%FF%FF%3F%031%80%89%81H0%AA%90%3A%0A%01%D5%12%03%11%E6B%AE%BB%00%00%00%00IEND%AEB%60%82");
			exit;
		}

		if (!isset($_GET['width']) || $_GET['width'] == '') {
			$_GET['width'] = 80;
		}
		if (!isset($_GET['height']) || $_GET['height'] == '') {
			$_GET['height'] = 180;
		}

		$maxWidth = (int)$_GET["width"];
		$maxHeight = (int)$_GET["height"];

		if ($this->fw->REQUEST('format') == 'png') {
			$cfn = \classes\FileUtils::getCacheFolder().'/' . md5(serialize($_GET).filemtime($fn))."_".$maxWidth."_".$maxHeight . '.png';
		} else {
			$cfn = \classes\FileUtils::getCacheFolder().'/' . md5(serialize($_GET).filemtime($fn))."_".$maxWidth."_".$maxHeight . '.jpg';
		}

		if (file_Exists(projectPath."/".$cfn)) {
			if ($this->fw->REQUEST('format') == 'png') {
				header("content-type: image/png");
			} else {
				header("content-type: image/jpeg");
			}
			readfile(projectPath."/".$cfn);
			exit;
		}


		$E = "convert ".$fn." -resize ".$maxWidth."x".$maxHeight." ".projectPath."/".$cfn;
		exec($E);
		if(file_exists(projectPath."/".$cfn)) {
			readfile(projectPath."/".$cfn);
			exit;
		}
#exit;
		$imagecontent = file_get_contents($fn);
		$im0 = @imagecreatefromstring($imagecontent);

		$wh0 = getImageSize($fn);

		$wh1 = $this->scaleFit($wh0[0], $wh0[1], $_GET["width"], $_GET["height"]);
		$im1 = imageCreateTrueColor($wh1[0], $wh1[1]);
		imageCopyResampled($im1, $im0, 0, 0, 0, 0, $wh1[0], $wh1[1], $wh0[0], $wh0[1]);

		if ($this->fw->REQUEST('format') == 'png') {
			#$cfn = projectPath . '/cache/' . md5(serialize($_GET)) . '.png';
			#var_dump(is_writable(dirname($cfn)));
			#vd($cfn);exit;
			header("content-type: image/png");
			imagePNG($im1, projectPath.'/'.$cfn); // , $cfn
			readfile(projectPath.'/'.$cfn);
		} else {
			header("content-type: image/jpeg");
			#$cfn = projectPath . '/cache/' . md5(serialize($_GET)) . '.jpg';
			imageJpeg($im1, projectPath.'/'.$cfn, 90);
			readfile(projectPath.'/'.$cfn);
		}

		exit;
		// }}}
	}

	/**
	 * icon
	 */
	public function view_icon($QS) {
		// {{{
		$tpl = $this->newTpl();
		$tpl->setVariable('CONTENT', date('H:i:s'));

		$this->fw->setContentBody('CONTENT', $tpl->get('tpl.icon.php'));
		// }}}
	}

	/**
	 * full
	 */
	public function view_full($QS) {
		// {{{
		$tpl = $this->newTpl();
		$tpl->setVariable('CONTENT', date('H:i:s'));

		$this->fw->setContentBody('CONTENT', $tpl->get('tpl.full.php'));
		// }}}
	}

	/**
	 * square
	 */
	public function view_square($QS) {
		// {{{
		$tpl = $this->newTpl();
		$tpl->setVariable('CONTENT', date('H:i:s'));

		$this->fw->setContentBody('CONTENT', $tpl->get('tpl.square.php'));
		// }}}
	}

	private function scaleFit($origWidth, $origHeight, $newWidth, $newHeight) {
		// {{{
		if ($origWidth != $newWidth) {
			$origHeight = $origHeight * ($newWidth / $origWidth);
			$origWidth = $newWidth;
		}
		if ($origHeight > $newHeight) {
			$origWidth = $origWidth * ($newHeight / $origHeight);
			$origHeight = $newHeight;
		}

		return (array(floor($origWidth), floor($origHeight)));
		// }}}
	}

}

?>