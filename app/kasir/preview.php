<?php
     session_start();
     $root_lib=$_SERVER["DOCUMENT_ROOT"];
     if (substr($root_lib,-1)!="/") {
         $root_lib=$root_lib."/";
     }
     include_once($root_lib.'app/kasir/setting.php');
 
 
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

<body class="skin-default fixed-layout" >
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