<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/tarbak/setting.php');
    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];
    $kode_pp=$_SESSION['kodePP'];
?>
<link href="<?=$folder_css?>/custom.css" rel="stylesheet">
    <div class="container-fluid mt-3">
        <div class="row" id="saku-datatable">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Jadwal Harian 
                        <button type="button" id="btn-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <hr>
                        <div class="table-responsive ">
                            <table id="table-data" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>Kode TA</th>
                                        <th>Kode Matpel</th>
                                        <th>Kode Guru</th>
                                        <th>Kelas</th>
                                        <th>Kode PP</th>
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
            <div class="col-sm-12" style="height: 90px;">
                <div class="card">
                    <div class="card-body pb-0">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Form Jadwal Harian
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:460px'>
                        <form class="form" id="form-tambah" style='margin-bottom:10px'>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_pp" class="col-2 col-form-label">Kode PP</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_pp" name="kode_pp">
                                    <option value='' disabled>--- Pilih PP ---</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                </div>
                                <label for="kode_ta" class="col-2 col-form-label">Tahun Ajaran</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_ta" name="kode_ta">
                                    <option value='' disabled>--- Pilih Tahun Ajaran ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nik_guru" class="col-2 col-form-label">NIK Guru</label>
                                <div class="col-3">
                                    <select class='form-control' id="nik_guru" name="nik_guru">
                                        <option value='' disabled>--- Pilih Guru ---</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                </div>
                                <label for="kode_matpel" class="col-2 col-form-label">Mata Pelajaran</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_matpel" name="kode_matpel">
                                        <option value='' disabled>--- Pilih Mata Pelajaran ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_kelas" class="col-2 col-form-label">Kode Kelas</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_kelas" name="kode_kelas">
                                        <option value='' disabled>--- Pilih Kelas ---</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="button" id="btn-load" class="btn btn-info" style="float:right;"><i class="ti-reload"></i> Tampil Data</button>
                                </div>
                                <label for="total" class="col-2 col-form-label">Total</label>
                                <div class="col-3">
                                    <input type='text' class='form-control currency' id='total' name='total'>
                                </div>
                            </div>
                            <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:250px; margin:0px; padding:0px;'>
                                <style>
                                th,td{
                                    padding:8px !important;
                                    vertical-align:middle !important;
                                }
                                </style>
                                <table class="table table-striped table-bordered table-condensed" id="input-grid" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="10%">Slot</th>
                                            <th width="20%">Keterangan Slot</th>
                                            <th width="10%">Senin</th>
                                            <th width="10%">Selasa</th>
                                            <th width="10%">Rabu</th>
                                            <th width="10%">Kamis</th>
                                            <th width="10%">Jumat</th>
                                            <th width="10%">Sabtu</th>
                                            <th width="10%">Minggu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id='mySidepanel' class='sidepanel close'>
            <h3 style='margin-bottom:20px;position: absolute;'>Filter Data</h3>
            <a href='#' id='btnClose'><i class="float-right ti-close" style="margin-top: 10px;margin-right: 10px;"></i></a>
            <form id="formFilter2" style='margin-top:50px'>
            <div class="row" style="margin-left: -5px;">
                <div class="col-sm-12">
                    <div class="form-group" style='margin-bottom:0'>
                        <label for="kode_pp">PP</label>
                        <select name="kode_pp" id="kode_pp2" class="form-control">
                        <option value="">Pilih PP</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary" style="margin-left: 6px;margin-top: 28px;"><i class="fa fa-search" id="btnPreview2"></i> Preview</button>
                </div>
            </div>
            </form>
        </div>
    </div>           
   
    <script src="<?=$folderroot_js?>/sai.js"></script>
    <script src="<?=$folderroot_js?>/inputmask.js"></script>            
    <script>
    
    function openFilter() {
        var element = $('#mySidepanel');
        
        var x = $('#mySidepanel').attr('class');
        var y = x.split(' ');
        if(y[1] == 'close'){
            element.removeClass('close');
            element.addClass('open');
        }else{
            element.removeClass('open');
            element.addClass('close');
        }
    }
    
    
    $('#kode_pp').selectize({
        selectOnTab:true,
        onChange: function (val){
            var id = val;
            if (id != "" && id != null && id != undefined){
                getTA(id);
                getNIKGuru(id);
                getMatpel(id);
            }
        }
    });

    $('#nik_guru').selectize({
        selectOnTab:true,
        onChange: function (val){
            var id = val;
            var pp = $('#kode_pp')[0].selectize.getValue();
            if (id != "" && id != null && id != undefined){
                getMatpel(pp,id);
            }
        }
    });

    function getPP(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JadwalHarian.php?fx=getPP',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_pp').selectize();
                        select = select[0];
                        var control = select.selectize;

                        var select2 = $('#kode_pp2').selectize();
                        select2 = select2[0];
                        var control2 = select2.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp}]);
                            control2.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp + '-' + result.daftar[i].nama}]);
                        }
                    }
                }
            }
        });
    }

    getPP();

    function getTA(id=null){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JadwalHarian.php?fx=getTA',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_pp':id},
            success:function(result){    
                var select = $('#kode_ta').selectize();
                select = select[0];
                var control = select.selectize;
                var kode = control.getValue();
                control.clearOptions();
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_ta + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_ta}]);

                        }
                    }
                }
                if(kode != ""){
                    control.setValue(kode);
                }
            }
        });
    }

    getTA();

    function getNIKGuru(id=null){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JadwalHarian.php?fx=getNIKGuru',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_pp':id},
            success:function(result){    
                var select = $('#nik_guru').selectize();
                select = select[0];
                var control = select.selectize;
                var kode = control.getValue();
                control.clearOptions();
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].nik + ' - ' + result.daftar[i].nama, value:result.daftar[i].nik}]);

                        }
                    }
                }
                if(kode != ""){
                    control.setValue(kode);
                }
            }
        });
    }

    getNIKGuru();

    function getMatpel(id=null,nik=null){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JadwalHarian.php?fx=getMatpel',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_pp':id,'nik_guru':nik},
            success:function(result){    
                var select = $('#kode_matpel').selectize();
                select = select[0];
                var control = select.selectize;
                var kode = control.getValue();
                control.clearOptions();
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_matpel + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_matpel}]);

                        }
                    }
                }
                if(kode != ""){
                    control.setValue(kode);
                }
            }
        });
    }

    getMatpel();

    
    function getKelas(id=null){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JadwalHarian.php?fx=getKelas',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_pp':id},
            success:function(result){    
                var select = $('#kode_kelas').selectize();
                select = select[0];
                var control = select.selectize;
                var kode = control.getValue();
                control.clearOptions();
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_kelas + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_kelas}]);

                        }
                    }
                }
                if(kode != ""){
                    control.setValue(kode);
                }
            }
        });
    }

    getKelas();

    
    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var kode_pp = '<?php echo $kode_pp ?>';
    var dataTable = $('#table-data').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/JadwalHarian.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi,'kode_pp':kode_pp},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 5, data: null, 'defaultContent': action_html }
        ],
        dom: 'lBfrtip',
        buttons: [
            {
                text: '<i class="fa fa-filter"></i> Filter',
                action: function ( e, dt, node, config ) {
                    openFilter();
                },
                className: 'btn btn-default ml-2' 
            }
        ]
    });

    var tableGrid = $('#input-grid').DataTable({
        'data' :[],
        'columns': [
            { data: 'no' },
            { data: 'kode_slot' },
            { data: 'nama' },
            { data: 'senin' },
            { data: 'selasa' },
            { data: 'rabu' },
            { data: 'kamis' },
            { data: 'jumat' },
            { data: 'sabtu' },
            { data: 'minggu' }
        ],
    });

    function loadJadwal(){
        var kode_pp= $('#kode_pp')[0].selectize.getValue();
        var kode_ta= $('#kode_ta')[0].selectize.getValue();
        var kode_matpel= $('#kode_matpel')[0].selectize.getValue();
        var nik_guru= $('#nik_guru')[0].selectize.getValue();
        var kode_kelas= $('#kode_kelas')[0].selectize.getValue();
        
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JadwalHarian.php?fx=getLoadJadwal',
            dataType: 'json',
            data: {'kode_lokasi':'<?php echo $kode_lokasi ?>','kode_pp':kode_pp,'kode_ta':kode_ta,'kode_matpel':kode_matpel,'nik_guru':nik_guru,'kode_kelas':kode_kelas},
            success:function(result){    
                if(result.status){
                    tableGrid.clear().draw();
                    if(typeof result.jadwal !== 'undefined' && result.jadwal.length>0){
                        tableGrid.rows.add(result.jadwal).draw(false);
                    }
                }
            }
        });
    }

    function hitungTotal(){
        var jumlah = 0;
        var data = tableGrid.data();
        // console.log(data.count());
        for (var i = 0; i < data.count();i++){
            for (var j = 3; j < 10;j++){
                if (tableGrid.cell(i,j).data() == "ISI") jumlah++;
            }
		}	
        // console.log(tableGrid.cell(4,5).data());
        $('#total').val(jumlah);
    }

$(document).ready(function(){


    $('#input-grid tbody').on('dblclick','td',function(e){
        var cell = tableGrid.cell( this );
        if(cell.data() == 'KOSONG'){
            var isi = 'ISI';
        }else if(cell.data() == 'ISI'){
            var isi = 'KOSONG';
        }
        cell.data(isi).draw();
        hitungTotal();
    });

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var kode_ta= $(this).closest('tr').find('td').eq(0).html();
        var nik_guru= $(this).closest('tr').find('td').eq(2).html();
        var kode_matpel= $(this).closest('tr').find('td').eq(1).html();
        var kode_kelas= $(this).closest('tr').find('td').eq(3).html();
        var tmp= $(this).closest('tr').find('td').eq(4).html().split('-');
        var kode_pp = tmp[0];
       
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JadwalHarian.php?fx=getLoadJadwal',
            dataType: 'json',
            async:false,
            data: {'kode_ta':kode_ta,'nik_guru':nik_guru,'kode_matpel':kode_matpel,'kode_kelas':kode_kelas,'kode_pp':kode_pp,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_pp')[0].selectize.setValue(kode_pp);
                    $('#kode_ta')[0].selectize.setValue(kode_ta);
                    $('#nik_guru')[0].selectize.setValue(nik_guru);
                    $('#kode_matpel')[0].selectize.setValue(kode_matpel);
                    $('#kode_kelas')[0].selectize.setValue(kode_kelas);
                    $('#btn-load').hide();

                    tableGrid.clear().draw();
                    if(typeof result.jadwal !== 'undefined' && result.jadwal.length>0){
                        tableGrid.rows.add(result.jadwal).draw(false);
                    }

                    hitungTotal();
                    
                    $('#saku-datatable').hide();
                    $('#saku-form').show();
                }
            }
        });
    });
		

     
    $('.sidepanel').on('submit', '#formFilter2', function(e){
        e.preventDefault();
        var kode_pp= $('#kode_pp2')[0].selectize.getValue();
        dataTable.search(kode_pp).draw();
    });
 
    $('.sidepanel').on('click', '#btnClose', function(e){
        e.preventDefault();
        openFilter();
    });

    $('#form-tambah').on('click', '#btn-load', function(e){
        e.preventDefault();
        loadJadwal();
    });
    
    $('#saku-datatable').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#kode_pp')[0].selectize.setValue('');
        $('#kode_ta')[0].selectize.setValue('');
        $('#nik_guru')[0].selectize.setValue('');
        $('#kode_matpel')[0].selectize.setValue('');
        $('#kode_kelas')[0].selectize.setValue('');
        $('#id').val('');
        $('#input-grid tbody').html('');
        $('#btn-load').show();
        $('#saku-datatable').hide();
        $('#saku-form').show();
    });

    $('#saku-form').on('click', '#btn-kembali', function(){
        $('#saku-datatable').show();
        $('#saku-form').hide();
    });

    
    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });


    $('#saku-datatable').on('click','#btn-delete',function(e){
        e.preventDefault();
        Swal.fire({
        title: 'Yakin Data Akan Dihapus?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Dihapus!'
        }).then((result) => {
            if (result.value) {
                var kode = $(this).closest('tr').find('td:eq(0)').html(); 
                var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
                
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/JadwalHarian.php',
                    dataType: 'json',
                    async:false,
                    data: {'id_pekerjaan':kode,'kode_lokasi':kode_lokasi},
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
                            title: 'Error',
                            text: result.message
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
      
        console.log('parameter:tambah');
        var formData = new FormData(this);
    
        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        
        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);

        var data = tableGrid.data();
        
        var i=0;
        $.each( data, function( key, value ) {
            formData.append('kode_slot[]',value.kode_slot);
            formData.append('senin[]',value.senin);
            formData.append('selasa[]',value.selasa);
            formData.append('rabu[]',value.rabu);
            formData.append('kamis[]',value.kamis);
            formData.append('jumat[]',value.jumat);
            formData.append('sabtu[]',value.sabtu);
            formData.append('minggu[]',value.minggu);
        });

        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
    
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/JadwalHarian.php?fx=simpan',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                if(result.status){
                    dataTable.ajax.reload();
                    Swal.fire(
                        'Great Job!',
                        'Data Berhasil Disimpan',
                        'success'
                    )
                    $('#saku-datatable').show();
                    $('#saku-form').hide();
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

    $('#id_pekerjaan,#nama').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['id_pekerjaan','nama'];
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
})
    </script>