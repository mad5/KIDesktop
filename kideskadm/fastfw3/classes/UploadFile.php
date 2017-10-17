<?php
namespace classes;

/**
 */
class UploadFile {

	protected $dim = array();
	protected $filename = "";
	public function __construct($filename) {
		$this->setFilename($filename);
	}

	/**
	 * @return mixed
	 */
	public function getFilename() {
		return (string)$this->filename;
	}
	public function getFiledate() {
		return filemtime(FILE_UPLOAD_FOLDER.'/'.$this->filename);
	}

	/**
	 * @param mixed $filename
	 */
	public function setFilename($filename) {
		$this->filename = $filename;
	}

	public function fileExists() {
		if($this->getFilename()=="") return false;
		if(!file_exists(FILE_UPLOAD_FOLDER.'/'.$this->getFilename())) return false;
		if(!is_file(FILE_UPLOAD_FOLDER.'/'.$this->getFilename())) return false;
		return true;
	}

	public function getCropped($crop) {

	}

	public function getWidthHeightAtt() {
		if($this->dim==array()) {
			$this->dim = getimagesize(FILE_UPLOAD_FOLDER.'/'.$this->getFilename());
		}
		return " width='".$this->dim[0]."' height='".$this->dim[1]."' ";
	}

	public function getImageTag($params = array()) {
		if($this->getFilename()=="") return "";
		$paramStr = array();
		foreach($params as $key => $param) {
			$paramStr[] = $key.'="'.$param.'""';
		}
		$html = "";
		if($this->fileExists()) {
			$html = '<img src="uploads/' . $this->getFilename() . '" ' . implode(" ", $paramStr) . ' >';
		}
		return $html;
	}

	public function getLink($caption) {
		return "<a href='uploads/".$this->getFilename()."'>".$caption."</a>";
	}

	public function getDownloadLink() {

		return \classes\FileUtils::getFileDownloadLink($this->getFilename());
		#return 'uploads/'.$this->getFilename();
	}
	
	public function getBase64() {
		
		$img = $this->getFilename();
		
		$prefix = "";
		if(getFileExt($img)=="png") {
		    $prefix = "data:image/png;base64,";
		} else if(getFileExt($img)=="jpg") {
		    $prefix = "data:image/jpeg;base64,";
		}
		if($prefix!="") {
			$base64 = $prefix.base64_encode(file_get_contents(FILE_UPLOAD_FOLDER.'/'.$this->getFilename()));
		} else {
			$base64 = "";
		}
		return $base64;
		
	}

	public function getSquare($size) {
		$cfn = \classes\FileUtils::getCacheFolder()."/".md5($this->getFilename().$this->getFiledate())."_".$size."_square.jpg";
		if(file_exists($cfn)) {
			return new \classes\UploadFile(str_nach($cfn,"/"));
		}
		
		$E = "convert -define jpeg:size=".$size."x".$size." ".FILE_UPLOAD_FOLDER.'/'.$this->getFilename()."  -thumbnail ".$size."x".$size."^ -gravity center -extent ".$size."x".$size."  ".$cfn;
		exec($E);
		if(file_exists($cfn)) {
			return new \classes\UploadFile(str_nach($cfn,"/"));
		}
		
		$wh = getImageSize(FILE_UPLOAD_FOLDER.'/'.$this->getFilename());
		$w = $wh[0];
		if($wh[1]<$w) $w = $wh[1];
		
		$im0 = imagecreatefromstring(file_get_contents(FILE_UPLOAD_FOLDER.'/'.$this->getFilename()));
		$im1 = imageCreateTrueColor($size,$size);
		//imageCopy($im1,$im0,0,0,$wh[0]/2-$w/2, $wh[1]/2-$w/2,$w,$w);
		imageCopyResampled($im1,$im0,0,0,$wh[0]/2-$w/2, $wh[1]/2-$w/2,$size,$size, $w,$w);
		imageJpeg($im1, projectPath . '/'.$cfn, 90);
		return new \classes\UploadFile(str_nach($cfn,"/"));
	}
	
	public function getScaledImageLink($maxWidth,$maxHeight,$min=false) {

		$cfn = \classes\FileUtils::getCacheFolder().'/'.md5($this->getFilename().$this->getFiledate())."_".$maxWidth."_".$maxHeight."_".($min ? 1 : 0).'.jpg';

		if(1==1 && file_exists(projectPath."/".$cfn)) {
			return new \classes\UploadFile(str_nach($cfn,"/"));
			#return $cfn;
		}

		if($min && $this->getFilename()!="") {
			$wh = getImageSize(FILE_UPLOAD_FOLDER.'/'.$this->getFilename());
			$wh0 = array($wh[0], $wh[1]);
			$wh1 = $this->scaleFit($wh0[0],$wh0[1], $maxWidth, $maxHeight);
			if($wh1[0]<$maxWidth) {
				$wh1 = $this->scaleFit($wh0[0],$wh0[1], $maxWidth*10, $maxHeight);
			} elseif($wh1[1]<$maxHeight) {
				$wh1 = $this->scaleFit($wh0[0],$wh0[1], $maxWidth, $maxHeight*10);
			}
			$maxWidth = $wh1[0];
			$maxHeight = $wh1[1];
		}
		
		if($this->getFilename()!="") {
			$E = "convert ".FILE_UPLOAD_FOLDER.'/'.$this->getFilename()." -resize ".$maxWidth."x".$maxHeight." ".projectPath."/".$cfn;
			exec($E);
			if(file_exists(projectPath."/".$cfn)) {
				return new \classes\UploadFile(str_nach($cfn,"/"));
				#return $cfn;
			}
		}

		if($this->getFilename()=="" || !file_Exists(FILE_UPLOAD_FOLDER.'/'.$this->getFilename())) {
			$im0 = imageCreateTrueColor(100,100);
		} else {
			$imagecontent = file_get_contents(FILE_UPLOAD_FOLDER . '/' . $this->getFilename());
			$im0 = @imagecreatefromstring($imagecontent);
		}
		$wh0 = array(imageSx($im0), imageSy($im0));
		$wh1 = $this->scaleFit($wh0[0],$wh0[1], $maxWidth, $maxHeight);
		$im1 = imageCreateTrueColor($wh1[0], $wh1[1]);
		imageCopyResampled($im1, $im0, 0,0,0,0,$wh1[0],$wh1[1],$wh0[0],$wh0[1]);

		imageJpeg($im1, projectPath . '/'.$cfn, 90);
		return new \classes\UploadFile(str_nach($cfn,"/"));
		#return $cfn;
	}

	function scaleFit($origWidth, $origHeight, $newWidth, $newHeight) {
        // {{{
        if ($origWidth != $newWidth) {
            $origHeight = $origHeight * ($newWidth / $origWidth);
            $origWidth = $newWidth;
        }
        if ($origHeight > $newHeight) {
            $origWidth = $origWidth * ($newHeight / $origHeight);
            $origHeight = $newHeight;
        }
        return(array(floor($origWidth), floor($origHeight)));
        // }}}
    }

	public function __toString() {
		if($this->getFilename()=="") return "";
		return $this->getFilename();
	}


}
?>