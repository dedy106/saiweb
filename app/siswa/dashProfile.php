<?php

$kode_lokasi=$_SESSION['lokasi'];
$periode=$_SESSION['periode'];
$kode_pp=$_SESSION['kodePP'];
$nik=$_SESSION['userLog'];

$path = "http://".$_SERVER["SERVER_NAME"]."/";		

?>
<style>
@import url('https://fonts.googleapis.com/css?family=Roboto&display=swap');


body {
    font-family: 'Roboto', sans-serif !important;
}
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: 'Roboto', sans-serif !important;
    font-weight: normal !important;
}
h2{
    margin-bottom: 5px;
    margin-top:5px;
}
.judul-box{
    font-weight:bold;
    font-size:18px !important;
}
.inner{
    padding:5px !important;
}

.box-nil{
    margin-bottom: 20px !important;
}

.pad-more{
    padding-left:10px !important;
    padding-right:0px !important;
}
.mar-mor{
    margin-bottom:10px !important;
}
.box-wh{
    box-shadow: 0 3px 3px 3px rgba(0,0,0,.05);
}
.small-box .icon{
    top: 0px !important;
    font-size: 20px !important;
}
.bg-white{
    background:white
}
.small-box .inner{
    background: white;
    border: 1px solid white;
    border-radius: 10px !important;
}
.small-box{
    border-radius:10px !important;
    box-shadow: 1px 2px 2px 2px #e6e0e0e6;
}
.widget-user-2 .widget-user-header {

    padding: 20px;
    border-top-right-radius: 10px;
    border-top-left-radius: 10px;
    box-shadow: 1px 2px 2px 2px #e6e0e0e6;
}
.icon-green {
    color:white;
    background: #00a65a;
    border: 1px solid #00a65a;
    padding: 2px;
    font-size: 12px;
    transition: all .3s linear;
    position: absolute;
    top: -10px;right: 10px;
    z-index: 0;
    padding: 2px 12px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    margin-right: 10px;
}
.icon-blue {
    color:white;
    background: #0073b7;
    border: 1px solid #0073b7;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    padding: 2px;
    font-size: 12px;
    transition: all .3s linear;
    position: absolute;
    top: -10px;right: 10px;
    z-index: 0;
    padding: 2px 12px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    margin-right: 10px;
}
.icon-purple {
    color:white;
    background: #605ca8 !important;
    border: 1px solid #605ca8 !important;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    padding: 2px;
    font-size: 12px;
    transition: all .3s linear;
    position: absolute;
    top: -10px;right: 10px;
    z-index: 0;
    padding: 2px 12px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    margin-right: 10px;
}
.icon-pink {
    color:white;
    background: #d81b60;
    border: 1px solid #d81b60;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    padding: 2px;
    font-size: 12px;
    transition: all .3s linear;
    position: absolute;
    top: -10px;right: 10px;
    z-index: 0;
    padding: 2px 12px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    margin-right: 10px;
}
.box-footer {

border-top-left-radius: 0;
border-top-right-radius: 0;
border-bottom-right-radius: 10px;
border-bottom-left-radius: 10px;
border-top: 1px solid #f4f4f4;
padding: 10px;
background-color: #fff;
box-shadow: 1px 2px 2px 2px #e6e0e0e6;

}

.box-nil{
    margin-bottom: 20px !important;
}

.icon{
    padding: 2px 12px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
}

.judulBox:hover{
    color:#0073b7
}
</style>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30" id="profile-header"> 
                        
                    </center>
                </div>
                <div class="card-body pb-0">
                    <form enctype='multipart/form-data' id='form-sis-foto'>
                        <!-- <div class='row'>
                            <div class='col-md-12'> -->
                                <center>
                                    <input name='file' type='file' accept='image/jpg, image/jpeg, image/png' required>
                                    <button type='submit' class='btn btn-success mt-2'><i class='fa fa-pencil'></i> Simpan Foto</button>
                                </center>
                            <!-- </div>
                            <div class='col-md-12'> -->
                                <div class='alert mt-3' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #31708f; border-color: #bce8f1; background-color: #d9edf7;font-size:8px'>
                                    <center>
                                        Ukuran file maksimum 1MB <br>
                                        Ukuran foto minimum 200x200px dan maksimum 800x800px
                                    </center>
                                </div>
                                <div id='validation-box'>
                                </div>
                        <!-- </div> -->
                    </form>
                </div>
                <div><hr> </div>
                <div class="card-body" id="profile-sub"> 
               
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-header">
                    <a href='#' class='btn btn-primary float-right' id='btn-ubtelp'>
                    <i class='fa fa-pencil'></i> Ubah No Hp
                    </a>
                    <a href='#' class='btn btn-primary float-right mr-2' data-toggle="modal" data-target="#modalPass">
                    <i class='fa fa-pencil'></i> Ubah Password
                    </a>
                </div>
                <div class="card-body">
                    <form class="form-horizontal form-material">
                        <div class="form-group">
                            <label class="col-md-12">ID Bank</label>
                            <div class="col-md-12">
                                <input type="text" placeholder="" class="form-control form-control-line" id="id_bank"  readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Angkatan</label>
                            <div class="col-md-12">
                                <input type="text" placeholder="" class="form-control form-control-line" id="kode_akt" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Kelas</label>
                            <div class="col-md-12">
                            <input type="text" value="" id="kode_kelas" class="form-control form-control-line" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Jurusan</label>
                            <div class="col-md-12">
                            <input type="text" placeholder=""  id="kode_jur" class="form-control form-control-line" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Tingkat</label>
                            <div class="col-md-12">
                            <input type="text" placeholder=""  id="kode_tingkat" class="form-control form-control-line" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Status</label>
                            <div class="col-md-12">
                            <input type="text" placeholder=""  id="status" class="form-control form-control-line" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Tanggal Lulus</label>
                            <div class="col-md-12">
                            <input type="text" placeholder=""  id="tgl_lulus" class="form-control form-control-line" readonly>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-md-12">No Hp Ayah</label>
                            <div class="col-md-12">
                            <input type="text" placeholder=""  id="hp_ayah" class="form-control form-control-line" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">No Hp Ibu</label>
                            <div class="col-md-12">
                            <input type="text" placeholder=""  id="hp_ibu" class="form-control form-control-line" readonly>
                            </div>
                        </div>
                    </form>
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

function sepNum(x){
    var num = parseFloat(x).toFixed(2);
    var parts = num.toString().split('.');
    var len = num.toString().length;
    // parts[1] = parts[1]/(Math.pow(10, len));
    parts[0] = parts[0].replace(/(.)(?=(.{3})+$)/g,'$1.');
    return parts.join(',');
}
function sepNumPas(x){
    var num = parseInt(x);
    var parts = num.toString().split('.');
    var len = num.toString().length;
    // parts[1] = parts[1]/(Math.pow(10, len));
    parts[0] = parts[0].replace(/(.)(?=(.{3})+$)/g,'$1.');
    return parts.join(',');
}

function toJuta(x) {
    var nil = x / 1000000;
    return sepNum(nil) + '';
}

function loadService(index,method,url,param=null){
    $.ajax({
        type: method,
        url: url,
        dataType: 'json',
        data: {'periode':'<?=$periode?>','param':param},
        success:function(result){    
            if(result.status){
                switch(index){
                    case 'profile' :
                    var html = `<img src="<?=$root_upload?>/`+result.daftar[0].foto+`" class="img-circle" width="150" / id='img-siswa'>
                        <h4 class="card-title m-t-10">`+result.daftar[0].nama+`</h4>
                        <h6 class="card-subtitle">`+result.daftar[0].nis+`</h6>`;

                    var html2 =`
                    <small class="text-muted">Email address </small>
                    <h6>`+result.daftar[0].email+`</h6> <small class="text-muted p-t-30 db">Sekolah</small>
                    <h6>`+result.daftar[0].kode_pp+` - `+result.daftar[0].nama_pp+`</h6>`;

                    $('#profile-header').html(html);
                    $('#profile-sub').html(html2);
                    $('#id_bank').val(result.daftar[0].id_bank);
                    $('#kode_akt').val(result.daftar[0].nama_akt);
                    $('#kode_jur').val(result.daftar[0].nama_jur);
                    $('#kode_kelas').val(result.daftar[0].nama_kls);
                    $('#kode_tingkat').val(result.daftar[0].nama_tingkat);
                    $('#status').val(result.daftar[0].status);
                    $('#tgl_lulus').val(result.daftar[0].tgl_lulus);
                    $('#hp_ayah').val(result.daftar[0].hp_ayah);
                    $('#hp_ibu').val(result.daftar[0].hp_ibu);
                    break;

                }
            }
        }
    });
}
function initDash(){
    loadService('profile','GET','<?=$root_ser?>/Siswa.php?fx=getProfile2','<?=$kode_lokasi?>|<?=$nik?>|<?=$kode_pp?>'); 
     
}
initDash();


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
                    $('#img-siswa').attr('src', data.new_img);
                    $('#imguser').attr('src', data.new_img);
                    // imguser
                    // window.localStorage.setItem("foto", data.new_img);
                    // $('#foto-siswa').html("<img src='"+data.new_img+"' style='width:25%; height:25%; min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'> <br><br>");
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