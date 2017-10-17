<?php

class fastfw_dbtableconfig extends fastfw_modul {

    public $dbtableconfig;

    public function __construct() {
// {{{
        $this->fw = $GLOBALS["FastFW"];
        if (!$this->fw->getDevelop()) die("development-mode not active!");
        $this->fw->fw_useClass('fe_user', array('checklogin' => true)); // , array('requirelogin' => true) // , array('checklogin' => true)
// }}}
    }

    public function generate_output($QS) {
        
    }

    public function view_select_table($QS) {
// {{{
        $tpl = $this->newTpl();
        $tables = $this->dbtableconfig->showtables();
        $tpl->setVariable('tables', $tables);

        $this->fw->setContentBody('CONTENT', $tpl->get('tpl.select_table.php'));
// }}}
    }

    public function view_edit_table($QS) {
// {{{
        $tpl = $this->newTpl();

        if (isset($_POST["tables"])) {
            $table = $_POST["tables"];
        }

        $data = $this->dbtableconfig->loadJSON($table);
        $structure = $this->dbtableconfig->describetable($table);
        $tpl->setVariable('data', $data);
        $tpl->setVariable('filename', $table);
        $tpl->setVariable('structure', $structure);
        $this->fw->setContentBody('CONTENT', $tpl->get('tpl.edit_table.php'));
// }}}
    }

    public function view_save_table($QS) {

        $data = $_POST;
        $filename = $data['file'];
        array_shift($data);

        $json = json_encode($data);
        $tpl = $this->newTpl();
        $this->dbtableconfig->saveJSON($filename, $json);

        $data = $this->dbtableconfig->loadJSON($filename);

        $out = '$this->simpletable = $this->fw->fw_useSingleClass("simpletable");';
        $out.="\n";


        $out.='$this->simpletable->setTable("' . $filename . '");';
        $out.="\n";

        $out.="\n";

        $primkey = $this->dbtableconfig->getPrimarykey($filename);

        $out.='$this->simpletable->setPrimaryKey("' . $primkey . '");';
        $out.="\n";


        $out.=' $this->simpletable->setTitle("' . $filename . '");';
        $out.="\n";


        for ($i = 0; $i < count($data); $i++) {


            if (strlen($data[$i]["Sort"]) > 1) {

                $sort = $data[$i]["Sort"];
            }
        }

        $out.='$this->simpletable->setOrderBy("' . $sort . '");';
        $out.="\n";


        $out.='$this->simpletable->CONFIG["viewPerPage"] = 50;';
        $out.="\n";


        $array = array();
        $fields = array();
        $listfields = array();
        $editfields = array();
        $viewfields = array();
        $out.="\n";
        $out.='$fields=array(';

        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]["Check"] == "active") {



                if ($data[$i]["Selector"] == "SelectBox" || $data[$i]["Selector"] == "CheckBox") {
                    $out.='array(';
                    $vtemp = explode(',', $data[$i]["In1"]);
                    $ttemp = explode(',', $data[$i]["In2"]);
                    $values = array();
                    $texts = array();

                    for ($o = 0; $o < count($vtemp); $o++) {

                        array_push($values, '"' . $vtemp[$o] . '"');
                        array_push($texts, '"' . $ttemp[$o] . '"');
                    }

                    if ($data[$i]["Selector"] == "SelectBox") {
                        $type = "select";
                    }
                    if ($data[$i]["Selector"] == "CheckBox") {
                        $type = "checkbox";
                    }

                    $out.='"field"=>"' . $data[$i]["Field"] . '","caption"=>"' . $data[$i]["Field"] . '","type"=>"' . $type . '","values"=>array(' . implode(",", $values) . '),"texts"=>array(' . implode(",", $texts) . '),"hideempty"=>"true","class"=>"' . $data[$i]["Class"] . '"';

                    $out.='),';
                    $out.="\n";
                    $out.="\n";
                } else {

                    $type = "text";
                    if ($data[$i]["Selector"] == "Password") {
                        $type = "password";
                    }
                    if ($data[$i]["Selector"] == "Date") {
                        $type = "date";
                    }
                    if ($data[$i]["Selector"] == "Time") {
                        $type = "time";
                    }
                    if ($data[$i]["Selector"] == "File") {
                        $type = "file";
                    }
                    if ($data[$i]["Selector"] == "Image") {
                        $type = "image";
                    }
                    if ($data[$i]["Selector"] == "HTML") {
                        $type = "html";
                    }
                    if ($data[$i]["Selector"] == "Callback") {
                        $type = "callback";
                    }
                    if ($data[$i]["Selector"] == "Hidden") {
                        $type = "hidden";
                    }

                    $out.='array(';
                    $out.='"field"=>"' . $data[$i]["Field"] . '","caption"=>"' . $data[$i]["Field"] . '","type"=>"' . $type . '","class"=>"' . $data[$i]["Class"] . '"';
                    $out.='),';
                }


                array_push($fields, $array);
                if ($data[$i]["List"] == "active") {
                    array_push($listfields, '"' . $data[$i]["Field"] . '"');
                }
                if ($data[$i]["Edit"] == "active") {
                    array_push($editfields, '"' . $data[$i]["Field"] . '"');
                }
                if ($data[$i]["View"] == "active") {
                    array_push($viewfields, '"' . $data[$i]["Field"] . '"');
                }
            }
        }
        $out.=');';
        $out.="\n";

        $out.=' $this->simpletable->useFields("edit", $this->simpletable->leaveFields($fields, array(' . implode(",", $editfields) . ')));';
        $out.=' $this->simpletable->useFields("view", $this->simpletable->leaveFields($fields, array(' . implode(",", $viewfields) . ')));';
        $out.='  $this->simpletable->useFields("list", $this->simpletable->leaveFields($fields, array(' . implode(",", $listfields) . ')));';
        $out.=' $html = $this->simpletable->manage($QS);';
        $out.=' $this->fw->setContentBody("CONTENT", $html);';

        // $tpl->setVariable('table', $html);
        $tpl->setVariable('simpletable', $out);
        $this->fw->setContentBody('CONTENT', $tpl->get('tpl.saved_table.php'));
    }

    public function view_test($QS) {

        $this->simpletable = $this->fw->fw_useSingleClass("simpletable");
        $this->simpletable->setTable("aaasimpletest");

        $this->simpletable->setPrimaryKey("a_index");
        $this->simpletable->setTitle("aaasimpletest");
        $this->simpletable->setOrderBy("");
        $this->simpletable->CONFIG["viewPerPage"] = 50;

        $fields = array(array("field" => "INT_", "caption" => "INT_", "type" => "hidden", "class" => "890u"), array("field" => "VARCHAR_", "caption" => "VARCHAR_", "type" => "text", "class" => "u89"), array("field" => "TEXT_", "caption" => "TEXT_", "type" => "text", "class" => "89u"), array("field" => "DATE_", "caption" => "DATE_", "type" => "text", "class" => "89u"), array("field" => "a_index", "caption" => "a_index", "type" => "text", "class" => "89"),);
        $this->simpletable->useFields("edit", $this->simpletable->leaveFields($fields, array("INT_", "VARCHAR_", "TEXT_", "DATE_", "a_index")));
        $this->simpletable->useFields("view", $this->simpletable->leaveFields($fields, array("INT_", "VARCHAR_", "TEXT_", "DATE_", "a_index")));
        $this->simpletable->useFields("list", $this->simpletable->leaveFields($fields, array("INT_", "VARCHAR_", "TEXT_", "DATE_", "a_index")));
        $html = $this->simpletable->manage($QS);
        $this->fw->setContentBody("CONTENT", $html);
    }

}

?>