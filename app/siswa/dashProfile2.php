<?php
$kode_lokasi=$_SESSION['lokasi'];
$periode=$_SESSION['periode'];
$kode_pp=$_SESSION['kodePP'];
$nik=$_SESSION['userLog'];

$path = "http://".$_SERVER["SERVER_NAME"]."/";		

// echo $_SESSION["userStatus"];
?>
<style>
    td{
        padding:4px !important;
    }
</style>
 <div class="container-fluid mt-3">
        <div class="row" id="saku-data-barang">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class='fa fa-user'></i> Profile
                        <a href='#' class='btn btn-primary float-right' id='btn-ubtelp'>
                            <i class='fa fa-pencil'></i> Ubah No Hp
                        </a>
                        <a href='#' class='btn btn-primary float-right mr-2' data-toggle="modal" data-target="#modalPass">
                            <i class='fa fa-pencil'></i> Ubah Password
                        </a>
                    </div>
                    <div class='card-body' id='akademik-profile-content'>
                        <table class='table table-striped table-bordered' id='table-profile'>
                            <tbody>
                            </tbody>
                        </table>
                        <form enctype='multipart/form-data' id='form-sis-foto'>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <center>
                                        <div id='foto-siswa'>
                                        
                                        </div>
                                        <input name='file' type='file' accept='image/jpg, image/jpeg, image/png' required> <br>
                                        <button type='submit' class='btn btn-success mt-2'><i class='fa fa-pencil'></i> Simpan Foto</button>
                                    </center>
                                </div>
                            </div>
                            <br>
                            <div class='col-md-12'>
                                <div class='alert' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #31708f; border-color: #bce8f1; background-color: #d9edf7;'>
                                    <center>
                                        Ukuran file maksimum 1MB <br>
                                        Ukuran foto minimum 200x200px dan maksimum 800x800px
                                    </center>
                                </div><br>
                                <div id='validation-box'></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPass" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Password</h4>
            </div>
            
            <form id="form-sis-ubpass">
                <div class="modal-body">
                        <div class="form-group row">
                            <label for="password_lama" class="col-3 col-form-label">Password Lama</label>
                            <div class="col-9">
                                <input class="form-control" type="password" placeholder="Masukkan Password Lama" id="password_lama" name="password_lama">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_baru" class="col-3 col-form-label">Password Baru</label>
                            <div class="col-9">
                                <input type='password' name='password_baru' class='form-control' maxlength='10' placeholder='Masukkan Password Baru' required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama" class="col-3 col-form-label">Konfirmasi Password</label>
                            <div class="col-9">
                                <input type='password' name='password_repeat' class='form-control' maxlength='10' placeholder='Masukkan Konfirmasi Password Baru' required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <input type='hidden' name='nik' class='form-control' value='<?php echo $nik; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <input type='hidden' name='kode_lokasi' class='form-control' value='<?php echo $kode_lokasi; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <input type='hidden' name='kode_pp' class='form-control' maxlength='10' value='<?php echo $kode_pp; ?>'>
                            </div>
                        </div>
                    <div class='row'>
                        <div class='col-sm-12' style='margin-bottom:5px;'>
                            <div id='validation-box2'></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default" data-dismiss="modal"> Tutup</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalTlp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah No HP</h4>
            </div>
            <form id="form-sis-ubtelp">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="nama" class="col-3 col-form-label">No Hp Ayah</label>
                        <div class="col-9">
                            <input type='text' name='hp_ayah' class='form-control' id='form-edit-hp-ayah' required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nama" class="col-3 col-form-label">No Hp Ibu</label>
                        <div class="col-9">
                            <input type='text' name='hp_ibu' class='form-control' id='form-edit-hp-ibu' required>
                        </div>
                    </div>    
                    <div class='row'>
                        <div class='col-sm-12' style='margin-bottom:5px;'>
                            <div id='validation-box3'></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default" data-dismiss="modal"> Tutup</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function getProfile(){
    
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Siswa.php?fx=getProfile',
        dataType: 'json',
        data: {'nik':'<?=$nik?>','kode_lokasi':'<?=$kode_lokasi?>','kode_pp':'<?=$kode_pp?>'},
        success:function(res){
            if(res.status){
                $('#table-profile tbody').html(res.html);
                $('#foto-siswa').html(res.img);
            }
        },
        fail: function(xhr, textStatus, errorThrown){
            alert('request failed:');
        }
    });

}

getProfile();

 $('#ajax-content-section').on('submit', '#form-sis-foto', function(event){
        event.preventDefault();
        var formData = new FormData(this);

        formData.append('nik','<?=$nik?>');
        formData.append('kode_pp','<?=$kode_pp?>');
        formData.append('kode_lokasi','<?=$kode_lokasi?>');
        
        $.ajax({
            url: "<?=$root_ser?>/Siswa.php?fx=ubahFoto",
            data: formData,
            type: "post",
            dataType: "json",
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false, 
            success: function (data) {
                // console.log(data);
                alert(data.alert);

                if(data.status == 1){
                    $('.foto-ui-ajax').attr('src', data.new_img);
                    // window.localStorage.setItem("foto", data.new_img);
                    $('#foto-siswa').html("<img src='"+data.new_img+"' style='width:25%; height:25%; min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'> <br><br>");
                }else if (data.status == 3){
                    // https://stackoverflow.com/a/26166303
                    var error_array = Object.keys(data.error_input).map(function (key) { return data.error_input[key]; });

                    // append input element error
                    var error_list = "<div class='alert alert-danger' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #a94442; background-color: #f2dede; border-color: #ebccd1;'><ul>";
                    for(i = 0; i<error_array.length; i++){
                        error_list += '<li>'+error_array[i]+'</li>';
                    }
                    error_list += "</ul></div>";
                    status = false;
                    $('#validation-box').html(error_list);
                }
            }
        });
    });

    function clearInput(){
        $("input:not([type='radio'],[type='checkbox'],[type='submit'])").val('');
        $('textarea').val('');
        $("select:not('.selectize')").val('');
        $('#validation-box2').text('');
    }
    
    $('#modalPass').on('submit', '#form-sis-ubpass', function(event){
        event.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: '<?=$root_ser?>/Siswa.php?fx=ubahPassword',
            data: formData,
            type: "post",
            dataType: "json",
            contentType: false, 
            cache: false, 
            processData:false, 
            success: function (data) {
                alert(data.alert);

                if(data.status == 1){
                    $('#modalPass').modal('hide');
                    $('#validation-box2').html("");
                    clearInput();
                }else if (data.status == 3){
                    // var error_array = Object.keys(data.error_input).map(function (key) { return data.error_input[key]; });

                    // append input element error
                    var error_list = "<div class='alert alert-danger' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #a94442; background-color: #f2dede; border-color: #ebccd1;'><ul>";
                    for(i = 0; i<data.error_input.length; i++){
                        error_list += '<li>'+data.error_input[i]+'</li>';
                    }
                    error_list += "</ul></div>";
                    status = false;
                    $('#modalPass').find('#validation-box2').html(error_list);
                    // $('#validation-box').append(error_list);
                }
            }
        });
    });

    
    $('#btn-ubtelp').click(function(){
        
        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        var kode_pp='<?php echo $kode_pp; ?>' ;

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Siswa.php?fx=getEditTelp',
            dataType: 'json',
            data: {'nik':nik,'kode_lokasi':kode_lokasi,'kode_pp':kode_pp},
            success:function(res){
                // alert('id ='+res.daftar[0].hp_ayah);

                if(res.status){
                    
                    $('#form-edit-hp-ayah').val(res.daftar[0].hp_ayah);
                    $('#form-edit-hp-ibu').val(res.daftar[0].hp_ibu);
                    $('#modalTlp').modal('show');
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:');
            }
        });

    });

    $('#modalTlp').on('submit', '#form-sis-ubtelp', function(event){
        event.preventDefault();
        var formData = new FormData(this);

        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        var kode_pp='<?php echo $kode_pp; ?>' ;

        formData.append('nik', nik);
        formData.append('kode_lokasi', kode_lokasi);
        formData.append('kode_pp', kode_pp);
        
        $.ajax({
            url: '<?=$root_ser?>/Siswa.php?fx=ubahNoTelp',
            data: formData,
            type: "post",
            dataType: "json",
            contentType: false, 
            cache: false, 
            processData:false, 
            success: function (data) {
                alert(data.alert);

                if(data.status == 1){
                    $('#modalTlp').modal('hide');
                    $('#validation-box3').html("");
                    clearInput();
                    location.reload();
                }else if (data.status == 3){
                    // var error_array = Object.keys(data.error_input).map(function (key) { return data.error_input[key]; });

                    // append input element error
                    var error_list = "<div class='alert alert-danger' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #a94442; background-color: #f2dede; border-color: #ebccd1;'><ul>";
                    for(i = 0; i<data.error_input.length; i++){
                        error_list += '<li>'+data.error_input[i]+'</li>';
                    }
                    error_list += "</ul></div>";
                    status = false;
                    $('#modalTlp').find('#validation-box3').html(error_list);
                    // $('#validation-box').append(error_list);
                }
            }
        });
    });
</script>