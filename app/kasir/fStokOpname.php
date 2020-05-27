<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/kasir/setting.php');


   $kode_lokasi=$_COOKIE['lokasi'];
   $nik=$_COOKIE['userLog'];
   $periode=$_COOKIE['periode'];
   $kode_pp=$_COOKIE['kodePP'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-datatable">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Stok Opname 
                            <button type="button" id="btn-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
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
                            <table id="table-data" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>No Bukti</th>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
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
        <div class="row" id="saku-form" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body pb-0">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Form Stok Opname
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        <div id="loading-bar" class="float-right" style="display:none;"><button type="button" disabled="" class="btn btn-info">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                        </button></div>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:441px'>
                        <form class="form" id="form-tambah" style=''>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal" class="col-2 col-form-label">Tanggal</label>
                                <div class="col-3">
                                    <input class='form-control' type="date" id="tanggal" name="tanggal" value="<?=date('Y-m-d')?>">
                                </div>
                                <div class="col-2">
                                </div>
                                <div class="col-3" style="display:none">
                                    <input class="form-control" type="text" placeholder="No Bukti" id="no_bukti" name="no_bukti" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="deskripsi" class="col-2 col-form-label">Deskripsi</label>
                                <div class="col-4">
                                    <input class="form-control" type="text" placeholder="Deskripsi" id="deskripsi" name="deskripsi">
                                </div>
                                <div class="col-1">
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <label for="kode_gudang" class="col-2 col-form-label">Gudang</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_gudang" name="kode_gudang">
                                    <option value=''>--- Pilih Gudang ---</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                   
                                </div>
                                <div class='col-4 pull-right'>
                                    <button type="button" class="btn btn-info ml-2" id="btn-rekon" style="float:right;">Rekon</button>
                                    <button type="button" class="btn btn-info ml-2" id="btn-load" style="float:right;">Load</button>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#sistem" role="tab" aria-selected="true"><span class="hidden-xs-down">Data Item Barang</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#fisik" role="tab" aria-selected="false"><span class="hidden-xs-down">Data Jumlah Fisik</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#error_tab" role="tab" aria-selected="false"><span class="hidden-xs-down">Error Upload</span></a> </li>
                                <li class="nav-item ml-auto"> <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="file_dok" name="file_dok">
                                <label class="custom-file-label form-control" for="file_dok" name="file_dok" data-browse="Browse" style="color: grey;font-style: italic;">Upload File .xls</label>
                                </div></li>
                            </ul>
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="sistem" role="tabpanel">
                                    <div class='col-xs-12' style='overflow-y: scroll; height:230px; margin:0px; padding:0px;'>
                                        <table class="table table-striped table-bordered table-condensed" id="input-grid" width="100%">
                                        <style>
                                            th{
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="10%">Kode Barang</th>
                                                <th width="25%">Nama</th>
                                                <th width="10%">Satuan</th>
                                                <th width="10%">Stok Sistem</th>
                                                <th width="10">Stok Fisik</th>
                                                <th width="10">Selisih</th>
                                                <th width="20">Barcode</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="fisik" role="tabpanel">
                                    <div class='col-xs-12' style='overflow-y: scroll; height:230px; margin:0px; padding:0px;'>
                                        <table class="table table-striped table-bordered table-condensed" id="input-grid2" width="100%">
                                        <style>
                                            th{
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="30%">Kode Barang</th>
                                                <th width="40%">Jumlah Fisik</th>                                            
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                    </div>      
                                </div>
                                <div class="tab-pane" id="error_tab" role="tabpanel">
                                    <div class='col-xs-12' style='overflow-y: scroll; height:230px; margin:0px; padding:0px;'>
                                        <table class="table table-striped table-bordered table-condensed" id="input-error" width="100%">
                                        <style>
                                            th{
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="30%">Kode Barang</th>
                                                <th width="40%">Error Message</th>                                            
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                    </div>      
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
            
    <script>
    var $iconLoad = $('#loading-bar');
    
    var table = $('#input-grid').DataTable({
            data: [],
            columns: [
                { data: 'no' },
                { data: 'kode_barang' },
                { data: 'nama' },
                { data: 'satuan' },
                { data: 'stok' },
                { data: 'jumlah' },
                { data: 'selisih' },
                { data: 'barcode' },
            ],
            "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]]
    });
 
    table.on( 'order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    var table2 = $('#input-grid2').DataTable({
            data: [],
            columns: [
                { data: 'no' },
                { data: 'kode_barang' },
                { data: 'jumlah' },
            ],
            "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]]
    });
 
    table2.on( 'order.dt search.dt', function () {
        table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    var table3 = $('#input-error').DataTable({
            data: [],
            columns: [
                { data: 'no' },
                { data: 'kode_barang' },
                { data: 'err_msg' },
            ],
            "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]]
    });
 
    table3.on( 'order.dt search.dt', function () {
        table3.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();

    function getGudang(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/StokOpname.php?fx=getGudang',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_pp':'<?=$kode_pp?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_gudang').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_gudang + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_gudang}]);
                        }
                        control.setValue(result.kode_gudang)
                    }
                }
            }
        });
    }
    
    getGudang();

    $('.custom-file-input').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })

    function doLoad(){
        var kode_gudang = $('#kode_gudang')[0].selectize.getValue();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/StokOpname.php?fx=getLoad',
            dataType: 'json',
            async:false,
            data: {'kode_gudang':kode_gudang,'kode_lokasi':'<?=$kode_lokasi?>','nik_user':'<?=$nik?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        table.clear().draw();
                        table.rows.add(result.daftar).draw(false);
                        Swal.fire(
                            'Great Job!',
                            'Load data '+result.message,
                            'success'
                        )
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>'+result.message+'</a>'
                    })
                }
            }
        });
    }

    function doRekon(){
        var data = table2.data();
        var formData = new FormData();
        
        var tempData = []; 
        var i=0;
        $.each( data, function( key, value ) {
            formData.append('kode_barang[]',value.kode_barang);
            formData.append('jumlah[]',value.jumlah);
        });
        
        formData.append('kode_lokasi',"<?=$kode_lokasi?>");
        formData.append('nik',"<?=$nik?>");
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/StokOpname.php?fx=simpanRekon',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                
                // alert('Rekon data '+result.message);
                
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        table.clear().draw();
                        table.rows.add(result.daftar).draw(false);
                        table2.clear().draw();
                    }
                    Swal.fire(
                        'Great Job!',
                        'Rekon data '+result.message,
                        'success'
                    )
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>'+result.message+'</a>'
                    })
                }
            }
        });
    }

    $('#saku-datatable').on('click', '#btn-tambah', function(){

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/StokOpname.php?fx=execSP',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','nik':'<?=$nik?>','periode':'<?=$periode?>'},
            success:function(result){
                if(result.status){
                    
                    $('#row-id').hide();
                    $('#form-tambah')[0].reset();
                    $('#id').val('');
                    $('#input-grid tbody').html('');
                    $('#saku-datatable').hide();
                    $('#saku-form').show();
                    table.clear().draw();
                    table2.clear().draw();
                    table3.clear().draw();
                }else{
                    alert(result.message);
                }
            }
        });
    });

    $('#form-tambah').on('click', '#btn-load', function(){
        doLoad();
    });

    $('#form-tambah').on('click', '#btn-rekon', function(){
        doRekon();

    });
  
   
    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();
        
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/StokOpname.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'no_bukti':id,'kode_lokasi':'<?=$kode_lokasi?>','nik':'<?=$nik?>','periode':'<?=$periode?>'},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#no_bukti').val(id);
                    $('#no_bukti').attr('readonly', true);
                    $('#tanggal').val(result.daftar[0].tanggal);
                    $('#deskripsi').val(result.daftar[0].keterangan);
                    $('#kode_gudang')[0].selectize.setValue(result.daftar[0].param1);
                    if(result.daftar2.length > 0){
                        table.clear().draw();
                        table.rows.add(result.daftar2).draw(false);
                    }
                    $('#row-id').show();
                    $('#saku-datatable').hide();
                    $('#saku-form').show();
                }else{
                    alert(result.message);
                }
            }
        });
    });


    $('#saku-form').on('click', '#btn-kembali', function(){
        $('#saku-datatable').show();
        $('#saku-form').hide();
    });

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-data').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/StokOpname.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 3, data: null, 'defaultContent': action_html }
            ]
    });

    $('#saku-datatable').on('click','#btn-delete',function(e){
        
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                var kode = $(this).closest('tr').find('td:eq(0)').html(); 
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/StokOpname.php',
                    dataType: 'json',
                    async:false,
                    data: {'no_bukti':kode},
                    success:function(result){
                        if(result.status){
                            dataTable.ajax.reload();
                            Swal.fire(
                                'Deleted!',
                                'Your data has been deleted.',
                                'success'
                            )
                        }else{
                            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: '<a href>'+result.message+'</a>'
                            })
                        }
                    }
                });
                
            }else{
                return false;
            }
        })
    });

    $('#saku-form').on('submit', '#form-tambah', function(e){
    e.preventDefault();

        var formData = new FormData(this);
        $iconLoad.show();
        formData.append('kode_lokasi',"<?=$kode_lokasi?>");
        formData.append('nik',"<?=$nik?>");
        formData.append('kode_pp',"<?=$kode_pp?>");

        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/StokOpname.php?fx=simpan',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
            // alert('Input data '+result.message);
                if(result.status){
                    // location.reload();
                    dataTable.ajax.reload();
                    Swal.fire(
                        'Great Job!',
                        'Your data has been saved',
                        'success'
                        )
                        $('#saku-datatable').show();
                        $('#saku-form').hide();
                    
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>'+result.message+'</a>'
                    })
                }
                $iconLoad.hide();
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:'+textStatus);
            }
        });        
        
    });

    $('#file_dok').change(function(e){
        var formData = new FormData();
        formData.append("file_dok", document.getElementById('file_dok').files[0]);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        formData.append("kode_lokasi", "<?=$kode_lokasi?>");
        formData.append("nik", "<?=$nik?>");
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/StokOpname.php?fx=simpanFisikTmp',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                // alert('Upload data fisik '+result.message);
                if(result.status){
                    // location.reload();
                    // dataTable.ajax.reload();
                    table3.clear().draw();
                    if(typeof result.data !== 'undefined' && result.data.length>0){
                        table2.clear().draw();
                        table2.rows.add(result.data).draw(false);
                    }
                    Swal.fire(
                        'Great Job!',
                        'Upload data fisik '+result.message,
                        'success'
                    )
                    
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>'+result.message+'</a>'
                    })
                    table2.clear().draw();
                    if(typeof result.error_list !== 'undefined' && result.error_list.length>0){
                        table3.clear().draw();
                        table3.rows.add(result.error_list).draw(false);
                    }
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:'+textStatus);
            }
        });        

    });


    $('#kode_pp,#nama,#initial,#kode_bidang,#kode_ba,#kode_pc,#kota,#flag_aktif ').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_pp','nama','initial','kode_bidang','kode_ba','kode_pc','kota','flag_aktif'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 8){
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