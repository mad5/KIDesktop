<?php
class FastFWAutoLoader {
    public static function loadClass($classname) {
#function __autoload($classname) {

        if (stristr($classname, "\\")) {

            $x = explode("\\", $classname);
            if (file_exists(projectPath . '/modul/' . $x[0] . "/classes") && file_Exists(projectPath . '/modul/' . $x[0] . "/classes/class." . $x[1] . ".php")) {
				include_once projectPath . '/modul/' . $x[0] . "/classes/class." . $x[1] . ".php";
            } else if (file_Exists(projectPath . '/' . str_replace("\\", "/", $classname) . ".php")) {
				include_once projectPath . '/' . str_replace("\\", "/", $classname) . ".php";
            } else if (file_Exists(libPath . '/modul/' . $x[0] . "/classes") && file_Exists(libPath . '/modul/' . $x[0] . "/classes/class." . $x[1] . ".php")) {
				include_once libPath . '/modul/' . $x[0] . "/classes/class." . $x[1] . ".php";
            } else {
                //vd($x);
                $fn = '/modul/';
                //$x[count($x)-1] = str_replace($x[count($x)-2], "", $x[count($x)-1]).".php";
                $fn .= implode("/", $x);
                $fn .= ".php";
				$fn2 = implode("/", $x).".php";
#var_dump(projectPath . $fn);
#var_dump(libPath . "/".$fn2);
				#vd(libPath . "/modul/".$fn2);
				#vd($fn);
				#vd(libPath . "/".$fn2);
				#vd(libPath . '/'.$fn2);
				#var_dump(file_exists(libPath .'/'. $fn2));
                if (file_exists(projectPath . $fn)) {
                	include_once projectPath . $fn;
				} else if (file_exists(libPath . "/".$fn2)) {
					include_once libPath . "/".$fn2;
                } else if (file_exists(libPath . $fn)) {
                	include_once libPath . $fn;
                } else {
                    // Klasse existiert nicht.
#var_dump($x);
					if($x[0]=="classes" && $x[1]=="template") {
						#vd(getTrace());
					}
					//var_dump(libPath . '/classes/' . $x[1] . ".php");echo "<br>";
//var_dump(file_Exists(libPath . '/classes/' . $x[1] . ".php"));
					if($x[0]=="classes") {
						if (file_Exists(projectPath . '/classes/class.' . $x[1] . ".php")) {
							include_once projectPath . '/classes/class.' . $x[1] . ".php";
						} else if (file_Exists(libPath . '/classes/' . $x[1] . ".php")) {
							include_once libPath . '/classes/' . $x[1] . ".php";
						} else if (file_Exists(libPath . '/classes/class.' . $x[1] . ".php")) {
							include_once libPath . '/classes/class.' . $x[1] . ".php";
						} else {
							#vd($x);vd($classname);
						}
					} else {
						#vd($x);vd($classname);
					}
                }
            }
        } else {
            if (file_Exists(projectPath . '/classes/class.' . $classname . ".php")) {
				include_once projectPath . '/classes/class.' . $classname . ".php";
            } else if (file_Exists(libPath . '/classes/class.' . $classname . ".php")) {
				include_once libPath . '/classes/class.' . $classname . ".php";
            } else {
				#vd($classname);
			}
        }
    }
}
spl_autoload_register(array('FastFWAutoLoader', 'loadClass'));
?>