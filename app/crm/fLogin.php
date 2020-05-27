<?php
$root="http://".$_SERVER['SERVER_NAME'];
$folder_css=$root."/assets/css";
$folder_js=$root."/assets/js";
$folder_img=$root."/assets/img";

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $app_nama?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?=$folder_css?>/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=$folder_css?>/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?=$folder_css?>/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=$folder_css?>/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=$folder_css?>/blue.css">
  <!--selectize-->
  <link rel="stylesheet" href="<?=$folder_css?>/selectize.bootstrap3.css" >

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- jQuery 3 -->
    <script src="<?=$folder_js?>/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?=$folder_js?>/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="<?=$folder_js?>/icheck.min.js"></script>
    <!-- Selectize -->
    <script src="<?=$folder_js?>/selectize.min.js"></script>
    
</head>
<body class="hold-transition login-page">
    <div class="login-box" style="width:300px;margin: 10% auto !important;">
        <!--<div class="login-logo">
            <b>Telkom Schools </b><small>Student</small>
        </div>-->
        <!-- /.login-logo -->
        <div class="login-box-body" style="width:300px">
            <img src="<?=$root?>/assets/img/dev.jpg" style="height:70%; width:90%; max-height:300px; max-width:300px; margin: 0 auto; display:block;"> <br>
            <p class="login-box-msg" style="text-align: left;font-size: 18px;padding-left: 2px;margin-top: 20px;">LOGIN</p>

            <form action="<?=$root?>/server/crm/cLogin.php?fx=login" method="post">
            <div class="form-group has-feedback">
                <input type="username" class="form-control" placeholder="username" id='login-user' name="nik" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="pass" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-6">
                    <button type="submit" class="btn btn-danger btn-block btn-flat" style="margin-top:3px;font-size:16px"><i class="glyphicon glyphicon-circle-arrow-right"></i>&nbsp; Login</button>
                </div>
                <!-- /.col -->
            </div>
            </form>


        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    
</body>
</html>
<script>
        $(function () {
            $('#login-pp').selectize({
                selectOnTab: true,
            });

            $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
            });
        });
</script>