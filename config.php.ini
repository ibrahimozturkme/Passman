<?php

    $dbhost = '';
    $dbname = '';
    $dbuser = '';
    $dbpass = '';

    try{
        $db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', $dbuser, $dbpass);
    }catch(PDOException $e){
        print $e->getMessage();
    }

    $config['app_name']         = 'PassMan';
    $config['app_version']      = 'v1.0';
    $config['app_description']  = '(Password Manager)';

?>
