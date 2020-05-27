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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data kalender Akademik 
                        <button type="button" id="btn-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <hr>
                        <div class="table-responsive ">
                            <table id="table-data" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>Semester</th>
                                        <th>Tahun Ajaran</th>
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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Form Kalender Akademik
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
                                <label for="kode_pp" class="col-3 col-form-label">Kode PP</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_pp" name="kode_pp">
                                    <option value='' disabled>--- Pilih PP ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_ta" class="col-3 col-form-label">Tahun Ajaran</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_ta" name="kode_ta">
                                    <option value='' disabled>--- Pilih Tahun Ajaran ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_sem" class="col-3 col-form-label">Semester</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="kode_sem" name="kode_sem">
                                        <option value='' disabled>--- Pilih Semester ---</option>
                                        <option value='GANJIL'>GANJIL</option>
                                        <option value='GENAP'>GENAP</option>
                                    </select>
                                </div>
                            </div>
                            <div class='table-responsive'>
                            <table class="table table-striped table-bordered" id="mainTable" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Agenda</th>
                                            <th width="5%" class="text-center"><button type="button" style='padding:0' href="#" id="add-row" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>03/03/2020</td>
                                            <td>Testing Agenda</td>
                                            <td width='5%'></td>
                                        </tr>
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
            }
        }
    });

    function getPP(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/KalAkad.php?fx=getPP',
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
            url: '<?=$root_ser?>/KalAkad.php?fx=getTA',
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

    function addRow(no){
        inputGrid.row.add({
            "no":       no,
            "tanggal": "yyyy/mm/dd",
            "agenda": "",
            "action":""
        }).draw();
    }
    
    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var kode_pp = '<?php echo $kode_pp ?>';
    var dataTable = $('#table-data').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/KalAkad.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi,'kode_pp':kode_pp},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 3, data: null, 'defaultContent': action_html }
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

    
    $('#mainTable').editableTableWidget();
    var inputGrid = $('#mainTable').DataTable({
        // 'data' :[],
        'columns': [
            { data: 'no' },
            { data: 'tanggal' },
            { data: 'agenda' },
            { data: 'action' },
        ],
        'createdRow': function( row, data, dataIndex ) {
            $(row).addClass('row-kal');
        },
        'columnDefs': [
            {
                'targets': 0,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-editable', false); 
                    $(td).removeAttr('tabindex'); 
                    $(td).addClass('text-center'); 
                }
            },
            {
                'targets': 1,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('tabindex', 1);
                }
            },
            {
                'targets': 2,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('tabindex', 1);
                }
            },
            {
                'targets': 3,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-editable', false);
                    $(td).removeAttr('tabindex');  
                    $(td).addClass('text-center'); 
                    $(td).html('<button type="button" class="btn btn-sm btn-danger btn-hapus"><i class="ti-close" aria-hidden="true"></i></button>');
                }
            }
        ]
        // 'columnDefs': [
        //     {'targets': 3, data: null, 'defaultContent': '<button type="button" class="btn btn-sm btn-danger btn-hapus"><i class="ti-close" aria-hidden="true"></i></button>' }
        // ],
    });

    // addRow();
    // inputGrid.rows.add( [ {
    //     "no":       1,
    //     "tanggal": "2011/04/25",
    //     "agenda":     "Edinburgh",
    // }, {
    //     "no":       2,
    //     "tanggal": "2011/04/25",
    //     "agenda":     "test",
    // } ] )
    // .draw();

    $('.sidepanel').on('submit', '#formFilter2', function(e){
        e.preventDefault();
        var kode_pp= $('#kode_pp2')[0].selectize.getValue();
        dataTable.search(kode_pp).draw();
    });
 
    $('.sidepanel').on('click', '#btnClose', function(e){
        e.preventDefault();
        openFilter();
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
                    url: '<?=$root_ser?>/KalAkad.php',
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
   
    $('#saku-datatable').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#kode_pp')[0].selectize.setValue('');
        $('#kode_ta')[0].selectize.setValue('');
        $('#input-grid tbody').html('');
        $('#saku-datatable').hide();
        $('#saku-form').show();
    });

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var kode_sem= $(this).closest('tr').find('td').eq(0).html();
        var tmp= $(this).closest('tr').find('td').eq(2).html();
        var tmp = tmp.split("-");
        var kode_pp = tmp[0];   
        var kode_ta = $(this).closest('tr').find('td').eq(1).html();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/KalAkad.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_sem':kode_sem,'kode_ta':kode_ta,'kode_lokasi':kode_lokasi,'kode_pp':kode_pp},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_pp')[0].selectize.setValue(kode_pp);
                    $('#kode_sem')[0].selectize.setValue(kode_sem);
                    $('#kode_ta')[0].selectize.setValue(kode_ta);
                    
                    if(result.daftar2.length > 0){
                        var input = '';
                        var no=1;
                        for(var i=0;i<result.daftar2.length;i++){
                            var line =result.daftar2[i];
                                input += "<tr class='row-kal'>";
                                input += "<td width='5%' class='no-kal'>"+no+"<input type='hidden' required class='form-control inp-no' name='no_kal[]' value='"+no+"' ></td>";
                                input += "<td width='20%'><input type='date' required class='form-control inp-date' name='tanggal[]' value='"+line.tanggal+"' ></td>";
                                input += "<td width='60%'><input type='text' required class='form-control inp-agenda' name='agenda[]' value='"+line.agenda+"'></td>";
                                input += "<td width='5%' class='text-center'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                                input += "</tr>";
                            no++;
                        }
                        $('#input-grid tbody').html(input);
                    }
                   
                    $('#row-id').show();
                    $('#saku-datatable').hide();
                    $('#saku-form').show();
                }
            }
        });
    });

    $('#form-tambah').on('click', '#add-row', function(){
        var no=$('#mainTable .row-kal:last').index();
        console.log(no);
        no=no+2;
        addRow(no);
        // var input = "";
        // input += "<tr class='row-kal'>";
        // input += "<td width='5%' class='no-kal'>"+no+"<input type='hidden' required class='form-control inp-no' name='no_kal[]' value='"+no+"' ></td>";
        // input += "<td width='20%'><input type='date' required class='form-control inp-date' name='tanggal[]' ></td>";
        // input += "<td width='60%'><input type='text' required class='form-control inp-agenda' name='agenda[]' ></td>";
        // input += "<td width='5%' class='text-center'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
        // input += "</tr>";
        // $('#input-grid tbody').append(input);
        // $('#input-grid tbody tr:last').find('.inp-date')[0].selectize.focus();
    });

     
    $('#mainTable').on('click','.btn-hapus',function(){
        // $(this).closest('tr').remove();
        // no=1;
        // $('.row-kal').each(function(){
        //     var nom = $(this).closest('tr').find('.no-kal');
        //     nom.html(no+"<input type='hidden' required class='form-control inp-no name='no_kal[]' value='"+no+"' >");
        //     no++;
        // });
        console.log('hapus');
        inputGrid.row( $(this).parents('tr') ).remove().draw();
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });


    $('#saku-form').on('click', '#btn-kembali', function(){
        $('#saku-datatable').show();
        $('#saku-form').hide();
    });

    
    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });


    $('#saku-form').on('submit', '#form-tambah', function(e){
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
                url: '<?=$root_ser?>/KalAkad.php?fx=simpan',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    // alert('Input data '+result.message);
                    var pesan=result.message;
                    if(result.status){
                        // location.reload();
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
                url: '<?=$root_ser?>/KalAkad.php?fx=ubah',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false,  
                success:function(result){
                    // alert('Update data '+result.message);
                    if(result.status){
                        // location.reload();
                        dataTable.ajax.reload();
                        Swal.fire(
                            'Great Job!',
                            'Data Berhasil di Update',
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
                }
            });
        }
        
    });

    $('#kode_pp,#kode_ta,#kode_sem').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_pp','kode_ta','kode_sem'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 1 || idx == 2 || idx == 3){
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