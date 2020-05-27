<?php
    $root=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
    $root_ser=$root."/server";
    $folder_css=$root."/vendor/asset_elite/dist/css";
    $folder_js=$root."/vendor/asset_elite/dist/js";
    $folder_assets=$root."/vendor/asset_elite";
    $folder_upload=$root."/vendor/asset_elite/upload";
    $folderoot_assets=$root."/assets";
    $folderroot_js=$root."/assets/js";
    $folderroot_css=$root."/assets/css";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?=$folder_assets?>/images/sai_icon/saku.png">
    <title>SAKU - Sign In</title>
    
    <!-- page css -->
    <link href="<?=$folder_css?>/pages/login-register-lock.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=$folder_css?>/style.min.css" rel="stylesheet">
    <!-- Selectize -->
    <link href="<?=$folderroot_css?>/selectize.bootstrap3.css" rel="stylesheet">
</head>

<body class="skin-default card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">SAKU admin</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper">
        <div class="login-register" style="background-image:url(<?=$folder_assets?>/images/background/login-register.jpg);">
            <div class="login-box card">
                <div class="card-body">
                    <form class="form-horizontal form-material" method="post" id="loginform" action="<?=$root_ser?>/siswa/cLogin.php?fx=login">
                        <div style="text-align: center;" class="mb-2"><img src="<?=$folderoot_assets?>/img/ts.png" style="width: 200px;"></div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" id="login-user" placeholder="Username" name="nik" required></div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password"  id="pass" placeholder="Password" name="pass" required> </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="col-xs-12">
                                <select name="kode_pp" class="form-control" id="kode_pp">
                                <option>Pilih Kode PP</option>
                                </select>
                            </div>
                        </div>                       
                        <div class="form-group text-center">
                            <div class="col-xs-12 p-b-20">
                                <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit">Log In</button>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal" id="recoverform" action="index.html">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>Recover Password</h3>
                                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" placeholder="Email"> </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?=$folder_assets?>/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?=$folder_assets?>/node_modules/popper/popper.min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?=$folderroot_js?>/standalone/selectize.min.js"></script>
    <script type="text/javascript">
        // $(".select2").select2();
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        // ============================================================== 
        // Login and Recover Password 
        // ============================================================== 
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
        $(function () {
            $('#kode_pp').selectize({
                selectOnTab: true,
            });

            // var selectize= $('#login-pp').selectize();

            $('#login-user').on('change', function(){
                var nis = $(this).val();
                var selectize = $('#kode_pp')[0].selectize;

                $.ajax({
                    url: "<?=$root_ser?>/siswa/cLogin.php?fx=getDaftarPP",
                    type: 'POST',
                    dataType: 'json',
                    data: {'nis': nis},
                    success: function(data){
                        selectize.clearOptions();
                        if(data.pp.length > 0){
                            for(i=0; i<data.pp.length; i++){
                                var new_text = data.pp[i].kode_pp+' - '+data.pp[i].nama;
                                selectize.addOption({value:data.pp[i].kode_pp, text:new_text});
                                selectize.addItem(new_text);
                            }

                            selectize.setValue(data.pp[0].kode_pp);
                        }else{
                            alert('Data NIS belum terdaftar di sekolah');
                        }
                    }
                });
            });

           
        });
    </script>
    
</body>

</html>