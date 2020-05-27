<?php
    $kode_lokasi='99';
    $root_ser="http://".$_SERVER['SERVER_NAME']."/web/server/sakuaja";
?>

<html>
    <head>
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
        <meta name="viewport" content="height=device-height, user-scalable=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="icon" type="image/png" href="img/saku.png" sizes="32x32">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans|Hind&display=swap" rel="stylesheet">
        <title>Saku! - Data Perusahaan</title>
        <style>
            body {
                position: absolute;
                margin: 0px;
                overflow: hidden;
                top: 0px;
                bottom: 0px;
                width: 100%;
            }

            .bgLogin {
                background: url(img/20606.jpg) no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                width: 100%;
                height: 100%;
                opacity: 0.05;
            }

            input[type=text],
            input[type=password] {
                background: transparent;
                border: none;
                border-bottom: 2px solid #000000;
                border-color: #FFF;
                color: #FFF;
                text-align: left;
                width: 95%;
                padding: 2px 5px;
            }

            input:focus {
                border-color: yellow;
                background-color: #0060FF;
            }

            .button {
                border-radius: 12px;
                background-color: #FFF;
                border: none;
                color: #668cff;
                width: 30%;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin-left: 68.3%;
                margin-top: 1.4%;
            }

            .button:hover {
                transform: scale(1.05);
                transition: 1s;
                cursor: pointer;
            }

            .chat {
                position: absolute;
                max-width: 100%;
                top: 85%;
                left: 93%;
                width: 5%;
                -webkit-filter: drop-shadow(5px 5px 5px rgba(0,0,0,0.24));
                filter: drop-shadow(5px 5px 5px rgba(0,0,0,0.24));
            }

            .wrapper {
                display: block;
                max-width: 70%;
                width: 100%;
                height: 55%;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%,-50%);
                background-color: #FFFFFF;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                border-radius: 15px;
                padding-top: 2%;
                padding-left: 4%;
            }

            .wrapper .top {
                padding-bottom: 21%;
            }

            .wrapper .top h4 {
                color: #478be7;
                font-family: 'Open Sans', sans-serif;
            }

            .wrapper .bottom {
                font-size: 11px;
            }

            .wrapper .bottom .pa1 {
                color: #478be7;
            }

            .wrapper2 {
                display: block;
                max-width: 475px;
                width: 34%;
                height: 80%;
                position: absolute;
                top: 50%;
                left: 65%;
                transform: translate(-50%,-50%);
                background-color: #668cff;
                border-radius: 15px;
                color: #FFFFFF;
            }

            .wrapper2 .top {
                padding-left: 3%;
            }

            .wrapper2 .top h4 {
                font-family: 'Open Sans', sans-serif;
                margin-top: 20px;
            }

            .wrapper2 .mid {
                margin-left: 3%;
                /* margin-right: 3%; */
                max-height: 78.4%;
                height: 100%;
            }

            .wrapper2 .mid a{
                color: #FFF;
            }

            .wrapper2 .bottom {
                padding-left: 3%;
                width: 100%;
            }

            /* ------------------------------------------------------------------------------------------------- */
            /* ------------------------------------------------------------------------------------------------- */
            .wrapper-dropdown-3 {
                /* Size and position */
                position: relative;
                width: 200px;
                margin: 0 auto;
                padding: 10px;

                /* Styles */
                background: #fff;
                border-radius: 7px;
                border: 1px solid rgba(0,0,0,0.15);
                box-shadow: 0 1px 1px rgba(50,50,50,0.1);
                cursor: pointer;
                outline: none;

                /* Font settings */
                font-weight: bold;
                color: #8AA8BD;
            }

            .wrapper-dropdown-3:after {
                content: "";
                width: 0;
                height: 0;
                position: absolute;
                right: 15px;
                top: 50%;
                margin-top: -3px;
                border-width: 6px 6px 0 6px;
                border-style: solid;
                border-color: #8aa8bd transparent;
            }

            .wrapper-dropdown-3 .dropdown {
            /* Size & position */
                position: absolute;
                top: 140%;
                left: 0;
                right: 0;

                /* Styles */
                background: white;
                border-radius: inherit;
                border: 1px solid rgba(0,0,0,0.17);
                box-shadow: 0 0 5px rgba(0,0,0,0.1);
                font-weight: normal;
                transition: all 0.5s ease-in;
                list-style: none;

                /* Hiding */
                opacity: 0;
                pointer-events: none;
            }

            .wrapper-dropdown-3 .dropdown li a {
                display: block;
                padding: 10px;
                text-decoration: none;
                color: #8aa8bd;
                border-bottom: 1px solid #e6e8ea;
                box-shadow: inset 0 1px 0 rgba(255,255,255,1);
                transition: all 0.3s ease-out;
            }

            .wrapper-dropdown-3 .dropdown li i {
                float: right;
                color: inherit;
            }

            .wrapper-dropdown-3 .dropdown li:first-of-type a {
                border-radius: 7px 7px 0 0;
            }

            .wrapper-dropdown-3 .dropdown li:last-of-type a {
                border-radius: 0 0 7px 7px;
                border: none;
            }

            /* Hover state */

            .wrapper-dropdown-3 .dropdown li:hover a {
                background: #f3f8f8;
            }

            .wrapper-dropdown-3 .dropdown:after {
                content: "";
                width: 0;
                height: 0;
                position: absolute;
                bottom: 100%;
                right: 15px;
                border-width: 0 6px 6px 6px;
                border-style: solid;
                border-color: #fff transparent;    
            }

            .wrapper-dropdown-3 .dropdown:before {
                content: "";
                width: 0;
                height: 0;
                position: absolute;
                bottom: 100%;
                right: 13px;
                border-width: 0 8px 8px 8px;
                border-style: solid;
                border-color: rgba(0,0,0,0.1) transparent;    
            }

            .wrapper-dropdown-3.active .dropdown {
                opacity: 1;
                pointer-events: auto;
            }

            /* ----------------------------------------------------------------------------------------------- */
            /* ----------------------------------------------------------------------------------------------- */

            #pilihYa {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="bgLogin"></div>
        <!-- section login -->
        <section>
            <div class="wrapper">
                <div class="top">
                    <h4>Data Perusahaan</h4>
                    Lengkapi Data perusahaan anda
                </div>
                <div class="bottom">
                    <b>Pengaturan awal :</b> <br>
                    <a class="pa1">1. Data Perusahaan</a> <br>
                    <a class="pa2">2. Pilih Kelompok Akun</a> <br>
                    <a class="pa3">3. Saldo Awal</a> <br>
                </div>
            </div>
            <div class="wrapper2">
                <div class="top mb-2">
                    <h4>Data Perusahaan</h4>
                </div>
                <div class="mid">
                    Nama Perusahaan <br>
                    <input type="text" style="margin-right: 3%;" id="naper" autofocus> <br><br>
                    Bidang Usaha <br>
                    <div class="custom-select-al" style="margin-right: 5%;">
                        <select class="form-control" id="pilihYa" name="nama">
                            <!-- <option value="0">&nbsp;</option> -->
                        	<!-- <option value="0">&nbsp;</option>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option> -->
                        </select>
                    </div>
                </div>
                <div class="bottom">
                    <button class="button py-2" onclick="window.location.href = 'fKoda.php';">Next</button>
                </div>
            </div>
            <div>
                <img class="chat" src="img/chat.png">
            </div>
        </section>
        <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        
        <!-- <script src="/web/js/standalone/selectize.min.js"></script> -->
        <script>
            window.onblur = function () { document.title = 'Hey, sini balik :)'; }
            window.onfocus = function () { document.title = 'Saku! - Data Perusahaan'; }
        </script>
        <script type="text/javascript">
        	function getBidang(){
                // var select = $('#pilihYa').selectize();
		        $.ajax({
		            type: 'GET',
		            url: '<?=$root_ser?>/fDaperBid.php?fx=getBid',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>'},
		            success:function(result){    
                        var html='';
		                if(result.status){
		                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
		                        for(i=0;i<result.daftar.length;i++){
                                    // select[0].selectize.addOption([{text:result.daftar[i].nama, value:result.daftar[i].nama}]);
                                    html+="<option value='"+result.daftar[i].kode_bidang+"'>"+result.daftar[i].nama+"</option>";
                                    $('#pilihYa').html(html);
                                    
		                        }
		                    }
		                }
		            }
		        });
		    }
            // function nyoba() {
            //     alert('percobaan bismillah');
            // }
            // nyoba();
		    getBidang();
        </script>
        <script>
            $(document).ready(function() {
            	// getBidang();
                $('input').on('keydown',function(e){
                    if (e.which == 13) {
                        // alert('hae al');
                        // document.getElementById("naper").blur();
                        // $(".select-selected").addClass("select-arrow-active");
                        // $(".select-items").removeClass("select-hide");
                        
                        // $("#pilihYa").focus();
                        // e.preventDefault();
                        // alert('this enter');
                        // $(this).next('input').focus();
                        // return false;
                        // var focusable = $('input').filter(':visible');
                        // focusable.eq(focusable.index(this)+1).focus();
                        // return false;
                        // $('#loginYa').on('keydown', function(i) {
                        //     if (i.which == 13) {
                        //         window.location.replace("fDaper.php");
                        //     }
                        // });
                    }
                });
            });
            // getBidang();

            
        </script>
    </body>
</html>