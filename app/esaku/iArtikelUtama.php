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

            <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" rel="stylesheet"> -->
            <!-- <link href="https://owlcarousel2.github.io/OwlCarousel2/assets/css/docs.theme.min.css" rel="stylesheet"/> -->
            <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
            <link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet"/>
            <link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css" rel="stylesheet"/>
            <!-- <script src="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/owl.carousel.js"></script> -->
            
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

            .link:hover {
                color: blue;
            }
            
            .item {
                /* background: red; */
                height: 70px;
            }

            .kiri {
                /* background: blue; */
                height: 70px;
                margin-left: 15px;
                padding-left: 0px;
                padding-right: 0px;
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

            #contentAl {
                text-align: justify;
            }

            #bagikanAl {
                color: #4086F4;
            }

            #bagikanAlter {
                color: #000;
            }

            #bagikeAl {
                width: 11%;
            }

            #tanggal {
                font-size: 10px;
                color: #747474;
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

                #bagikanAl, #bagikanAlter {
                    font-size: 13px;
                }

                #bagikeAl {
                    width: 5%;
                }

                #judart {
                    font-size: 12px;
                    display: none;
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
                    height: 50px;
                }

                #text-kanan {
                    display: none;
                }
                
                #tanggal {
                    font-size: 6px;
                }
            }
        </style>
    </head>
    <body>
        <div class="col nutuPin" onclick="closeNav()" style="background: #000; opacity: 0.5; width: 20%; height: 100%; z-index: 3; display: block; position: fixed; left: 80%; transition: 0.5s;"></div>
        <div><?php include 'iNavbar.php' ?></div>

        <div id="tempat" value="art"></div>

        <!-- -------------------------------------------------------------------------------------------------------------- -->
        <div class="container" id="content">
            <div class="row">
                <div class="col col-md-9">
                    <div class="row">
                        <div class="col" id="judul"></div>
                    </div>
                    <div class="row">
                        <div class="col mb-2" id="tanggal">Oleh SAKU Team &nbsp;&nbsp;&nbsp; Tanggal </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3" id="gambar"></div>
                    </div>
                    <div class="row">
                        <div class="col" id="contentAl">
                        </div>
                    </div>
                    <div class="row">
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
                            
                            <a href="http://www.facebook.com/sharer.php?u=<?= $link ?>" target="_blank"><img class="ml-4 mx-1" src="<?= $path ?>/berbagi/facebook.svg" id='bagikeAl' alt=""></a>

                            <a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=Coba cek link ini deh <?= $link ?>" target="_blank" data-size="large"><img class="mx-1" src="<?= $path ?>/berbagi/twitter.svg" id='bagikeAl' alt=""></a>
                            
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $link ?>&title=Create LinkedIn Share button on Website Webpages" target="_blank"><img class="mx-1" src="<?= $path ?>/berbagi/linkedin.svg" id='bagikeAl' alt=""></a>
                            
                            <img class="mx-1 link" src="<?= $path ?>/berbagi/share.svg" id='bagikeAl' alt="" onclick="copyToClipboard('#p1')">
                            <p id="p1"><?= $link ?></p>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="col col-md-3" id="judArt">
                    <div class="row">
                        <div class="col py-3" id="bagikanAlter"> Artikel Terbaru</div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col" id="bagikanAl">Artikel Terkait</div>
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
                        url: '<?=$root_ser?>/iArtikel.php?fx=getArt',
                        dataType: 'json',
                        data: {'kode_lokasi':'<?=$kode_lokasi;?>'},
                        success:function(result){
                            var html=`<div class="owl-carousel owl-theme">`;
                            if(result.status){
                                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                                    for(i=0;i<result.daftar.length;i++){
                                        var linknya="<?= $buatSlider ?>/iArtikelUtama/?id="+result.daftar[i].no_konten;
                                        
                                        html+="<div class='item'>"+
                                                    "<div class='row'>"+
                                                        "<div class='col-md-4 kiri' onclick='window.location.href = \""+linknya+"\";'><img src='http://saiweb.simkug.com"+result.daftar[i].file_gambar+"' id='sliderOwl' alt=''></div>"+
                                                        "<div class='col kanan'><a href='"+linknya+"' id='text-kanan'>"+result.daftar[i].judul+"</a></div>"+
                                                    "</div>"+
                                                "</div>";
                                    }
                                }
                            }
                            html+=`</div><hr>`;
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

                // function customDataSuccess(data){
                //     var content = "";
                //     for(var i in data["items"]){
                    
                //     var img = data["items"][i].img;
                //     var alt = data["items"][i].alt;
                
                //     content += "<img src=\"" +img+ "\" alt=\"" +alt+ "\">"
                //     }
                //     $("#owl-demo").html(content);
                // }}
                
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
            $(document).ready(function() {
                $('#carouselExample').on('slide.bs.carousel', function (e) {

                    var $e = $(e.relatedTarget);
                    var idx = $e.index();
                    var itemsPerSlide = 3;
                    var totalItems = $('.carousel-item').length;

                    if (idx >= totalItems-(itemsPerSlide-1)) {
                        var it = itemsPerSlide - (totalItems - idx);
                        for (var i=0; i<it; i++) {
                            // append slides to end
                            if (e.direction=="left") {
                                $('.carousel-item').eq(i).appendTo('.carousel-inner');
                            }
                            else {
                                $('.carousel-item').eq(0).appendTo('.carousel-inner');
                            }
                        }
                    }
                });

                $(document).ready(function() {
                    /* show lightbox when clicking a thumbnail */
                    $('a.thumb').click(function(event){
                        event.preventDefault();
                        var content = $('.modal-body');
                        content.empty();
                        var title = $(this).attr("title");
                        $('.modal-title').html(title);        
                        content.html($(this).html());
                        $(".modal-profile").modal({show:true});
                    });
                });
            });

        	function getArtikel(){
		        $.ajax({
		            type: 'GET',
		            url: '<?=$root_ser?>/iArtikel.php?fx=getArtUtama',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>','no':'<?=$_GET['id']?>'},
		            success:function(result){    
                        var konten='';
                        konten+=result.daftar[0].isi;
                        $('#contentAl').html(konten);

                        var gambar='';
                        gambar+="<img src='http://saiweb.simkug.com"+result.daftar[0].file_gambar+"' width='100%' alt=''>";
                        $('#gambar').html(gambar);

                        var judul='';
                        judul+=result.daftar[0].judul;
                        $('#judul').html(judul);

                        var tanggal='';
                        tanggal+=result.daftar[0].tanggalnya;
                        $('#tanggal').append(tanggal);
		            }
		        });
		    }
		    getArtikel();
            
            function getArtikelYah(){
		        $.ajax({
		            type: 'GET',
		            url: '<?=$root_ser?>/iArtikel.php?fx=getArt',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>'},
		            success:function(result){
                        var judart='';
                        if(result.status){
		                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
		                        for(i=1;i<result.daftar.length;i++){
                                    var lanjut="<?= $buatSlider ?>/iArtikelUtama/?id="+result.daftar[i].no_konten;
                                    judart+="<div class='row py-2'>"+
                                                "<div class='col link' style='' onclick='window.location.href = \""+lanjut+"\";'>"+result.daftar[i].judul+"</div>"+
                                            "</div>";
                                }
                                // $('#slider').html(slider);
                                $('#judArt').append(judart);
                            }
                        }
		            }
		        });
		    }
		    getArtikelYah();
            </script>
        <!-- -------------------------------------------------------------------------------------------------------------- -->

    </body>
</html>