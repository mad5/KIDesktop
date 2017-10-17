<div style="display:none;" id="newmodulform">
	<form role="form" action="<?= getLink('*/newmodul');?>" method="post">
		<div class="form-group">
			<label for="modulname">Modulname</label>
			<input type="text" value="" id="modulname" name="modulname" class="form-control" placeholder="Modulname, z.B. Statistik">
		</div>
		<button type="submit" class="btn btn-success btn-xs">Modul erzeugen</button>
	</form>
</div>

<?php
$M = glob(projectPath.'/modul/*', GLOB_ONLYDIR);
if($M=="") $M = array();
echo '<ul class="list-group">';
for($i=0;$i<count($M);$i++) { ?>
    <li class="list-group-item active">

        Modul: <b><?= basename($M[$i]); ?></b>
    </li>
    <li class="list-group-item">

        <a href="#" onclick="$(this).closest('ul').find('.newctrlform<?= $i;?>').slideToggle();return false;" title="Neuen Controller anlegen"><i style='float:right;' class="glyphicon glyphicon-plus-sign"></i></a>
        Controller:<br>
        <div class="newctrlform<?= $i;?>" style="display:none;">

            <form role="form" action="<?= getLink('*/newctrl');?>" method="post">
                <input type="hidden" name="modul" value="<?= basename($M[$i]); ?>">
                <div class="form-group">
                    <label for="ctrlname">Neuer Controllername</label>
                    <input type="text" value="" id="ctrlname" name="ctrlname" class="form-control" placeholder="Controllername">
                    <!--<select class="form-control" id="newControllerType" name="newControllerType">
                        <option value=""></option>
                        <option value="crud">CRUD-Controller</option>
                    </select>
                    -->
                </div>
                <button type="submit" class="btn btn-success btn-xs">Controller erzeugen</button>
            </form>

        </div>

        <?php
        $Ctrl = glob($M[$i].'/*.php');
        if($Ctrl=="") $Ctrl = array();
        for($j=0;$j<count($Ctrl);$j++) {

        ?>


            <b><?= basename($Ctrl[$j]); ?></b> <!-- <i>(Action-Methoden)</i> -->
            <a href="#" onclick="$(this).closest('li').find('.newmethodform<?= $j;?>').slideToggle();return false;" title="Neue Action-Methode anlegen"><i style='float:right;' class="glyphicon glyphicon-plus-sign"></i></a>
            <?php
            $mod = file_get_contents($Ctrl[$j]);

            $anz = preg_match_all("/function (.*?)Action\(/", $mod, $views);
            $Action=true;
            if($anz==0) {
                $Action=false;
                $anz = preg_match_all("/view_(.*?)\(/", $mod, $views);
            }
            if($anz>0) {
                ?>
                <ul>
                    <?php for($k=0;$k<$anz;$k++) { ?>
                        <li><?= (!$Action ? 'view_' : '').$views[1][$k];?><?= ($Action ? 'Action' : '');?>();</li>
                    <?php } ?>
                </ul>
            <?php } ?>

            <div style="display:none;" class="newmethodform<?= $j;?>">
                <form role="form" action="<?= getLink('*/newmethod');?>" method="post">
                    <input type="hidden" name="modul" value="<?= basename($M[$i]); ?>">
                    <input type="hidden" name="ctrl" value="<?= basename($Ctrl[$j]); ?>">
                    <div class="form-group">
                        <label for="methodname">Methodenname ohne <i>Action</i></label>
                        <input type="text" value="" id="methodname" name="methodname" class="form-control" placeholder="Methodenname z.B. details">
                    </div>
                    <button type="submit" class="btn btn-success btn-xs">Action-Methode erzeugen</button>
                </form>
            </div>
        <?php } ?>

        <hr>
        <a href="#" onclick="$(this).closest('ul').find('.newmodelform<?= $i;?>').slideToggle();return false;" title="Neues Model anlegen"><i style='float:right;' class="glyphicon glyphicon-plus-sign"></i></a>
        Model:<br>
        <div class="newmodelform<?= $i;?>" style="display:none;">

            <form role="form" action="<?= getLink('*/newmodel');?>" method="post">
                <input type="hidden" name="modul" value="<?= basename($M[$i]); ?>">
                <div class="form-group">
                    <label for="modelname">Neuer Modelname</label>
                    <input type="text" value="adressen" id="modelname" name="modelname" class="form-control" placeholder="Modelname">
                </div>
                <div class="form-group">
                    <label for="modelshort">Kürzel</label>
                    <input type="text" value="adr" id="modelshort" name="modelshort" class="form-control" placeholder="Kürzel für die DB">
                </div>


                <div class="form-group">
                    <label for="modelfelder">Felder</label>
                    <textarea id="modelfelder" name="modelfelder" class="form-control" placeholder="" rows="10">vorname,vc
nachname,vc
email,vc
beschreibung,t,
strasse,vc
hausnummer,i
geburtsdatum,d

                    </textarea>
                        <div style="font-size: 0.8em;">
                            <div style="float:right;"><a href="https://www.databay.de/easypad/index.php?s=9b5281a529ed326c99cfd640be592739#Model-anlegen" target="_blank">Info</a></div>
                            [name],[type]{t,int,vc,ti,d,dt,f}<br>
                            [feld],<b>rel</b>,modul/model,defaultField,[s/m] <br>
                            [feld],<b>subrel</b>,modul/model/kürzel,fk-field,fields(|) <br>
							[feld],<b>lang</b>,[type]{t,int,vc,ti,d,dt,f}<br>
                        </div>
                </div>

                <div class="form-group">
                    <label for="ctrlname">CRUD-Controller</label>
                    <input type="text" value="Adressenverwalten" id="ctrlname" name="ctrlname" class="form-control" placeholder="Name eines CRUD-Controllers">
                </div>

                <button type="submit" class="btn btn-success btn-xs">Model erzeugen</button>
            </form>

        </div>

        <?php
        $Mdl = glob($M[$i].'/Model/*.php');
        if($Mdl=="") $Mdl = array();
        for($j=0;$j<count($Mdl);$j++) { ?>
            <div>
            <b><?= basename($Mdl[$j]); ?></b> <!-- <i>(Action-Methoden)</i> -->
            <a href="#" onclick="$(this).closest('div').find('.addmodelvarform<?= $i;?>_<?= $j;?>').slideToggle();return false;" title="Neue Variable im Model anlegen"><i style='float:right;' class="glyphicon glyphicon-plus-sign"></i></a>
                <div class="addmodelvarform<?= $i;?>_<?= $j;?>" style="display:none;">
                    <form role="form" action="<?= getLink('*/addmodelvar');?>" method="post">
                        <input type="text" name="modul" value="<?= basename($M[$i]); ?>">
                        <input type="text" name="model" value="<?= str_replace("Model.php", "", basename($Mdl[$j])); ?>">
                        <textarea id="modelfelder" name="modelfelder" class="form-control" placeholder="" rows="3"></textarea>
                        [name],[type]{t,int,vc,ti,d,dt,f}
                        <button type="submit" class="btn btn-success btn-xs">Variable hinzufügen</button>
                    </form>
                </div>
            </div>
        <?php } ?>


    </li>
<?php } ?>
</ul>

