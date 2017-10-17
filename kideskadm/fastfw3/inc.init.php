<?php
include dirname(__FILE__).'/autoload.php';

if(defined('DB_NAME')) {
    $UPDATE_SQL = array();
    if (file_exists(projectPath . '/inc.update.php')) {
        $update_fn = projectPath . '/cache/update.log';
        $doUpdate = FALSE;
        if (!file_exists($update_fn)) {
            $doUpdate = TRUE;
        } else {
            if (filemtime($update_fn) < filemtime(projectPath . '/inc.update.php')) {
                $doUpdate = TRUE;
            }
        }
        if ($doUpdate == TRUE) {
            touch($update_fn);
            include_once(projectPath . '/inc.update.php');
        }
    }

    $STEP = -1;
    if (file_exists($update_fn . '.count')) {
        $STEP = getFile($update_fn . '.count');
    }

    for ($i = 0; $i < count($UPDATE_SQL); $i++) {
        // {{{
        #if(defined("unittesting")) vd($UPDATE_SQL[$i]);
        if (!is_array($UPDATE_SQL[$i]['query'])) {
            $UPDATE_SQL[$i]['query'] = array($UPDATE_SQL[$i]['query']);
        }

        if ($i <= $STEP) {
            continue;
        }

        if ($UPDATE_SQL[$i]['type'] == 'newfield') {
            // {{{
            if (!$FW->DC->fieldExists($UPDATE_SQL[$i]['table'], $UPDATE_SQL[$i]['field'])) {
                for ($j = 0; $j < count($UPDATE_SQL[$i]['query']); $j++) {
                    $FW->DC->sendQuery($UPDATE_SQL[$i]['query'][$j]);
                }
                addFile($update_fn, date("Y-m-d H:i:s") . "\t" . "UPDATE-SQL-STEP " . $i);
            }
            setFile($update_fn . '.count', $i);
            // }}}
        }

        if ($UPDATE_SQL[$i]['type'] == 'newtable') {
            // {{{
            if (!$FW->DC->tableExists($UPDATE_SQL[$i]['table'])) {
                for ($j = 0; $j < count($UPDATE_SQL[$i]['query']); $j++) {
                    $FW->DC->sendQuery($UPDATE_SQL[$i]['query'][$j]);
                }
                addFile($update_fn, date("Y-m-d H:i:s") . "\t" . "UPDATE-SQL-STEP " . $i);
            }
            setFile($update_fn . '.count', $i);
            // }}}
        }

        if ($UPDATE_SQL[$i]['type'] == 'newentry') {
            // {{{
            if (!isset($UPDATE_SQL[$i]['countquery']) || $UPDATE_SQL[$i]['countquery'] == "") {
                die("missing countquery in update-step " . $i);
            }
            if ($FW->DC->countByQuery($UPDATE_SQL[$i]['countquery']) == 0) {
                for ($j = 0; $j < count($UPDATE_SQL[$i]['query']); $j++) {
                    $FW->DC->sendQuery($UPDATE_SQL[$i]['query'][$j]);
                }
            }
            setFile($update_fn . '.count', $i);
            // }}}
        }
        if ($UPDATE_SQL[$i]['type'] == 'alter') {
            // {{{
            for ($j = 0; $j < count($UPDATE_SQL[$i]['query']); $j++) {
                $FW->DC->sendQuery($UPDATE_SQL[$i]['query'][$j]);
            }
            setFile($update_fn . '.count', $i);
            // }}}
        }

        if (isset($UPDATE_SQL[$i]['callAfter']) && $UPDATE_SQL[$i]['callAfter'] != "") {
            $UPDATE_SQL[$i]['callAfter']();
        }
        // }}}
    }
}



?>
