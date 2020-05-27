<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/sakube/setting2.php');

    function getTanggal(){

        // if(date('l')=="Sunday"){
        //     $hari="Minggu, ";
        // }elseif (date('l')=="Monday") {
        //     $hari= "Senin, ";
        // }elseif (date('l')=="Tuesday") {
        //     $hari= "Selasa, ";
        // }elseif (date('l')=="Wednesday") {
        //     $hari= "Rabu, ";
        // }elseif (date('l')=="Thursday") {
        //     $hari= "Kamis, ";
        // }elseif (date('l')=="Friday") {
        //     $hari= "Jum'at, ";
        // }elseif (date('l')=="Saturday") {
        //     $hari= "Sabtu, ";
        // }else{
        //     $hari="-";
        // }

        if (date('m')==1){
            $bulan= "Januari ";
        }elseif (date('m')==2) {
            $bulan= "Februari ";
        }elseif (date('m')==3) {
            $bulan= "Maret ";
        }elseif (date('m')==4) {
            $bulan= "April ";
        }elseif (date('m')==5) {
            $bulan= "Mei ";
        }elseif (date('m')==6) {
            $bulan= "Juni ";
        }elseif (date('m')==7) {
            $bulan= "Juli ";
        }elseif (date('m')==8) {
            $bulan= "Agustus ";
        }elseif (date('m')==9) {
            $bulan= "September ";
        }elseif (date('m')==10) {
            $bulan= "Oktober ";
        }elseif (date('m')==11) {
            $bulan= "November ";
        }elseif (date('m')==12) {
            $bulan= "Desember ";
        }else{
            $bulan= "-";
        }

        echo "<div class='row align-items-center'>
        <div class='col-3' style='font-size:40px;'>
            ".date('d')."
        </div>
        <div class='col-9'>
            <div class='row'>    
        ".
            $bulan."
            </div>
            <div class='row'>    
            ".
                date('Y')."
                </div>
        </div>
    </div>";
        
        // $tanggal="<p style='40px'>".date('d ')."</p>".$bulan.date('Y');
        
        // echo $tanggal;
    }

    function getPeriodee(){
        $tahun=substr($_SESSION['periode'],0,4);
        $bulan=substr($_SESSION['periode'],4);
        if ($bulan){
            $bulan= "Januari ";
        }elseif ($bulan=="02") {
            $bulan= "Februari ";
        }elseif ($bulan=="03") {
            $bulan= "Maret ";
        }elseif ($bulan=="04") {
            $bulan= "April ";
        }elseif ($bulan=="05") {
            $bulan= "Mei ";
        }elseif ($bulan=="06") {
            $bulan= "Juni ";
        }elseif ($bulan=="07") {
            $bulan= "Juli ";
        }elseif ($bulan=="08") {
            $bulan= "Agustus ";
        }elseif ($bulan=="09") {
            $bulan= "September ";
        }elseif ($bulan=="10") {
            $bulan= "Oktober ";
        }elseif ($bulan=="11") {
            $bulan= "November ";
        }elseif ($bulan=="12") {
            $bulan= "Desember ";
        }else{
            $bulan= "-";
        }
        echo $bulan." ".$tahun;
    }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>SAKU - Admin Dashboard</title>

    <!-- Selectize -->
    <link href="<?=$folderroot_css?>/selectize.bootstrap3.css" rel="stylesheet">
    <!-- Sweet Alert CSS -->
    <link rel="stylesheet" href="<?=$folder_assets?>/swal/sweetalert2.min.css">
    <!-- Bootstrap CSS CDN -->
    <link href="<?=$folder_bootstrap?>/css/bootstrap.min.css" rel="stylesheet">
    <!-- Datatables CSS -->
    <!-- <link rel="stylesheet" type="text/css" href="<?=$folder_assets?>/datatables/datatables.css"> -->
    <!-- Datatables Button CSS -->
    <link rel="stylesheet" href="<?=$folder_assets?>/datatables/datatables/buttons/css/buttons.dataTables.min.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="<?=$folder_css?>/sakube.css">
    <link rel="stylesheet" href="<?=$folder_css?>/toggle.scss">
    <style>
        
    </style>
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="<?=$folder_bootstrap?>/css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="<?=$folder_bootstrap?>/css/font-awesome.min.css">

    <!-- jQuery CDN - full version (=with AJAX) -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="<?=$folder_assets?>/datatables/datatables/css/dataTables.bootstrap4.min.css">
    <script src="<?=$folder_bootstrap?>/js/jquery-3.4.1.js"></script>
    <script src="<?=$folder_assets?>/datatables/datatables/js/jquery.dataTables.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script> -->

    <!-- 

    ====================== DATATABLES BOOTSTRAP 4 RESPONSIVE PACKAGE
        <link rel="stylesheet" href="<?=$folder_assets?>/datatables/datatables/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="<?=$folder_assets?>/datatables/responsive/css/responsive.bootstrap4.min.css">
        <script src="<?=$folder_bootstrap?>/js/jquery-3.4.1.js"></script>
        <script src="<?=$folder_assets?>/datatables/datatables/js/jquery.dataTables.min.js"></script>
        -->
        <script src="<?=$folder_assets?>/datatables/datatables/js/dataTables.bootstrap4.min.js"></script>
        <!-- <script src="<?=$folder_assets?>/datatables/fixedheader/js/dataTables.fixedHeader.min.js"></script> -->
        <script src="<?=$folder_assets?>/datatables/datatables/js/dataTables.responsive.min.js"></script>
        <script src="<?=$folder_assets?>/datatables/datatables/js/responsive.bootstrap4.min.js"></script>
        <!-- <script scr="<?=$folder_assets?>/datatables/responsive/js/dataTables.responsive.min.js"></script> -->
        <!-- <script scr="<?=$folder_assets?>/datatables/responsive/js/responsive.bootstrap4.min.js"></script> -->
    
    <!-- jQuery Custom Scroller CDN -->
    <script src="<?=$folder_bootstrap?>/js/jquery.mCustomScrollbar.concat.min.js"></script>

    <!-- Selectize JS -->
    <script src="<?=$folderroot_js?>/standalone/selectize.min.js"></script>
    <!-- Sweet Alert JS -->
    <script src="<?=$folder_assets?>/swal/sweetalert2.all.min.js"></script>
    <!-- Font Awesome JS -->
    <script defer src="<?=$folder_bootstrap?>/js/solid.js"></script>
    <script defer src="<?=$folder_bootstrap?>/js/fontawesome.js"></script>
    
    
    <!-- Popper.JS -->
    <script src="<?=$folder_bootstrap?>/js/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="<?=$folder_bootstrap?>/js/bootstrap.js"></script>
    <!-- Datatables JS -->
    <!-- <script type="text/javascript" charset="utf8" src="<?=$folder_assets?>/datatables/datatables.js"></script> -->
    
    <!-- Datatables Button -->
    <!-- <script type="text/javascript" charset="utf8" src="<?=$folder_assets?>/datatables/datatables/buttons/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="<?=$folder_assets?>/datatables/datatables/buttons/js/buttons.flash.min.js"></script>
    <script type="text/javascript" charset="utf8" src="<?=$folder_assets?>/datatables/datatables/jszip/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="<?=$folder_assets?>/datatables/datatables/pdfmake/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="<?=$folder_assets?>/datatables/datatables/pdfmake/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8" src="<?=$folder_assets?>/datatables/datatables/buttons/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="<?=$folder_assets?>/datatables/datatables/buttons/js/buttons.print.min.js"></script> -->
    
</head>

<body>
<div class="loading" style="display: none;">
</div>
<div class="loading-text" style="display: none;">
Selamat Bekerja
</div>
<div class="overlay"></div>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header" style="padding:.65rem; height:10vh;">
            <div class="row align-items-center justify-content-center" >
                <div class="col-2">
                <img src="<?=$folder_assets?>/assets/icons/InfoKeuangan.svg" height="25px" width="25px" alt="Info Keuangan Logo" title="Info Keuangan"> 
                </div>
                <div class="col-7 pl-4">
                    <div class="row"  style="font-size: 14px;">
                        <b>
                            <?php
                                getPeriodee();
                            ?>
                        </b>
                    </div>
                    <div class="row"  style="font-size: 12px;">
                            <?php
                                if($_SESSION['kodePP']==null||$_SESSION['kodePP']==""){
                                    echo "Lokasi -";
                                }else{
                                    echo "Lokasi ".$_SESSION['kodePP'];
                                }
                            ?>
                    </div>
                </div>
                <div class="col-3 ">
                    <a href="#" id="sidebarCollapse" class="sidebarCollapseHide">
                        <img src="<?=$folder_assets?>/assets/icons/BackButton.svg" height="25px" width="25px" alt="Hide" title="Hide"> 
                    </a>
                </div>
            </div>
            
            <!-- <img src="<?=$folder_assets?>/img/logo.png" width="30px" alt="homepage"/>
            <img src="<?=$folder_assets?>/img/logo-text.png" width="90px" alt="homepage" /></span> </a> -->
            </div>
            <!-- <div class="dropdown-divider"></div> -->
            <center>
            <!-- <div class="can-toggle">
            <input id="b" type="checkbox">
            <label for="b">
                <div class="can-toggle__switch" data-checked="Transaksi" data-unchecked="Laporan"></div>
            </label>
            </div> -->
                <div class="jenis-menu btn-group btn-group-toggle bg-light " data-toggle="buttons" style="margin:0px 0px 0px 0px;padding:5px;border-radius:15px;width:90%;">
                    <label class="btn btn-primary active" style="border-radius:10px 10px 10px 10px;border-width:0px;font-size:14px;margin-right:2.5px;" id="btn-transaksi">
                        <input type="radio" name="options" value="transaksi" autocomplete="off" checked> Transaksi
                    </label>
                    <label class="btn btn-light" id="btn-laporan" style="border-radius:10px 10px 10px 10px;border-width:0px;font-size:14px;margin-left:2.5px;">
                        <input type="radio" name="options" value="laporan" id="btn-laporan" autocomplete="off"> Laporan
                    </label>
                </div>
            </center>

            <ul class="list-unstyled components" id="menu-sidebar">

            </ul>
        </nav>

        <nav id="sidebar-right" class="active">
            <section id="right-sidebarProfile" style="display: none;">
                <div class="container" style="padding:25px;">
                    <div class="row justify-content-end">
                        <button type="button" class="btn btn-primary btn-sm" style="background-color: white;color:#007aff;">Edit</button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-4 justify-content-center align-items-center">
                            <center>
                                <img src="data:image/jpeg;base64,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" class="profile-image-sm" style="border-color: #007aff;">
                            </center>
                        </div>
                        <div class="col-8 justify-content-center">
                            <div class="row mb-1">
                                We Bare Bears
                            </div>
                            <div class="row mt-1" >
                                12351215
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2 profile-title">
                        Jabatan
                    </div>
                    <div class="row profile-desc">
                        CFO
                    </div>
                    <div class="row mt-2 profile-title">
                        E-mail
                    </div>
                    <div class="row profile-desc">
                        golonganpertengahan@gmail.com
                    </div>
                    <div class="row mt-2 profile-title">
                        Nomor Ponsel
                    </div>
                    <div class="row profile-desc">
                        0875684387
                    </div>
                    <div class="row mt-2 profile-title">
                        Password
                    </div>
                    <div class="row profile-desc">
                        <div class="col-6" style="padding-left:0px;">
                            &#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;
                        </div>
                        <div class="col-4">
                            <span id="password-level" class="level-kuat">
                                Kuat
                            </span>
                        </div>
                        <div class="col-2">
                            <img src="<?=$root?>/vendor/sakube/assets/icons/eye.svg" width="20px" height="20px"  alt="show" id="show-hidePassword" style="cursor:pointer;">
                        </div>
                    </div>
                </div>
                <hr class="mt-4">
                <div class="container" style="padding:  0px 25px 0px 25px;">
                    <div class="row profile-title">
                        Pusat Pertanggungjawaban / PP
                    </div>
                </div>
                <hr>
                <div class="container" style="padding: 0px 25px 0px 25px;">
                    <div class="row profile-title">
                        Data Personal
                    </div>
                </div>
                <div class="container" style="padding: 5px 25px 5px 25px;position:fixed;bottom:0;background:black;">
                    <a href="<?=$root_ser?>/cLogin.php?fx=logout">
                        <div class="row">
                            Keluar
                        </div>
                    </a>
                </div>
                    
            </section>
            <section id="right-sidebarNotif" style="display: none;">
            <div class="container mt-2">

                <div class="sidebar-header">
                    <?php
                    getTanggal();
                    // setlocale(LC_ALL, 'IND');
                    // echo strftime('%A %B %Y');
    
                    // setlocale(LC_TIME, 'IND');  // or setlocale(LC_TIME, 'id_ID');
                    // $monthNum = sprintf("%02s", $row["month"]);
                    // $monthName = date("F", strtotime($monthNum));
                    // echo "".$monthName. " ";
                    // echo date('l, F Y');
                    ?>
                </div>
                <div class="sidebar-content">
                    Nama Modul
                </div>
                <div class="card" style="border-color: #7f7f7f;">
                    <div class="card-header active" style="background-color:#9f9f9f;">
                        KKN09876
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span> -->
                    </div>
                    <div class="card-body " style="background-color: #afafaf;">
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Incidunt tempore cupiditate est, velit impedit atque quas provident maxime quos facilis ut placeat saepe sint? Maxime aut minima cumque veniam? Ratione!    
                    </div>
                </div>
            </div>

            </section>
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light sticky-top">
                <div class="container-fluid">

                    <a href="#" id="sidebarCollapse" class="sidebarCollapseShow mr-2" style="display:none;">
                        <img src="<?=$folder_assets?>/assets/icons/menuButton.svg" height="25px" width="25px" alt="Show" title="Show Sidebar"> 
                        <!-- <i class="fas fa-align-justify" id="sidebar-hide-icon" ></i> -->
                        <!-- TODO : LOKASI -->
                        <!-- <span> -->
                        <!-- </span> -->
                    </a>
                    <span class="lokasi-title">
                        <?php
                        echo $_SESSION['namalokasi'];
                        ?>    
                    </span>
                    
                    <!-- <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button"  aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> -->
                    <!-- <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> -->
                        <!-- <i class="fas fa-align-justify"></i>
                    </button> -->

                    <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent"> -->
                    <!-- <ul class="nav navbar-nav ml-auto"> -->
                        <ul class="nav ml-auto">
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="right-sidebarCollapseProfile">
                                <!-- <span class="dropdown-text">Profile</span> -->
                                <span class="dropdown-text">
                                    <img src="data:image/jpeg;base64,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" class="profile-image-xs">
                                    
                                    <?php 
                                    echo $_SESSION['namaUser']; 
                                    // echo "<br>".$_SESSION['kodePP'];
                                    ?>
                                </span>
                            </a>
                            <div class="vl"></div>
                                <!-- <a class="nav-link dropdown-toggle" href="#profileCollapse" data-toggle="dropdown" role="button" aria-expanded="false" aria-controls="profileCollapse">Profile ^</a> -->
                                <!-- <div class="dropdown-menu" aria-labelledby="profileCollapse">
                                    <a href="#" class="dropdown-item">Gambar User</a>
                                    <a href="#" class="dropdown-item">Nama User</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="<?=$root_ser?>/cLogin.php?fx=logout" class="dropdown-item">Log Out</a>
                                </div> -->
                                
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" id="link-dashboard">
                                <img src="<?=$folder_assets?>/assets/icons/Dashboard.svg" height="25px" width="25px" alt="Dashboard" data-toggle="tooltip" data-placement="bottom" title="Dashboard"> 
                                <!-- <i class="menu-icon fa fa-desktop"></i> -->
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=$root_app?>/" target="_blank">
                                <img src="<?=$folder_assets?>/assets/icons/NewTab.svg" height="25px" width="25px" alt="New Tab" data-toggle="tooltip" data-placement="bottom" title="New Tab"> 
                                <!-- <i class="menu-icon fa fa-window-restore"></i>     -->
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" id="right-sidebarCollapseNotif">
                                    <!-- <i class="menu-icon fas fa-box"></i> -->
                                    <img src="<?=$folder_assets?>/assets/icons/Notif.svg" height="25px" width="25px" alt="Notif" data-toggle="tooltip" data-placement="bottom" title="Notifikasi"> 
                                </a>
                            </li>
                        </ul>
                    <!-- </div> -->
                </div>
            </nav>

            <!-- <div class="container-fluid"> -->
                <section id="content-body">
                
                </section>
            <!-- </div> -->

            <!-- <div class="content-wrapper">
                <div class="container-fluid"> -->
                    <!-- <div class="overlay"> -->
                    <!-- </div> -->
                <!-- </div>
            </div> -->
        </div>
    </div>

<script type="text/javascript">
    $('#content-body').load("http://saiweb.simkug.com/app/sakube/dashboard.php");
    // FUNCTION LOAD FORM
    function loadForm(url){
        $('#content-body').load(url);
    }

    $('#btn-transaksi').on('click',function(){
        $('#btn-transaksi').addClass('btn-primary');
        $('#btn-transaksi').removeClass('btn-light');
        $('#btn-laporan').addClass('btn-light');
        $('#btn-laporan').removeClass('btn-primary');
    });

    $('#btn-laporan').on('click',function(){
        $('#btn-laporan').addClass('btn-primary');
        $('#btn-laporan').removeClass('btn-light');
        $('#btn-transaksi').addClass('btn-light');
        $('#btn-transaksi').removeClass('btn-primary');
    });

// GET VARIABLE
    // var $request = "<?=$_SERVER['REQUEST_URI']?>";
    // var $request2 = $request.split("/");
    // var form = $request2[2];
    // var param = "<?=$_GET['param']?>";
    // var pmb = "<?=$_GET['pmb']?>";
    // var stsPrint = "<?=$_GET['print']?>";

    // if(form !="" || form != "-"){
    //     loadForm("<?=$root?>/app/sakube/"+form+".php")
    // }


// ON CLICK MENU
    $('#menu-sidebar').on('click','.a_link',function(e){
        e.preventDefault();
        var url = $(this).data('href');
        var breadcrumb = $(this).data('breadcrumb');
        console.log(breadcrumb);
        '<%$_SESSION["breadcrumb"] = "' + breadcrumb + '"; %>';
        var tmp = url.split("/");
        if(tmp[2] == "" || tmp[2] == "-"){
            // alert('Form dilock!');
            // return false;
        }else{
            loadForm("<?=$root?>/app/sakube/"+tmp[2]+".php");
            // window.localStorage.setItem('judulLayout',$('#menu-sidebar').on('click').html());
            // console.log(window.localStorage.getItem('judulLayout'));
        }
        // alert(tmp[3]);
    });

    // Menu Handler
    $(".btn-group-toggle input:radio").on('change', function() {
        let val = $(this).val();
        var kodeMenu = '<?=$_SESSION['kodeMenu']?>';
        if (val == 'transaksi') {
            var jenis= 'T';
            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/loadMenu.php?fx=loadMenu',
                dataType: 'json',
                async:false,
                data: {'kodeklp':kodeMenu,'jenis':jenis},
                success:function(result){
                    // console.log(result);
                    if(result.status){
                        $('#menu-sidebar').html('');
                        $(result.hasil).appendTo('#menu-sidebar').slideDown();
                        // $('#menu-sidebar').append(result.hasil).slideDown(500);
                    }
                }
            });
        }else{
            var jenis= 'L';
            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/loadMenu.php?fx=loadMenu',
                dataType: 'json',
                async:false,
                data: {'kodeklp':kodeMenu,'jenis':jenis},
                success:function(result){
                    // console.log(result);
                    if(result.status){
                        $('#menu-sidebar').html('');
                        $(result.hasil).appendTo('#menu-sidebar').slideDown();
                        // $('#menu-sidebar').append(result.hasil).slideDown('normal');
                    }
                }
            });
        }
    });

    $('#link-dashboard').on('click',function(){
        // console.log('<?=$root?>/app/sakube/dashboard.php');
        loadForm("<?=$root?>/app/sakube/dashboard.php");
    });

    $('#sidebarCollapse').on('click',function(){
        $('#sidebar-hide-icon').hide();
    });


    $(document).ready(function(){
        var kodeMenu='<?=$_SESSION['kodeMenu']?>';
        var jenis= 'T';
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/loadMenu.php?fx=loadMenu',
            dataType: 'json',
            async:false,
            data: {'kodeklp':kodeMenu,'jenis':jenis},
            success:function(result){
                console.log(result);
                if(result.status){
                    $('#menu-sidebar').html('');
                    $(result.hasil).appendTo('#menu-sidebar').slideDown();
                    // $('#menu-sidebar').append(result.hasil).slideDown('normal');
                }
            }
        });
    });
    
        if (!sessionStorage.isVisited) {
                $('.loading').show();
                $('.loading-text').show();
            $(document).ready(function(){
                sessionStorage.isVisited = 'true'
                $('.loading').fadeOut(3000);
                $('.loading-text').fadeOut(3000);
            });
        }
    $(document).ready(function () {


        $('[data-toggle="tooltip"]').tooltip()

        $("#sidebar").mCustomScrollbar({
            theme: "minimal"
        });

        // if($('#sidebar, $content').hasClass('active')){
        //     $('#sidebar, #content').removeClass('active');
        //     $('.sidebarCollapseHide').show();
        //     $('.sidebarCollapseShow').hide();
        // }else{
        //     $('#sidebar, #content').addClass('active');
        //     $('.sidebarCollapseShow').show();
        //     $('.sidebarCollapseHide').hide();
        // }

        $('.sidebarCollapseHide').on('click',function(){
            $('#sidebar, #content').addClass('active');
            $('.sidebarCollapseShow').show();
            $('.sidebarCollapseHide').hide();
        });

        $('.sidebarCollapseShow').on('click',function(){
            $('#sidebar, #content').removeClass('active');
            $('.sidebarCollapseHide').show();
            $('.sidebarCollapseShow').hide();
        });

        // $('#sidebarCollapse').on('click', function () {
        //     $('#sidebar, #content').toggleClass('active');
        //     // $('.collapse.in').toggleClass('in');
        //     // $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        // });

        
        
        $('#right-sidebarCollapseNotif').on('click', function () {
            $('#sidebar-right').toggleClass('active');
            $('#right-sidebarNotif').show();
            $('#right-sidebarProfile').hide();
            $('.overlay').toggleClass('active');
            // $('.collapse.in').toggleClass('in');
            // $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });

        $('#right-sidebarCollapseProfile').on('click', function () {
            $('#sidebar-right').toggleClass('active');
            $('#right-sidebarProfile').show();
            $('#right-sidebarNotif').hide();
            $('.overlay').toggleClass('active');
            // $('.collapse.in').toggleClass('in');
            // $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });
    });
    </script>

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
</body>



</html>