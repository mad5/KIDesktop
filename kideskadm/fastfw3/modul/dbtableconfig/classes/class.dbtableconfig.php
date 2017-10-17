<?php

class dbtableconfig {

    public $fw;
    public $id;
    public $params = array();

    public function __construct($fw, $params = array()) {
        // {{{
        $this->params = $params;
        $this->fw = $fw;

        //
        // }}}
    }

    public function showtables() {
        $q = $this->fw->DC->getAllByQuery("SHOW tables");
        
            $index= (array_keys($q[0]));
            $out = array();
        foreach ($q as $key => $row) {
            array_push($out, $row[$index[0]]);
        }
     
        return $out;
        
      
    }
    
    public function describetable($tablename) {
        $q = $this->fw->DC->getAllByQuery('DESCRIBE '.$tablename.'');
        return $q;
    }
    
    public function getPrimarykey($filename){
          $primkey= $this->fw->DC->getByQuery('SELECT column_name FROM information_schema.key_column_usage WHERE TABLE_NAME = "'.$filename.'"');
       return $primkey["column_name"];
    }
    
    
    public function mergeDescribeandJSON($json,$tablename){
             $q = $this->fw->DC->getAllByQuery('DESCRIBE '.$tablename.'');   
             $i=0;
            
            $data=array();
            
             $json=(array)json_decode($json[0]);
        
                foreach ($q as $key => $row) {

                $row["Check"] = $json["Check"][$i];
                $row["Titel"] = $json["Titel"][$i];
                $row["Class"] = $json["Class"][$i];
                $row["List"] = $json["List"][$i];
                $row["View"] = $json["View"][$i];
                $row["Edit"] = $json["Edit"][$i];
                $row["Selector"] = $json["Selector"][$i];
                $row["In1"] = $json["In1"][$i];
                $row["In2"] = $json["In2"][$i];
                 $row["Sort"] = $json["Sort"][$i];
         
                $i+=1;
                array_push($data, $row);
               }    
               return $data;
      
    }

    public function loadJSON($tablename) {
        $projectpath = "";
        $filename = $projectpath . './cache/' . $tablename . '.json';

        if (file_exists($filename)) {
         
            $array = file($filename, FILE_IGNORE_NEW_LINES);
     
          $data=  $this->mergeDescribeandJSON($array,$tablename);
       
            return $data;
    
        }else{return "false";}
    }

    public function saveJSON($tablename,$array) {
        $projectpath = "";
        $filename = $projectpath . './cache/' . $tablename . '.json';


        if (file_exists($filename)) {
            unlink($filename);
        }

        $filehandler = fopen($filename, 'w') or die('Cannot open file:  '.$filename);;
        fwrite($filehandler, $array);
        fclose($filehandler);
        return "Gespeichert";
    }

}

?>