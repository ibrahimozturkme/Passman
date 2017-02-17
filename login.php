<?php
    include 'config.php';
    session_start();

    $lang   = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : 'tr';

    include 'lang/'.$lang.'.php';
    include 'functions.php';
    login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$config['app_name'];?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alegreya+Sans+SC:500,900italic,900,800italic,800,700italic,700,500italic,400italic,400,300italic,300,100italic,100|Roboto:400,100,300,300italic,100italic,400italic,500italic,500,700,700italic,900,900italic&subset=latin,latin-ext">
    <link rel="stylesheet" href="assets/plugins/icons/flaticon.css">
    <link rel="stylesheet" href="assets/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="assets/plugins/iconpicker/css/jquery.fonticonpicker.min.css">
    <link rel="stylesheet" href="assets/plugins/iconpicker/themes/bootstrap-theme/jquery.fonticonpicker.bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/toastr8/css/toastr8.min.css">
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <script>
        var msg_1   = '<?=$lang['index']['js_1'];?>',
            msg_2   = '<?=$lang['index']['js_2'];?>',
            msg_3   = '<?=$lang['index']['js_3'];?>',
            msg_4   = '<?=$lang['index']['js_4'];?>',
            msg_5   = '<?=$lang['index']['js_7'];?>',
            msg_6   = '<?=$lang['index']['js_5'];?>',
            msg_7   = '<?=$lang['index']['js_6'];?>';
    </script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/plugins/clipboard/clipboard.min.js"></script>
    <script type="text/javascript" src="assets/plugins/summernote/summernote.min.js"></script>
    <script type="text/javascript" src="assets/plugins/summernote/lang/summernote-tr-TR.min.js"></script>
    <script type="text/javascript" src="assets/plugins/iconpicker/jquery.fonticonpicker.min.js"></script>
    <script type="text/javascript" src="assets/plugins/toastr8/js/toastr8.min.js"></script>
    <script type="text/javascript" src="assets/plugins/select2/js/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/app.js"></script>

</head>
<body id="loginPage">

    <div class="row">

        <div class="flexbox">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="flaticon-record"></i> <?=$config['app_name'].' '.$config['app_version'];?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="username"><?=$lang['login']['msg_1'];?></label>
                        <input type="text" id="username" class="form-control" placeholder="<?=$lang['login']['msg_1'];?>" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="password"><?=$lang['login']['msg_2'];?></label>
                        <input type="password" id="password" class="form-control" placeholder="<?=$lang['login']['msg_2'];?>" autocomplete="off">
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="pull-left lang">
                        <a href="/lang.php?lang=tr"><?=$lang['install']['msg_9'];?></a>
                        <a href="/lang.php?lang=en"><?=$lang['install']['msg_10'];?></a>
                    </div>
                    <button id="login" class="btn btn-default pull-right"><?=$lang['login']['msg_3'];?></button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
