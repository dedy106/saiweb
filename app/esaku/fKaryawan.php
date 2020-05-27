<?php
   $kode_lokasi=$_SESSION['lokasi'];
   $nik=$_SESSION['userLog'];
   $root_ser="http://".$_SERVER['SERVER_NAME']."/server/esaku";
?>
            <div class="container-fluid mt-3">
                
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
                                                <th>Nama</th>
                                                <th>No. Telepon</th>
                                                <th>Email</th>
                                                <th>Foto</th>
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
                                        <label for="nama" class="col-2 col-form-label">Nama Aplikasi</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" placeholder="Ex : Aplikasi aplikasi aplikasi" id="nama" name="nama" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="no_telp" class="col-2 col-form-label">No. Telepon</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="Ex : 0888-xxxx-xxxx" />
                                            <!-- <input class="form-control" type="text" placeholder="Ex : No_telp no_telp no_telp" id="no_telp" name="no_telp" autocomplete="off"> -->
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-2 col-form-label">Email</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" placeholder="Ex : Email email email" id="email" name="email" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="foto" class="col-2 col-form-label">Foto</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" placeholder="Ex : Foto foto foto" id="foto" name="foto" autocomplete="off">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    include $root_app.'dist/php/right-sidebar.php';
                ?>
            </div>
        <?php
            include $folder_assets.'/dist/php/footer.php';
        ?>

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
            'url': '<?=$root_ser?>/Karyawan.php?fx=getKaryawan',
            'data': {'kode_lokasi':kode_lokasi},
            'type': 'POST',
        },
        'columnDefs': [
            { "width": "10%", "targets": 0 },
            { "width": "23%", "targets": 1 },
            { "width": "19%", "targets": 2 },
            { "width": "26%", "targets": 3 },
            { "width": "10%", "targets": 4 },
            { "width": "12%", "targets": 5 },
            {'targets': 5, data: null, 'defaultContent': action_html }
        ]
    });

    $('#saku-data-konten').on('click', '#btn-form-tambah', function(){
        // getKategori();
        $('#id').hide();
        $('#saku-data-konten').hide();
        $('#form-tambah-konten').show();
      });

      // Fungsi Button Kembali
      $('#form-tambah-konten').on('click', '#btn-form-kembali', function(){
        // getKategori();
        $('#saku-data-konten').show();
        $('#form-tambah-konten').hide();
      });

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
                    url: '<?=$root_ser?>/Karyawan.php?fx=draftKonten',
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
                        url: '<?=$root_ser?>/Karyawan.php?fx=deleteKonten',
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
                    url: '<?=$root_ser?>/Karyawan.php?fx=insertLangsung',
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
                    url: '<?=$root_ser?>/Karyawan.php?fx=updateKonten',
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
                    url: '<?=$root_ser?>/Karyawan.php?fx=insertKonten',
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
                    url: '<?=$root_ser?>/Karyawan.php?fx=updateKontenDraft',
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

    $('#saku-data-konten').on('click', '#btn-form-edit', function(){
        // getHeader();
        // getKelompok();
        var kode = $(this).closest('tr').find('td:eq(0)').text();
        alert(kode);
        $('#row-id').hide();//hilangin id
        // $('#a')
        // getGaleri();
        // getKategori();
        var lokasi = '<?php echo $kode_lokasi; ?>';
        var paths='<?php echo $path."web"; ?>';

        $('#saku-data-konten').hide();
        // $('.wysihtml5-sandbox').contents().find('.wysihtml5-editor').html('');
        $('#form-tambah-konten').show();
        // alert("hello");
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Karyawan.php?fx=getEditData',
            dataType: 'json',
            data: {'lokasi':lokasi, 'kode':kode}, 
            success:function(res){
                // alert('id ='+res.daftar[0].id);

                if(res.status){
                    // $('#link').val(res.daftar[0].file_gambar);
                    // $('#id').val(res.daftar[0].kode_cust);
                    $('#nama').val(res.daftar[0].nama);
                    $('#no_telp').val(res.daftar[0].no_telp);
                    $('#email').val(res.daftar[0].email);
                    $('#foto').val(res.daftar[0].foto);
                    $('#saku-data-konten').hide();
                    $('#form-tambah-konten').show();
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:');
            }
        });
      });

    // function getGaleri(){
    //   $.ajax({
    //         type: 'POST',
    //         url: '<?=$root_ser?>/Karyawan.php?fx=getGaleri',
    //         dataType: 'json',
    //         data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
    //         success:function(result){    
    //             if(result.status){
    //                 if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
    //                     for(i=0;i<result.daftar.length;i++){
    //                         $('#gambar')[0].selectize.addOption([{text:result.daftar[i].kode + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode}]);  
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // }

    $('.datepicker-dmy').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
        
    $('.datepicker, .daterangepicker').on('keydown keyup keypress', function(e){
        e.preventDefault();
            return false;
        });

    

    function getKategori(){

        $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Karyawan.php?fx=getKtg',
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
</body>

</html>