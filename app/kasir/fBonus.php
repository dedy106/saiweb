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
        <div class="row" id="saku-data-bonus">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4" ><i class='fas fa-cube'></i> Data Bonus 
                        <button type="button" id="btn-bonus-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button></h4>
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
                            <table id="table-bonus" class="table table-bordered table-striped" style='width: 100%;'>
                                <thead>
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Nama Bonus</th>
                                        <th>Referensi Qty</th>
                                        <th>Bonus Qty</th>
                                        <th>Tgl Mulai</th>
                                        <th>Tgl Selesai</th>
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
        <div class="row" id="form-tambah-bonus" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title">Form Data Bonus
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-bonus-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <h6 class="card-subtitle">Input Data Bonus</h6>
                            <hr>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_barang" class="col-3 col-form-label">Kode Barang</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_barang" name="kode_barang">
                                    <option value=''>--- Pilih Barang ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-3 col-form-label">Keterangan Bonus</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Keterangan Bonus" id="keterangan" name="keterangan">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ref_qty" class="col-3 col-form-label">Referensi Qty</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Ref Qty" id="ref_qty" name="ref_qty">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bonus_qty" class="col-3 col-form-label">Bonus Qty</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Bonus Qty" id="bonus_qty" name="bonus_qty">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_mulai" class="col-3 col-form-label">Tgl Mulai</label>
                                <div class="col-9">
                                    <input class="form-control" type="date" id="tgl_mulai" name="tgl_mulai">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_selesai" class="col-3 col-form-label">Tgl Selesai</label>
                                <div class="col-9">
                                    <input class="form-control" type="date" id="tgl_selesai" name="tgl_selesai">
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
    function getBarang(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Bonus.php?fx=getBarang',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_barang').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_barang + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_barang}]);
                        }
                    }
                }
            }
        });
    }
    getBarang();
    $('#saku-data-bonus').on('click', '#btn-bonus-tambah', function(){
        $('#row-id').hide();
        $('#id').val('');
        $('#kode_barang').attr('readonly', false);
        $('#saku-data-bonus').hide();
        $('#form-tambah-bonus').show();
        $('#form-tambah')[0].reset();
    });

    $('#saku-data-bonus').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Bonus.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_barang':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#keterangan').val(result.daftar[0].keterangan);
                    $('#kode_barang')[0].selectize.setValue(result.daftar[0].kode_barang);
                    $('#ref_qty').val(result.daftar[0].ref_qty);
                    $('#bonus_qty').val(result.daftar[0].bonus_qty);
                    $('#tgl_mulai').val(result.daftar[0].tgl_mulai);
                    $('#tgl_selesai').val(result.daftar[0].tgl_selesai);
                    $('#row-id').show();
                    $('#saku-data-bonus').hide();
                    $('#form-tambah-bonus').show();
                }
            }
        });
    });


    $('#form-tambah-bonus').on('click', '#btn-bonus-kembali', function(){
        $('#saku-data-bonus').show();
        $('#form-tambah-bonus').hide();
    });

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-bonus').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Bonus.php?fx=getBonus',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 6, data: null, 'defaultContent': action_html }
            ]
    });

    $('#saku-data-bonus').on('click','#btn-delete',function(e){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/Bonus.php',
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

    $('#form-tambah-bonus').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id').val();
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
                url: '<?=$root_ser?>/Bonus.php?fx=simpan',
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
                        $('#saku-data-bonus').show();
                        $('#form-tambah-bonus').hide();
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
                url: '<?=$root_ser?>/Bonus.php?fx=ubah',
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
                        $('#saku-data-bonus').show();
                        $('#form-tambah-bonus').hide();
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

    $('#kode_barang,#keterangan,#ref_qty,#bonus_qty,#tgl_mulai,#tgl_selesai').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_barang','keterangan','ref_qty','bonus_qty','tgl_mulai','tgl_selesai'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 0){
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