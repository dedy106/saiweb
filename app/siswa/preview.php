<?php
//   $root="http://".$_SERVER['SERVER_NAME'];
    session_start();
    $root2=$_SERVER["DOCUMENT_ROOT"];
    $root="http://".$_SERVER['SERVER_NAME'];
    $folder_css=$root."/vendor/asset_elite/dist/css";
    $folder_js=$root."/vendor/asset_elite/dist/js";
    $folder_assets=$root."/vendor/asset_elite";
    $folder_upload=$root."/vendor/asset_elite/upload";
    $folderroot_css=$root."/assets/css";
    $folderroot_js=$root."/assets/js";
    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];
    $root_ser="http://".$_SERVER['SERVER_NAME']."/server/siswa";
    $root_app="http://".$_SERVER['SERVER_NAME']."/siswa_main";
    $root_log="http://".$_SERVER['SERVER_NAME']."/siswa";
    if(!$_SESSION['isLogedIn']){
        echo "<script>alert('Harap login terlebih dahulu !'); window.location='$root_log';</script>";
    }
    include_once($root2.'lib/helpers.php');
    include_once($root2.'lib/koneksi5.php');
    // include_once($root2.'/web/setting.php');
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
    <title>SAKU | Sistem Akuntansi Keuangan Digital</title>
    <!-- This page CSS -->
    <!-- Font Awesome CSS -->
    <link href="<?=$folder_assets?>/icons/font-awesome/css/fa-brands.css" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/css/fa-regular.css" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/css/fa-solid.css" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/css/fontawesome.css" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/css/fontawesome-all.css" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/webfonts/fa-solid-900.woff2" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/webfonts/fa-solid-900.ttf" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/webfonts/fa-solid-900.woff" rel="stylesheet">
    
    <link href="<?=$folder_css?>/style.min.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="<?=$folder_css?>/pages/dashboard1.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=$folder_assets?>/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <!-- Select 2 -->
    <link href="<?=$folder_assets?>/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- SAI CSS -->
    <link href="<?=$folder_css?>/sai.css" rel="stylesheet">
    
    <!-- <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.6/dist/sweetalert2.min.css">
     <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="<?=$folder_assets?>/node_modules/popper/popper.min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?=$folder_assets?>/dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="<?=$folder_assets?>/dist/js/waves.js"></script>
    <!--stickey kit -->
    <script src="<?=$folder_assets?>/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <!--Menu sidebar -->
    <script src="<?=$folder_assets?>/dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?=$folder_assets?>/dist/js/custom.min.js"></script>
    <!--morris JavaScript -->
    <script src="<?=$folder_assets?>/node_modules/raphael/raphael-min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/morrisjs/morris.min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
   
    <!-- Popup message jquery -->
    <script src="<?=$folder_assets?>/node_modules/toast-master/js/jquery.toast.js"></script>
    <!-- Datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.6/dist/sweetalert2.all.min.js"></script>
</head>

<body class="skin-default fixed-layout">
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
    <div id="main-wrapper" style='color:black'>
        <div class="page-wrapper" style='min-height: 674px;margin: 0;padding: 10px;background: white;color: black !important;'>
            <section class="content" id='ajax-content-section' style='color:black !important'>
                    <?php
                    if(ISSET($_GET['hal'])){
                        
                        include_once($_GET['hal']); 

                    }
                    
                    ?>
            </section>
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    
</body>

</html>