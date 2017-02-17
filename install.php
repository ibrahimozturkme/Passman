<?php
    session_start();
    $lang   = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : 'tr';
    $config['app_name']         = 'PassMan';
    $config['app_version']      = 'v1.0';
    $config['app_description']  = '(Password Manager)';
    include 'lang/'.$lang.'.php';

    if($_POST){

        $file       = 'config.php.ini';
        if(file_exists($file)){
             $fopen      = fopen($file, 'r');
             $content    = @fread($fopen, filesize($file));

             $content = str_replace("host = '", "host = '".$_POST['dbhost']."", $content);
             $content = str_replace("name = '", "name = '".$_POST['dbname']."", $content);
             $content = str_replace("user = '", "user = '".$_POST['dbuser']."", $content);
             $content = str_replace("pass = '", "pass = '".$_POST['dbpass']."", $content);

             $config_file    = 'config.php';
             $c_fopen        = fopen($config_file, 'r+');
             fwrite($c_fopen, $content);
             fclose($c_fopen);
             fclose($fopen);
             unlink($file);

             include 'config.php';

             $sql    = file_get_contents('passman.sql');
             $db->exec($sql);

             $query = $db->prepare("UPDATE settings SET name = :name, username = :username, password = :password, lang = :lang");
             $query->execute(array(
                  ':name'     => $_POST['name'],
                  ':username' => $_POST['app_username'],
                  ':password' => $_POST['app_password'],
                  ':lang'     => $_POST['lang']
             ));
             header('Location:/login.php');
        }else{
             die($lang['install']['msg_16']);
        }

    }else{

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
<body id="installPage">
     <p class="alert">
          <span>Lütfen kurulumdan sonra install.php dosyasını siliniz.</span>
     </p>

    <div class="row">

        <div class="flexbox">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="flaticon-record"></i> <?=$config['app_name'].' '.$config['app_version'];?></div>
                <form action="" method="POST" id="installForm">
                    <div class="panel-body">
                        <?php if($_POST){ ?>
                        <p class="text-center"><?=$lang['install']['msg_12'];?></p>
                        <p class="text-center"><b><?=$lang['install']['msg_13'];?></b><?=$_POST['app_username'];?></p>
                        <p class="text-center"><b><?=$lang['install']['msg_14'];?></b><?=$_POST['app_password'];?></p>
                        <?php }else{ ?>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dbhost"><?=$lang['install']['msg_1'];?></label>
                                    <input type="text" name="dbhost" id="dbhost" class="form-control" placeholder="localhost" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="dbname"><?=$lang['install']['msg_2'];?></label>
                                    <input type="text" name="dbname" id="dbname" class="form-control" placeholder="passman" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="dbuser"><?=$lang['install']['msg_3'];?></label>
                                    <input type="text" name="dbuser" id="dbuser" class="form-control" placeholder="root" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="dbpass"><?=$lang['install']['msg_4'];?></label>
                                    <input type="dbpass" name="dbpass" id="dbpass" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name"><?=$lang['install']['msg_5'];?></label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="<?=$lang['install']['msg_5'];?>" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="app_username"><?=$lang['install']['msg_6'];?></label>
                                    <input type="text" name="app_username" id="app_username" class="form-control" placeholder="<?=$lang['install']['msg_6'];?>" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="app_password"><?=$lang['install']['msg_7'];?></label>
                                    <input type="text" name="app_password" id="app_password" class="form-control" placeholder="<?=$lang['install']['msg_7'];?>" autocomplete="off">
                                </div>
                                <label><?=$lang['install']['msg_8'];?></label>
                                <div class="form-group">
                                    <label for="lang_tr" class="radio">
                                        <input type="radio" name="lang" id="lang_tr" class="form-control" value="tr">
                                        <span><?=$lang['install']['msg_9'];?></span>
                                    </label>
                                    <label for="lang_en" class="radio">
                                        <input type="radio" name="lang" id="lang_en" class="form-control" value="en">
                                        <span><?=$lang['install']['msg_10'];?></span>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-footer">
                        <div class="pull-left lang">
                            <a href="/lang.php?lang=tr"><?=$lang['install']['msg_9'];?></a>
                            <a href="/lang.php?lang=en"><?=$lang['install']['msg_10'];?></a>
                        </div>
                        <?php if($_POST){ ?>
                        <a href="login.php" class="btn btn-default pull-right"><?=$lang['install']['msg_15'];?></a>
                        <?php }else{ ?>
                        <button id="install" class="btn btn-default pull-right"><?=$lang['install']['msg_11'];?></button>
                        <?php } ?>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
<?php } ?>
