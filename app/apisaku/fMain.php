<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/apisaku/setting.php');


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
    
    <!-- chartist CSS -->
    <link href="<?=$folder_assets?>/node_modules/morrisjs/morris.css" rel="stylesheet">
     
    <!--Toaster Popup message CSS -->
    <link href="<?=$folder_assets?>/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=$folder_css?>/style.min.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="<?=$folder_css?>/pages/dashboard1.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=$folder_assets?>/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <!-- Select 2 -->
    <link href="<?=$folder_assets?>/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- SAI CSS -->
    <link href="<?=$folder_css?>/sai.css" rel="stylesheet">
    
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.6/dist/sweetalert2.min.css"> -->
    
    <link rel="stylesheet" href="<?=$folder_js?>/swal/sweetalert2.min.css">
    
    <!-- <link href="<?php //echo $folder_js?>/swal/sweetalert2.min.css" rel="stylesheet"> -->
    <!-- Quill Theme included stylesheets -->
    <!-- <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet"> -->
    <!-- Selectize -->
    <link href="<?=$folderroot_css?>/selectize.bootstrap3.css" rel="stylesheet">
    <!-- Datepicker -->
    <link rel="stylesheet" href="<?=$folderroot_css?>/bootstrap-datepicker.min.css">
    <!-- Tagify -->
    <link rel="stylesheet" href="<?=$folder_assets?>/tagify/dist/tagify.css">
    <style>
     .navbar-header{
            width:270px;
        }

        .left-sidebar{
            width:270px;
        }

        .sidebar-nav > ul > li > ul > li > a {
            padding: 7px 10px 7px 15px;
        }

        .footer, .page-wrapper {
            margin-left: 270px;
        }
        .page-wrapper{
            min-height: 600px !important;
        }

        .topbar .top-navbar .navbar-header {
            height: 50px;
            line-height: 50px;
        }
        .topbar .top-navbar,.navbar-collapse,.navbar-nav {
            height: 50px;
        }

        .left-sidebar {
            padding-top: 50px !important;
        }

        .fixed-header .page-wrapper, .fixed-layout .page-wrapper {
            padding-top: 50px !important;
        }

        .topbar .top-navbar .navbar-nav > .nav-item > .nav-link {
            line-height: 35px !important;
        }

        .selectize-input, input.form-control , .custom-file-label{
            border-color:#929090;
        }

        .error input.form-control{
            border-color: #e46a76; 
        }

        .validate input.form-control {
            border-color: #00c292; 
        }

        input.form-control:focus{
            border-color:#929090;
        }
    </style>
    <script src="<?=$folderroot_js?>/highcharts2.js"></script>
    <!-- <script src="js/highcharts-3d.js"></script> -->
    <!-- <script src="https://code.highcharts.com/modules/bullet.js"></script> -->
    <script src="<?=$folderroot_js?>/highcharts-more.js"></script>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!-- ===================================================================== -->
    <!-- ================================JS=================================== -->
    <!-- ===================================================================== -->

        
    <!-- All Jquery -->
    <!-- <script src="<?=$folder_assets?>/node_modules/jquery/jquery-3.2.1.min.js"></script> -->
    <!-- ============================================================== -->
    <!-- <script src="<?=$folder_assets?>/node_modules/jquery/jquery-3.2.1.min.js"></script> -->
    <script src="<?=$folderroot_js?>/jquery-3.4.1.js" ></script>
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
    <!-- Select 2 -->
    <script src="<?=$folder_assets?>/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <!--Menu sidebar -->
    <script src="<?=$folder_assets?>/dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?=$folder_assets?>/dist/js/custom.min.js"></script>
    <!-- <script src="js/selectize.min.js"></script> -->
    <script src="<?=$folderroot_js?>/standalone/selectize.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
    <script src="<?=$folder_assets?>/node_modules/raphael/raphael-min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/morrisjs/morris.min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
   
    <!-- Popup message jquery -->
    <script src="<?=$folder_assets?>/node_modules/toast-master/js/jquery.toast.js"></script>
    <!-- Datepicker -->
    <script src="<?=$folderroot_js?>/bootstrap-datepicker.min.js"></script>
    <!-- Chart JS -->
    <!-- <script src="<?=$folder_js?>/dashboard1.js"></script> -->
    <!-- JS Tagify -->
    <!-- <script src="<?=$folder_assets?>/tagify/dist/tagify.js"></script>
    <script src="<?=$folder_assets?>/tagify/dist/tagify.min.js"></script>
    <script src="<?=$folder_assets?>/tagify/dist/jQuery.tagify.min.js"></script> -->

    <!-- sweet alert -->
    <!-- <script src="sweetalert2.all.min.js"></script> -->
    <!-- <script src="/swal/sweetalert2.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.6/dist/sweetalert2.all.min.js"></script>
     -->
    <script src="<?=$folder_js?>/swal/sweetalert2.all.min.js"></script>
    
    <!-- This is data table -->
    <script src="<?=$folder_assets?>/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <!-- <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.19/sorting/formatted-numbers.js"></script> -->

    
    <script src="<?=$folderroot_js?>/printThis/printThis.js"></script>
    <script src="<?=$folderroot_js?>/jquery.tableToExcel.js"></script>
    <script src="<?=$folderroot_js?>/jquery.twbsPagination.min.js"></script>
    
    
    <script src="<?=$folderroot_js?>/inputmask.js"></script>
    <script src="<?=$folderroot_js?>/sai.js"></script>

    <!-- Tiny Editor -->
    <script src="<?=$folder_assets?>/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- end - This is for export functionality only -->
    <!-- Main Quill library -->
    <!-- <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script> -->
    <!-- End Main Quill Libary -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    
    
<![endif]-->
<script src="<?=$folder_assets?>/tagify/dist/tagify.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.selectize').selectize();
            });
            tinymce.init({
                selector: '#isi',
                plugins: 'print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
                // plugins: 'fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists help',
                menubar: 'file edit view insert format tools table tc help',
                // images_upload_url: '<?=$root_ser?>/imageUpload.php',
                paste_data_images: true,
                height: 450,
                toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment'
            });
        </script>
        
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
    <div id="main-wrapper">

        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?=$root_app?>/">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <!-- <img src="<?=$folder_assets?>/images/sai_icon/esaku-landscape2.png" alt="homepage" class="dark-logo" /> -->
                            <!-- Light Logo icon -->
                            <img src="<?=$folder_assets?>/images/sai_icon/logo.png" width="30px" alt="homepage" class="light-logo" />
                            <!-- <img src="../assets/images/logo-light-icon.png" alt="homepage" class="light-logo" /> -->
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                         <!-- dark Logo text -->
                         <!-- <img src="../assets/images/logo-text.png" alt="homepage" class="light-logo" /> -->
                         <!-- Light Logo text -->    
                         <img src="<?=$folder_assets?>/images/sai_icon/logo-text.png" width="90px"  class="light-logo" alt="homepage" /></span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <!-- <li class="nav-item">
                            <form class="app-search d-none d-md-block d-lg-block">
                                <input type="text" class="form-control" placeholder="Search & enter">
                            </form>
                        </li> -->
                        <li class="nav-item"> 
                        <h3 style='line-height:50px;color:white'> 
                        <?=$_SESSION['namalokasi']?></h3>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti-email"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)">
                                                <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Luanch Admin</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span> </div>
                                            </a> -->
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)">
                                                <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Event today</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span> </div>
                                            </a> -->
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)">
                                                <div class="btn btn-info btn-circle"><i class="ti-settings"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Settings</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span> </div>
                                            </a> -->
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a> -->
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                     
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- mega menu -->
                        <!-- ============================================================== -->
                        
                        <!-- ============================================================== -->
                        <!-- End mega menu -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- User Profile-->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown u-pro">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?=$folder_assets?>/images/users/1.jpg"  alt="user" class=""> <span class="hidden-md-down">Mark &nbsp;<i class="fa fa-angle-down"></i></span> </a>
                            <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                <!-- text-->
                                <a href="javascript:void(0)" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                                <!-- text-->
                                <div class="dropdown-divider"></div>
                                <!-- text-->
                                <a href="<?=$root_ser?>/cLogin.php?fx=logout" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                                <!-- text-->
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End User -->
                        <!-- ============================================================== -->
                        <!-- <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li> -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper" style="min-height: 600px !important;">
            
            <section class="content" id='ajax-content-section'>
                <div class="body-content">
                </div>
            </section>
            <script>
                        
                var $request = "<?=$_SERVER['REQUEST_URI']?>";
                var $request2 = $request.split("/");
                var form = $request2[2];
                var param = "<?=$_GET['param']?>";
                var pmb = "<?=$_GET['pmb']?>";
                var stsPrint = "<?=$_GET['print']?>";

                function loadForm(url){
                    $('.body-content').load(url);
                }

                function loadMenu(){
                    var kodeMenu='<?=$_SESSION['kodeMenu']?>';
                    $.ajax({
                        type: 'POST',
                        url: '<?=$root_ser?>/Menu.php?fx=loadMenu',
                        dataType: 'json',
                        async:false,
                        data: {'kodeklp':kodeMenu},
                        success:function(result){
                            if(result.status){
                                $('#sidebarnav').html('');
                                $(result.hasil).appendTo('#sidebarnav').slideDown();
                            }
                        }
                    });
                }
                
                if(form !="" || form != "-"){
                    loadForm("<?=$root?>/app/apisaku/"+form+".php")
                }

                loadMenu();
                
                $('.sidebar-nav').on('click','.a_link',function(e){
                    e.preventDefault();
                    var url = $(this).data('href');
                    var tmp = url.split("/");
                    if(tmp[5] == "" || tmp[5] == "-"){
                        // alert('Form dilock!');
                        return false;
                    }else{
                        loadForm("<?=$root?>/app/apisaku/"+tmp[5]+".php");

                    }
                    // alert(tmp[4]);
                });
            </script>
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
       <?php
            include $folder_assets.'/dist/php/footer.php';
        ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    
</body>
<?php
	
    if(!$_SESSION['isLogedIn']){
        // if(ISSET($_SESSION['user'])){
        //     $user=$_SESSION['user'];
        //     echo"<script>alert('Session timeout !. Klik Ok to continue.'); 
        //     window.location='$root_ser/cLogin.php?fx=autoLogin&nik=$user';
        //     </script>";
        // }else{

            echo "<script>
            alert('Harap login terlebih dahulu !'); 
            window.location='$root_log';
            </script>";
        // }
    }
?>

</html>