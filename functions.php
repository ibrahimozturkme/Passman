<?php
    function login(){
        if(file_exists('install.php')){
            if($_SERVER['PHP_SELF'] != '/install.php'){
                header('Location:/install.php');
            }
        }else{
            if(!isset($_SESSION['login'])){
                if($_SERVER['PHP_SELF'] != '/login.php'){
                    header('Location:/login.php');
                }
            }
        }
    }

    function timeAgo($date){
        global $lang;
        $currentDate    = new DateTime('@'.$date);
        $nowDate        = new DateTime('@'.time());
        $result         = $currentDate->diff($nowDate)->format('<b>%Y</b> '.$lang['timeAgo']['year'].' <b>%m</b> '.$lang['timeAgo']['month'].' <b>%d</b> '.$lang['timeAgo']['day'].' <b>%H</b> '.$lang['timeAgo']['hour'].' <b>%i</b> '.$lang['timeAgo']['minute'].' <b>%s</b> '.$lang['timeAgo']['second'].' '.$lang['timeAgo']['ago'].'.');
        $result         = str_replace('<b>00</b> '.$lang['timeAgo']['year'].' ', '', $result);
        $result         = str_replace('<b>0</b> '.$lang['timeAgo']['month'].' ', '', $result);
        $result         = str_replace('<b>0</b> '.$lang['timeAgo']['day'].' ', '', $result);
        $result         = str_replace('<b>00</b> '.$lang['timeAgo']['hour'].' ', '', $result);
        $result         = str_replace('<b>0</b> '.$lang['timeAgo']['minute'].'', '', $result);
        return  $result;
    }

    function convert_date($date){
        global $lang;
        $new_date   = date('d F Y l H:i:s', $date);
        foreach($lang['days'] as $key => $value){
            $new_date = str_replace($key, $value, $new_date);
        }
        foreach($lang['months'] as $key => $value){
            $new_date = str_replace($key, $value, $new_date);
        }
        return $new_date;
    }
?>
