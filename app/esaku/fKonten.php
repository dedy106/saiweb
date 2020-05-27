<?php
   $kode_lokasi=$_SESSION['lokasi'];
   $nik=$_SESSION['userLog'];
   $root_ser="http://".$_SERVER['SERVER_NAME']."/server/elite";
?>
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid mt-3">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                
                <div class="row" id="saku-data-konten">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Data Konten 
                                <button type="button" id="btn-form-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                                </h4>
                                <h6 class="card-subtitle">Tabel Data Konten</h6>
                                <hr>
                                <div class="table-responsive ">
                                    <table id="table-konten" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Judul</th>
                                                <th>Jenis</th>
                                                <th>Status</th>
                                                <th>Tanggal</th>
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
                <div class="row" id="form-tambah-konten" style="display:none;">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="form" id="form-tambah">
                                    <h4 class="card-title">Form Data Konten
                                    <button type="button" class="btn btn-success ml-2" style="float:right;" id="btn-pub"><i class="fas fa-upload"></i> Publish</button>
                                    <button type="button" class="btn btn-warning ml-2" style="float:right;" id="btn-save"><i class="fa fa-save"></i> Draft</button>
                                    <button type="button" class="btn btn-secondary ml-2" id="btn-form-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                                    </h4>
                                    <h6 class="card-subtitle">Input Data Konten</h6>
                                    <hr>
                                    <div class="form-group m-t-40 row" style="display:none;" id="row-id">
                                        <label for="id" class="col-2 col-form-label">ID</label>
                                        <div class="col-10">
                                            <input class="form-control" id="id" name="id" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="judul" class="col-2 col-form-label">Judul Konten</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" placeholder="Ex : Prinsip Akuntansi" id="judul" name="judul" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="z-index:-9;">
                                        <label for="jenis" class="col-2 col-form-label">Jenis</label>
                                        <div class="col-10">
                                            <select class="selectize form-control" id="jenis" name="jenis" style="width: 100%; height:36px;">
                                                <option value="" hidden selected>Pilih Jenis Konten</option>
                                                <option value="Artikel">Artikel</option>
                                                <option value="Video">Video</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label for="tanggal" class="col-2 col-form-label">Tanggal Publish</label>
                                        <div class="col-10">
                                            <input class="form-control datepicker-dmy" type="text" id="tanggal" name="tanggal" autocomplete="off">
                                        </div>
                                    </div> -->
                                    <div id="jenis-gambar" style="display:none;">
                                        <div class="form-group row">
                                            <label for="gambar" class="col-2 col-form-label">Gambar</label>
                                            <div class="col-10">
                                                <select class="selectize form-control gambar-input" id="gambar" name="gambar" style="width: 100%; height:36px;">
                                                    <option value="" hidden selected>Pilih Gambar Utama</option>
                                                </select>
                                                <a style="font-size: 13px">*note : Gambar yang dipilih menjadi gambar utama.</a>
                                            </div>
                                        </div>
                                        <div class="form-group row" id="tipeAl">
                                            <label for="tipe" class="col-2 col-form-label">Tipe</label>
                                            <div class="col-10">
                                                <select class="selectize form-control" id="tipe" name="tipe" style="width: 100%; height:36px;">
                                                    <option value="" hidden selected>Pilih Tipe</option>
                                                    <option value="Ringkas">Ringkas</option>
                                                    <option value="Sekilas">Sekilas</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="jenis-video" style="display:none;">
                                        <div class="form-group row">
                                            <label for="gambar" class="col-2 col-form-label">Link</label>
                                            <div class="col-10">
                                                <input type="url" name="gambar" id="link" class="form-control link-input" autocomplete="off">
                                                <!-- <select class="selectize form-control" id="gambar" name="gambar" style="width: 100%; height:36px;">
                                                    <option value="" hidden selected>Pilih Gambar</option>
                                                </select> -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="form-group row">
                                        <label for="file_gambar" class="col-2 col-form-label">Gambar</label>
                                        <div class="col-10">
                                        <input type="file" class="custom-file-input" id="file_gambar" aria-describedby="fileHelp" nama="file_gambar">
                                        </div>
                                    </div> -->
                                    <div class="form-group row" style="z-index:-9;">
                                        <label for="kategori" class="col-2 col-form-label">Kategori</label>
                                        <div class="col-10">
                                            <select class="selectize form-control" id="kategori" name="kode_kategori" style="width: 100%; height:36px;">
                                                <option value="" hidden selected>Pilih Kategori</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2 col-form-label">
                                            <div class="form-group">
                                                <label for="isi" class="">Isi</label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group ">
                                                <!-- <div id="editor"></div> -->
                                                <textarea name="isi" id="isi" cols="30" rows="10" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tag" class="col-2 col-form-label">Tag</label>
                                        <div class="col-10">
                                            <input class="form-control" type="tags" placeholder="ex: investasi, asuransi" id="tag" name="tag" autocomplete="off">
                                        </div>
                                        <button class='removeTags' type='button' id="btnRemoveTag">Remove all tags</button>
                                    </div>
                                    <div class="row justify-content-center text-center mb-2">
                                    <b class="text-danger">Keterangan</b>
                                    </div>
                                    <div class="row justify-content-center text-center">
                                        <div class="col">
                                            <div class="form-group">
                                                <div class='alert' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #31708f; border-color: #bce8f1; background-color: #d9edf7;'>
                                                &nbsp; Tag dipisahkan dengan ',' dan maksimal karakter sebanyak 200
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                    include $root_app.'dist/php/right-sidebar.php';
                ?>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            
        <!-- </div> -->
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <?php
            include $folder_assets.'/dist/php/footer.php';
        ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    <!-- </div> -->
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->

    <script>
    // $(".select2").select2();
    $("#table-konten").find("tr").each(function () {
        // var kode = $(this).closest('tr').find('td:eq(0)').html(); 
        // var status= $(this).find("td").eq(2).html();  
        var kode = $(this).closest('tr').find('td:eq(2)').html(); 
        if(kode=='0')
            {
                $(this).html("Draft");
            }
    });

    $('#jenis').change(function(){
        if ($('#jenis').val()=='Artikel') {
            $('#gambar').prop('disabled',false);
            $('#tipe').prop('disabled',false);
            $('#link').prop('disabled',true);
            $('#jenis-gambar').show();
            $('#jenis-video').hide();
            // var idJenis = document.getElementById("ini");
            // idJenis.id = "isi";
        }else{
            // document.getElementById('isi').remove();
            $('#gambar').prop('disabled',true);
            $('#link').prop('disabled',false);
            $('#jenis-gambar').hide();
            $('#jenis-video').show();
        }
    });

    var action_html = "<a href='#' title='Edit' id='btn-form-edit' class='badge badge-info'><i class='fas fa-pencil-alt' style='color:white;'></i></a> &nbsp; <a href='#' title='Draft' id='btn-draft' class='badge badge-warning'><i class='fas fa-clipboard-list' style='color:white;'></i></a> &nbsp; <a href='#' title='Hapus' id='btn-form-hapus' class='badge badge-danger'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-konten').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
        'url': '<?=$root_ser?>/Konten.php?fx=getKonten',
          'data': {'kode_lokasi':kode_lokasi},
          'type': 'POST',
      },
      'columnDefs': [
          {'targets': 5, data: null, 'defaultContent': action_html }
      ]
    });

    $('#saku-data-konten').on('click', '#btn-form-tambah', function(){
        getKategori();
        getGaleri();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#row-id').hide();
        $('#jenis')[0].selectize.setValue('');
        $('#link').prop('disabled',true);
        $('#gambar').prop('disabled',true);
        // $('#tipe').prop('disabled',true);
        $('#jenis-gambar').hide();
        $('#jenis-video').hide();
        // editor.setText('');
        $('#kategori')[0].selectize.setValue('');
        $('#gambar')[0].selectize.setValue('');
        $('#saku-data-konten').hide();
        $('#form-tambah-konten').show();
      });

      // Fungsi Button Kembali
      $('#form-tambah-konten').on('click', '#btn-form-kembali', function(){
        // getKategori();
        $('#saku-data-konten').show();
        $('#form-tambah-konten').hide();
      });
    
    // var toolbarOptions = [
    //     ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    //     ['blockquote', 'code-block'],
    
    //     [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    //     [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
    //     [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
    //     [{ 'direction': 'rtl' }],                         // text direction
    //     [ 'link', 'image', 'video', 'formula' ],
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
    //         toolbar: toolbarOptions,
    //         imageResize: {
    //                         displaySize: true
    //                      },
    //     },
    //     placeholder: 'Compose an epic...',
    //     readOnly: false,
    //     theme: 'snow'
    //     };
    //     var editor = new Quill('#editor', options);  // First matching element will be used

        $('#saku-data-konten').on('click','#btn-draft',function(e){
            e.preventDefault();
            if (confirm('Apakah anda yakin ingin un-publish/draft konten ini ?')) {
                console.log('parameter:draft');
                var nik='<?php echo $nik; ?>' ;
                var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
                var kode = $(this).closest('tr').find('td:eq(0)').html(); 
                // console.log(kode);

                $.ajax({
                    type: 'POST',
                    url: '<?=$root_ser?>/Konten.php?fx=draftKonten',
                    dataType: 'json',
                    data: {
                        'id':kode,
                        'kode_lokasi':kode_lokasi
                    },
                    // contentType: true,
                    // cache: true,
                    processData: true, 
                    success:function(result){
                        // console.log('what');
                        // echo 'apa';
                        alert('Draft Konten '+result.message);
                        if(result.status){
                            dataTable.ajax.reload();
                        }
                    }
                });
            }
        });

        // $('#saku-data-konten').on('click','#btn-publish',function(e){
        //     e.preventDefault();
        //     if (confirm('Apakah anda yakin ingin mempublish konten ini ?')) {
        //         console.log('parameter:publish');
        //         var nik='<?php echo $nik; ?>' ;
        //         var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        //         var kode = $(this).closest('tr').find('td:eq(0)').html(); 
        //         // console.log(kode);

        //         $.ajax({
        //             type: 'POST',
        //             url: '<?=$root_ser?>/Konten.php?fx=publishKonten',
        //             dataType: 'json',
        //             data: {
        //                 'id':kode,
        //                 'kode_lokasi':kode_lokasi
        //             },
        //             // contentType: true,
        //             // cache: true,
        //             processData: true, 
        //             success:function(result){
        //                 // console.log('what');
        //                 alert('Publish Konten '+result.message);
        //                 if(result.status){
        //                     dataTable.ajax.reload();
        //                 }
        //             }
        //         });
        //     }
        // });

        $('#saku-data-konten').on('click','#btn-form-hapus',function(e){
            e.preventDefault();
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var nik='<?php echo $nik; ?>';
            var kode_lokasi='<?php echo $kode_lokasi; ?>';   

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
                        url: '<?=$root_ser?>/Konten.php?fx=deleteKonten',
                        dataType: 'json',
                        data: {'kode':kode,'kode_lokasi':kode_lokasi},
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
        });

    $('#form-tambah-konten').on('click','#btn-pub',function(){
        // $('#btn-pub').val('publish');
        document.getElementById("btn-pub").type = "submit";
        $('#form-tambah').submit(function(e){
            alert('publish al');
            e.preventDefault();
            var parameter = $('#id').val();
            if (parameter=='') {
                alert('insert');
                var formData = new FormData(this);
                var nik='<?php echo $nik; ?>' ;
                var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
                formData.append('nik_user', nik);
                formData.append('kode_lokasi', kode_lokasi);
                for(var pair of formData.entries()) {
                    console.log(pair[0]+ ', '+ pair[1]); 
                }

                var link;

                if (formData.get('jenis') == 'Artikel') {
                    link = 'iArtikelUtama';
                } else if (formData.get('jenis') == 'Video') {
                    link = 'iVideoUtama';
                }

                $.ajax({
                    type: 'POST',
                    url: '<?=$root_ser?>/Konten.php?fx=insertLangsung',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false, 
                    success:function(result){
                        alert('Input data '+result.message);
                        document.getElementById("btn-pub").type = "button";
                        if(result.status){
                            dataTable.ajax.reload();
                            $('#saku-data-konten').show();
                            $('#form-tambah-konten').hide();
                            // alert(result.daftar[0].no_konten);
                            var id = result.id;
                            var server = "<?= $_SERVER['SERVER_NAME'] ?>";
                            location.reload();
                            window.open('http://'+server+'/esaku/'+link+'/?id='+id, '_blank');
                        }
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        alert('request failed:'+textStatus);
                    }
                });
            } else {
                alert('update');
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

                if (formData.get('jenis') == 'Artikel') {
                    link = 'iArtikelUtama';
                } else if (formData.get('jenis') == 'Video') {
                    link = 'iVideoUtama';
                }

                $.ajax({
                    type: 'POST',
                    url: '<?=$root_ser?>/Konten.php?fx=updateKonten',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false, 
                    success:function(result){
                        alert('Update data '+result.message);
                        if(result.status){
                            dataTable.ajax.reload();
                            $('#saku-data-konten').show();
                            $('#form-tambah-konten').hide();
                            // .val(result.daftar[0].no_konten)
                            var server = "<?= $_SERVER['SERVER_NAME'] ?>";
                            var id = $('#id').val();
                            location.reload();
                            window.open('http://'+server+'/esaku/'+link+'/?id='+id, '_blank');
                        }
                    }
                });   
            }
        });
    })

    $('#form-tambah-konten').on('click','#btn-save',function(){
        // $('#btn-save').val('draft');
        document.getElementById("btn-save").type = "submit";
        $('#form-tambah').submit(function(e){
            alert('draft al');
            e.preventDefault();
            var parameter = $('#id').val();
            if (parameter=='') {
                console.log('parameter:tambah');
                // var data_isi = editor.root.innerHTML;
                var formData = new FormData(this);
                // formData.append('isi',data_isi);
                var nik='<?php echo $nik; ?>' ;
                var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
                formData.append('nik_user', nik);
                formData.append('kode_lokasi', kode_lokasi);
                for(var pair of formData.entries()) {
                    console.log(pair[0]+ ', '+ pair[1]); 
                }
                $.ajax({
                    type: 'POST',
                    url: '<?=$root_ser?>/Konten.php?fx=insertKonten',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false, 
                    success:function(result){
                        alert('Input data '+result.message);
                        document.getElementById("btn-save").type = "button";
                        if(result.status){
                            dataTable.ajax.reload();
                            location.reload();
                            // $('#saku-data-konten').show();
                            // $('#form-tambah-konten').hide();
                        }
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        alert('request failed:'+textStatus);
                    }
                });
            }else{
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
                    url: '<?=$root_ser?>/Konten.php?fx=updateKontenDraft',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false, 
                    success:function(result){
                        alert('Update data '+result.message);
                        if(result.status){
                            dataTable.ajax.reload();
                            location.reload();
                            // $('#saku-data-konten').show();
                            // $('#form-tambah-konten').hide();
                        }
                    }
                });
            }
        });

    })

    // $('#form-tambah-konten').on('submit','#form-tambah',function(e){
    //     e.preventDefault();
    //     var parameter = $('#id').val();
    //     if (parameter=='') {
    //         console.log('parameter:tambah');
    //         // var data_isi = editor.root.innerHTML;
    //         var formData = new FormData(this);
    //         // formData.append('isi',data_isi);
    //         for(var pair of formData.entries()) {
    //             console.log(pair[0]+ ', '+ pair[1]); 
    //             }
    //         var nik='<?php echo $nik; ?>' ;
    //         var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
    //         formData.append('nik_user', nik);
    //         formData.append('kode_lokasi', kode_lokasi);
    //         $.ajax({
    //             type: 'POST',
    //             url: '<?=$root_ser?>/Konten.php?fx=insertKonten',
    //             dataType: 'json',
    //             data: formData,
    //             contentType: false,
    //             cache: false,
    //             processData: false, 
    //             success:function(result){
    //                 alert('Input data '+result.message);
    //                 if(result.status){
    //                     dataTable.ajax.reload();
    //                     $('#saku-data-konten').show();
    //                     $('#form-tambah-konten').hide();
    //                 }
    //             },
    //             fail: function(xhr, textStatus, errorThrown){
    //                 alert('request failed:'+textStatus);
    //             }
    //         });
    //     }else{
    //         console.log('parameter:ubah');
    //         // var data_isi = editor.root.innerHTML;
    //         var formData = new FormData(this);
    //         // formData.append('isi',data_isi);
            
    //         for(var pair of formData.entries()) {
    //             console.log(pair[0]+ ', '+ pair[1]); 
    //         }
            
    //         var nik='<?php echo $nik; ?>' ;
    //         var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

    //         formData.append('nik_user', nik);
    //         formData.append('kode_lokasi', kode_lokasi);

    //         $.ajax({
    //             type: 'POST',
    //             url: '<?=$root_ser?>/Konten.php?fx=updateKonten',
    //             dataType: 'json',
    //             data: formData,
    //             contentType: false,
    //             cache: false,
    //             processData: false, 
    //             success:function(result){
    //                 alert('Update data '+result.message);
    //                 if(result.status){
    //                     dataTable.ajax.reload();
    //                     $('#saku-data-konten').show();
    //                     $('#form-tambah-konten').hide();
    //                 }
    //             }
    //         });
    //     }
      
    // });

    $('#saku-data-konten').on('click', '#btn-form-edit', function(){
        // getHeader();
        // getKelompok();
        var kode = $(this).closest('tr').find('td:eq(0)').text();
        $('#row-id').hide();//hilangin id
        // $('#a')
        getGaleri();
        getKategori();
        var lokasi = '<?php echo $kode_lokasi; ?>';
        var paths='<?php echo $path."web"; ?>';

        $('#saku-data-konten').hide();
        // $('.wysihtml5-sandbox').contents().find('.wysihtml5-editor').html('');
        $('#form-tambah-konten').show();
        // alert("hello");
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Konten.php?fx=getEditData',
            dataType: 'json',
            data: {'lokasi':lokasi, 'kode':kode}, 
            success:function(res){
                // alert('id ='+res.daftar[0].id);

                if(res.status){
                    $('#link').val(res.daftar[0].file_gambar);
                    $('#id').val(res.daftar[0].no_konten);
                    $('#judul').val(res.daftar[0].judul);
                    $('#tanggal').val(res.daftar[0].tanggal);
                    $('#jenis')[0].selectize.setValue(res.daftar[0].jenis);
                    $('#tipe').val(res.daftar[0].tipe);
                    tinymce.get('isi').setContent(res.daftar[0].isi);
                    $('#kategori')[0].selectize.setValue(res.daftar[0].kode_kategori);
                    $('#gambar')[0].selectize.setValue(res.daftar[0].file_gambar);
                    $('#tag').val(res.daftar[0].tag);
                    $('#saku-data-konten').hide();
                    $('#form-tambah-konten').show();
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:');
            }
        });
      });

    function getGaleri(){
      $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Konten.php?fx=getGaleri',
            dataType: 'json',
            data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            $('#gambar')[0].selectize.addOption([{text:result.daftar[i].kode + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode}]);  
                        }
                    }
                }
            }
        });
    }

    $('.datepicker-dmy').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
        
    $('.datepicker, .daterangepicker').on('keydown keyup keypress', function(e){
        e.preventDefault();
            return false;
        });

    

    function getKategori(){
        // $("#kategori").select2({
        //     ajax: {
        //         url: '<?=$root_ser?>/Konten.php?fx=getKtg',
        //         type: 'post',
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //         return {
        //             kode_lokasi: '<?php echo $kode_lokasi ?>' // search term
        //         };
        //         },
        //         processResults: function (response) {
        //         return {
        //             results: response.no_konten+response.nama
        //             };
        //         },
        //         cache: true
        //     },
        //     placeholder: 'Pilih Kategori',
        //     minimumInputLength: 3
        //     // templateResult: formatRepo,
        //     // templateSelection: formatRepoSelection
        //     });

        $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Konten.php?fx=getKtg',
                dataType: 'json',
                data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
                success:function(result){    
                    if(result.status){
                        if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                            for(i=0;i<result.daftar.length;i++){
                                $('#kategori')[0].selectize.addOption([{text:result.daftar[i].kode_ktg + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_ktg}]);  
                            }
                        }
                    }
                }
        });
    }
    var input = document.querySelector('input[name=tag]');

    // init Tagify script on the above inputs
    new Tagify(input);

    $('#btnRemoveTag').hide();
    $('#tipeAl').hide();
    </script>

    <!-- <script data-name="basic">
        $(document).ready(function(){
            var input = document.querySelector('input[name=tags]'),
            // init Tagify script on the above inputs
            tagify = new Tagify(input, {
                whitelist : ["A# .NET", "A# (Axiom)", "A-0 System", "A+", "A++", "ABAP", "ABC", "ABC ALGOL", "ABSET", "ABSYS", "ACC", "Accent", "Ace DASL", "ACL2", "Avicsoft", "ACT-III", "Action!", "ActionScript", "Ada", "Adenine", "Agda", "Agilent VEE", "Agora", "AIMMS", "Alef", "ALF", "ALGOL 58", "ALGOL 60", "ALGOL 68", "ALGOL W", "Alice", "Alma-0", "AmbientTalk", "Amiga E", "AMOS", "AMPL", "Apex (Salesforce.com)", "APL", "AppleScript", "Arc", "ARexx", "Argus", "AspectJ", "Assembly language", "ATS", "Ateji PX", "AutoHotkey", "Autocoder", "AutoIt", "AutoLISP / Visual LISP", "Averest", "AWK", "Axum", "Active Server Pages", "ASP.NET", "B", "Babbage", "Bash", "BASIC", "bc", "BCPL", "BeanShell", "Batch (Windows/Dos)", "Bertrand", "BETA", "Bigwig", "Bistro", "BitC", "BLISS", "Blockly", "BlooP", "Blue", "Boo", "Boomerang", "Bourne shell (including bash and ksh)", "BREW", "BPEL", "B", "C--", "C++ – ISO/IEC 14882", "C# – ISO/IEC 23270", "C/AL", "Caché ObjectScript", "C Shell", "Caml", "Cayenne", "CDuce", "Cecil", "Cesil", "Céu", "Ceylon", "CFEngine", "CFML", "Cg", "Ch", "Chapel", "Charity", "Charm", "Chef", "CHILL", "CHIP-8", "chomski", "ChucK", "CICS", "Cilk", "Citrine (programming language)", "CL (IBM)", "Claire", "Clarion", "Clean", "Clipper", "CLIPS", "CLIST", "Clojure", "CLU", "CMS-2", "COBOL – ISO/IEC 1989", "CobolScript – COBOL Scripting language", "Cobra", "CODE", "CoffeeScript", "ColdFusion", "COMAL", "Combined Programming Language (CPL)", "COMIT", "Common Intermediate Language (CIL)", "Common Lisp (also known as CL)", "COMPASS", "Component Pascal", "Constraint Handling Rules (CHR)", "COMTRAN", "Converge", "Cool", "Coq", "Coral 66", "Corn", "CorVision", "COWSEL", "CPL", "CPL", "Cryptol", "csh", "Csound", "CSP", "CUDA", "Curl", "Curry", "Cybil", "Cyclone", "Cython", "Java", "Javascript", "M2001", "M4", "M#", "Machine code", "MAD (Michigan Algorithm Decoder)", "MAD/I", "Magik", "Magma", "make", "Maple", "MAPPER now part of BIS", "MARK-IV now VISION:BUILDER", "Mary", "MASM Microsoft Assembly x86", "MATH-MATIC", "Mathematica", "MATLAB", "Maxima (see also Macsyma)", "Max (Max Msp – Graphical Programming Environment)", "Maya (MEL)", "MDL", "Mercury", "Mesa", "Metafont", "Microcode", "MicroScript", "MIIS", "Milk (programming language)", "MIMIC", "Mirah", "Miranda", "MIVA Script", "ML", "Model 204", "Modelica", "Modula", "Modula-2", "Modula-3", "Mohol", "MOO", "Mortran", "Mouse", "MPD", "Mathcad", "MSIL – deprecated name for CIL", "MSL", "MUMPS", "Mystic Programming L"],
                blacklist : [".NET", "PHP"], // <-- passed as an attribute in this demo
                dropdown : {
                    classname: "basicDropdown"
                }
            })


        // "remove all tags" button event listener
        document.querySelector('.removeTags')
            .addEventListener('click', tagify.removeAllTags.bind(tagify))

        // Chainable event listeners
        tagify.on('add', onAddTag)
            .on('remove', onRemoveTag)
            .on('input', onInput)
            .on('edit', onTagEdit)
            .on('invalid', onInvalidTag)
            .on('click', onTagClick)
            .on('dropdown:show', onDropdownShow)
            .on('dropdown:hide', onDropdownHide)

        // tag added callback
        function onAddTag(e){
            console.log("onAddTag: ", e.detail);
            console.log("original input value: ", input.value)
            tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
        }

        // tag remvoed callback
        function onRemoveTag(e){
            console.log(e.detail);
            console.log("tagify instance value:", tagify.value)
        }

        // on character(s) added/removed (user is typing/deleting)
        function onInput(e){
            console.log(e.detail);
            console.log("onInput: ", e.detail);
        }

        function onTagEdit(e){
            console.log("onTagEdit: ", e.detail);
        }

        // invalid tag added callback
        function onInvalidTag(e){
            console.log("onInvalidTag: ", e.detail);
        }

        // invalid tag added callback
        function onTagClick(e){
            console.log(e.detail);
            console.log("onTagClick: ", e.detail);
        }

        function onDropdownShow(e){
            console.log("onDropdownShow: ", e.detail)
        }

        function onDropdownHide(e){
            console.log("onDropdownHide: ", e.detail)
        }
        });
    </script> -->
</body>

</html>