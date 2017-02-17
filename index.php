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
    <title>PassMan</title>
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
            msg_7   = '<?=$lang['index']['js_6'];?>',
            msg_8   = '<?=$lang['index']['js_8'];?>';
    </script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/plugins/clipboard/clipboard.min.js"></script>
    <script type="text/javascript" src="assets/plugins/summernote/summernote.min.js"></script>
    <?php if($_SESSION['lang'] == 'tr'){?>
    <script type="text/javascript" src="assets/plugins/summernote/lang/summernote-tr-TR.min.js"></script>
    <?php } ?>
    <script type="text/javascript" src="assets/plugins/iconpicker/jquery.fonticonpicker.min.js"></script>
    <script type="text/javascript" src="assets/plugins/toastr8/js/toastr8.min.js"></script>
    <script type="text/javascript" src="assets/plugins/select2/js/select2.min.js"></script>
    <script type="text/javascript" src="assets/plugins/enscroll-0.6.2.min.js"></script>
    <script type="text/javascript" src="assets/js/app.js"></script>


</head>
<body>

    <div class="row">
        <div class="col-md-3 col-lg-2" id="categories">
            <div id="logo">
                <a href=""><i class="flaticon-record"></i> <?=$config['app_name'];?></a>
                <button class="btn btn-default visible-xs visible-sm" id="menu-button"><i class="glyphicon glyphicon-menu-hamburger"></i></button>
                <button class="btn btn-default visible-xs visible-sm" id="search-button"><i class="glyphicon glyphicon-search"></i></button>
                <button class="btn btn-default visible-xs visible-sm" id="back-button"><i class="glyphicon glyphicon-chevron-left"></i></button>
                <a href="logout.php" class="btn btn-default" data-toggle="tooltip" data-placement="left" title="Çıkış Yap"><i class="glyphicon glyphicon-off"></i></a>
                <div class="clearfix"></div>
            </div>
            <ul class="">
                <li class="title">
                    <span class="pull-left"><?=$lang['index']['msg_1'];?></span>
                    <span class="pull-right btn btn-xs btn-default" id="edit-category" data-toggle="tooltip" data-placement="left" title="<?=$lang['index']['msg_2'];?>"><i class="glyphicon glyphicon-edit"></i></span>
                    <div class="clearfix"></div>
                </li>
                <?php
                    $query  = $db->query("SELECT * FROM categories");
                    if($query->rowCount()){
                        foreach($query as $row){
                            $pass_query  = $db->prepare("SELECT * FROM passwords WHERE cat_id = ?");
                            $pass_query->execute(array($row['id']));
                            echo '<li class="item"><a href="" data-id="'.$row['id'].'"><i class="'.$row['icon'].'"></i> '.$row['name'].' <span class="badge">'.$pass_query->rowCount().' </span> <button class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button></a></li>';
                        }
                    }else{
                        echo '<div class="empty">'.$lang['index']['msg_3'].'</div>';
                    }
                ?>
            </ul>
            <button class="btn btn-default" data-toggle="modal" data-target="#addCategoryModal"><i class="glyphicon glyphicon-plus"></i> <?=$lang['index']['msg_4'];?></button>
        </div>
        <div class="col-md-4 col-lg-3" id="list">
            <div class="search input-group">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-search"></i>
                </span>
                <input type="text" id="search" placeholder="<?=$lang['index']['msg_5'];?>" autocomplete="off">
                <span class="input-group-btn" data-toggle="tooltip" data-placement="left" title="<?=$lang['index']['msg_6'];?>">
                    <a href="" class="btn btn-default" id="loadP"><i class="glyphicon glyphicon-plus"></i></a>
                </span>
            </div>
            <ul>
                <?php
                    $query  = $db->query('SELECT * FROM passwords');
                    if($query->rowCount() > 0){
                        $array  = array();
                        $i = 0;
                        foreach($query as $row){
                            $i++;
                            $c_query = $db->prepare('SELECT * FROM categories WHERE id = :id');
                            $c_query->execute(array(
                                ':id'   => $row['cat_id']
                            ));
                            $c_row   = $c_query->fetch();
                            @$array[$row['account_name'][0]][$i]['id']               = $row['id'];
                            @$array[$row['account_name'][0]][$i]['account_name']     = ($row['account_name'] == "") ? $row['username'] : $row['account_name'];
                            @$array[$row['account_name'][0]][$i]['favicon_url']      = $row['favicon_url'];
                            @$array[$row['account_name'][0]][$i]['category']         = $c_row['name'];
                            @$array[$row['account_name'][0]][$i]['category_icon']    = $c_row['icon'];
                        }
                        ksort($array);
                        foreach($array as $key => $value){
                            $key    = ($key == "") ? '#' : $key;
                            echo '<li class="title">'.strtoupper($key).'</li>';
                            if(is_array($value)){
                                foreach($value as $k => $v){
                                    echo '
                                    <li class="item" data-id="'.$v['id'].'">
                                        <div class="item-img" style="background-image:url('.$v['favicon_url'].')"></div>
                                        <div class="item-title">'.$v['account_name'].'</div>
                                        <div class="item-category"><i class="'.$v['category_icon'].'"></i>'.$v['category'].'</div>
                                        <div class="clearfix"></div>
                                    </li>';
                                }
                            }
                        }
                    }else{
                        echo '<li class="item empty">'.$lang['index']['msg_7'].'</li>';
                    }
                ?>
            </ul>
        </div>
        <div class="col-md-5 col-lg-7" id="content">

            <div id="landing" class="text-center">

                <div class="title">
                    <i class="flaticon-record"></i>
                    <span><?=$config['app_name'].' '.$config['app_version']?></span>
                    <p><?=$config['app_description'];?></p>
                </div>
            </div>

        </div>
    </div>

    <div id="addCategoryModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?=$lang['index']['msg_8'];?></h4>
                </div>
                <div class="modal-body">
                    <label for="category"><?=$lang['index']['msg_9'];?></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <select name="category_icon" id="category_icon">
                                <option>flaticon-add</option>
                                <option>flaticon-airplane</option>
                                <option>flaticon-app</option>
                                <option>flaticon-arrows</option>
                                <option>flaticon-art</option>
                                <option>flaticon-attachment</option>
                                <option>flaticon-back</option>
                                <option>flaticon-bag</option>
                                <option>flaticon-ball</option>
                                <option>flaticon-ball-1</option>
                                <option>flaticon-billiard-ball</option>
                                <option>flaticon-black</option>
                                <option>flaticon-black-1</option>
                                <option>flaticon-black-2</option>
                                <option>flaticon-book</option>
                                <option>flaticon-book-1</option>
                                <option>flaticon-book-2</option>
                                <option>flaticon-buildings</option>
                                <option>flaticon-business</option>
                                <option>flaticon-business-1</option>
                                <option>flaticon-business-2</option>
                                <option>flaticon-business-3</option>
                                <option>flaticon-business-4</option>
                                <option>flaticon-business-5</option>
                                <option>flaticon-business-6</option>
                                <option>flaticon-business-7</option>
                                <option>flaticon-business-8</option>
                                <option>flaticon-business-9</option>
                                <option>flaticon-calculator</option>
                                <option>flaticon-camera</option>
                                <option>flaticon-car</option>
                                <option>flaticon-car-1</option>
                                <option>flaticon-certificate</option>
                                <option>flaticon-checked</option>
                                <option>flaticon-cinema</option>
                                <option>flaticon-circle</option>
                                <option>flaticon-clock</option>
                                <option>flaticon-clock-1</option>
                                <option>flaticon-cloud</option>
                                <option>flaticon-coding</option>
                                <option>flaticon-cogwheel</option>
                                <option>flaticon-cogwheel-1</option>
                                <option>flaticon-commerce</option>
                                <option>flaticon-commerce-1</option>
                                <option>flaticon-commerce-2</option>
                                <option>flaticon-commerce-3</option>
                                <option>flaticon-communication</option>
                                <option>flaticon-communication-1</option>
                                <option>flaticon-communication-2</option>
                                <option>flaticon-communication-3</option>
                                <option>flaticon-computer</option>
                                <option>flaticon-contacts</option>
                                <option>flaticon-creative-commons-logo</option>
                                <option>flaticon-crown</option>
                                <option>flaticon-cup</option>
                                <option>flaticon-cup-1</option>
                                <option>flaticon-delete</option>
                                <option>flaticon-download</option>
                                <option>flaticon-drop</option>
                                <option>flaticon-education</option>
                                <option>flaticon-external-link</option>
                                <option>flaticon-fashion</option>
                                <option>flaticon-favorite</option>
                                <option>flaticon-favorite-1</option>
                                <option>flaticon-favorite-2</option>
                                <option>flaticon-favorite-3</option>
                                <option>flaticon-folder</option>
                                <option>flaticon-folder-1</option>
                                <option>flaticon-folder-2</option>
                                <option>flaticon-game</option>
                                <option>flaticon-game-1</option>
                                <option>flaticon-game-2</option>
                                <option>flaticon-game-3</option>
                                <option>flaticon-game-4</option>
                                <option>flaticon-game-5</option>
                                <option>flaticon-game-6</option>
                                <option>flaticon-globe</option>
                                <option>flaticon-hammer</option>
                                <option>flaticon-hand</option>
                                <option>flaticon-heart</option>
                                <option>flaticon-heartbeat</option>
                                <option>flaticon-hearts</option>
                                <option>flaticon-help</option>
                                <option>flaticon-home</option>
                                <option>flaticon-identity-card</option>
                                <option>flaticon-info</option>
                                <option>flaticon-info-1</option>
                                <option>flaticon-interface</option>
                                <option>flaticon-interface-1</option>
                                <option>flaticon-interface-2</option>
                                <option>flaticon-internet</option>
                                <option>flaticon-key</option>
                                <option>flaticon-key-1</option>
                                <option>flaticon-keyhole</option>
                                <option>flaticon-letter</option>
                                <option>flaticon-like</option>
                                <option>flaticon-link</option>
                                <option>flaticon-link-1</option>
                                <option>flaticon-link-symbol</option>
                                <option>flaticon-megaphone</option>
                                <option>flaticon-menu</option>
                                <option>flaticon-menu-1</option>
                                <option>flaticon-microphone</option>
                                <option>flaticon-mobile-app</option>
                                <option>flaticon-money</option>
                                <option>flaticon-money-1</option>
                                <option>flaticon-money-2</option>
                                <option>flaticon-money-3</option>
                                <option>flaticon-money-4</option>
                                <option>flaticon-money-5</option>
                                <option>flaticon-money-6</option>
                                <option>flaticon-multimedia</option>
                                <option>flaticon-multimedia-1</option>
                                <option>flaticon-music</option>
                                <option>flaticon-new</option>
                                <option>flaticon-pacman</option>
                                <option>flaticon-padlock</option>
                                <option>flaticon-password</option>
                                <option>flaticon-pawprint</option>
                                <option>flaticon-people</option>
                                <option>flaticon-photo</option>
                                <option>flaticon-photo-1</option>
                                <option>flaticon-picture</option>
                                <option>flaticon-pin-code</option>
                                <option>flaticon-placeholder</option>
                                <option>flaticon-placeholder-1</option>
                                <option>flaticon-play</option>
                                <option>flaticon-play-1</option>
                                <option>flaticon-play-button</option>
                                <option>flaticon-power</option>
                                <option>flaticon-power-1</option>
                                <option>flaticon-quote</option>
                                <option>flaticon-rate-star-button</option>
                                <option>flaticon-record</option>
                                <option>flaticon-restaurant</option>
                                <option>flaticon-ribbon</option>
                                <option>flaticon-ribbon-1</option>
                                <option>flaticon-save</option>
                                <option>flaticon-security</option>
                                <option>flaticon-security-1</option>
                                <option>flaticon-shape</option>
                                <option>flaticon-shapes</option>
                                <option>flaticon-shapes-1</option>
                                <option>flaticon-shield</option>
                                <option>flaticon-sign</option>
                                <option>flaticon-signs</option>
                                <option>flaticon-signs-1</option>
                                <option>flaticon-signs-2</option>
                                <option>flaticon-signs-3</option>
                                <option>flaticon-signs-4</option>
                                <option>flaticon-signs-5</option>
                                <option>flaticon-six-pool-balls</option>
                                <option>flaticon-social</option>
                                <option>flaticon-social-1</option>
                                <option>flaticon-social-network</option>
                                <option>flaticon-sport</option>
                                <option>flaticon-sport-1</option>
                                <option>flaticon-sports</option>
                                <option>flaticon-sports-1</option>
                                <option>flaticon-spy</option>
                                <option>flaticon-star</option>
                                <option>flaticon-star-1</option>
                                <option>flaticon-symbol</option>
                                <option>flaticon-symbol-1</option>
                                <option>flaticon-symbol-2</option>
                                <option>flaticon-symbols</option>
                                <option>flaticon-target</option>
                                <option>flaticon-target-1</option>
                                <option>flaticon-technology</option>
                                <option>flaticon-technology-1</option>
                                <option>flaticon-technology-10</option>
                                <option>flaticon-technology-11</option>
                                <option>flaticon-technology-12</option>
                                <option>flaticon-technology-13</option>
                                <option>flaticon-technology-14</option>
                                <option>flaticon-technology-15</option>
                                <option>flaticon-technology-2</option>
                                <option>flaticon-technology-3</option>
                                <option>flaticon-technology-4</option>
                                <option>flaticon-technology-5</option>
                                <option>flaticon-technology-6</option>
                                <option>flaticon-technology-7</option>
                                <option>flaticon-technology-8</option>
                                <option>flaticon-technology-9</option>
                                <option>flaticon-telephone</option>
                                <option>flaticon-tool</option>
                                <option>flaticon-tool-1</option>
                                <option>flaticon-tool-2</option>
                                <option>flaticon-transfer</option>
                                <option>flaticon-transport</option>
                                <option>flaticon-video</option>
                                <option>flaticon-video-1</option>
                                <option>flaticon-warning</option>
                                <option>flaticon-web</option>
                                <option>flaticon-webcam</option>
                                <option>flaticon-webcam-1</option>
                                <option>flaticon-webcam-2</option>
                                <option>flaticon-webcam-tool-black-circular-shape</option>
                                <option>flaticon-wifi</option>
                                <option>wifi-signal-tower</option>
                            </select>
                        </span>
                        <input type="text" name="category" id="category" placeholder="<?=$lang['index']['msg_9'];?>" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> <?=$lang['index']['msg_10'];?></button>
                    <button class="btn btn-success" id="addCategory"><i class="glyphicon glyphicon-plus"></i> <?=$lang['index']['msg_11'];?></button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
