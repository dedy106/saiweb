<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/dago/setting.php');
    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-form">
            <div class="col-sm-12">
                <div class="card">
                <form class="form" id="form-tambah">
                    <div class="card-body pb-0">
                        <h4 class="card-title mb-4"><i class='fas fa-pencil-alt'></i> Ubah Password
                        <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:450px'>
                            <div class="form-group row ">
								<label for="kode" class="col-3 col-form-label">Username</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" name="nik" readonly value='<?=$nik?>'>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Password Lama</label>
                                <div class="col-3">
                                    <input class="form-control" type="password" placeholder="Masukkan Password Lama" name="password_lama" id="password_lama" required>
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class=' col-form-label col-3'>Password Baru</label>
                                <div class='col-3' >
                                    <input type='password' name='password_baru' class='form-control' placeholder='Masukkan Password Baru' required id="password_baru">
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-form-label col-3'>Konfirmasi Password</label>
                                <div class='col-3' >
                                    <input type='password' name='password_confirm' class='form-control' placeholder='Masukkan Password Konfirmasi' required id="password_confirm">
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    <script>
   
    $('#saku-form').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var formData = new FormData(this);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }

        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        formData.append('kode_lokasi', kode_lokasi);

        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/UbahPass.php?fx=simpan',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                if(result.status){
                    Swal.fire(
                        'Great Job!',
                        'Password berhasil diubah',
                        'success'
                    )
                    $('#form-tambah')[0].reset();   
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message
                    })
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:'+textStatus);
            }
        });
       
        
    });

    $('#password_lama,#password_baru,#password_confirm').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['password_lama','password_baru','password_confirm'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            // if(idx == 2 || idx == 3){
            //     $('#'+nxt[idx])[0].selectize.focus();
            // }else{
                
                $('#'+nxt[idx]).focus();
            // }
        }else if(code == 38){
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx--;
            if(idx != -1){ 
                $('#'+nxt[idx]).focus();
            }
        }
    });
    </script>