<?php
    $kode_lokasi='99';
    $root_ser="http://".$_SERVER['SERVER_NAME']."/server/esaku";
    $path = "http://".$_SERVER['SERVER_NAME']."/assets/img/esaku";
	$path2 = "http://".$_SERVER['SERVER_NAME']."/assets/css/esaku";
	$path3 = "http://".$_SERVER['SERVER_NAME']."/assets/js/esaku";
	$path4 = "http://".$_SERVER['SERVER_NAME']."/assets/fonts/esaku";
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

            .btnTampil:hover {
                cursor: pointer;
            }

            #judul {
                font-size: 23px;
                /* padding-top: 40px; */
                /* padding-top: 45px; */
                /* position: relative;
                float: left;
                top: 100%;
                left: 50%;
                transform: translate(-50%, -50%); */
            }

            #text-judul {
                /* font-size: 23px; */
                /* text-align: center; */
                vertical-align: middle;
                line-height: 120px;
                /* padding-top: 50px; */
            }
            
            #gambar:hover,
            #judul:hover {
                cursor: pointer;
                color: #74A6F8;
            }

            #loadMore, #loadMore :visited {
                color: #33739E;
                text-decoration: none;
                display: block;
                /* margin: 10px 0; */
            }
            
            #loadMore {
                padding: 5px;
                text-align: center;
                background-color: #4285F4;
                color: #fff;
                border-width: 0 1px 1px 0;
                border-style: solid;
                border-color: #fff;
                border-radius: 8px;
                box-shadow: 0 1px 1px #ccc;
                transition: all 600ms ease-in-out;
                -webkit-transition: all 600ms ease-in-out;
                -moz-transition: all 600ms ease-in-out;
                -o-transition: all 600ms ease-in-out;
            }
            
            #loadMore:hover {
                /* background-color: #fff; */
                background-color: #EAEAEC;
                text-decoration: none;
                /* color: #33739E; */
                color: #4285F4;
            }

            #tCari2 {
                display: none;
                width: 250px;
            }

            #iframeAl {
                width: 100%;
                height: 120px;
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
                    font-size: 10px;
                    padding-top: 27px;
                }

                #loadMore {
                    font-size: 8px;
                }

                #iframeAl {
                    width: 100%;
                    height: 80px;
                }

                #text-judul {
                    /* font-size: 10px; */
                    /* text-align: center; */
                    /* vertical-align: middle; */
                    line-height: 10px;
                    /* padding-top: 10px; */
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
        </div>
        <div class="container">
            <a href="#" id="loadMore">Tampilkan Artikel Lain</a>
        </div>
        <!-- -------------------------------------------------------------------------------------------------------------- -->

        <div><?php include 'iFooter.php' ?></div>

        <script src="<?= $path3 ?>/ijs/navbar.js"></script>
        
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        <!-- -------------------------------------------------------------------------------------------------------------- -->
            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
            <script>AOS.init();</script>
            <script>
                $(document).ready(function() {
                    $('.video').addClass('active');
                    $('.kategori').on('click', function() {
                        $('#third').toggle();
                    });
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
                                    kat+="<a href='iKategori/?cari="+result.daftar[i].nama+"' style='color:inherit'>"+result.daftar[i].nama+"</a>"+"&nbsp;&nbsp;&nbsp;";
                                    $('#katArt').html(kat);
                                    kat2+="<a href='iKategori/?cari="+result.daftar[i].nama+"' style='margin-left: -5%; font-size: 12px'>- "+result.daftar[i].nama+"</a>";
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
                                    kat+="<a href='iKategori/?cari="+result.daftar[i].nama+"' style='color:inherit'>"+result.daftar[i].nama+"</a>"+"&nbsp;&nbsp;&nbsp;";
                                    $('#katVid').html(kat);
                                    kat2+="<a href='iKategori/?cari="+result.daftar[i].nama+"' style='margin-left: -5%; font-size: 12px'>- "+result.daftar[i].nama+"</a>";
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
        	function getArtikel(){
		        $.ajax({
		            type: 'GET',
		            url: '<?=$root_ser?>/iArtikel.php?fx=getVid',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>'},
		            success:function(result){    
                        var konten='';
                        var button='';
                        var al='';
		                if(result.status){
		                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
		                        for(i=0;i<result.daftar.length;i++){
                                    var lanjut="iVideoUtama/?id="+result.daftar[i].no_konten;
                                    konten+="<div class='moreAl'>";
                                    konten+="<div class='row'>"+
                                                "<div class='col col-md-3' id='gambar'><iframe id='iframeAl' src="+result.daftar[i].file_gambar+" frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe></div>"+
                                                "<div class='col'>"+
                                                    "<div class='row'>"+
                                                        "<div class='col pl-0' id='judul' onclick='window.location.href = \""+lanjut+"\";'><a id='text-judul'>"+result.daftar[i].judul+"</a></div>"+
                                                    "</div>"+
                                                "</div>"+
                                            "</div>"+
                                            "<hr>";
                                            konten+="</div>";
                                    $('#content').html(konten);
		                        }
		                    }
                        }
                        $(".moreAl").hide();
                        $(".moreAl").slice(0, 5).show();
                        $("#loadMore").on('click', function (e) {
                            e.preventDefault();
                            $(".moreAl:hidden").slice(0, 5).slideDown();
                            if ($(".moreAl:hidden").length == 0) {
                                $("#load").fadeOut('slow');
                            }
                            $('html,body').animate({
                                scrollTop: $(this).offset().top
                            }, 1500);
                        });
                        button+="<input class='py-1 btnTampil' type='button' value='Tampilkan artikel lain' style='background-color: #4285F4; border: none; border-radius: 8px; color: #FFF;'>";
                        // $('#content').append(button);
		            }
		        });
		    }
		    getArtikel();
        </script>
        <!-- -------------------------------------------------------------------------------------------------------------- -->

    </body>
</html>