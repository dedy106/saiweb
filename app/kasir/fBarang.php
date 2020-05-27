<?php

    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/kasir/setting.php');

    $kode_lokasi=$_COOKIE['lokasi'];
    $nik=$_COOKIE['userLog'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-data-barang">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4" ><i class='fas fa-cube'></i> Data Barang 
                        <button type="button" id="btn-barang-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button></h4>
                        <hr>
                        <div class="table-responsive ">
                            <style>
                            th,td{
                                padding:8px !important;
                                vertical-align:middle !important;
                            }
                            .form-group{
                                margin-bottom:15px !important;
                            }
                            
                            .dataTables_wrapper{
                                padding:5px
                            }
                            </style>
                            <table id="table-barang" class="table table-bordered table-striped" style='width: 100%;'>
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Satuan</th>
                                        <th>Keterangan</th>
                                        <th>Harga</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="form-tambah-barang" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title">Form Data Barang
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-barang-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <h6 class="card-subtitle">Input Data Barang</h6>
                            <hr>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="hidden" id="id_edit" name="id">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kode</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode Barang" id="kode_barang" name="kode_barang">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Barang" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Barcode</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Barcode" id="barcode" name="barcode">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kode Kelompok</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_klp" name="kode_klp">
                                    <option value=''>--- Pilih Kelompok Barang ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Keterangan</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Keterangan" id="keterangan" name="keterangan">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Satuan</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_satuan" name="kode_satuan">
                                    <option value=''>--- Pilih Satuan ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="hrg_satuan" class="col-3 col-form-label">Harga Satuan</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Harga Satuan" id="hrg_satuan" name="hrg_satuan" value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ppn" class="col-3 col-form-label">PPN %</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Masukkan Persen PPN" id="ppn" name="ppn" value="0">
                                </div>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" id="n_ppn" name="n_ppn" readonly value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ppn" class="col-3 col-form-label">Profit %</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Masukkan Persen Profit" id="profit" name="profit" value="0">
                                </div>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" id="n_profit" name="n_profit" readonly value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="hrg_jual" class="col-3 col-form-label">Harga Jual</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Harga Jual" id="harga_jual" name="harga_jual" value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="flag_aktif" class="col-3 col-form-label">Status Aktif</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="flag_aktif" name="flag_aktif">
                                    <option value=''>--- Pilih Status Aktif ---</option>
                                    <option value='1'>AKTIF</option>
                                    <option value='0'>NON</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Safety Stock</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Safety Stock" id="ss" name="ss" value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Slow Moving</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Slow Moving" id="sm1" name="sm1" value="0">
                                </div>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Slow Moving" id="sm2" name="sm2" value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Medium Moving</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Medium Moving" id="mm1" name="mm1" value="0">
                                </div>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Medium Moving" id="mm2" name="mm2" value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Fast Moving</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Fast Moving" id="fm1" name="fm1" value="0">
                                </div>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Fast Moving" id="fm2" name="fm2" value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Foto</label>
                                <div class="input-group col-9">
                                    <div class="custom-file">
                                        <input type="file" name="file_gambar" class="custom-file-input" id="file_gambar">
                                        <label class="custom-file-label" for="file_gambar">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="preview col-3">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    
    <script src="<?=$folderroot_js?>/jquery.scannerdetection.js"></script>
    <script src="<?=$folderroot_js?>/inputmask.js"></script>
    <script src="<?=$folderroot_js?>/sai.js"></script>
    <script>

    function getKlp(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Barang.php?fx=getKlp',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_klp').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_klp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_klp}]);
                        }
                    }
                }
            }
        });
    }

    function getSatuan(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Barang.php?fx=getSatuan',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_satuan').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_satuan + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_satuan}]);
                        }
                    }
                }
            }
        });
    }

    function hitungHrgJual(){
        var hrg = toNilai($('#hrg_satuan').val());
        var ppn = toNilai($('#ppn').val());

        var n_ppn = (hrg*ppn)/100;
        $('#n_ppn').val(n_ppn);
        var s_ppn = hrg+n_ppn;
        var profit = toNilai($('#profit').val());
        var n_profit = (s_ppn*profit)/100;
        
        $('#n_profit').val(n_profit);
        var hrg_jual = hrg+n_ppn+n_profit;

        $('#harga_jual').val(hrg_jual);
    }

    $('.custom-file-input').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })

    getKlp();
    getSatuan();
    $('#saku-data-barang').on('click', '#btn-barang-tambah', function(){
        $('#row-id').hide();
        $('#id_edit').val('');
        $('#kode_barang').attr('readonly', false);
        $('#saku-data-barang').hide();
        $('#form-tambah-barang').show();
        $('#form-tambah')[0].reset();
    });

    $('#saku-data-barang').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Barang.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_barang':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id_edit').val('edit');
                    $('#kode_barang').val(id);
                    $('#kode_barang').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#barcode').val(result.daftar[0].barcode);
                    $('#kode_klp')[0].selectize.setValue(result.daftar[0].kode_klp);
                    $('#keterangan').val(result.daftar[0].keterangan);
                    $('#kode_satuan')[0].selectize.setValue(result.daftar[0].satuan.toUpperCase());
                    $('#hrg_satuan').val(result.daftar[0].hrg_satuan);
                    $('#ppn').val(result.daftar[0].ppn); 
                    $('#profit').val(result.daftar[0].profit);
                    $('#harga_jual').val(result.daftar[0].hna);
                    $('#flag_aktif')[0].selectize.setValue(result.daftar[0].flag_aktif);
                    $('#ss').val(result.daftar[0].ss);
                    $('#sm1').val(result.daftar[0].sm1);
                    $('#sm2').val(result.daftar[0].sm2);
                    $('#mm1').val(result.daftar[0].mm1);
                    $('#mm2').val(result.daftar[0].mm2);
                    $('#fm1').val(result.daftar[0].fm1);
                    $('#fm2').val(result.daftar[0].fm2);
                    var html = "<img src='<?=$path?>/upload/"+result.daftar[0].file_gambar+"'>";
                    $('.preview').html(html);
                    // $('#row-id').show();
                    $('#saku-data-barang').hide();
                    $('#form-tambah-barang').show();
                }
            }
        });
    });


    $('#form-tambah-barang').on('change', '#kode_barang', function(){
        var id= $(this).val();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Barang.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_barang':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    if(result.daftar.length > 0){

                        $('#id_edit').val('edit');
                        $('#kode_barang').val(id);
                        // $('#kode_barang').attr('readonly', true);
                        $('#nama').val(result.daftar[0].nama);
                        $('#barcode').val(result.daftar[0].barcode);
                        $('#kode_klp')[0].selectize.setValue(result.daftar[0].kode_klp);
                        $('#keterangan').val(result.daftar[0].keterangan);
                        $('#kode_satuan')[0].selectize.setValue(result.daftar[0].satuan.toUpperCase());
                        $('#hrg_satuan').val(result.daftar[0].hrg_satuan);
                        $('#ppn').val(result.daftar[0].ppn); 
                        $('#profit').val(result.daftar[0].profit);
                        $('#harga_jual').val(result.daftar[0].hna);
                        $('#flag_aktif')[0].selectize.setValue(result.daftar[0].flag_aktif);
                        $('#ss').val(result.daftar[0].ss);
                        $('#sm1').val(result.daftar[0].sm1);
                        $('#sm2').val(result.daftar[0].sm2);
                        $('#mm1').val(result.daftar[0].mm1);
                        $('#mm2').val(result.daftar[0].mm2);
                        $('#fm1').val(result.daftar[0].fm1);
                        $('#fm2').val(result.daftar[0].fm2);
                        var html = "<img src='<?=$path?>/upload/"+result.daftar[0].file_gambar+"'>";
                        $('.preview').html(html);
                    }
                }
            }
        });
    });


    $('#form-tambah-barang').on('click', '#btn-barang-kembali', function(){
        $('#saku-data-barang').show();
        $('#form-tambah-barang').hide();
    });

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-barang').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Barang.php?fx=getBarang',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 5, data: null, 'defaultContent': action_html },
            {
                'targets': 4,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, '' )
            }
        ]
    });

    $('#saku-data-barang').on('click','#btn-delete',function(e){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/Barang.php',
                dataType: 'json',
                async:false,
                data: {'kode_barang':kode,'kode_lokasi':kode_lokasi},
                success:function(result){
                    alert('Penghapusan data '+result.message);
                    if(result.status){
                        dataTable.ajax.reload();
                    }
                }
            });
        }else{
            return false;
        }
    });

    
    $('#form-tambah-barang').on('change', '#hrg_satuan,#ppn,#profit', function(){
       
        hitungHrgJual();
    });    

    $('#form-tambah-barang').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id_edit').val();
        if(parameter==''){
            // tambah
            console.log('parameter:tambah');
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
                url: '<?=$root_ser?>/Barang.php?fx=simpan',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    alert('Input data '+result.message);
                    if(result.status){
                        // location.reload();
                        dataTable.ajax.reload();
                        $('#saku-data-barang').show();
                        $('#form-tambah-barang').hide();
                    }
                },
                fail: function(xhr, textStatus, errorThrown){
                    alert('request failed:'+textStatus);
                }
            });
        }else{
            console.log('parameter:ubah');
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
                url: '<?=$root_ser?>/Barang.php?fx=ubah',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false,  
                success:function(result){
                    alert('Update data '+result.message);
                    if(result.status){
                        dataTable.ajax.reload();
                        $('#saku-data-barang').show();
                        $('#form-tambah-barang').hide();
                    }
                }
            });
        }
        
    });

    $('#barcode').scannerDetection({
        
        //https://github.com/kabachello/jQuery-Scanner-Detection

        timeBeforeScanTest: 200, // wait for the next character for upto 200ms
        avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
        preventDefault: true,

        endChar: [13],
        onComplete: function(barcode, qty){
        validScan = true;
            $('#barcode').val (barcode);
        
        } // main callback function	,
        ,
        onError: function(string, qty) {
            console.log('barcode-error');
        }	
    });

    $('#kode_barang,#nama,#barcode,#kode_klp,#keterangan,#kode_satuan,#harga_jual,#flag_aktif,#ss,#sm1,#sm2,#mm1,#mm2,#fm1,#fm2,#file_gambar').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_barang','nama','barcode','kode_klp','keterangan','kode_satuan','harga_jual','flag_aktif','ss','sm1','sm2','mm1','mm2','fm1','fm2','file_gambar'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 3 || idx == 5 || idx == 7){
                $('#'+nxt[idx])[0].selectize.focus();
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

    </script>