<?php
	session_start();
	$root_lib=$_SERVER["DOCUMENT_ROOT"];
	if (substr($root_lib,-1)!="/") {
		$root_lib=$root_lib."/";
	}
	include_once($root_lib.'app/ajax/setting.php');
	
	$nik=$_SESSION['userLog'];
	$kode_lokasi=$_SESSION['lokasi'];
	$nik=$_SESSION['userLog'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-data-job">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data BackLog 
                        <button type="button" id="btn-job-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <!-- <h6 class="card-subtitle">Tabel Data Proyek</h6> -->
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
                            </style>
                            <table id="table-job" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>Kode Job</th>
                                        <th>Nama Job</th>
                                        <th>Kode Proyek</th>
                                        <th>NIK</th>
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
        <div class="row" id="form-tambah-job" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title mb-2">Form Data BackLog
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-job-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-3">
                                    <input class="form-control" type="hidden" placeholder="Kode" id="kode_job" name="kode_job" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama Job</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Job" id="nama" name="nama" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Proyek</label>
                                <div class="col-3">
                                    <select class='form-control' id="no_proyek" name="no_proyek" required>
                                    <option value=''>--- Pilih Proyek ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">NIK</label>
                                <div class="col-3">
                                    <select class='form-control' id="nik" name="nik" required>
                                    <option value=''>--- Pilih NIK ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_mulai" class="col-3 col-form-label">Tgl Mulai</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="tgl_mulai" name="tgl_mulai">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_selesai" class="col-3 col-form-label">Tgl Selesai</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="tgl_selesai" name="tgl_selesai">
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#det" role="tab" aria-selected="true"><span class="hidden-xs-down">Detail Job</span></a> </li>
                            </ul>
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="det" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                            th,td{
                                                padding:8px !important;
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="input-grid2">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="45%">Nama Detail</th>
                                                <th width="40%">Keterangan</th>
                                                <th width="10%"><button type="button" href="#" id="add-row" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></th>
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
        <div class="row" id="slide-history" style="display:none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-secondary ml-2" id="btn-job-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        <div class="profiletimeline mt-5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    <script>

    function getProyek(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Job.php?fx=getProyek',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#no_proyek').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].no_proyek + ' - ' + result.daftar[i].nama, value:result.daftar[i].no_proyek}]);
                        }
                    }
                }
            }
        });
    }

    function getKaryawan(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Job.php?fx=getKaryawan',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#nik').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].nik + ' - ' + result.daftar[i].nama, value:result.daftar[i].nik}]);
                        }
                    }
                }
            }
        });
    }

    getProyek();
    getKaryawan();
    $('#saku-data-job').on('click', '#btn-job-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#kode_job').attr('readonly', false);
        $('.preview').html('');
        $('#input-grid2 tbody').html('');
        $('#saku-data-job').hide();
        $('#form-tambah-job').show();
    });

    $('#saku-data-job').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Job.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_job':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_job').val(id);
                    $('#kode_job').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#no_proyek')[0].selectize.setValue(result.daftar[0].no_proyek);
                    $('#nik')[0].selectize.setValue(result.daftar[0].nik);
                    $('#tgl_mulai').val(result.daftar[0].tgl_mulai);
                    $('#tgl_selesai').val(result.daftar[0].tgl_selesai);
                    $('#row-id').show();
                    $('#saku-data-job').hide();
                    $('#form-tambah-job').show();

                    var input="";
                    $('#input-grid2 tbody').html('');
                    no=1;
                    if(result.daftar2.length>0){
                        for(var i=0;i<result.daftar2.length;i++){
                            input += "<tr class='row-job'>";
                            input += "<td width='5%' class='no-job'>"+no+"<input type='hidden' name='no[]' class='form-control inp-no' value='"+no+"' required></td>";
                            input += "<td width='45%'><input type='text' name='nama_det[]' class='form-control inp-nama' value='"+result.daftar2[i].nama+"' required></td>";
                            input += "<td width='40%'><input type='text' name='ket_det[]' class='form-control inp-ket' value='"+result.daftar2[i].keterangan+"' required></td>";
                            input += "<td width='10%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                            input += "</tr>";
                            no++;
                        }
                    }
                    $('#input-grid2 tbody').append(input);
                }
            }
        });
    });


    $('#form-tambah-job').on('click', '#btn-job-kembali', function(){
        $('#saku-data-job').show();
        $('#form-tambah-job').hide();
    });

    $('#slide-history').on('click', '#btn-job-kembali', function(){
        $('#saku-data-job').show();
        $('#form-tambah-job').hide();
        $('#slide-history').hide();
    });

    $('#form-tambah-job').on('click', '#add-row', function(){

        var no=$('#input-grid2 .row-job:last').index();
        no=no+2;
        var input = "";
        input += "<tr class='row-job'>";
        input += "<td width='5%' class='no-job'>"+no+"<input type='hidden' name='no[]' class='form-control inp-no' value='"+no+"' required></td>";
        input += "<td width='45%'><input type='text' name='nama_det[]' class='form-control inp-nama' value='' required></td>";
        input += "<td width='40%'><input type='text' name='ket_det[]' class='form-control inp-ket' value='' required></td>";
        input += "<td width='10%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
        input += "</tr>";
        $('#input-grid2 tbody').append(input);
        // $('.currency').inputmask("numeric", {
        //     radixPoint: ",",
        //     groupSeparator: ".",
        //     digits: 2,
        //     autoGroup: true,
        //     rightAlign: true,
        //     oncleared: function () { self.Value(''); }
        // });
        $('#input-grid2 tbody tr:last').find('.inp-nama').focus();
    });

    $('#input-grid2').on('keydown', '.inp-nama', function(e){
        if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            $(this).closest('tr').find('.inp-ket').focus();
        }
    });

    $('#input-grid2').on('keydown', '.inp-ket', function(e){
        if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            $('#add-row').click();
        }
    });

    $('#input-grid2').on('click', '.hapus-item', function(){
        $(this).closest('tr').remove();
        no=1;
        $('.row-job').each(function(){
            var nom = $(this).closest('tr').find('.no-job');
            nom.html(no);
            no++;
        });
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-job').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Job.php?fx=getJob',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        }
        // 'columnDefs': [
        //     {'targets': 6, data: null, 'defaultContent': action_html }
        //     ]
    });

    $('#saku-data-job').on('click','#btn-delete',function(e){
        
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
                var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
                
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/Job.php',
                    dataType: 'json',
                    async:false,
                    data: {'kode_job':kode,'kode_lokasi':kode_lokasi},
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

    $('#form-tambah-job').on('submit', '#form-tambah', function(e){
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
                url: '<?=$root_ser?>/Job.php?fx=simpan',
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
                        $('#saku-data-job').show();
                        $('#form-tambah-job').hide();
                        
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: '<a href>'+result.message+'</a>'
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
                url: '<?=$root_ser?>/Job.php?fx=ubah',
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
                            'Your data has been updated',
                            'success'
                        )
                        $('#saku-data-job').show();
                        $('#form-tambah-job').hide();
                        
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
        
    });

    $('#saku-data-job').on('click','#btn-history',function(e){
        var id = $(this).closest('tr').find('td').eq(0).html();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Job.php?fx=getHistory',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_job':id},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var html='';
                        for(var i=0;i<result.daftar.length;i++){
                            html +=`<div class="sl-item"> <div class="sl-left" style="margin-left: -65px;"> <div style="padding: 10px;border: 1px solid #03a9f3;border-radius: 50%;background: #03a9f3;color: white;width: 50px;text-align: center;"><i style="font-size: 25px;" class="fas fa-clipboard-check"></i> </div> 
                                </div>
                                <div class="sl-right">
                                    <div><a href="javascript:void(0)" class="link">`+result.daftar[i].nama+`</a> <span class="sl-date">`+result.daftar[i].tanggal+`</span>
                                    <div class="row mt-3 mb-2">
                                        <div class="col-md-6">No Bukti : </div>
                                        <div class="col-md-6">`+result.daftar[i].kode_job+`</div>
                                        <div class="col-md-6">No Urut Detail : </div>
                                        <div class="col-md-6">`+result.daftar[i].nu+`</div>
                                        <div class="col-md-6">NIK : </div>
                                        <div class="col-md-6">`+result.daftar[i].nik+`</div>
                                        <div class="col-md-6">Catatan : </div>
                                        <div class="col-md-6">`+result.daftar[i].keterangan+`</div>
                                    </div>
                            </div>
                            </div>
                            <hr>`;
                        }
                        
                        $('.profiletimeline').html(html);
                        $('#slide-history').show();
                        $('#saku-data-job').hide();
                        $('#form-tambah-job').hide();
                    }
                }
            }
        });
    });

    $('#kode_job,#nama,#no_proyek,#nik,#tgl_awal,#tgl_selesai').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_job','nama','no_proyek','nik','tgl_awal','tgl_selesai'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 1){
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