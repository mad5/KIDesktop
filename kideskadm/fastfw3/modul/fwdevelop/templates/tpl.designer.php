<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FW-Designer</title>

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <style>
        .headline {
            margin-top:20px;
            border-top:solid 1px silver;
            padding-top:20px;
            display:block;
            border-bottom:solid 1px gray;
        }
    </style>

</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">FW-Designer</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?= getLink("*/*");?>">Neu laden</a></li>
                <li><a href="" onclick="fwdesigner.addScreen();return false;"><i class="glyphicon glyphicon-plus-sign"></i> Screen</a></li>
                <li><a href="" onclick="fwdesigner.addView();return false;"><i class="glyphicon glyphicon-plus-sign"></i> View</a></li>
                <li><a href="" onclick="fwdesigner.closeViews();return false;">close Views</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<div style="height:70px;"></div>



<style>
    .viewtitles {
        font-size:0.8em;
    }
    .fd_info {
        padding: 5px;
    }
</style>

<script type="text/plain" id="screentpl">
    <div id='##sid##' class='fd_box fwd_screens' style="position:absolute;min-width:200px;min-height:150px;border: solid 1px gray;background-color:white;">
        <div class='fd_title' rel='##nr##' style="background-color: gray;color: white;padding:5px;border-bottom:solid 1px gray;"></div>
        <a href="#" onclick="fwdesigner.openViews(##nr##);return false;" style="float:right;"><i class="glyphicon glyphicon-th-large"></i></a>
        <a href="#" onclick="fwdesigner.editScreenTitle(##nr##);return false;" style="float:right;"><i class="glyphicon glyphicon-pencil"></i>&nbsp;</a>
        <div class='fd_info'></div>
    </div>
</script>

<script type="text/plain" id="viewtpl">
    <div id='##vid##' class='fdv_box fwd_views' style="position:absolute;min-width:200px;min-height:120px;border: solid 1px gray;background-color:white;">
        <div class='fdv_title' rel='##vnr##' style="background-color: gray;color: white;padding:5px;border-bottom:solid 1px gray;"></div>
        <a href="#" onclick="fwdesigner.editViewTitle(##vnr##);return false;" style="float:right;"><i class="glyphicon glyphicon-pencil"></i>&nbsp;</a>
    </div>
</script>

<script>
<?php include dirname(__FILE__)."/fwdesigner.js";?>

fwdesigner.screens = <?= $screens;?>;
fwdesigner.init();
</script>

</body>
</html>