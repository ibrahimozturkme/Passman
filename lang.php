<?php
    session_start();
    $lang = @$_GET['lang'];
    $dir = opendir('lang');
    while(($file = readdir($dir)) !== false){
        if(!is_dir($file)){
            $file = explode('.php', $file)[0];
            if($file == $lang){
                $_SESSION['lang'] = $lang;
                exit('<script>window.history.go(-1);</script>');
            }else{
                $_SESSION['lang'] = 'tr';
            }
        }
    }

    if($_GET){
        echo '<script>window.history.go(-1);</script>';
    }
?>
