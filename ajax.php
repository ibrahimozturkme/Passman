<?php
    header('Content-type:application/json;charset=utf-8');
    session_start();

    $lang   = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : 'tr';

    include 'lang/'.$lang.'.php';
    include 'functions.php';
    if($_POST){
        include 'config.php';
        login();

        $type           = $_POST['type'];
        $notification   = array();

        if($type == 1){ // Add Category
            $icon   = $_POST['icon'];
            $name   = $_POST['name'];

            $query  = $db->prepare("SELECT * FROM categories WHERE icon = ? AND name = ?");
            $query->execute(array($icon, $name));
            if($query->rowCount()){
                $notification['info']['msg']   = $lang['msg_1'];
            }else{
                $query  = $db->prepare("INSERT INTO categories SET icon = :icon, name = :name");
                $insert = $query->execute(array(
                    ':icon' => $icon,
                    ':name' => $name
                ));
                if($insert){
                    $notification['success']['id']   = $db->lastInsertId();
                    $notification['success']['msg']  = $lang['msg_2'];
                }else{
                    $notification['error']['msg']    = $db->errorInfo();
                }
            }
        }else if($type == 2){   // Remove Category
            $id = $_POST['id'];

            $query  = $db->prepare("SELECT * FROM categories WHERE id = ?");
            $query->execute(array($id));
            if($query->rowCount()){
                $query = $db->prepare('DELETE FROM categories WHERE id = ?');
                $query->execute(array($id));
                if($query){
                    $query = $db->prepare('DELETE FROM passwords WHERE cat_id = ?');
                    $query->execute(array($id));
                    $notification['success']['msg'] = $lang['msg_3'];
                    if($query){
                        $notification['success']['msg'] = $lang['msg_4'];
                    }else{
                        $notification['error']['msg']   = $lang['msg_5'].$db->errorInfo();
                    }
                }else{
                    $notification['error']['msg']   = $lang['msg_6'].$db->errorInfo();
                }
            }else{
                $notification['info']['msg']    = $lang['msg_7'];
            }
        }else if($type == 3){   // Add password

            $account_name       = $_POST['account_name'];
            $account_category   = $_POST['account_category'];
            $username           = $_POST['username'];
            $password           = $_POST['password'];
            $website            = $_POST['website'];
            $notes              = $_POST['notes'];
            $favicon            = $_POST['favicon'];
            $time               = time();

            $query  = $db->prepare("INSERT INTO passwords SET account_name = :account_name, cat_id = :cat_id, username = :username, password = :password, website = :website, notes = :notes, favicon_url = :favicon_url, time = :time");
            $insert = $query->execute(array(
                ':account_name' => $account_name,
                ':cat_id'       => $account_category,
                ':username'     => $username,
                ':password'     => $password,
                ':website'      => $website,
                ':notes'        => $notes,
                ':favicon_url'  => $favicon,
                ':time'         => $time
            ));
            if($insert){
                $notification['success']['msg']    = $lang['msg_8'];
            }else{
                $notification['error']['msg']    = $lang['msg_9'];
            }
        }else if($type == 4){   // Update password

            $id                 = $_POST['id'];
            $account_name       = $_POST['account_name'];
            $account_category   = $_POST['account_category'];
            $username           = $_POST['username'];
            $password           = $_POST['password'];
            $website            = $_POST['website'];
            $notes              = $_POST['notes'];
            $favicon            = $_POST['favicon'];
            $time               = time();

            $query  = $db->prepare("SELECT * FROM passwords WHERE id = ?");
            $query->execute(array($id));
            if($query->rowCount()){
                $u_query    = $db->prepare("UPDATE passwords SET account_name = :account_name, cat_id = :cat_id, username = :username, password = :password, website = :website, notes = :notes, favicon_url = :favicon_url, time = :time WHERE id = :id");
                $update     = $u_query->execute(array(
                    ':id'           => $id,
                    ':account_name' => $account_name,
                    ':cat_id'       => $account_category,
                    ':username'     => $username,
                    ':password'     => $password,
                    ':website'      => $website,
                    ':notes'        => $notes,
                    ':favicon_url'  => $favicon,
                    ':time'         => $time
                ));
                if($update){
                    $notification['success']['msg']    = $lang['msg_10'];
                }else{
                    $notification['error']['msg']    = $lang['msg_11'];
                }
            }else{
                $notification['info']['msg']    = $lang['msg_12'];
            }
        }else if($type == 5){   // Remove password
            $id     = $_POST['id'];
            $query  = $db->prepare("SELECT * FROM passwords WHERE id = ?");
            $query->execute(array($id));
            if($query->rowCount()){
                $query = $db->prepare('DELETE FROM passwords WHERE id = ?');
                $query->execute(array($id));
                if($query){
                    $notification['success']['msg'] = $lang['msg_13'];
                }else{
                    $notification['error']['msg']   = $lang['msg_14'].$db->errorInfo();
                }
            }else{
                $notification['info']['msg']    = $lang['msg_15'];
            }

        }else if($type == 6){   // Login
            $username   = $_POST['username'];
            $password   = $_POST['password'];

            $query = $db->prepare("SELECT * FROM settings WHERE username = :username AND password = :password");
            $query->execute(array(
                ':username' => $username,
                ':password' => $password
            ));
            if($query->rowCount()){
                $row    = $query->fetch(PDO::FETCH_ASSOC);
                $_SESSION['login']  = true;
                $_SESSION['name']   = $row['name'];
                $_SESSION['lang']   = $row['lang'];
                $notification['success']['msg']   = $lang['msg_16'].$row['name'];
            }else{
                $_SESSION['login']  = false;
                $notification['error']['msg']   = $lang['msg_17'];
            }
        }else{
            header('Location:/404');
        }

        echo json_encode($notification);

    }else{
        header('Location:/404');
    }
?>
