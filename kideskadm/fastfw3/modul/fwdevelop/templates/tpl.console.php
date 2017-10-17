<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FW-Console</title>

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
            <a class="navbar-brand" href="#">FW-Console</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?= getLink("*/*");?>">Neu laden</a></li>
                <li><a href="" onclick="window.close();return false;">Schließen</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<div style="height:70px;"></div>

<div class="container">

    <div class="alert alert-info" role="alert">
        Module
        <a href="#" onclick="$('#newmodulform').slideToggle();return false;" title="Neues Modul anlegen"><i style='float:right;' class="glyphicon glyphicon-plus-sign"></i></a>
    </div>
    <?php include dirname(__FILE__).'/tpl.console_module.php'; ?>

    <div class="alert alert-info" role="alert">
        Snippets
        <a href="#" onclick="$('#snippets').slideToggle();return false;" title="Snippets öffnen"><i style='float:right;' class="glyphicon glyphicon-download"></i></a>
    </div>
    <?php include dirname(__FILE__).'/tpl.console_snippets.php'; ?>


</div>

</body>
</html>