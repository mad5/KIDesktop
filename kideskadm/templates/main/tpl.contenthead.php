<body>
<?php if(!defined("hideBar")) { ?> 
<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?= getLink("Eintrag"); ?>">KIDesktop - Administration</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          <?php if(loggedIn()) { ?>       
            <li class="<?= ($this->fw->QS[0]=="Rechner" ? 'active' : '');?>"><a href="<?= getLink("Rechner");?>">Rechner</a></li>
            <li class="<?= ($this->fw->QS[0]=="Bereiche" ? 'active' : '');?>"><a href="<?= getLink("Bereiche");?>">Bereiche</a></li>
            <!-- <li class="<?= ($this->fw->QS[0]=="Kategorien" ? 'active' : '');?>"><a href="<?= getLink("Kategorien");?>">Kategorien</a></li> -->
            <li class="<?= ($this->fw->QS[0]=="Eintrag" ? 'active' : '');?>"><a href="<?= getLink("Eintrag");?>">Einträge</a></li>
            <?php if(me()->getPk()==1) { ?>
            <li class="<?= ($this->fw->QS[0]=="Mailkontakt" ? 'active' : '');?>"><a href="<?= getLink("Mailkontakt");?>">Mailkontakt</a></li>
            <?php } ?>
          <?php } ?>
          </ul>
          
             <ul class="nav navbar-nav navbar-right">
             <li class="<?= ($this->fw->QS[0]=="Index" ? 'active' : '');?>"><a href="<?= getLink("Index/infos");?>">Infos</a></li>
            <?php if(loggedIn()) { ?>       
            	    <li class="dropdown">

                <a class="dropdown-toggle topnavitem" href="#" data-toggle="dropdown">
                  <?php
                  $backenduser = \Backenduser\Service\ActiveBackenduserService::getUser();
                  if(trim($backenduser->getFirstname()." ".$backenduser->getLastname())=="") {
                    echo $backenduser->getUsername();
                  } else {
                    echo (trim($backenduser->getFirstname())!='' ? strtoupper(substr($backenduser->getFirstname(),0,1)) . ". " : '') . $backenduser->getLastname();
                  }
                  ?>
                  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="<?= getLink("Einstellungen/changePW");?>"><?= transFull("Einstellungen");?></a></li>
                  <li class="divider"></li>
                  <li><a href="<?= getLink("Backenduser/logout");?>"><?= transFull("abmelden");?></a></li>
                  
                </ul>
              </li>            	    
          <?php } else { ?>
              <li><a href="<?= getLink("Backenduser/Registration");?>" class="topnavitem"><?= transFull("registrieren");?></a></li>
              <li><a href="<?= getLink("Backenduser/login");?>" class="topnavitem"><?= transFull("anmelden");?></a></li>
            <?php } ?>
          </ul>
        </div><!--/.nav-collapse -->

      </div>
      
      
        
</div>
<?php } ?>