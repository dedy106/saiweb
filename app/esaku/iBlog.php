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
        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet"> -->

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

            .juds, .lans, .jus {
                cursor: pointer;
            }

            .jus {
                /* margin: 0 0 0 0;
                background: grey; */
            }

            .tiga {
                max-height: 100px;
            }

            .juds {
                font-size: 33px;
            }

            .lans {
                font-size: 12px;
                text-align: right;
                color: #747474;
            }

            .juds:hover, .lans:hover {
                color: #74A6F8;
            }

            html, body {
                max-width: 100%;
                overflow-x: hidden;
            }

            #gambarnya {
                max-height: 290px;
            }

            /* styles for '...' */ 
            .block-with-text {
                /* hide text if it more than N lines  */
                overflow: hidden;
                /* for set '...' in absolute position */
                position: relative; 
                /* use this value to count block height */
                line-height: 1.2em;
                /* max-height = line-height (1.2) * lines max number (3) */
                max-height: 3.6em; 
                /* fix problem when last visible word doesn't adjoin right side  */
                text-align: justify;  
                /* place for '...' */
                margin-right: -1em;
                padding-right: 1em;
            }

            /* create the ... */
            .block-with-text:before {
                /* points in the end */
                content: '...';
                /* absolute position */
                position: absolute;
                /* set position to right bottom corner of block */
                right: 0;
                bottom: 0;
            }

            /* hide ... if we have text, which is less than or equal to max lines */
            .block-with-text:after {
                /* points in the end */
                content: '';
                /* absolute position */
                position: absolute;
                /* set position to right bottom corner of text */
                right: 0;
                /* set width and height */
                width: 1em;
                height: 1em;
                margin-top: 0.2em;
                /* bg color = bg color under block */
                background: white;
            }
            
            a.videoArt {
                font-size: 18px;
                color: #747474;
                position: relative;
                float: left;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-decoration: none;
            }

            a.videoArtt {
                font-size: 30px;
                color: #747474;
                text-decoration: none;
            }

            a.videoArt:hover,
            a.videoArtt:hover {
                color: #000;
            }

            #tCari2 {
                display: none;
                width: 250px;
            }

            #rowvid {
                height: 432px;
            }

            #rowvid2, #rowvid3,
            #rowvid4, #rowvid5,
            #rowvid6 {
                /* height: 100px; */
            }

            #gambarArt {
                width: 100%;
                max-height: 150px;
            }

            #crMobile {
                display: none;
            }

            @media only screen and (max-width: 768px) {
                /* For mobile phones: */
                #gambarnya {
                    max-height: 150px;
                    /* margin-left: 16px;
                    margin-right: 16px; */
                }

                #tCari {
                    display: none;
                    /* max-width: 280px; */
                }

                #tCari2 {
                    display: inline;
                }

                .juds {
                    font-size: 20px;
                }

                #art1, #art2, #art3 {
                    margin-top: 20px;
                }

                #vid1 {
                    /* height: 202px; */
                    display: none;
                }

                .lans {
                    font-size: 12px;
                    text-align: left;
                    color: #747474;
                    margin-left: -7px;
                }

                #gambarArt {
                    height: 190px;
                }

                .jus {
                    margin: 0 0.01% 0 0.01%;
                }

                #crMobile {
                    display: inline;
                }
            }
        </style>
    </head>
    <body>
        <!-- <div class="col nutuPin" style="background: green; width: 80%; height: 100%; z-index: 3; display: block; position: fixed;"></div> -->
        <div class="col nutuPin" onclick="closeNav()" style="background: #000; opacity: 0.5; width: 20%; height: 100%; z-index: 3; display: block; position: fixed; left: 80%; transition: 0.5s;"></div>
        <div><?php include 'iNavbar.php' ?></div>

        <div id="tempat" value="home"></div>
        
        <!-- -------------------------------------------------------------------------------------------------------------- -->
        <div class="container" id="content">
            <div class="row">
                <div class="col col-md-7" data-aos="fade-down">
                    <div class="row" id="gambar"></div>
                </div>
                <div class="col col-md-5" data-aos="fade-left">
                    <div class="row" id="judul">
                    
                    </div>
                    <div class="row">
                        <div class="col mt-2 mb-3" style="font-size: 10px; color: #747474;">
                            <div class="row ml-1">
                                <div class="">Oleh SAKU Team&nbsp;&nbsp;&nbsp;</div>
                                <div class="" id="kategori"></div>
                                <div class="" id="tanggal"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="isi">
                        
                    </div>
                    <div class="row">
                        <div class="col lans" id="lanjut"></div>
                    </div>
                </div>
            </div>

            <div class="row mt-4" style="text-align: center; color: #000; font-size: 20px;">
                <div class="col px-0 jus" data-aos="fade-up-right" id="art1"></div>
                <div class="col-12" data-aos="fade-up" id="crMobile"><hr style="height:0.03em; width: 50%; border:none; color:#747474; background-color:#747474;"></div>
                <div class="col mx-5 px-0 jus" data-aos="fade-up" id="art2"></div>
                <div class="col-12" data-aos="fade-up" id="crMobile"><hr style="height:0.03em; width: 50%; border:none; color:#747474; background-color:#747474;"></div>
                <div class="col px-0 jus" data-aos="fade-up-left" id="art3"></div>
                <div class="col-12" data-aos="fade-up" id="crMobile"><hr style="height:0.03em; width: 50%; border:none; color:#747474; background-color:#747474;"></div>
            </div>

            <div class="row mt-5" data-aos="fade-right">
                <div class="col"><h4>VIDEO</h4></div>
            </div>
            <div class="row" id="rowvid">
                <div class="col col-md-8 pl-0" style="padding-right: 5px !important;" data-aos="fade-up" id="vid1">
                    
                </div>
                <div class="col col-md-4" data-aos="fade-left">
                    <div class="row" id="rowvid2">
                        <div class="col-5 px-0" id="vid2">
                            
                        </div>
                        <div class="col-7" id="vids2"></div>
                    </div>
                    <div class="row my-4" id="rowvid3" style="margin-top: 5px !important; margin-bottom: 5px !important;">
                        <div class="col-5 px-0" id="vid3">
                            
                        </div>
                        <div class="col-7" id="vids3"></div>
                    </div>
                    <div class="row" id="rowvid4">
                        <div class="col-5 px-0" id="vid4">
                            
                        </div>
                        <div class="col-7" id="vids4"></div>
                    </div>
                    <div class="row my-4" id="rowvid5" style="margin-top: 5px !important; margin-bottom: 5px !important;">
                        <div class="col-5 px-0" id="vid5">
                            
                        </div>
                        <div class="col-7" id="vids5"></div>
                    </div>
                    <div class="row" id="rowvid6">
                        <div class="col-5 px-0" id="vid6">
                            
                        </div>
                        <div class="col-7" id="vids6"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- -------------------------------------------------------------------------------------------------------------- -->

        <div><?php include 'iFooter.php' ?></div>

        <script src="<?= $path3 ?>/ijs/navbar.js"></script>
        
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/js/mdb.min.js"></script> -->

        <!-- -------------------------------------------------------------------------------------------------------------- -->
            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
            <script>AOS.init();</script>
            <script>
                $(document).ready(function() {
                    $('.home').addClass('active');
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

                $('#judul').on('click', function() {
                    // alert('judul');
                });
                
                $('#lanjut').on('click', function() {
                    // alert('lanjut');
                });
                // $('#art1').on('click', function() {
                //     header('Location: ');
                // });
            </script>
            <script>
                $('#katArt').on('click', function() {
                    // alert('hai al');
                });
            </script>
            <script type="text/javascript">
        	function getVideo(){
		        $.ajax({
		            type: 'GET',
		            url: '<?=$root_ser?>/iArtikel.php?fx=getVid',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>'},
		            success:function(result){
                        var vid1='';
                        vid1+="<iframe width='100%' height='80%' src='"+result.daftar[0].file_gambar+"' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe><center><a class='videoArtt' href='iVideoUtama/?id="+result.daftar[0].no_konten+"'>"+result.daftar[0].judul+"</a></center>";
                        $('#vid1').html(vid1);

                        var vid2='';
                        vid2+="<iframe width='100%' height='100%' src='"+result.daftar[1].file_gambar+"' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                        $('#vid2').html(vid2);

                        var vids2='';
                        vids2+="<a class='videoArt' href='iVideoUtama/?id="+result.daftar[1].no_konten+"'>"+result.daftar[1].judul+"</a>";
                        $('#vids2').html(vids2);

                        var vid3='';
                        vid3+="<iframe width='100%' height='100%' src='"+result.daftar[2].file_gambar+"' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                        $('#vid3').html(vid3);

                        var vids3='';
                        vids3+="<a class='videoArt' href='iVideoUtama/?id="+result.daftar[2].no_konten+"'>"+result.daftar[2].judul+"</a>";
                        $('#vids3').html(vids3);

                        var vid4='';
                        vid4+="<iframe width='100%' height='100%' src='"+result.daftar[3].file_gambar+"' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                        $('#vid4').html(vid4);

                        var vids4='';
                        vids4+="<a class='videoArt' href='iVideoUtama/?id="+result.daftar[3].no_konten+"'>"+result.daftar[3].judul+"</a>";
                        $('#vids4').html(vids4);

                        var vid5='';
                        vid5+="<iframe width='100%' height='100%' src='"+result.daftar[4].file_gambar+"' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                        $('#vid5').html(vid5);

                        var vids5='';
                        vids5+="<a class='videoArt' href='iVideoUtama/?id="+result.daftar[4].no_konten+"'>"+result.daftar[4].judul+"</a>";
                        $('#vids5').html(vids5);

                        var vid6='';
                        vid6+="<iframe width='100%' height='100%' src='"+result.daftar[5].file_gambar+"' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                        $('#vid6').html(vid6);

                        var vids6='';
                        vids6+="<a class='videoArt' href='iVideoUtama/?id="+result.daftar[5].no_konten+"'>"+result.daftar[5].judul+"</a>";
                        $('#vids6').html(vids6);

                        // if(result.status){
		                //     if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
		                //         for(i=0;i<result.daftar.length;i++){
                                    
                        //         }
                        //     }
                        // }
		            }
		        });
		    }
		    getVideo();
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
		            url: '<?=$root_ser?>/iArtikel.php?fx=getArt',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>'},
		            success:function(result){
                        var server = "saiweb.simkug.com";

                        var gambar='';
                        gambar+="<img id='gambarnya' src='http://"+server+result.daftar[0].file_gambar+"' width='100%' alt=''>";
                        $('#gambar').html(gambar);

                        var tgl='';
                        tgl+="Tanggal "+result.daftar[0].tanggalnya;
                        $('#tanggal').html(tgl);

                        var kat='';
                        kat+="Kategori "+result.daftar[0].nama+"&nbsp;&nbsp;&nbsp;";
                        $('#kategori').html(kat);

                        // var link="<?= $_SERVER['SERVER_NAME']?>?id="+result.daftar[0].no_konten;
                        // var server="window.location.href = 'http://"+link+"'";
                        var lanjut="iArtikelUtama/?id="+result.daftar[0].no_konten;

                        var judul='';
                        judul+="<div class='col juds' onclick='window.location.href = \""+lanjut+"\";' style=''>"+result.daftar[0].judul+"</div>";
                        $('#judul').html(judul);

                        var lanjutBaca='';
                        lanjutBaca+="<div class='col lans' id='lanjut' onclick='window.location.href = \""+lanjut+"\";'><b>Lanjutkan baca ></b></div>";
                        $('#lanjut').html(lanjutBaca);

                        var isi='';
                        isi+="<div class='col block-with-text' style='font-size: 16px; color: #747474;'>"+result.daftar[0].isi+"</div>"
                        $('#isi').html(isi);

                        var lanjut1="iArtikelUtama/?id="+result.daftar[1].no_konten;

                        var art1='';
                        art1+="<img src='http://"+server+result.daftar[1].file_gambar+"' id='gambarArt' alt=''  onclick='window.location.href = \""+lanjut1+"\";'><a href='"+lanjut1+"' style='color:inherit; text-decoration: none;'>"+result.daftar[1].judul+"</a>";
                        $('#art1').html(art1);

                        var lanjut2="iArtikelUtama/?id="+result.daftar[2].no_konten;

                        var art2='';
                        art2+="<img src='http://"+server+result.daftar[2].file_gambar+"' id='gambarArt' alt='' onclick='window.location.href = \""+lanjut2+"\";'><a href='"+lanjut2+"' style='color:inherit; text-decoration: none;'>"+result.daftar[2].judul+"</a>";
                        $('#art2').html(art2);

                        var lanjut3="iArtikelUtama/?id="+result.daftar[3].no_konten;

                        var art3='';
                        art3+="<img src='http://"+server+result.daftar[3].file_gambar+"' id='gambarArt' alt='' onclick='window.location.href = \""+lanjut3+"\";'><a href='"+lanjut3+"' style='color:inherit; text-decoration: none;'>"+result.daftar[3].judul+"</a>";
                        $('#art3').html(art3);
		            }
		        });
		    }
            getArtikel();
            
            if(/iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent) || screen.availWidth < 480){
                alert('mobile dulu');
                function myFunction() {
                    var element = document.getElementById("art1");
                    element.classList.remove("px-0");

                    var element2 = document.getElementById("art2");
                    element2.classList.remove("px-0");
                    element2.classList.remove("mx-5");

                    var element3 = document.getElementById("art3");
                    element3.classList.remove("px-0");
                }

                myFunction();
            }
        </script>
        <!-- -------------------------------------------------------------------------------------------------------------- -->

    </body>
</html>