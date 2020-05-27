<?php

    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/saku/setting2.php');

    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];
?>

<section id="layout-data">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><i class="fas fa-cube"></i> Data Form
            <button type="button" id="btn-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
            </h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body ">
            <div class="table-responsive ">
                <table id="table-data" class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th>Kode Form</th>
                            <th>Nama</th>
                            <th>Program</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section id="layout-form" style="display: none;">
    <div class="card">
        <div class="card-body pb-0">
            <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Form
            <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
            </h4>
            <hr>
        </div>
        <div class="card-body table-responsive pt-0" style='height:200px'>
            <form class="form" id="form-tambah" style='margin-bottom:100px'>
                <div class="form-group row" id="row-id">
                    <div class="col-9">
                        <input class="form-control" type="hidden" id="id_edit" name="id">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kode_form" class="col-3 col-form-label">Kode</label>
                    <div class="col-3">
                        <input class="form-control" type="text" placeholder="Kode Form" id="kode_form" name="kode_form" required >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama_form" class="col-3 col-form-label">Nama</label>
                    <div class="col-9">
                        <input class="form-control" type="text" placeholder="Nama Form" id="nama_form" name="nama_form">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form" class="col-3 col-form-label">Program</label>
                    <div class="col-9">
                        <input class="form-control" type="text" placeholder="Alamat program" id="form" name="form">
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>

    // var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    // var kode_lokasi = '<?php echo $kode_lokasi ?>';
    // var dataTable = $('#table-data').DataTable({
    //     'processing': true,
    //     'serverSide': true,
    //     'ajax': {
    //         'url': '<?=$root_ser?>/Form.php?fx=getView',
    //         'data': {'kode_lokasi':kode_lokasi},
    //         'async':false,
    //         'type': 'POST',
    //         'dataSrc' : function(json) {
    //             return json.data;   
    //         }
    //     },
    //     'columnDefs': [
    //         {'targets': 3, data: null, 'defaultContent': action_html }
    //     ]
    // });

    $('#layout-data').on('click', '#btn-tambah', function(){
        // $('#row-id').hide();
        // $('#id_edit').val('');
        $('#layout-data').hide();
        $('#layout-form').show();
        // $('#form-tambah')[0].reset();
    });

    $('#layout-form').on('click', '#btn-kembali', function(){
        $('#layout-data').show();
        $('#layout-form').hide();
    });

    $(document).ready( function () {
        $('#table-data').DataTable( {
            // "paging":   true,
            // "ordering": true,
            // "info":     true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        } );
    } );
</script>