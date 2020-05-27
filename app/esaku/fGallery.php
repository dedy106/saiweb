<?php
   $kode_lokasi=$_SESSION['lokasi'];
   $nik=$_SESSION['userLog'];
   $root_ser="http://".$_SERVER['SERVER_NAME']."/server/elite";
   $path = "http://".$_SERVER["SERVER_NAME"]."/";	
?>

    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid mt-3">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row" id="saku-data-galeri">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Galeri 
                        <button type="button" id="btn-galeri-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <h6 class="card-subtitle">Tabel Data Galeri</h6>
                        <hr>
                        <div class="table-responsive ">
                            <table id="table-konten" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Gambar</th>
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
        <div class="row" id="form-tambah-galeri" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                        <h4 class="card-title">Form Data Galeri
                        <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-galeri-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <h6 class="card-subtitle">Input Data Galeri</h6>
                        <hr>
                            <div class="form-group m-t-40 row" id="row-id">
                                <label for="id" class="col-2 col-form-label">ID</label>
                                <div class="col-10">
                                    <input class="form-control" type="text" id="id" name="id" readonly>
                                </div>
                            </div>
                            <div class="form-group m-t-40 row" id="row-preview">
                                <label for="preview" class="col-2 col-form-label">Preview</label>
                                <div class="col-10" id="preview">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="file_gambar" class="col-2 col-form-label">Gambar</label>
                                <div class="col-10">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="filename" id="file_gambar">
                                        <label class="custom-file-label" for="file_gambar">Pilih Gambar</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-2 col-form-label">Nama</label>
                                <div class="col-10">
                                    <input class="form-control" type="text" placeholder="money" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kategori" class="col-2 col-form-label">Kategori</label>
                                <div class="col-10">
                                    <select class="selectize form-control custom-select" id="kategori" name="kategori" style="width: 100%; height:36px;">
                                        <option value="" hidden selected>Pilih Kategori</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2 col-form-label">
                                    <div class="form-group">
                                        <label for="editor" class="">Keterangan</label>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="form-group ">
                                        <textarea name="isi" id="isiyah" rows="10" class="form-control"></textarea>
                                        <!-- <div id="editor" name="editor"></div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" id="jenis">
                                <label for="jenis" class="col-2 col-form-label">Jenis</label>
                                <div class="col-10">
                                    <select class="selectize form-control custom-select" id="jenis" name="jenis" style="width: 100%; height:36px;">
                                            <option value="" hidden selected>Pilih Jenis</option>
                                            <option value="Video">Video</option>
                                            <option value="Konten">Konten</option>
                                            <!-- <option value="Slider">Slider</option> -->
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="tag" class="col-2 col-form-label">Tag</label>
                                <div class="col-10">
                                    <input class="form-control" type="text" placeholder="ex: investasi, asuransi" id="tag" name="tag">
                                </div>
                            </div> -->
                            <!-- <div class="form-group row">
                                <label for="example-month-input" class="col-2 col-form-label">Select</label>
                                <div class="col-10">
                                    <select class="custom-select col-12" id="inlineFormCustomSelect">
                                        <option selected="">Choose...</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div> -->
                            <!-- <div class="row justify-content-center text-center mb-2">
                            <b class="text-danger">Keterangan</b>
                            </div> -->
                            <!-- <div class="row justify-content-center text-center">
                                <div class="col">
                                    <div class="form-group">
                                        <div class='alert' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #31708f; border-color: #bce8f1; background-color: #d9edf7;'>
                                        &nbsp; Tag dipisahkan dengan ',' dan maksimal karakter sebanyak 200
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <?php
            include $folder_assets.'/dist/php/right-sidebar.php';
        ?>
        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->

    <script>
    // $(".select2").select2();   
    var action_html = "<a href='#' title='Edit' id='btn-edit' class='badge badge-info'><i class='fas fa-pencil-alt' style='color:white;'></i></a> &nbsp; <a href='#' title='Hapus' id='btn-delete' class='badge badge-danger'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-konten').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Galeri.php?fx=getGaleri',
            'data': {'kode_lokasi':kode_lokasi},
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;
            }
        },
        'columnDefs': [
            { "width": "10%", "targets": 0 },
            { "width": "10%", "targets": 1 },
            { "width": "70%", "targets": 2 },
            { "width": "10%", "targets": 3 },
            {'targets': 3, data: null, 'defaultContent': action_html },
            // {
            //     'mData': 'IMAGE', 
            //     'targets': 1,
            //     "render": function (data) {
            //         return 'gambarnya';
            //     }
            // },
        ]
    });
    $('#saku-data-galeri').on('click', '#btn-galeri-tambah', function(){
        // getHeader();
        // getKelompok();
        getKategori();
        $('#row-id').hide();
        $('#id').val('');
        $('#row-preview').hide();
        $('#saku-data-galeri').hide();
        // editor.setText('');
        $('#form-tambah-galeri').show();
        $('#form-tambah')[0].reset();
        // alert("hello");
    });

    $('#form-tambah-galeri').on('click', '#btn-galeri-kembali', function(){
        $('#saku-data-galeri').show();
        $('#form-tambah-galeri').hide();
      });

    //   var toolbarOptions = [
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

        function getKategori(){
        $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Galeri.php?fx=getKtg',
                dataType: 'json',
                data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
                success:function(result){    
                    if(result.status){
                        if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                            for(i=0;i<result.daftar.length;i++){
                                $('#kategori')[0].selectize.addOption([{text:result.daftar[i].kode_ktg + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_ktg}]);  
                                // $('#web_form_edit_ktg')[0].selectize.addOption([{text:result.daftar[i].kode_ktg + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_ktg}]);  
                            }
                        }
                    }
                }
        });
    }

    $('#saku-data-galeri').on('click', '#btn-edit', function(e){
        e.preventDefault();
        getKategori();
        $('#row-preview').show();
        $('#row-id').show();
        $('#saku-data-galeri').hide();
        $('#form-tambah-galeri').show();
        var kode = $(this).closest('tr').find('td:eq(0)').html();
        var lokasi = '<?php echo $kode_lokasi; ?>';
        var paths='<?php echo $path; ?>';

        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Galeri.php?fx=getEditGaleri',
            dataType: 'json',
            data: {'lokasi':lokasi, 'kode':kode},
            success:function(res){
                // alert('id ='+res.daftar[0].id);

                if(res.status){
                  
                    var path_file=paths+res.daftar[0].file_gambar;

                    if(res.daftar[0].file_type == "video"){
                        $('#preview').html("<video controls  style='min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'><source src='"+path_file+"' type='"+res.daftar[0].file_type+"'></video><br><br><center><b>Url:</b> <i>"+path_file+"</i></center>");
                    }else{
                        $('#preview').html("<img src='"+path_file+"' style='width:25%; height:25%; min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'><br><br><center><b>Url:</b> <i>"+path_file+"</i></center>");
                    }             

                    $('#id').val(res.daftar[0].id);
                    $('#nama').val(res.daftar[0].nama);
                    tinymce.get('isi').setContent(res.daftar[0].isi);
                    // editor.setText(res.daftar[0].isi);
                    // $('#form-tambah').find('#editor').innerHTML(kode);
                    $('#jenis')[0].selectize.setValue(res.daftar[0].jenis);
                    $('#kategori')[0].selectize.setValue(res.daftar[0].kode_ktg);
                    $('#row-id').show();
                    $('#row-preview').show();
                    $('#saku-data-galeri').hide();
                    $('#form-tambah-galeri').show();
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:');
            }
        });
      });


    $('#saku-data-galeri').on('click', '#btn-delete', function(){
        if(confirm('Apakah anda ingin menghapus data ini?')){
          var kode = $(this).closest('tr').find('td:eq(0)').html();        
          var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

          $.ajax({
              type: 'POST',
              url: '<?=$root_ser?>/Galeri.php?fx=hapusGaleri',
              dataType: 'json',
              data: {'id':kode,'kode_lokasi':kode_lokasi},
              success:function(result){
                  alert('Penghapusan data '+result.message);
                  if(result.status){
                      // location.reload();
                      dataTable.ajax.reload();
                  }
              }
          });
        }else{
            return false;
        }
      });

      $('#form-tambah-galeri').on('submit','#form-tambah',function(e){
      e.preventDefault();
      if ($('#id').val()=='') {
        //tambah 
        console.log('parameter:tambah');
        // var data_isi = editor.root.innerHTML;
        var formData = new FormData(this);
        // formData.append('isi',data_isi);

        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
            }

        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);        
          $.ajax({
              type: 'POST',
              url: '<?=$root_ser?>/Galeri.php?fx=simpanGaleri',
              dataType: 'json',
              data: formData,
              contentType: false,
              cache: false,
              processData: false, 
              success:function(result){
                  alert('Input data '+result.message);
                // console.log(result.status+'+'+result.message+'+'+result.error+'+'+result.sql+'+'+result.id)
                  if(result.status){
                      // location.reload();
                      dataTable.ajax.reload();
                      $('#saku-data-galeri').show();
                      $('#form-tambah-galeri').hide();
                      $('#form-tambah')[0].reset();
                  }
              },
              fail: function(xhr, textStatus, errorThrown){
                  alert('request failed:'+textStatus);
                  // console.log(result.sql)

              }
          }); 
      }else{
        //Ubah
        console.log('parameter:ubah');
        // var data_isi = editor.root.innerHTML;
        var formData = new FormData(this);
        // formData.append('isi',data_isi);

        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
            }

        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);        
          $.ajax({
              type: 'POST',
              url: '<?=$root_ser?>/Galeri.php?fx=ubahGaleri',
              dataType: 'json',
              data: formData,
              contentType: false,
              cache: false,
              processData: false, 
              success:function(result){
                  alert('Ubah data '+result.message);
                // console.log(result.status+'+'+result.message+'+'+result.error+'+'+result.sql+'+'+result.id)
                  if(result.status){
                      // location.reload();
                      dataTable.ajax.reload();
                      $('#saku-data-galeri').show();
                      $('#form-tambah-galeri').hide();
                      $('#form-tambah')[0].reset();
                  }
              },
              fail: function(xhr, textStatus, errorThrown){
                  alert('request failed:'+textStatus);
                  // console.log(result.sql)

              }
          }); 
      }
      
  }); 

  $('#jenis').hide();
    </script>
</body>

</html>