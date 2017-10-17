<?php
namespace classes;

class collectFiles {
    private $files = array();
    private $name = "";
    private $fn = "";
    function __construct($name) {
        $this->files = array();
        $this->name = $name;
        $this->fn = \classes\FileUtils::getCacheFolder().'/'.$this->name;
    }
    function add($fn) {
        $this->files[] = $fn;
    }
    function getURL($replaceURL=false) {
        $rebuild = false;
        if(file_Exists($this->fn)) {
            $filetime = filemtime($this->fn);
            for($i=0;$i<count($this->files);$i++) {
                $cfn = projectPath.'/'.$this->files[$i];
                if(filemtime($cfn)>$filetime) {
                    $rebuild = true;
                    break;
                }
            }
        } else {
            $filetime = 0;
            $rebuild = true;
        }

        if($rebuild) {
            $filedata = "";
            for($i=0;$i<count($this->files);$i++) {
                $cfn = projectPath.'/'.$this->files[$i];
                $FF = file_get_contents($cfn);
                if($replaceURL && substr($cfn,strrpos($cfn,"."))==".css") {
                    $FF = str_replace("url(", "url(../../".dirname($this->files[$i]).'/', $FF);
                    $FF = str_replace("url(../../".dirname($this->files[$i])."/'", "url('../../".dirname($this->files[$i]).'/', $FF);
                    $FF = str_replace('url(../../'.dirname($this->files[$i]).'/"', 'url("../../'.dirname($this->files[$i]).'/', $FF);

                    $FF = str_replace('../../'.dirname($this->files[$i]).'/data:image/', 'data:image/', $FF);
                }
                $filedata .= $FF;
                $filedata .= "\n\n";
            }
            file_put_contents($this->fn, $filedata);
            $filetime = time();
        }
        return \classes\FileUtils::getCacheFolder()."/".$this->name."?dat=".$filetime;
    }
}
