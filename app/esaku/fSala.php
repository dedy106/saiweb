<?php
    $kode_lokasi='99';
    $root_ser="http://".$_SERVER['SERVER_NAME']."/web/server/sakuaja";
?>

<html>
    <head>
        <!-- <meta name="viewport" content="height=device-height"> -->
        <meta name="viewport" content="height=device-height, user-scalable=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="icon" type="image/png" href="img/saku.png" sizes="32x32">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans|Hind&display=swap" rel="stylesheet">
        <title>Saku! - Saldo Awal</title>
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

            input[type=number] {
                background: #fbe2b3;
                border: none;
                border-radius: 3px;
                color: #000;
                text-align: left;
                width: 100%;
            }

            .button1, .button2 {
                border-radius: 12px;
                background-color: #FFF;
                border: none;
                color: #668cff;
                width: 30%;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                /* margin-top: 62.5%; */
            }
            
            .button2 {
                margin-left: 37.3%;
            }

            .button1:hover, .button2:hover {
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
                max-width: 80%;
                width: 70%;
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
                padding-bottom: 13%;
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
                margin-right: 5.4%;
            }

            .wrapper .bottom .pa2 {
                color: #478be7;
                margin-right: 1%;
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
                margin-right: 3%;
                max-height: 78.4%;
                height: 100%;
            }

            .wrapper2 .bottom {
                padding-left: 3%;
                width: 100%;
            }

            .table-striped {
                font-size: 13px;
            }

            /* table.table-striped thead {
                position: fixed;
                top: 50px;
                left: 0;
                right: 0;
                width: 100%;
                table-layout: fixed;
                display: table;
            } */

            .table-striped > thead > tr > td {
                background-color: #FFF;
                text-align: center;
            }
            
            .table-striped > thead > tr > td:nth-child(2n) {
                text-align: left;
            }

            .table-striped > tbody > tr:nth-child(2n+1) > td:nth-child(1) {
                text-align: center;
            }

            .table-striped > tbody > tr:nth-child(2n) > td:nth-child(1) {
                padding-left: 11%;
            }

            .table-striped > tbody > tr:nth-child(2n) > td:nth-child(1) {
                text-align: center;
            }

            .table-striped > tbody > tr:nth-child(2n) > td, .table-striped > tbody > tr:nth-child(2n) > th {
                background-color: #FFF;
            }

            .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
                background-color: #add0f9;
                font-weight: bold;
            }

            /* .wrapper .bottom .row .col .pa3 {
                margin-right: 12%;
            } */

            .my-custom-scrollbar {
                position: relative;
                height: 95%;
                overflow: auto;
            }
            
            .table-wrapper-scroll-y {
                display: block;
            }

            .table th, .table td { 
                border: none !important;
            }

            header {
                font-family: 'Lobster', cursive;
                text-align: center;
                font-size: 25px;	
            }

            .scrollbar {
                background: transparent;
                overflow-y: scroll;
            }

            #style-1::-webkit-scrollbar-track {
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0);
                border-radius: 10px;
                background-color: transparent;
            }

            #style-1::-webkit-scrollbar {
                width: 5px;
                background-color: transparent;
            }

            #style-1::-webkit-scrollbar-thumb {
                border-radius: 10px;
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0);
                background-color: #FFF;
            }

            table {
                counter-reset: section;
            }

            .count:before {
                counter-increment: section;
                content: counter(section);
            }
        </style>
    </head>
    <body>
        <div class="bgLogin"></div>
        <!-- section login -->
        <section>
            <div class="wrapper">
                <div class="top">
                    <h4>Saldo Awal</h4>
                    Jika Anda memiliki nilai saldo awal bisa di <br>
                    sisi. Jika Anda belum siap mengisi saldo <br>
                    awal Anda bisa melanjutkan. Terima <br>
                    kasih.
                </div>
                <div class="bottom">
                    <div class="row">
                        <div class="col">
                            <b>Pengaturan awal :</b> <br>
                            <a class="pa1">1. Data Perusahaan</a> <img src="img/sukses.png" width="3%"> <br>
                            <a class="pa2">2. Pilih Kelompok Akun</a> <img src="img/sukses.png" width="3%"> <br>
                            <a class="pa3">3. Saldo Awal</a> <img src="img/sukses.png" width="3%" hidden> <br>
                        </div>
                        <div class="col"></div>
                    </div>
                </div>
            </div>
            <div class="wrapper2">
                <div class="top pb-2">
                    <h4>Saldo Awal</h4>
                </div>
                <div class="mid">
                    <div class="table-wrapper-scroll-y my-custom-scrollbar scrollbar" id="style-1">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td scope="col">Kode Akun</td>
                                    <td scope="col">Nama Akun</td>
                                    <td scope="col">Nilai</td>
                                </tr>
                            </thead>
                            <tbody id="dataYa">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bottom">
                    <button class="button1 py-2" onclick="window.location.href = 'fKoda.php';">Back</button>
                    <button class="button2 py-2" onclick="window.location.href = '#';">Proses</button>
                </div>
            </div>
            <div>
                <img class="chat" src="img/chat.png">
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script>
            window.onblur = function () { document.title = 'Hey, sini balik :)'; }
            window.onfocus = function () { document.title = 'Saku! - Data Perusahaan'; }
        </script>
        <script>
            function getKoda(){
                $.ajax({
		            type: 'GET',
		            url: '<?=$root_ser?>/fDaperBid.php?fx=getKod',
		            dataType: 'json',
		            data: {'kode_lokasi':'<?=$kode_lokasi;?>'},
		            success:function(result){    
                        var html='';
		                if(result.status){
		                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
		                        for(i=0;i<result.daftar.length;i++){
                                    // select[0].selectize.addOption([{text:result.daftar[i].nama, value:result.daftar[i].nama}]);
                                    // al = 1;
                                    html+=  "<tr>"+
                                                "<td scope='row' class='count'></td>"+
                                                "<td>"+result.daftar[i].jenis+"</td>"+
                                                "<td></td>"+
                                            "</tr>"+
                                            "<tr>"+
                                                "<td scope='row'>"+result.daftar[i].kode_akun+"</td>"+
                                                "<td>"+result.daftar[i].nama+"</td>"+
                                                "<td width='30%'><input type='number' width='20%'></td>"+
                                            "</tr>";
                                    $('#dataYa').html(html);
		                        }
		                    }
		                }
		            }
		        });
            }
            getKoda();
        </script>
        <!-- <script>
            $(document).ready(function () {
                if (!$.browser.webkit) {
                    $('.wrapper').html('<p>Sorry! Non webkit users. :(</p>');
                }
            });
        </script> -->
    </body>
</html>