<?php
   $kode_lokasi=$_SESSION['lokasi'];
   $nik=$_SESSION['userLog'];
   $root_ser="http://".$_SERVER['SERVER_NAME']."/server/elite";
//    console.log(nik);
?>

    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid mt-3">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row" id="saku-data-kategori">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Kategori 
                        <button type="button" id="btn-kategori-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah Kategori</button>
                        </h4>
                        <h6 class="card-subtitle">Tabel Data Kategori</h6>
                        <hr>
                        <div class="table-responsive ">
                            <table id="table-konten" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
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
        <div class="row" id="form-tambah-kategori" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                        <h4 class="card-title">Form Data Kategori
                        <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kategori-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <h6 class="card-subtitle">Input Data Kategori</h6>
                        <hr>
                            <div class="form-group m-t-40 row" id="row-id">
                                <label for="id" class="col-2 col-form-label">ID</label>
                                <div class="col-10">
                                    <input class="form-control" type="text" id="id" name="id" readonly>
                                </div>
                            </div>
                            <div class="form-group m-t-40 row">
                                <label for="nama" class="col-2 col-form-label">Nama</label>
                                <div class="col-10">
                                    <input class="form-control" type="text" placeholder="Nama Kategori" id="nama" name="nama">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Page Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
            

    <script>
    // $(".select2").select2();

    $('#saku-data-kategori').on('click', '#btn-kategori-tambah', function(){
        // getHeader();
        // getKelompok();
        // getKategori();
        $('#row-id').hide();
        $('#id').val('');
        // $('#row-preview').hide();
        $('#saku-data-kategori').hide();
        // editor.setText('');
        $('#form-tambah-kategori').show();
        $('#form-tambah')[0].reset();
        // alert("hello");
    });

    $('#saku-data-kategori').on('click', '#btn-edit', function(){
        $('#id').val($(this).closest('tr').find('td').eq(0).html());
        $('#nama').val($(this).closest('tr').find('td').eq(1).html());
        $('#row-id').show();
        $('#saku-data-kategori').hide();
        $('#form-tambah-kategori').show();
      });


    $('#form-tambah-kategori').on('click', '#btn-kategori-kembali', function(){
        $('#saku-data-kategori').show();
        $('#form-tambah-kategori').hide();
      });

    // var toolbarOptions = [
    //     ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    //     ['blockquote', 'code-block','link'],
    
    //     [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    //     [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
    //     [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
    //     [{ 'direction': 'rtl' }],                         // text direction
    
    //     [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
    //     [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    
    //     [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    //     [{ 'font': [] }],
    //     [{ 'align': [] }],
    
    //     ['clean']             
    //     ];
    //     var options = {
    //     debug: 'info',
    //     modules: {
    //         toolbar: toolbarOptions
    //     },
    //     placeholder: 'Compose an epic...',
    //     readOnly: false,
    //     theme: 'snow'
    //     };
    //     var editor = new Quill('#editor', options);  // First matching element will be used
        
        var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
        var kode_lokasi = '<?php echo $kode_lokasi ?>';
        var dataTable = $('#table-konten').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': {
                'url': '<?=$root_ser?>/KontenKtg.php?fx=getKontenKtg',
                'data': {'kode_lokasi':kode_lokasi},
                'type': 'POST',
                'dataSrc' : function(json) {
                    return json.data;   
                }
            },
            'columnDefs': [
                {'targets': 2, data: null, 'defaultContent': action_html }
            ]
        });

        $('#saku-data-kategori').on('click','#btn-delete',function(e){
        // if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var kode_lokasi = '<?php echo $kode_lokasi; ?>';

            Swal.fire({
                title: 'Apakah anda ingin menghapus data ini?',
                text: "Data akan terhapus secara permanen!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus Data!',
                cancelButtonText: 'Tidak.'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: '<?=$root_ser?>/KontenKtg.php?fx=hapusKtg',
                        dataType: 'json',
                        data: {'kode_ktg':kode,'kode_lokasi':kode_lokasi},
                        success:function(result){
                            alert('Penghapusan data '+result.message);
                            if(result.status){
                                // location.reload();
                                dataTable.ajax.reload();
                            }
                        }
                    });
                    Swal.fire({
                        title: 'Terhapus',
                        text: 'Data berhasil dihapus.',
                        type: 'success',
                        confirmButtonText: 'Selesai'
                    })
                }
            })
        // }else{
        //     return false;
        // }
        });

        $('#form-tambah-kategori').on('submit', '#form-tambah', function(e){
        e.preventDefault();
        var parameter = $('#id').val();
        // var parameter=1;
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
            url: '<?=$root_ser?>/KontenKtg.php?fx=simpanKtg',
            dataType: 'json',
            data: formData,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                alert('Input data '+result.message);
                if(result.status){
                    // location.reload();
                    dataTable.ajax.reload();
                    $('#saku-data-kategori').show();
                    $('#form-tambah-kategori').hide();
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:'+textStatus);
            }
        });
        }else{
        console.log('paramete:ubah');
        var formData = new FormData(this);
        
        
        
        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        

        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/KontenKtg.php?fx=ubahKtg',
            dataType: 'json',
            data: formData,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                alert('Update data '+result.message);
                // console.log(JSON.stringify(result));
                if(result.status){
                    // location.reload();
                    dataTable.ajax.reload();
                    $('#saku-data-kategori').show();
                    $('#form-tambah-kategori').hide();
                }
            }
        });
        }
        
    });
    </script>
</body>

</html>