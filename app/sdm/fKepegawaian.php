<div class="box-body mt-2">
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left">Gelar Depan</label>
        <div class="col-md-4">
            <input type="text" class="form-control"  id="inp-gelar_depan" placeholder="" readonly="readonly">
        </div>
        <label class="col-md-2 control-label" style="text-align:left">Gelar Belakang</label>
        <div class="col-md-4">
            <input type="text" class="form-control"  id="inp-gelar_belakang" placeholder="" readonly="readonly">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left;">Status SDM</label>
        <div class="col-md-4">
            <input type="text" class="form-control" id="inp-sts_sdm" readonly="readonly">
        </div>
    </div>   
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left;">Golongan</label>
        <div class="col-md-4">
            <input type="text" class="form-control" id="inp-golongan" readonly="readonly">
        </div>
    </div>  
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left;">Jabatan</label>
        <div class="col-md-4">
            <input type="text" class="form-control" id="inp-jabatan" placeholder="" readonly="readonly">
        </div>
    </div>    
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left;">Unit</label>
        <div class="col-md-6">
            <input type="text" class="form-control"  id="inp-unit" placeholder="" readonly="readonly">
        </div>
    </div>    
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left;">PP</label>
        <div class="col-md-4">
            <input type="text" class="form-control" id="inp-kode_pp" placeholder="" readonly="readonly">
        </div>
    </div>    
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left;">Lokasi Kerja</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="inp-loker" placeholder="" readonly="readonly">
        </div>
    </div>    
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left;">Status IJHT</label>
        <div class="col-md-10">
            <div class="radio">
                    <label>
                        <input type="radio" id="inp-status_ijht1" value="1" disabled=""> Ya
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" id="inp-status_ijht0" value="0" disabled=""> Tidak
                    </label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left;">Status BPJS</label>
        <div class="col-md-10">
            <div class="radio">
                    <label>
                        <input type="radio" id="inp-status_bpjs1" value="1" disabled=""> Ya
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" id="inp-status_bpjs0" value="0" disabled=""> Tidak
                    </label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left;">Status JP</label>
        <div class="col-md-10">
            <div class="radio">
                    <label>
                        <input type="radio" id="inp-status_jp1" value="1" disabled=""> Ya
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" id="inpstatus_jp0" value="0" disabled=""> Tidak
                    </label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left">Masa Kerja Gol</label>
        <div class="col-md-4">
            <input type="number" class="form-control" id="inp-mk_gol"  placeholder="" readonly="readonly">
        </div>   

        <label class="col-md-2 control-label" style="text-align:left">Tanggal Masuk</label>
        <div class='col-md-4'>
            <input class='form-control' id='inp-tgl_msk' type="date" readonly="readonly">
        </div>                       
    </div>
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left">No SK Tetap</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="inp-sk_tetap" readonly="readonly">
        </div>              
    </div>
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left">Masa Kerja YTB</label>
        <div class="col-md-4">
            <input type="number" class="form-control" id="inp-mk_ytb" readonly="readonly">
        </div>
        <label class="col-md-2 control-label" style="text-align:left">Tgl SK Tetap</label>
        <div class='col-md-4'>
            <input name='tgl_sk_tetap' class='form-control' id='inp-tgl_sk_tetap' type="date" readonly="readonly">
        </div>                         
    </div>
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left">No Kontrak</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="inp-no_kontrak" readonly="readonly">
        </div>              
    </div>
    <div class="form-group row">
        <label class="col-md-2 control-label" style="text-align:left">Tgl SK Kontrak</label>
        <div class='col-md-4' >
            <input name='tgl_sk_kontrak' type="date" class='form-control' id='inp-tgl_kontrak' readonly="readonly">
        </div>               
    </div>
</div>
<!-- FORM UBAH MODAL -->
<div class='modal' id='modal-dKepegawaian' tabindex='-1' role='dialog'>
    <div class='modal-dialog modal-lg' role='document'>
        <div class='modal-content'>
            <form id='form-dKepegawaian' enctype='multipart/form-data'>
                <div class='modal-header'>
                    <div class='row' style='width:100%'>
                        <div class='col-9'>
                            <h5 class='modal-title' id='header_modal'></h5>
                        </div>
                        <div class='col-3 text-right'>
                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                            <button type='submit' class='btn btn-primary'>Simpan</button> 
                        </div>
                    </div>
                </div>
                <div class='modal-body'>
                    <div class="box-body">
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left">Gelar Depan</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="modal-gelar_depan" name="gelar_depan">
                            </div>
                            <label class="col-md-2 control-label" style="text-align:left">Gelar Belakang</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="gelar_belakang"  id="modal-gelar_belakang">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left;">Status SDM</label>
                            <div class="col-md-4">
                                <select  class="form-control selectize" id="modal-sts_sdm" name="kode_sdm">
                                    <option value='' disabled>--- Pilih Status SDM ---</option>
                                </select>
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left;">Golongan</label>
                            <div class="col-md-4">
                                <select  class="form-control selectize" id="modal-golongan" name="kode_gol">
                                    <option value='' disabled>--- Pilih Golongan ---</option>
                                </select>
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left;">Jabatan</label>
                            <div class="col-md-4">
                                <select  class="form-control selectize" id="modal-jabatan" name="kode_jab">
                                    <option value='' disabled>--- Pilih Jabatan ---</option>
                                </select>
                            </div>
                        </div>    
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left;">Unit</label>
                            <div class="col-md-6">
                                <select  class="form-control selectize" id="modal-unit" name="kode_unit">
                                    <option value='' disabled>--- Pilih Unit ---</option>
                                </select>
                            </div>
                        </div>    
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left;">PP</label>
                            <div class="col-md-4">
                                <select  class="form-control selectize" id="modal-kode_pp" name="kode_pp">
                                    <option value='' disabled>--- Pilih PP ---</option>
                                    
                                </select>
                            </div>
                        </div>    
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left;">Lokasi Kerja</label>
                            <div class="col-md-6">
                                <select  class="form-control selectize" id="modal-loker" name="kode_loker">
                                    <option value='' disabled>--- Pilih Lokasi Kerja ---</option>
                                </select>
                            </div>
                        </div>    
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left;">Status IJHT</label>
                            <div class="col-md-10">
                                <div class="radio">
                                    
                                        <label>
                                            <input type="radio" name="status_ijht" id="modal-status_ijht1" value="1" > Ya
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="status_ijht" id="modal-status_ijht0"  value="0" > Tidak
                                        </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left;">Status BPJS</label>
                            <div class="col-md-10">
                                <div class="radio">
                                        <label>
                                            <input type="radio" name="status_bpjs" id="modal-status_bpjs1"  value="1" checked="" > Ya
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="status_bpjs" id="modal-status_bpjs0"  value="0" > Tidak
                                        </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left;">Status JP</label>
                            <div class="col-md-10">
                                <div class="radio">
                                        <label>
                                            <input type="radio" name="status_jp" id="modal-status_jp1"  value="1" > Ya
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="status_jp" value="0" id="modal-status_jp0"  checked="" > Tidak
                                        </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left">Masa Kerja Gol</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="mk_gol" id="modal-mk_gol"  placeholder="" >
                            </div>   

                            <label class="col-md-2 control-label" style="text-align:left">Tanggal Masuk</label>
                            <div class='col-md-4' >
                                
                                <input name='tgl_masuk' type='date' class='form-control ' id="modal-tgl_masuk"  >
                            </div>                       
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left">No SK Tetap</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="no_sk"  id="modal-no_sk"  placeholder="" >
                            </div>              
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left">Masa Kerja YTB</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="mk_ytb"  id="modal-mk_ytb"  placeholder="" >
                            </div>
                            <label class="col-md-2 control-label" style="text-align:left">Tgl SK Tetap</label>
                            <div class='col-md-4' >
                                
                                <input name='tgl_sk' type="date" class='form-control ' id="modal-tgl_sk_tetap"  >
                            </div>                         
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left">No Kontrak</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="no_kontrak"  id="modal-no_kontrak"  >
                            </div>              
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 control-label" style="text-align:left">Tgl SK Kontrak</label>
                            <div class='col-md-4' >
                                
                                <input name='tgl_sk_kontrak' type="date" class='form-control ' id='modal-tgl_kontrak' >
                            </div>               
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

var path = "<?php echo $path; ?>";
function getUpdate(){
    $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Kepegawaian.php?fx=getKepegawaian',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?php echo $kode_lokasi ?>','nik_user':'<?php echo $nik; ?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var line = result.daftar[0];
                        $('#modal-gelar_depan').val(line.gelar_depan);
                        $('#modal-gelar_belakang').val(line.gelar_belakang);
                        $('#modal-sts_sdm')[0].selectize.setValue(line.kode_sdm);
                        $('#modal-golongan')[0].selectize.setValue(line.kode_gol);
                        $('#modal-jabatan')[0].selectize.setValue(line.kode_jab);
                        $('#modal-unit')[0].selectize.setValue(line.kode_unit);
                        $('#modal-kode_pp')[0].selectize.setValue(line.kode_pp);
                        $('#modal-loker')[0].selectize.setValue(line.kode_loker);
                        if(line.ijht == "1"){
                            $('#modal-status_ijht1').prop('checked', true);
                        }else{
                            $('#modal-status_ijht0').prop('checked', true);
                        }
                        if(line.bpjs == "1"){
                            $('#modal-sts_bpjs1').prop('checked', true);
                        }else{
                            $('#modal-sts_bpjs0').prop('checked', true);
                        }
                        if(line.jp == "1"){
                            $('#modal-status_jp1').prop('checked', true);
                        }else{
                            $('#modal-status_jp0').prop('checked', true);
                        }
                        $('#modal-mk_gol').val(line.mk_gol);
                        $('#modal-tgl_masuk').val(line.tgl_masuk);
                        $('#modal-no_sk').val(line.no_sk);
                        $('#modal-mk_ytb').val(line.mk_ytb);
                        $('#modal-tgl_sk_tetap').val(line.tgl_sk);
                        $('#modal-no_kontrak').val(line.no_kontrak);
                        $('#modal-tgl_kontrak').val(line.tgl_kontrak);
                         
                    }
                }
            }
      });
}

function init(){
    $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Kepegawaian.php?fx=getKepegawaian',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?php echo $kode_lokasi ?>','nik_user':'<?php echo $nik; ?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        // alert('ada');
                        var line = result.daftar[0];
                        $('#inp-gelar_depan').val(line.gelar_depan);
                        $('#inp-gelar_belakang').val(line.gelar_belakang);
                        $('#inp-sts_sdm').val(line.kode_sdm);
                        $('#inp-golongan').val(line.kode_gol);
                        $('#inp-jabatan').val(line.kode_jab);
                        $('#inp-unit').val(line.kode_unit);
                        $('#inp-kode_pp').val(line.kode_pp);
                        $('#inp-loker').val(line.kode_loker);
                        if(line.ijht == "1"){
                            $('#inp-status_ijht1').prop('checked', true);
                        }else{
                            $('#inp-status_ijht0').prop('checked', true);
                        }
                        if(line.bpjs == "1"){
                            $('#inp-status_bpjs1').prop('checked', true);
                        }else{
                            $('#inp-status_bpjs0').prop('checked', true);
                        }
                        if(line.jp == "1"){
                            $('#inp-status_jp1').prop('checked', true);
                        }else{
                            $('#inp-status_jp0').prop('checked', true);
                        }
                        $('#inp-mk_gol').val(line.mk_gol);
                        $('#inp-tgl_msk').val(line.tgl_masuk);
                        $('#inp-sk_tetap').val(line.no_sk);
                        $('#inp-mk_ytb').val(line.mk_ytb);
                        $('#inp-tgl_sk_tetap').val(line.tgl_sk);
                        $('#inp-no_kontrak').val(line.no_kontrak);
                        $('#inp-tgl_kontrak').val(line.tgl_kontrak);
                         
                    }
                }
            }
      });
}

function getSDM(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Kepegawaian.php?fx=getSDM',
        dataType: 'json',
        async:false,
        data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#modal-sts_sdm')[0].selectize.addOption([{text:result.daftar[i].kode_sdm + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_sdm}]); 
                    }
                }
            }
        }
    });
}

function getGol(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Kepegawaian.php?fx=getGol',
        dataType: 'json',
        async:false,
        data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#modal-golongan')[0].selectize.addOption([{text:result.daftar[i].kode_gol + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_gol}]); 
                    }
                }
            }
        }
    });
}

function getJab(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Kepegawaian.php?fx=getJab',
        dataType: 'json',
        async:false,
        data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#modal-jabatan')[0].selectize.addOption([{text:result.daftar[i].kode_jab + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_jab}]); 
                    }
                }
            }
        }
    });
}

function getUnit(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Kepegawaian.php?fx=getUnit',
        dataType: 'json',
        async:false,
        data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#modal-unit')[0].selectize.addOption([{text:result.daftar[i].kode_unit + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_unit}]); 
                    }
                }
            }
        }
    });
}

function getPP(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Kepegawaian.php?fx=getPP',
        dataType: 'json',
        async:false,
        data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#modal-kode_pp')[0].selectize.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp}]); 
                    }
                }
            }
        }
    });
}

function getLoker(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Kepegawaian.php?fx=getLoker',
        dataType: 'json',
        async:false,
        data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#modal-loker')[0].selectize.addOption([{text:result.daftar[i].kode_loker + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_loker}]); 
                    }
                }
            }
        }
    });
}

$(document).ready(function(){

    init();
    $('#aKepegawaian').click(function(){
        $('#header_modal').html("<i class='fa fa-pencil'></i> Edit Data Kepegawaian");
        getSDM();
        getGol();
        getJab();
        getUnit();
        getPP();
        getLoker();
        getUpdate();
        $('#modal-dKepegawaian').modal('show');
    });

    $('#form-dKepegawaian').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }

        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);
        
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Kepegawaian.php?fx=ubahKepegawaian',
            dataType: 'json',
            data: formData,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                alert('Update data '+result.message);
                if(result.status){
                    location.reload();
                }
            }
        });
        
    });  

});

</script>


