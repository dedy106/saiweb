<?php
$regis="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja/registrasi";

$root=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
$root_ser=$root."/server";
$folder_css=$root."/vendor/asset_elite/dist/css";
$folder_js=$root."/vendor/asset_elite/dist/js";
$folder_assets=$root."/vendor/asset_elite";
$folder_upload=$root."/vendor/asset_elite/upload";
$folderroot_js=$root."/assets/js";
$folderroot_css=$root."/assets/css";
$root_app="http://".$_SERVER['SERVER_NAME'];
?>

<html>
    <head>
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
        <meta name="viewport" content="height=device-height">
        <!-- <meta name="viewport" content="height=device-height, user-scalable=1"> -->
        <link rel="stylesheet" href="<?=$folder_assets?>/node_modules/bootstrap/dist/css/bootstrap.min.css" crossorigin="anonymous">
        <link rel="icon" type="image/png" href="img/saku.png" sizes="32x32">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans|Hind&display=swap" rel="stylesheet">
        <title>SAKU | Login</title>
        <style>
            /* @media (min-width: 1281px) { */
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
                background: #2E92FF;
                border: 2px solid #2E92FF;
                color: white;

                text-align: left;
                width: 100%;
                border-radius:20px;
                height: 35px;
                padding-right: 32px;
                padding-left: 20px;
            }

            input[type=text]::placeholder,
            input[type=password]::placeholder {
                color: white;
            }

            /* input[type=password] {
                padding-right: 20px;
                padding-left: 20px;
            } */

            input:focus {
                border-color: yellow;
                background-color: #0060FF;
            }

            .button {
                border-radius: 12px;
                background-color: #FFF;
                border: none;
                color: #668cff;
                width: 100%;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin-top: 10%;
                margin-bottom: 5%;
            }

            .button:hover {
                transform: scale(1.05);
                transition: 1s;
                cursor: pointer;
            }

            .button2 {
                position: fixed;
                border: none;
                border-radius: 15px;
                left: 0;
                bottom: 0;
                width: 100%;
                background-color: #7EACF9;
                text-align: center;
                color: #FFF;
            }

            .button2:hover {
                box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
                cursor: pointer;
            }

            #buttonPass {
                background-image: url('<?=$root?>/vendor/sakube/assets/icons/eye.svg');
                background-color: Transparent;
                background-repeat: no-repeat;
                background-size: 25px 25px;
                border: none;
                height: 30px;
                border-radius:20px;
                width:80%;
            }

            .chat {
                position: absolute;
                max-width: 100%;
                top: 85%;
                left: 93%;
                width: 5%;
                -webkit-filter: drop-shadow(5px 5px 5px rgba(0,0,0,0.24));
                filter: drop-shadow(5px 5px 5px rgba(0,0,0,0.24));
                display:none;
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
            }

            .wrapper .top {
                padding-left: 4%;
                text-align: center;
                height: 100%;
            }

            .wrapper .top h4 {
                color: #478be7;
                font-family: 'Open Sans', sans-serif;
            }

            .wrapper .midLeft {
                position: absolute;
                border: 1px;
                border-style: solid;
                border-radius: 14px;
                border-color: #668cff;
                width: 50%;
                padding-left: 1%;
                margin-left: 3%;
            }

            .wrapper .midLeft .left {
                padding-right: 4%;
            }

            .wrapper .midLeft .right {
                color: #007AFF;
            }

            .wrapper .bottom {
                padding-top: 10%;
                padding-left: 3%;
                font-size: 15px;
            }

            .wrapper2 {
                display: block;
                max-width: 275px;
                width: 100%;
                height: 80%;
                position: absolute;
                top: 50%;
                left: 70%;
                transform: translate(-50%,-50%);
                background-color: #007AFF;
                border-radius: 15px;
                color: #FFFFFF;
            }

            .wrapper2 .top {
                text-align: center;
            }

            .wrapper2 .top h2 {
                font-family: 'Hind', sans-serif;
                font-weight: bold;
                margin-top: 8%;
            }

            .wrapper2 .mid {
                text-align: center;
                margin-top: 15%;
            }

            .wrapper2 .mid a{
                color: #FFF;
            }
            /* } */
        </style>
    </head>
    <body>
        <div class="bgLogin"></div>
        <!-- section login -->
        <section>
            <div class="wrapper row">
                <div class="top col-md-8">
                    <h4 style='text-align:left' class="greetings">Hi, Selamat Pagi</h4>
                    <img src='<?=$root?>/assets/img/esaku/saku.png' width='120px' style='padding-top:30px'>
                    <h3>Sistem Informasi Keuangan</h3>
                </div>
                <div class="midLeft row py-2">
                    <div class="left">
                        <img src="img/bell.png" width="50px">
                    </div>
                    <div class="right">
                        Selamat Anda berhasil daftar, silahkan log in. <br>
                        Konfirmasi e-mail segera. Terima kasih.
                    </div>
                </div>
                <!-- <div class="bottom">
                </div> -->
            </div>
            <div class="wrapper2">
            <form id='form-login' method="post" action="<?=$root_ser?>/apisaku/cLogin.php?fx=login" >
                <div class="top">
                <h2><u>Masuk</u>
                <span style="font-size:20px;padding-left: 10px;color: #75B7FF;">Daftar</span></h2>
                </div>
                <div class="mid" style="padding:10px">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input type="text" id="username" autocomplete="off" placeholder="username" name="nik" autofocus required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style='z-index:0;'><input type="password" id="myPass" placeholder="password" name="pass" required>
                        </div>
                        <div class="col-md-2 px-0" style='z-index:1;top: -30px;left: 230px;'>
                        <button type="button" name="button" onclick="showPass()" id="buttonPass"></button>
                        </div>
                    </div>
                    <div class="row" id="error-layout" style="color:red;font-size:12px;display:none;">
                    <!-- text-shadow: 0 0 2px #fff; -->
                        <div class="col-12">
                            <span class="error-message">
                            
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="button py-1"> Masuk</button> <br>
                            <a href="#">Lupa kata sandi? <b>Klik disini.</b></a>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            <!-- <div>
                <img class="chat" src="img/chat.png">
            </div> -->
        </section>
        <script src="<?=$folderroot_js?>/jquery-3.4.1.js" ></script>
        <script src="<?=$folder_assets?>/node_modules/popper/popper.min.js"></script>
        <script src="<?=$folder_assets?>/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script>
            // window.onblur = function () { document.title = 'Hey, sini balik :)'; }
            // window.onfocus = function () { document.title = 'Saku! - Data Perusahaan'; }
        </script>
        <script>
            $(document).ready(function() {
                

                var hours = new Date().getHours();
                if(hours>=4 && hours<12){
                    $('.greetings').html('Hi, Selamat Pagi');
                }else if(hours>=12 && hours<15){
                    $('.greetings').html('Hi, Selamat Siang');
                }else if(hours>=15 && hours<18){
                    $('.greetings').html('Hi, Selamat Sore');
                }else{
                    $('.greetings').html('Hi, Selamat Malam');
                }
                $('.midLeft').hide();
                $('.button2').click(function() {
                    $('.midLeft').show();
                });
               
                $('#username,#myPass').keydown(function(e){
                    
                    var code = (e.keyCode ? e.keyCode : e.which);
                    var nxt = ['username','myPass'];
                    if (code == 13 || code == 40) {
                        e.preventDefault();
                        var idx = nxt.indexOf(e.target.id);
                        idx++;
                        if(idx == 2){
                            $('#form-login').submit();
                        }else{

                            $('#'+nxt[idx]).focus();
                        }
                    }else if(code == 38){
                        e.preventDefault();
                        var idx = nxt.indexOf(e.target.id);
                        idx--;
                        if(idx != -1){ 
                            $('#'+nxt[idx]).focus();
                        }
                    }
                });

                
                
            });
            
            function showPass() {
                var x = document.getElementById("myPass");
                if (x.type === "password") {
                    x.type = "text";
                    // document.getElementById("buttonPass").src = "<?=$root?>/vendor/sakube/assets/icons/eye2.svg";
                    document.getElementById("buttonPass").style.backgroundImage = "url('<?=$root?>/vendor/sakube/assets/icons/eye2.svg')";// document.getElementById("myPass").style.paddingLeft = "30px";
                    // document.getElementById("myPass").style.paddingRight = "30px";
                } else {
                    x.type = "password";
                    // document.getElementById("buttonPass").src = "<?=$root?>/vendor/sakube/assets/icons/eye.svg";
                    document.getElementById("buttonPass").style.backgroundImage = "url('<?=$root?>/vendor/sakube/assets/icons/eye.svg')";
                    // document.getElementById("buttonPass").style.backgroundImage = "url('<?=$root?>/assets/img/hide.png')"
                }
            }

        $('#form-login').submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            for (var value of formData.values()) {
               console.log(value); 
            }
            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/apisaku/cLogin.php?fx=login',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false,  
                success:function(result){
                    console.log(result);
                    if(result.status){
                        console.log('success');
                        location.href="<?=$root_app?>/apisaku_main/";
                    }else{
                        $('.error-message').html(result.message);
                        $('#error-layout').show();
                    }
                }
            });
        });
        </script>
    </body>
</html>