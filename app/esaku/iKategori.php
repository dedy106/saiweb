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

            .container {
                padding-top: 125px;
            }

            #content {
                margin-top: 30px;
            }

            html, body {
                max-width: 100%;
                overflow-x: hidden;
            }

            .btnTampil:hover {
                cursor: pointer;
            }

            #gambarnya {
                width: 100%;
                height: 100%;
            }

            #atas {
                font-size: 32px;
            }

            #judul {
                font-size: 28px;
                margin-bottom: 40px;
            }

            #gambar:hover,
            #judul:hover {
                cursor: pointer;
                color: #74A6F8;
            }

            /* #judul {
                position: relative;
                margin: auto;
                float: left;
                left: 50%;
                transform: translate(-50%, -10%);
            } */

            #loadMore, #loadMore :visited {
                color: #33739E;
                text-decoration: none;
                display: block;
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
                background-color: #fff;
                text-decoration: none;
                color: #33739E;
            }

            #tCari2 {
                display: none;
                width: 250px;
            }

            #text-artikel-atas {
                font-size: 10px;
                color: #747474;
            }

            #float-artikel-atas {
                float: right
            }

            #iframeAl {
                width: 100%;
                height: 120px;
            }

            #teksArt{
                background: #4285F4;
                color: #FFF;
                border-radius: 10px;
            }

            #teksArt2 {
                display: none;
            }

            #teksVid {
                background: #F4425A;
                color: #FFF;
                border-radius: 10px;
            }

            #teksVid2 {
                display: none;
            }

            @media only screen and (max-width: 768px) {
                /* For mobile phones: */
                #gambarnya {
                    width: 100%;
                    height: 100%;
                }

                #tCari {
                    display: none;
                    /* max-width: 280px; */
                }

                #tCari2 {
                    display: inline;
                }

                #text-artikel-atas {
                    font-size: 5px;
                }

                #judul {
                    font-size: 10px;
                    margin-bottom: 0;
                }

                #float-artikel-atas {
                    float: left
                }

                #loadMore {
                    font-size: 8px;
                }
                
                #atas {
                    font-size: 16px;
                }

                #teksArt {
                    display: none;
                }

                #teksArt2 {
                    display: inline;
                    background: #4285F4;
                    color: #FFF;
                    border-radius: 10px;
                    padding: 4px 10px;
                    font-size: 6px;
                }

                #teksVid {
                    display: none;
                }

                #teksVid2 {
                    display: inline;
                    background: #F4425A;
                    color: #FFF;
                    border-radius: 10px;
                    padding: 4px 10px;
                    font-size: 6px;
                }

                #iframeAl {
                    width: 100%;
                    height: 80px;
                }
            }
        </style>
    </head>
    <body>
        <div class="col nutuPin" onclick="closeNav()" style="background: #000; opacity: 0.5; width: 20%; height: 100%; z-index: 3; display: block; position: fixed; left: 80%; transition: 0.5s;"></div>
        <div><?php include 'iNavbar.php' ?></div>
        
        <!-- -------------------------------------------------------------------------------------------------------------- -->
        <div class="container">
            <div class="row">
                <div class="col" id="atas"></div>
            </div>
            <div class="row">
                <div class="col" id="content"></div>
            </div>
            <!-- <div class="row">
                <div class="col" id="content2"></div>
            </div> -->
            <a href="#" id="loadMore">Tampilkan Artikel Lain</a>
        </div>
        <!-- -------------------------------------------------------------------------------------------------------------- -->

        <div><?php include 'iFooter.php' ?></div>

        <script src="<?= $path3 ?>/ijs/navbar.js"></script>
        
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        <!-- -------------------------------------------------------------------------------------------------------------- -->
            <script>
                $(document).ready(function() {
                    // $('.artikel').addClass('active');
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
            function getArtikelVideo(){
		        $.ajax({
		            type: 'GET',
		            url: '<?=$root_ser?>/iArtikel.php?fx=getArtVid',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>','cari':'<?=$_GET['cari']?>'},
		            success:function(result){    
                        var konten='';
                        var button='';
                        var al='';
		                if(result.status){
		                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
		                        for(i=0;i<result.daftar.length;i++){
                                    var lanjut="iArtikelUtama/?id="+result.daftar[i].no_konten;
                                    var lanjut2="iVideoUtama/?id="+result.daftar[i].no_konten;
                                    konten+="<div class='moreAl'>";
                                    if (result.daftar[i].jenisal == 'Artikel') {
                                        konten+="<div class='row' id='pst'>"+
                                                "<div class='col col-md-3' id='gambar'><img id='gambarnya' src='http://saiweb.simkug.com"+result.daftar[i].file_gambar+"' alt=''></div>"+
                                                "<div class='col'>"+
                                                    "<div class='row'>"+
                                                        "<div class='col py-1' id='text-artikel-atas'>"+
                                                            "<div class='row' id='float-artikel-atas'>"+
                                                                "<div class='py-1'>Oleh SAKU Team</div>"+
                                                                "<div class='py-1 mx-3'>Kategori "+result.daftar[i].namakat+"</div>"+
                                                                "<div class='py-1 mr-3'>Tanggal "+result.daftar[i].tanggalnya+"</div>"+
                                                                "<div class='py-1 px-3' id='teksArt'>Artikel</div>"+
                                                            "</div>"+
                                                        "</div>"+
                                                    "</div>"+
                                                    "<div class='row'>"+
                                                        "<div class='col pl-0 py-1' id='judul' onclick='window.location.href = \""+lanjut+"\";'>"+result.daftar[i].judul+"</div>"+
                                                    "</div>"+
                                                    "<div class='row'>"+
                                                        "<div id='teksArt2'>Artikel</div>"+
                                                    "</div>"+
                                                "</div>"+
                                            "</div>"+
                                            "<hr>";
                                    } else if (result.daftar[i].jenisal == 'Video') {
                                        konten+="<div class='row' id='pst'>"+
                                                "<div class='col col-md-3' id='gambar'><iframe id='iframeAl' src='"+result.daftar[i].filenya+"' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe></div>"+
                                                "<div class='col'>"+
                                                    "<div class='row'>"+
                                                        "<div class='col py-1' id='text-artikel-atas'>"+
                                                            "<div class='row' id='float-artikel-atas'>"+
                                                                "<div class='py-1 px-3' id='teksVid'>Video</div>"+
                                                            "</div>"+
                                                        "</div>"+
                                                    "</div>"+
                                                    "<div class='row'>"+
                                                        "<div class='col pl-0 py-1' id='judul' onclick='window.location.href = \""+lanjut2+"\";'>"+result.daftar[i].judul+"</div>"+
                                                    "</div>"+
                                                    "<div class='row'>"+
                                                        "<div id='teksVid2'>Video</div>"+
                                                    "</div>"+
                                                "</div>"+
                                            "</div>"+
                                            "<hr>";
                                    }
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
		            }
		        });
            }
            getArtikelVideo()
        </script>
        <!-- -------------------------------------------------------------------------------------------------------------- -->

    </body>
</html>

<script>
    function getArtJumlah(){
		$.ajax({
		    type: 'GET',
		    url: '<?=$root_ser?>/iArtikel.php?fx=getArtJum',
		    dataType: 'json',
		    data: {'kode_lokasi':'<?=$kode_lokasi;?>','cari':'<?=$_GET['cari']?>'},
	        success:function(result){
                var link = "<?= $_GET['cari'] ?>";
                var kat='';
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
		                for(i=0;i<result.daftar.length;i++){
                            $('#atas').html(result.daftar[0].urutan+" hasil yang cocok dengan '"+link+"'");
                        }
                    }
                }
		    }
	    });
	}
	getArtJumlah();

    // window.onload = console.log(al);
    $(document).ready(function () {
        
    })
</script>

<!-- <script>

$ShowHideMore = $('#content');
$ShowHideMore.each(function() {
    var $times = $(this).children('#pst');
    if ($times.length > 5) {
        $ShowHideMore.children(':nth-of-type(n+5)').addClass('moreShown').hide();
        $(this).find('span.message').addClass('more-times').html('+ Show more');
    }
});

$(document).on('click', '#content > span', function() {
  var that = $(this);
  var thisParent = that.closest('#content');
  if (that.hasClass('more-times')) {
    thisParent.find('.moreShown').show();
    // that.toggleClass('more-times', 'less-times').html('- Show less');
  } 
});


</script> -->