<?php
    $kode_lokasi='99';
    $root_ser="http://".$_SERVER['SERVER_NAME']."/server/esaku";
    $path = "http://".$_SERVER['SERVER_NAME']."/assets/img/esaku";
	$path2 = "http://".$_SERVER['SERVER_NAME']."/assets/css/esaku";
	$path3 = "http://".$_SERVER['SERVER_NAME']."/assets/js/esaku";
    $path4 = "http://".$_SERVER['SERVER_NAME']."/assets/fonts/esaku";
    
    $buatSlider = "http://".$_SERVER['SERVER_NAME']."/esaku";
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- ------------------------------------------------------------------------------------- -->
            <link rel="icon" type="image/png" href="<?= $path ?>/saku.png" sizes="32x32">
            <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
            <link href="<?= $path2 ?>/icss/navbar.css" rel="stylesheet" type="text/css">
            <link href="<?= $path2 ?>/icss/footer.css" rel="stylesheet" type="text/css">

            <link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet"/>
            <link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css" rel="stylesheet"/>
        <!-- ------------------------------------------------------------------------------------- -->

        <title>SAKU | Berita</title>
        <style>
            @font-face {
                font-family: "Font Mas Afnan";
                src: url('<?= $path4 ?>/SF-Pro-Text-Regular.otf');
            }

            body {
                font-family: 'Font Mas Afnan';
            }

            html::-webkit-scrollbar { 
                display: none;  // Safari and Chrome
            }

            #content {
                padding-top: 105px
            }

            html, body {
                max-width: 100%;
                overflow-x: hidden;
            }

            /* .col {
                border: solid;
            } */

            .link {
                cursor: pointer;
            }

            .item {
                /* background: red; */
                height: 70px;
            }

            .kiri {
                /* background: blue; */
                height: 70px;
                margin-left: 15px;
            }

            .kanan {
                /* background: green; */
                height: 70px;
                margin-right: 15px;
            }

            #text-kanan{
                font-size: 13px;
                position: relative;
                float: left;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: inherit;
                text-decoration: none;
            }

            #tCari2 {
                display: none;
                width: 250px;
            }

            #judul {
                font-size: 33px;
            }

            #bagikanAl {
                color: #4086F4;
            }

            #bagikeAl {
                width: 11%;
            }

            #iframeAl {
                height: 680px;
            }

            @media only screen and (max-width: 768px) {
                /* For mobile phones: */
                #tCari {
                    display: none;
                    /* max-width: 280px; */
                }

                #tCari2 {
                    display: inline;
                }

                #judul {
                    font-size: 15px;
                }

                #contentAl {
                    font-size: 10px !important;
                    text-align: justify;
                }

                #bagikanAl {
                    font-size: 13px;
                }

                #bagikeAl {
                    width: 5%;
                }

                #sliderOwl {
                    /* height: 20%;
                    width: 20%; */
                }

                .item {
                    /* background: red; */
                    height: 50px;
                }

                .kiri {
                    height: 50px;
                    padding-left: 7px;
                    padding-right: 7px;
                }

                .kanan {
                    /* height: 50px; */
                }

                #text-kanan {
                    display: none;
                }

                #iframeAl {
                    height: 200px;
                }
            }
        </style>
    </head>
    <body>
        <div class="col nutuPin" onclick="closeNav()" style="background: #000; opacity: 0.5; width: 20%; height: 100%; z-index: 3; display: block; position: fixed; left: 80%; transition: 0.5s;"></div>
        <div><?php include 'iNavbar.php' ?></div>

        <div id="tempat" value="vide"></div>
        
        <!-- -------------------------------------------------------------------------------------------------------------- -->
        <div class="container" id="content">
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col pb-3" id="judul"></div>
                    </div>
                    <div class="row">
                        <div class="col" id="vid" style="padding-left: 0 !important; padding-right: 0 !important"></div>
                    </div>
                    <div class="row mt-5">
                        <div class="col" id="bagikanAl">Bagikan</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <?php 
                                // Program to display current page URL. 
                                $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                                "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                                $_SERVER['REQUEST_URI'];  
                            ?>
                            <a href="http://www.facebook.com/sharer.php?u=<?= $link ?>" target="_blank"><img class="ml-4 mx-1" src="<?= $path ?>/berita/facebook-logo Blue.png" id="bagikeAl" alt=""></a>

                            <a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=Coba cek link ini deh <?= $link ?>" target="_blank" data-size="large"><img class="mx-1" src="<?= $path ?>/berita/twitter.png" id="bagikeAl" alt=""></a>
                            
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $link ?>&title=Create LinkedIn Share button on Website Webpages" target="_blank"><img class="mx-1" src="<?= $path ?>/berita/linkedin.png" id="bagikeAl" alt=""></a>

                            <img class="mx-1 link" src="<?= $path ?>/berita/link-symbol.png" id="bagikeAl" alt="" onclick="copyToClipboard('#p1')">
                            <p id="p1"><?= $link ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-5">
                        <div class="col" id="bagikanAl">Video Terkait</div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="iniSliderOwl"><hr></div>
            </div>
        </div>
        <!-- -------------------------------------------------------------------------------------------------------------- -->

        <div><?php include 'iFooter.php' ?></div>

        <script src="<?= $path3 ?>/ijs/navbar.js"></script>
        
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

        <!-- -------------------------------------------------------------------------------------------------------------- -->
            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
            <script>AOS.init();</script>
            <script>
                function copyToClipboard(element) {
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val($(element).text()).select();
                    document.execCommand("copy");
                    $temp.remove();

                    alert('Disalin ke papan klip');
                }
                $('#p1').hide();
                
                function setOwlSlider(){
                    $.ajax({
                        type: 'GET',
                        url: '<?=$root_ser?>/iArtikel.php?fx=getVid',
                        dataType: 'json',
                        data: {'kode_lokasi':'<?=$kode_lokasi;?>'},
                        success:function(result){
                            var html=`<div class="owl-carousel owl-theme">`;
                            if(result.status){
                                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                                    for(i=0;i<result.daftar.length;i++){
                                        var linknya="<?= $buatSlider ?>/iVideoUtama/?id="+result.daftar[i].no_konten;
                                        
                                        html+="<div class='item'>"+
                                                    "<div class='row'>"+
                                                        "<div class='col-md-4 kiri px-0'><iframe style='height:100%; width:100%' src='"+result.daftar[i].file_gambar+"' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe></div>"+
                                                        "<div class='col kanan'><a href='"+linknya+"' id='text-kanan'>"+result.daftar[i].judul+"</a></div>"+
                                                    "</div>"+
                                                "</div>";
                                    }
                                }
                            }
                            html+=`</div>`;
                            $('#iniSliderOwl').html(html); 
                            $('.owl-carousel').owlCarousel({
                                loop:true,
                                items: 3,
                                margin:10,
                                slideBy: 5, // slide 5 items
                                nav:true,
                                autoplay:true,
                                autoplayTimeout:3500,
                                autoplayHoverPause:true
                            })
                        }
                    });
                }

                $(document).ready(function() {
                    $('.home').addClass('active');
                    $('.kategori').on('click', function() {
                        $('#third').toggle();
                    });
                    setOwlSlider();
                    $('.nutuPin').hide();
                });

                function clickPress(event) {
                    if (event.keyCode == 13) {
                        var al = "<?= $_SERVER['SERVER_NAME']?>";
                        var cari = document.getElementById("tCari").value;
                        window.location.assign("http://"+al+"/esaku/iKategori/?cari="+cari);
                    }
                }

                function clickPress2(event) {
                    if (event.keyCode == 13) {
                        var al = "<?= $_SERVER['SERVER_NAME']?>";
                        var cari = document.getElementById("tCari2").value;
                        window.location.assign("http://"+al+"/esaku/iKategori/?cari="+cari);
                    }
                }
            </script>
            <script type="text/javascript">
        	function getKategoriA(){
		        $.ajax({
		            type: 'GET',
		            url: '<?=$root_ser?>/iArtikel.php?fx=getKatArt',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>'},
		            success:function(result){
                        var kat='';
                        var kat2='';
                        if(result.status){
		                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
		                        for(i=0;i<result.daftar.length;i++){
                                    kat+="<a href='<?= $buatSlider ?>/iKategori/?cari="+result.daftar[i].nama+"' style='color:inherit'>"+result.daftar[i].nama+"</a>"+"&nbsp;&nbsp;&nbsp;";
                                    $('#katArt').html(kat);
                                    kat2+="<a href='<?= $buatSlider ?>/iKategori/?cari="+result.daftar[i].nama+"' style='margin-left: -5%; font-size: 12px'>- "+result.daftar[i].nama+"</a>";
                                    $('#katArt2').html(kat2);
                                }
                            }
                        }
		            }
		        });
		    }
		    getKategoriA();
            function getKategoriV(){
		        $.ajax({
		            type: 'GET',
		            url: '<?=$root_ser?>/iArtikel.php?fx=getKatVid',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>'},
		            success:function(result){
                        var kat='';
                        var kat2='';
                        if(result.status){
		                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
		                        for(i=0;i<result.daftar.length;i++){
                                    // kat+=result.daftar[i].nama+"&nbsp;&nbsp;&nbsp;";
                                    kat+="<a href='<?= $buatSlider ?>/iKategori/?cari="+result.daftar[i].nama+"' style='color:inherit'>"+result.daftar[i].nama+"</a>"+"&nbsp;&nbsp;&nbsp;";
                                    $('#katVid').html(kat);
                                    kat2+="<a href='<?= $buatSlider ?>/iKategori/?cari="+result.daftar[i].nama+"' style='margin-left: -5%; font-size: 12px'>- "+result.daftar[i].nama+"</a>";
                                    $('#katVid2').html(kat2);
                                }
                            }
                        }
		            }
		        });
		    }
		    getKategoriV();
            </script>
            <script type="text/javascript">
            function getVideoUtama(){
		        $.ajax({
		            type: 'GET',
		            url: '<?=$root_ser?>/iArtikel.php?fx=getVidUtama',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>','no':'<?=$_GET['id']?>'},
		            success:function(result){
                        var vid='';
                        vid+="<iframe width='100%' id='iframeAl' src='"+result.daftar[0].file_gambar+"' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                        $('#vid').html(vid);

                        var judul='';
                        judul+=result.daftar[0].judul;
                        $('#judul').html(judul);
		            }
		        });
		    }
		    getVideoUtama();
            </script>
        <!-- -------------------------------------------------------------------------------------------------------------- -->

    </body>
</html>