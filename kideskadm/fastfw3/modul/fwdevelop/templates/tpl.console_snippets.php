<div id="snippets" style="display:none;">


    <h2 class="headline">Datenbank</h2>

    <h3>Abfragen</h3>
    <pre>$onedata = $this->fw->DC->getByQuery("SELECT * FROM tabelle WHERE spalte='wert' ");</pre>
    <pre>$alldata = $this->fw->DC->getAllByQuery("SELECT * FROM tabelle WHERE spalte='wert' ");</pre>
    <h3>Insert / Update</h3>
    <pre>$this->fw->DC->insert("tabelle", $dataArray);</pre>
    <pre>$this->fw->DC->update("tabelle", $dataArray, $wert, "tabelle_pk");</pre>
    <h3>direkter Query</h3>
    <pre>$this->fw->DC->sendQuery("DELETE FROM tabelle WHERE a=1");</pre>


    <h2 class="headline">Template</h2>

    <h3>im Modul-View</h3>
    <pre>$tpl = $this->newTpl();</pre>
    <h3>sonst</h3>
    <pre>$tpl = new \classes\template();</pre>

    <h3>Werte setzen</h3>
    <pre>$tpl->setVariable("bezeichner", $wert);
$html = $tpl->get("tpl.name.php");</pre>

    <h3>im Template</h3>
    <pre>Ausgabe &lt;?= $bezeichner; ?&gt; oder &lt;?php echo $bezeichner; ?&gt;</pre>


    <h2 class="headline">Sessions</h2>

    <pre>setS("bezeichner", $wert);</pre>
    <pre>$wert = getS("bezeichner");</pre>
    <b>Mit Ablaufdatum:</b> (z.B: 60 Sekunden)<br>
    <pre>setS("bezeichner", $wert, 60);</pre>

    <h2 class="headline">Routen</h2>

    Im Pfad steht z.B. ?fw_goto=auswertung/uebersicht, dann wird das Modul <b>auswertung</b> und darin die Methode <b>view_uebersicht</b> aufgerufen.<br>
    Wird nun als Pfad ?fw_goto=auswertung/uebersicht/neueste aufgerufen, dann wird der Methode <b>view_uebersicht</b> ein Array
    mit einem Wert "neueste" übergeben.<br>
    <br>
    Aus einem Template oder einer Methode heraus kann direkt eine Route aufgerufen werden.
    <pre>$html = $this->fw->route("auswertung/details");</pre>

<!--
    <h2 class="headline">Simpletable</h2>

<pre>$this->simpletable = new \classes\simpletable();
$this->simpletable->setTable('TABELLENNAME');
$this->simpletable->setPrimaryKey('PRIMARY_KEY_FELD');
$this->simpletable->setDeletedDateField = 'DELETED_FELD';

$this->simpletable->addWhere("SPALTE='einschränkung'");

if( isset($_POST['createEntry']) && $_POST['createEntry']==1) {

}
if( isset($_POST['updateEntry']) && $_POST['updateEntry']!='')   {

}

$fields = array(
          array('field'=>'fe_loginname','caption'=>'Benutzername'),
          array('field'=>'fe_password','caption'=>'Kennwort','type'=>'password'),
          array('field'=>'fe_mailberatung','caption'=>'Mailberatung', 'type'=>'select', "valuestexts" => array( array('value'=>'1', 'text'=>'ja'), array('value'=>'0', 'text'=>'nein') ), 'hideempty'=>true),
          array('field'=>'fe_info','caption'=>'Beschreibung','type'=>'textarea'),
);

$this->simpletable->useFields('edit', $this->simpletable->removeFields($fields, array('fe_info'));
$this->simpletable->useFields('view', $fields);
$this->simpletable->useFields('list', $this->simpletable->leaveFields($fields,array('fe_loginname'));

$html = $this->simpletable->manage($QS);

if( (isset($_POST['createEntry']) && $_POST['createEntry']==1) || (isset($_POST['updateEntry']) && $_POST['updateEntry']!=''))   {

}</pre>
-->

</div>