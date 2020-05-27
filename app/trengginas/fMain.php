
<?php
 $root_lib=$_SERVER["DOCUMENT_ROOT"];
 if (substr($root_lib,-1)!="/") {
     $root_lib=$root_lib."/";
 }
 include_once($root_lib.'app/trengginas/setting.php');

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="<?=$root?>/assets/img/trengginas-logo.png">
        <title>PT Trengginas Jaya</title>
        <!-- This page CSS -->
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="<?=$folder_assets?>/icons/font-awesome/css/fa-brands.css" rel="stylesheet">
        <link href="<?=$folder_assets?>/icons/font-awesome/css/fa-regular.css" rel="stylesheet">
        <link href="<?=$folder_assets?>/icons/font-awesome/css/fa-solid.css" rel="stylesheet">
        <link href="<?=$folder_assets?>/icons/font-awesome/css/fontawesome.css" rel="stylesheet">
        <link href="<?=$folder_assets?>/icons/font-awesome/css/fontawesome-all.css" rel="stylesheet">
        <link href="<?=$folder_assets?>/icons/font-awesome/webfonts/fa-solid-900.woff2" rel="stylesheet">
        <link href="<?=$folder_assets?>/icons/font-awesome/webfonts/fa-solid-900.ttf" rel="stylesheet">
        <link href="<?=$folder_assets?>/icons/font-awesome/webfonts/fa-solid-900.woff" rel="stylesheet">
        
        <!--Toaster Popup message CSS -->
        <link href="<?=$folder_assets?>/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
       
        <!-- <link href="<?=$folder_assets?>/node_modules/owl.carousel/owl.carousel.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=$folder_assets?>/node_modules/owl.carousel/owl.theme.default.css" rel="stylesheet" type="text/css" /> -->
        <!-- Custom CSS -->
        <link href="<?=$folder_assets?>/dist2/css/style.min.css" rel="stylesheet">

        <link href="<?=$folder_css?>/sai.css" rel="stylesheet">
        <link rel="stylesheet" href="<?=$folder_js?>/swal/sweetalert2.min.css">
        <style>
        #carousel-bidang .carousel-indicators{
            text-align: left;
            float: left;
            left: -10% !important;
            right: inherit;
        }

        .nama-klien{
            color: #DD1F1A;
            /* font-weight: 600;    */
        }

        #bidang-usaha .image img{
            width: 50%;
            height: 100%;
            background:red;
            box-sizing: border-box;
        }

        #bidang-usaha .content{
            width: 50%;
            height: 100%;
            background:red;
            box-sizing: border-box;
        }

        .page-jasa-desc{
            /* position: absolute; */
            background-color: #fff;
            left:-5%;
            height: 80%;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 10px;
            /* top:10%; */
            /* display: inline-block; */
            /* display: inline-block; */
            /* vertical-align: middle; */
        }

        .page-jasa{
            margin-right: 50px;
            margin-left: 50px;
            padding-top: 50px;
            padding-bottom: 100px;
        }

        .row-jasa{
            
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .page-jasa img{
            /* flex-shrink: 0; */
            width: 100%;
            /* height: auto;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center; */
            max-width: 100%;
            max-height: 100%;
            object-fit: fill;
        }

        .page-jasa h1{
            font-weight: 600;
        }

        .page-jasa li.active a{
            color: red !important;
            font-weight: 700;
        }

        .page-jasa li a{
            color: #000 ;
            font-size: 20px;
        }

        .carousel-item {
        height: 92vh;
        min-height: 350px;
        background: no-repeat center center scroll;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        }

        #bidang .carousel-item {
        height: 50vh;
        min-height: 350px;
        background: no-repeat center center scroll;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        }

        nav li.active{
            border-radius: 25px;
            background: red;
        }

        nav li.active a{
            color:white !important;
        }

        .carousel li.active{
            background-color: red !important;
        }

        .carousel li{
            background-color:white !important;
        }

        #bidang .carousel-caption {
        width:200px;
        z-index:10;
        color:#fff;
        text-align:center;
        top:5%;
        margin-top: 20px;
        padding-top: 10px;
        padding-bottom: 10px;
        background-color: red;
        left:0%;
        bottom:auto;
        -webkit-transform:translate(0, -50%);
        -ms-transform:translate(0, -50%);
        transform:translate(0, -50%);
        }

        .carousel-caption {
        width:200px;
        z-index:10;
        color:#fff;
        text-align:center;
        top:50%;
        left:10%;
        bottom:auto;
        -webkit-transform:translate(0, -50%);
        -ms-transform:translate(0, -50%);
        transform:translate(0, -50%);
        }
             @font-face {
                font-family: "Roboto";
                src: url(<?=$root?>'/assets/fonts/Roboto/Roboto-Regular.ttf');
            }
              
            body {
                font-family: 'Roboto', sans-serif !important;
            }
        </style>
        <script src="<?=$folderroot_js?>/jquery-3.4.1.js" ></script>
        <script src="<?=$folder_assets?>/node_modules/popper/popper.min.js"></script>
        <script src="<?=$folder_assets?>/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="<?=$folder_assets?>/dist/js/perfect-scrollbar.jquery.min.js"></script>
        <!--Wave Effects -->
        <script src="<?=$folder_assets?>/dist/js/waves.js"></script>
        <script src="<?=$folder_assets?>/dist2/js/custom.min.js"></script>
        <!-- Popup message jquery -->
        <script src="<?=$folder_assets?>/node_modules/toast-master/js/jquery.toast.js"></script>
        <script src="<?=$folder_js?>/swal/sweetalert2.all.min.js"></script>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <!-- <div class="container"> -->
            <a class="navbar-brand" href="#">
                <img src="<?=$root?>/assets/img/trengginas-logo.png" alt="logo" style='width:30px'>
                <span class="pl-3" style="font-size: 25px;color: #DD1F1A;"> PT TRENGGINAS JAYA </span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#jasa">Jasa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#perusahaan">Perusahaan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#masuk">Masuk</a>
                </li>
            </ul>
            </div>
        <!-- </div> -->
        </nav>
        <!-- <div class="wrapper">
            <div class="container-fluid">
                <div class="navbar-fixed py-0 px-0" >
                        <div class="header">
                            <nav class="navbar navbar-expand-md navbar-light bg-white pl-4">
                                <a class="navbar-brand" href="#">
                                <img src="<?=$root?>/assets/img/trengginas-logo.png" alt="logo" style='width:30px'>
                                <span class="pl-3" style="font-size: 25px;color: #DD1F1A;"> PT TRENGGINAS JAYA </span>
                            </a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav ml-auto">
                                        <li class="nav-item">
                                            <a class="nav-link" href="#beranda">Beranda</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#jasa">Jasa</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#perusahaan">Perusahaan</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#masuk">Masuk</a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                </div>
            </div>
         </div> -->
        <div class="page-wrapper">
            <div id="examplecarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                <!-- Slide One - Set the background image for this slide in the line below -->
                <div class="carousel-item active" style="background-image: url('https://source.unsplash.com/LAaSoL0LrYs/1920x1080')">
                    <div class="carousel-caption d-none d-md-block">
                    <h2 class="display-4">First Slide</h2>
                    <p class="lead">This is a description for the first slide.</p>
                    </div>
                </div>
                <!-- Slide Two - Set the background image for this slide in the line below -->
                <div class="carousel-item" style="background-image: url('https://source.unsplash.com/bF2vsubyHcQ/1920x1080')">
                    <div class="carousel-caption d-none d-md-block">
                    <h2 class="display-4">Second Slide</h2>
                    <p class="lead">This is a description for the second slide.</p>
                    </div>
                </div>
                <!-- Slide Three - Set the background image for this slide in the line below -->
                <div class="carousel-item" style="background-image: url('https://source.unsplash.com/szFUQoyvrxM/1920x1080')">
                    <div class="carousel-caption d-none d-md-block">
                    <h2 class="display-4">Third Slide</h2>
                    <p class="lead">This is a description for the third slide.</p>
                    </div>
                </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                    </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                    </a>
            </div>
        </div>

        <div class="page-jasa">
            <!-- <div class="container"> -->
                <h1>Jasa Kami</h1>
                <div class="row row-jasa">
                    <div class="col-4">
                        <ul style="list-style-type:none;padding-left:0;">
                            <li class="active">
                                <a href="#">
                                Security
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                Network Processor Supervisor
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                Catering
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                Academical Assistant
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                Cleaning Service
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                Building Maintenance
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                Car Rental
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                Parking Service
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-4 justify-content-center align-items-center">
                        <img src="https://source.unsplash.com/bF2vsubyHcQ/1920x1080" alt="logo">
                    </div>
                    <div class="col-4 page-jasa-desc">
                        Keamanan merupakan faktor penting Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam veritatis quibusdam amet eaque animi laboriosam magni explicabo sit velit fugiat consectetur, quas, error accusantium exercitationem deserunt officia dicta autem cupiditate!
                    </div>
                </div>
            <!-- </div> -->
        </div>

        <section id="bidang">
        <div id="carousel-bidang" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                <!-- Slide One - Set the background image for this slide in the line below -->
                <div class="carousel-item active" style="background-image: url('https://source.unsplash.com/LAaSoL0LrYs/1920x1080')">
                    <div class="carousel-caption d-none d-md-block">
                    BIDANG USAHA
                    <!-- <p class="lead">This is a description for the first slide.</p> -->
                    </div>
                </div>
                <!-- Slide Two - Set the background image for this slide in the line below -->
                <div class="carousel-item" style="background-image: url('https://source.unsplash.com/bF2vsubyHcQ/1920x1080')">
                    <div class="carousel-caption d-none d-md-block">
                    BIDANG USAHA
                    <!-- <p class="lead">This is a description for the second slide.</p> -->
                    </div>
                </div>
                <!-- Slide Three - Set the background image for this slide in the line below -->
                <div class="carousel-item" style="background-image: url('https://source.unsplash.com/szFUQoyvrxM/1920x1080')">
                    <div class="carousel-caption d-none d-md-block">
                    BIDANG USAHA
                    <!-- <p class="lead">This is a description for the third slide.</p> -->
                    </div>
                </div>
                </div>
            </div>
        </section>

        <section id="klien">
            <div class="page-wrapper">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <center>
                                <h1>KLIEN</h1>
                            </center>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque commodi sapiente accusantium aperiam at provident autem quos alias laborum, quibusdam exercitationem a sit magni officiis libero accusamus assumenda rerum quam.
                            </div>
                            <div class="col-8">
                                <div class="row">
                                    <div class="nama-klien">
                                        <h4>Nama Klien</h1>
                                    </div>
                                </div>
                                <div class="row jabatan">
                                    Jabatan 
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- <section id="bidang-usaha">
            <div class="image">
                <img src="https://source.unsplash.com/bF2vsubyHcQ/1920x1080" alt="">
            </div>
            <div class="content">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam facilis optio minima eum neque natus quod eaque, ad facere iure ratione veritatis beatae minus esse qui perferendis veniam asperiores adipisci!
            </div>
        </section> -->

    <!-- Footer -->
    <section id="footer">
            <div class="container">
                <div class="row text-center text-xs-center text-sm-left text-md-left">
                    <div class="col-xs-12 col-sm-4 col-md-4">
                        <h3>KONTAK KAMI</h3>
                        <ul class="list-unstyled quick-links">
                            <li><a href="javascript:void();"><i class="fa fa-angle-double-right"></i>Home</a></li>
                            <li><a href="javascript:void();"><i class="fa fa-angle-double-right"></i>About</a></li>
                            <li><a href="javascript:void();"><i class="fa fa-angle-double-right"></i>FAQ</a></li>
                            <li><a href="javascript:void();"><i class="fa fa-angle-double-right"></i>Get Started</a></li>
                            <li><a href="javascript:void();"><i class="fa fa-angle-double-right"></i>Videos</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-8">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-5">
                        <ul class="list-unstyled list-inline social text-center">
                            <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-facebook-square"></i></a></li>
                            <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-twitter"></i></a></li>
                            <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-instagram"></i></a></li>
                            <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-google-plus"></i></a></li>
                            <li class="list-inline-item"><a href="javascript:void();" target="_blank"><i class="fa fa-envelope"></i></a></li>
                        </ul>
                    </div>
                    </hr>
                </div>	
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center text-white">
                        <p><u><a href="https://www.nationaltransaction.com/">National Transaction Corporation</a></u> is a Registered MSP/ISO of Elavon, Inc. Georgia [a wholly owned subsidiary of U.S. Bancorp, Minneapolis, MN]</p>
                        <p class="h6">&copy All right Reversed.<a class="text-green ml-2" href="https://www.sunlimetech.com" target="_blank">Sunlimetech</a></p>
                    </div>
                    </hr>
                </div>	
            </div>
        </section>
        <!-- ./Footer -->
    </body>
</html>
