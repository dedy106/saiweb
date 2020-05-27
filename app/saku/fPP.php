<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/saku/setting.php');


   $kode_lokasi=$_SESSION['lokasi'];
   $nik=$_SESSION['userLog'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-datatable">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data PP 
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
                            </style>
                            <table id="table-data" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>Kode PP</th>
                                        <th>Nama</th>
                                        <th>Status</th>
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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Form Data PP
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:441px'>
                        <form class="form" id="form-tambah" style='margin-bottom:100px'>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="kode_pp" class="col-3 col-form-label">Kode PP</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode PP" id="kode_pp" name="kode_pp" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama PP" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="initial" class="col-3 col-form-label">Inisial</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Inisial PP" id="initial" name="initial">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_bidang" class="col-3 col-form-label">Bidang</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Bidang" id="kode_bidang" name="kode_bidang">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_ba" class="col-3 col-form-label">Kode BA</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode BA" id="kode_ba" name="kode_ba">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_pc" class="col-3 col-form-label">Kode PC</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode PC" id="kode_pc" name="kode_pc">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kota" class="col-3 col-form-label">Kota</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kota" id="kota" name="kota">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="flag_aktif" class="col-3 col-form-label">Status Aktif</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="flag_aktif" name="flag_aktif">
                                    <option value=''>--- Pilih Status ---</option>
                                    <option value='1'>AKTIF</option>
                                    <option value='0'>NON AKTIF</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="preview col-3">
                                </div>
                                <div class="col-2">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    <script>

    // function getPP(){
    //     $.ajax({
    //         type: 'GET',
    //         url: '<?=$root_ser?>/PP.php?fx=getPP',
    //         dataType: 'json',
    //         async:false,
    //         data: {'kode_lokasi':'<?=$kode_lokasi?>'},
    //         success:function(result){    
    //             if(result.status){
    //                 if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
    //                     var select = $('#kode_pp').selectize();
    //                     select = select[0];
    //                     var control = select.selectize;
    //                     for(i=0;i<result.daftar.length;i++){
    //                         control.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp}]);
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // }
   
    // getPP();
    $('.selectize').selectize();
    $('#saku-datatable').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#kode_pp').attr('readonly', false);
        $('.preview').html('');
        $('#saku-datatable').hide();
        $('#saku-form').show();
    });

    $('.custom-file-input').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })

    
    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();
        
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/PP.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_pp':id,'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_pp').val(id);
                    $('#kode_pp').attr('readonly', true);
                    $('#initial').val(result.daftar[0].initial);
                    $('#kode_bidang').val(result.daftar[0].kode_bidang);
                    $('#kode_ba').val(result.daftar[0].kode_ba);
                    $('#kode_pc').val(result.daftar[0].kode_pc);
                    $('#kota').val(result.daftar[0].kota);
                    $('#flag_aktif').val(result.daftar[0].flag_aktif);
                    $('#row-id').show();
                    $('#saku-datatable').hide();
                    $('#saku-form').show();
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
            'url': '<?=$root_ser?>/PP.php?fx=getView',
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
                var kode_lokasi = '<?=$kode_lokasi?>';
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/PP.php',
                    dataType: 'json',
                    async:false,
                    data: {'kode_pp':kode,'kode_lokasi':kode_lokasi},
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
        var parameter = $('#id').val();
        if(parameter==''){
            // tambah
            console.log('parameter:tambah');
            var formData = new FormData(this);
            for(var pair of formData.entries()) {
                    console.log(pair[0]+ ', '+ pair[1]); 
                }

            var kode_lokasi = '<?=$kode_lokasi?>';
            formData.append('kode_lokasi',kode_lokasi);

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/PP.php?fx=simpan',
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
            
            var kode_lokasi = '<?=$kode_lokasi?>';
            formData.append('kode_lokasi',kode_lokasi);
            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/PP.php?fx=ubah',
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
                }
            });
        }
        
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