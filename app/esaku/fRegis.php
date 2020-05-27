<?php
    $root_ser="http://".$_SERVER['SERVER_NAME']."/web/server/sakuaja";
    $root_app="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja";
?>

<html>
    <head>
        <!-- <meta name="viewport" content="height=device-height"> -->
        <meta name="viewport" content="height=device-height, user-scalable=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="icon" type="image/png" href="img/saku.png" sizes="32x32">
        <link href="https://fonts.googleapis.com/css?family=Oxygen&display=swap" rel="stylesheet">

        <title>Saku! - Regis</title>
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

            .row input[type=text],
            .row input[type=email],
            .row input[type=number],
            .row input[type=password] {
                background: transparent;
                border: none;
                border-bottom: 1px solid #000000;
                width: 100%;
                text-align: left;
                font-size: 80%;
                padding: 2px 5px;
            }

            .row input:focus {
                border-color: #668cff;
                text-align: left;
                outline: none;
            }

            .button {
                border-radius: 12px;
                background-color: #668cff;
                border: none;
                color: #FFF;
                width: 23%;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px;
            }

            .button:hover {
                transform: scale(1.05);
                transition: 1s;
                cursor: pointer;
            }

            .wrapper {
                display: block;
                max-width: 40%;
                width: 100%;
                height: 75%;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%,-50%);
                background-color: #FFFFFF;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                border-radius: 15px;
            }

            .wrapper .header {
                text-align: center;
                color: #668cff;
                font-size: 25px;
                font-family: 'Oxygen', sans-serif;
            }

            .wrapper .content {

            }

            .wrapper .footer {
                text-align: center;
            }

            .wrapper .footer .button {

            }

            #nama1,
            #nama2 {
                text-transform:capitalize;
            }
        </style>
    </head>
    <body>
        <div class="bgLogin"></div>

        <!-- section daftar -->
        <div class="start">
            <form id="formRegis" method="post">
                <div class="wrapper">
                    <div class="header py-3">
                        Data Pribadi
                    </div>
                    <div class="content px-5">
                        <div class="row">
                            <div class="col pb-3" style="font-size: 20px">Nama Lengkap</div>
                        </div>
                        <div class="row">
                            <div class="col"><input type="text" name="nama1" id="nama1" placeholder="Nama Depan" value="" autocomplete="off" autofocus required></div>
                            <div class="col"><input type="text" name="nama2" id="nama2" placeholder="Nama Belakang" value="" autocomplete="off"></div>
                        </div>
                        <div class="row">
                            <div class="col py-3">Alamat Email</div>
                        </div>
                        <div class="row">
                            <div class="col"><input type="email" name="email" id="email" placeholder="contoh_saku@gmail.com" value="" autocomplete="off"></div>
                            <div class="col"></div>
                        </div>
                        <div class="row">
                            <div class="col py-3">Nomor Telepon</div>
                        </div>
                        <div class="row">
                            <div class="col"><input type="number" name="nope" id="nope" placeholder="08xxxxxxxxxx" value="" autocomplete="off"></div>
                            <div class="col"></div>
                        </div>
                        <div class="row">
                            <div class="col py-3">Kata Sandi</div>
                        </div>
                        <div class="row">
                            <div class="col"><input type="password" name="password" id="password" placeholder="08xxxxxxxxxx" value="" autocomplete="off"></div>
                            <div class="col"></div>
                        </div>
                    </div>
                    <div class="py-5 footer">
                        <input type="submit" id="regisYa" class="button py-1" name="send" value="Daftar">
                    </div>
                </div>
            </form>
        </div>
        <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script>
            window.onblur = function () { document.title = 'Hey, sini balik :)'; }
            window.onfocus = function () { document.title = 'Saku! - Data Perusahaan'; }
        </script>
        <script>
            $(document).ready(function() {
                $('.start').on('submit', '#formRegis', function(e) {
                    e.preventDefault();

                    

                    var formData = new FormData(this);
                    for(var pair of formData.entries()) {
                        console.log(pair[0]+ ': '+ pair[1]); 
                    }

                    $.ajax({
                      type: 'POST',
                      url: '<?=$root_ser?>/fRegis.php?fx=tambahUser',
                      dataType: 'json',
                      data: formData,
                      contentType: false,
                      cache: false,
                      processData: false, 
                      success:function(result){
                        if (result.status=="nama1") {
                          alert('Nama Depan anda belum terisi!');
                          
                        }
                        if (result.status=="nama2") {
                          alert('Nama Belakang anda belum terisi!');
                          
                        }
                        if (result.status=="email") {
                          alert('Email anda belum terisi!');
                          
                        }
                        if (result.status=="nope") {
                          alert('Nomor Telepon anda belum terisi!');
                          
                        }
                        if (result.status=="password") {
                          alert('Password anda belum terisi!');
                          
                        }
                        if (result.status=="email-duplicate") {
                          alert('Email anda telah terdaftar, cek email anda untuk verifikasi!');
                          
                        }
                        if(result.status==true){
                            alert('Email verifikasi telah dikirim');
                            $('#nama1').val('');
                              $('#nama2').val('');
                              $('#email').val('');
                              $('#nope').val('');
                              $('#password').val('');
                              window.location.href="login";
                        }
                            
                      },
                      fail: function(xhr, textStatus, errorThrown){
                          alert('request failed:'+textStatus);
                      }
                    });
                    // header('Location: fLogin.php');
                    // end();
                    // for(var pair of formData.entries()) {
                    //     console.log(pair[0]+ ', '+ pair[1]); 
                    // }
                });
                $('input').on('keydown',function(e){
                    if (e.which == 13) {
                        // e.preventDefault();
                        // alert('this enter');
                        // $(this).next('input').focus();
                        // return false;
                        var focusable = $('input').filter(':visible');
                        focusable.eq(focusable.index(this)+1).focus();
                        return false;
                        $('#regisYa').on('keydown', function(i) {
                            if (i.which == 13) {
                                window.location.replace("fLogin.php");
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>