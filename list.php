<?php
    include 'config.php';
    session_start();

    $lang   = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : 'tr';

    include 'lang/'.$lang.'.php';
    include 'functions.php';
    login();
    if($_POST){
        include 'config.php';
        login();
        $letter     = array();
        $id         = $_POST['id'];
        $c_query    = $db->prepare("SELECT * FROM categories WHERE id = ?");
        $c_query->execute(array($id));
        $c_row      = $c_query->fetch(PDO::FETCH_ASSOC);
        if($c_query->rowCount()){
            $query   = $db->prepare("SELECT * FROM passwords WHERE cat_id = :id");
            $query->execute(array(
                ':id' => $id
            ));
            $i = 0;
            foreach($query as $row){
                $i++;
                $letter[$row['account_name'][0]][$i]['id']              = $row['cat_id'];
                $letter[$row['account_name'][0]][$i]['account_name']    = $row['account_name'];
                $letter[$row['account_name'][0]][$i]['favicon_url']     = $row['favicon_url'];
                $letter[$row['account_name'][0]][$i]['category_name']   = $c_row['name'];
                $letter[$row['account_name'][0]][$i]['category_icon']   = $c_row['icon'];
            }
            ksort($letter);
            $data   = '';
            foreach($letter as $key => $value){
                echo '<li class="title">'.$key.'</li>';
                if(is_array($value)){
                    foreach($value as $k => $v){
                        echo '
                        <li class="item" data-id="'.$v['id'].'">
                            <div class="item-img" style="background-image:url('.$v['favicon_url'].')"></div>
                            <div class="item-title">'.$v['account_name'].'</div>
                            <div class="item-category"><i class="'.$v['category_icon'].'"></i>'.$v['category_name'].'</div>
                            <div class="clearfix"></div>
                        </li>';
                    }
                }
            }
        }else{
            header('Location:/404');
        }
    }else{
        header('Location:/404');
    }
?>
