<?php
    header('Content-type:text/html;charset=utf-8');
    include 'config.php';
    session_start();

    $lang   = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : 'tr';

    include 'lang/'.$lang.'.php';
    include 'functions.php';
    login();
    login();
    if($_POST){
        $id     = $_POST['id'];
        $query  = $db->prepare('SELECT * FROM passwords WHERE id = :id');
        $query->execute(array(
            ':id'   => $id
        ));
        $row    = $query->fetch();

        $c_query = $db->prepare('SELECT * FROM categories WHERE id = :id');
        $c_query->execute(array(
            ':id'   => $row['cat_id']
        ));
        $c_row   = $c_query->fetch();
?>
<div class="header">

    <div class="pull-left">
        <img src="<?=$row['favicon_url'];?>" alt="<?=$row['account_name'];?>" class="favicon">
        <div class="item-title"><?=$row['account_name'];?></div>
        <div class="item-category"><i class="<?=$c_row['icon'];?>"></i><?=$c_row['name'];?></div>
        <div class="clearfix"></div>
    </div>
    <div class="pull-right">
        <button class="btn btn-success" data-id="<?=$row['id'];?>" id="update-password"><i class="glyphicon glyphicon-edit"></i> <?=$lang['index']['msg_12'];?></button>
        <button class="btn btn-danger" data-id="<?=$row['id'];?>" id="remove-password"><i class="glyphicon glyphicon-remove"></i> <?=$lang['index']['msg_13'];?></button>
    </div>
    <div class="clearfix"></div>
    <hr>

</div>

<form class="form-horizontal" id="passForm">

    <div class="form-group">
        <label for="account_name" class="col-md-12 col-lg-2"><?=$lang['index']['msg_14'];?></label>
        <div class="col-md-12 col-lg-10">
            <input type="text" name="account_name" id="account_name" value="<?=$row['account_name'];?>" class="form-control" autocomplete="off">
        </div>
    </div>

    <div class="form-group">
        <label for="account_category" class="col-md-12 col-lg-2"><?=$lang['index']['msg_15'];?></label>
        <div class="col-md-12 col-lg-10">
            <select name="account_category" id="account_category" class="form-control">
                <?php
                    $c_query = $db->query("SELECT * FROM categories");
                    foreach($c_query as $c_row){
                        $selected = ($row['cat_id'] == $c_row['id']) ? 'selected' : '';
                        echo '<option value="'.$c_row['id'].'" data-icon="'.$c_row['icon'].'" '.$selected.'>'.$c_row['name'].'</option>';
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="username" class="col-md-12 col-lg-2"><?=$lang['index']['msg_16'];?></label>
        <div class="col-md-12 col-lg-10">
            <div class="input-group">
                <input type="text" name="username" id="username" value="<?=$row['username'];?>" class="form-control" autocomplete="off">
                <span class="input-group-addon copy" data-toggle="tooltip" data-placement="top" title="<?=$lang['index']['msg_18'];?>"><i class="glyphicon glyphicon-copy"></i></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-md-12 col-lg-2"><?=$lang['index']['msg_17'];?></label>
        <div class="col-md-12 col-lg-10">
            <div class="input-group">
                <input type="password" name="password" id="password" value="<?=$row['password'];?>" class="form-control">
                <span class="input-group-addon" id="pToggle" data-toggle="tooltip" data-placement="top" title="<?=$lang['index']['msg_19'];?>"><i class="glyphicon glyphicon-eye-open"></i></span>
                <span class="input-group-addon copy" data-toggle="tooltip" data-placement="top" title="<?=$lang['index']['msg_18'];?>"><i class="glyphicon glyphicon-copy"></i></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="website" class="col-md-12 col-lg-2"><?=$lang['index']['msg_20'];?></label>
        <div class="col-md-12 col-lg-10">
            <div class="input-group">
                <span class="input-group-addon"><img src="<?=$row['favicon_url'];?>" alt=""></span>
                <input type="website" name="website" id="website" value="<?=$row['website'];?>" class="form-control" autocomplete="off">
                <span class="input-group-addon" data-toggle="tooltip" data-placement="top" title="<?=$lang['index']['msg_24'];?>"><a href="<?=$row['website'];?>" target="_blank"><i class="glyphicon glyphicon-link"></i></a></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="summernote" class="col-md-12 col-lg-2"><?=$lang['index']['msg_21'];?></label>
        <div class="col-md-12 col-lg-10">
            <textarea name="summernote" id="summernote" class="form-control"><?=$row['notes'];?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="favicon" class="col-md-12 col-lg-2"><?=$lang['index']['msg_22'];?></label>
        <div class="col-md-12 col-lg-10">
            <div class="input-group">
                <span class="input-group-addon"><img src="<?=$row['favicon_url'];?>" alt=""></span>
                <input type="text" name="favicon" id="favicon" value="<?=$row['favicon_url'];?>" class="form-control" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-12 col-lg-2"><?=$lang['index']['msg_23'];?></label>
        <div class="col-md-12 col-lg-10">
            <p><?=convert_date($row['time']);?></p>
            <p><?=timeAgo($row['time']);?></p>
        </div>
    </div>

</form>
<?php
    }else{
?>
<div class="header">

    <div class="pull-left">
        <img src="http://placehold.it/64?text=?" alt="" class="favicon">
        <div class="item-title"><?=$lang['index']['msg_25'];?></div>
        <div class="item-category"><i class="flaticon-padlock"></i> <?=$lang['index']['msg_26'];?></div>
        <div class="clearfix"></div>
    </div>
    <div class="pull-right">
        <button class="btn btn-primary" id="addAccount"><i class="glyphicon glyphicon-ok"></i> <?=$lang['index']['msg_27'];?></button>
    </div>
    <div class="clearfix"></div>
    <hr>

</div>

<form class="form-horizontal" id="addAccountForm">

    <div class="form-group">
        <label for="account_name" class="col-md-12 col-lg-2"><?=$lang['index']['msg_14'];?></label>
        <div class="col-md-12 col-lg-10">
            <input type="text" name="account_name" id="account_name" value="" class="form-control" autocomplete="off">
        </div>
    </div>

    <div class="form-group">
        <label for="account_category" class="col-md-12 col-lg-2"><?=$lang['index']['msg_15'];?></label>
        <div class="col-md-12 col-lg-10">
            <select name="account_category" id="account_category" class="form-control">
                <?php
                    $c_query = $db->query("SELECT * FROM categories");
                    foreach($c_query as $c_row){
                        echo '<option value="'.$c_row['id'].'" data-icon="'.$c_row['icon'].'">'.$c_row['name'].'</option>';
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="username" class="col-md-12 col-lg-2"><?=$lang['index']['msg_16'];?></label>
        <div class="col-md-12 col-lg-10">
            <div class="input-group">
                <input type="text" name="username" id="username" value="" class="form-control" autocomplete="off">
                <span class="input-group-addon copy" data-toggle="tooltip" data-placement="top" title="<?=$lang['index']['msg_18'];?>"><i class="glyphicon glyphicon-copy"></i></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-md-12 col-lg-2"><?=$lang['index']['msg_17'];?></label>
        <div class="col-md-12 col-lg-10">
            <div class="input-group">
                <input type="password" name="password" id="password" value="" class="form-control">
                <span class="input-group-addon" id="pToggle" data-toggle="tooltip" data-placement="top" title="<?=$lang['index']['msg_19'];?>"><i class="glyphicon glyphicon-eye-open"></i></span>
                <span class="input-group-addon copy" data-toggle="tooltip" data-placement="top" title="<?=$lang['index']['msg_18'];?>"><i class="glyphicon glyphicon-copy"></i></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="website" class="col-md-12 col-lg-2"><?=$lang['index']['msg_20'];?></label>
        <div class="col-md-12 col-lg-10">
            <div class="input-group">
                <span class="input-group-addon"><img src="http://placehold.it/16?text=?" alt=""></span>
                <input type="website" name="website" id="website" value="http://" class="form-control" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="summernote" class="col-md-12 col-lg-2"><?=$lang['index']['msg_21'];?></label>
        <div class="col-md-12 col-lg-10">
            <textarea name="summernote" id="summernote" class="form-control"></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="favicon" class="col-md-12 col-lg-2"><?=$lang['index']['msg_22'];?></label>
        <div class="col-md-12 col-lg-10">
            <div class="input-group">
                <span class="input-group-addon"><img src="http://placehold.it/16?text=?" alt=""></span>
                <input type="text" name="favicon" id="favicon" value="http://" class="form-control" autocomplete="off">
            </div>
        </div>
    </div>

</form>
<?php
    }
?>
<script>
    new Clipboard('.copy');
    $('.copy').each(function(i, e){
        $(e).attr('data-clipboard-target', '#'+ $(this).prevAll('input').attr('id'));
    });

    $("#summernote").summernote({
        height      : 200,
        minHeight   : 200,
        maxHeight   : 800,
        <?=($_SESSION['lang'] != 'en') ? 'lang        : \''.$_SESSION['lang'].'-'.strtoupper($_SESSION['lang']).'\''."\n" : '';?>
    });

    $("[data-toggle=tooltip]").tooltip();

    function template(data){
        var $data = $('<span><i class="' + $(data.element).data('icon') + '"></i> ' + data.text +'</span>');
        return $data;
    }
    $("#account_category").select2({
        templateResult  : template
    });

    $("#addAccount").click(function(){
        $("#summernote").html($("#summernote").summernote('code'));
        var account_name        = $("#account_name").val(),
            account_category    = $("#account_category").val(),
            username            = $("#username").val(),
            password            = $("#password").val(),
            website             = $("#website").val(),
            notes               = $("#summernote").html(),
            favicon             = $("#favicon").val();
        $.post('ajax.php', {'type':3, 'account_name':account_name, 'account_category':account_category, 'username':username, 'password':password, 'website':website, 'notes':notes, 'favicon':favicon}, function(response){
                console.log(response);
            if(response.success){
                toastr8.success({
                    title       : msg_2,
                    message     : response.success.msg,
                    iconClass   : 'flaticon-checked'
                });
            }else if(response.info){
                toastr8.info({
                    title       : msg_3,
                    message     : response.info.msg,
                    iconClass   : 'flaticon-info'
                });
            }else{
                toastr8.error({
                    title       : msg_4,
                    message     : response.error.msg,
                    iconClass   : 'flaticon-delete'
                });
            }
        })
    });

    $("#update-password").click(function(){
        $("#summernote").html($("#summernote").summernote('code'));
        var id                  = $(this).data('id'),
            account_name        = $("#account_name").val(),
            account_category    = $("#account_category").val(),
            username            = $("#username").val(),
            password            = $("#password").val(),
            website             = $("#website").val(),
            notes               = $("#summernote").html(),
            favicon             = $("#favicon").val();
        $.post('ajax.php', {'type':4, 'id':id, 'account_name':account_name, 'account_category':account_category, 'username':username, 'password':password, 'website':website, 'notes':notes, 'favicon':favicon}, function(response){
            if(response.success){
                toastr8.success({
                    title       : msg_2,
                    message     : response.success.msg,
                    iconClass   : 'flaticon-checked'
                });
            }else if(response.info){
                toastr8.info({
                    title       : msg_3,
                    message     : response.info.msg,
                    iconClass   : 'flaticon-info'
                });
            }else{
                toastr8.error({
                    title       : msg_4,
                    message     : response.error.msg,
                    iconClass   : 'flaticon-delete'
                });
            }
        });
    });

    $("#pToggle").click(function(){
        type    = $(this).prev().attr('type');
        if(type == 'password'){
            $(this).html('<i class="glyphicon glyphicon-eye-close"></i>');
            $(this).prev().attr('type', 'text');
        }else{
            $(this).html('<i class="glyphicon glyphicon-eye-open"></i>');
            $(this).prev().attr('type', 'password');
        }
    });

    $("#remove-password").click(function(){
        var id = $(this).data('id');
        if(confirm(msg_8)){
            $.post('ajax.php', {'type':5, 'id':id}, function(response){
                console.log(response);
                if(response.success){
                    toastr8.success({
                        title       : msg_2,
                        message     : response.success.msg,
                        iconClass   : 'flaticon-checked'
                    });
                    $("#list ul li[data-id='"+id+"']").remove();
                }else if(response.info){
                    toastr8.info({
                        title       : msg_3,
                        message     : response.info.msg,
                        iconClass   : 'flaticon-info'
                    });
                }else{
                    toastr8.error({
                        title       : msg_4,
                        message     : response.error.msg,
                        iconClass   : 'flaticon-delete'
                    });
                }
            });
        }
    });
</script>
