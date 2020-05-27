<?php
     session_start();
     $root_lib=$_SERVER["DOCUMENT_ROOT"];
     if (substr($root_lib,-1)!="/") {
         $root_lib=$root_lib."/";
     }
     include_once($root_lib.'app/kasir/setting.php');
 
 
   $kode_lokasi=$_COOKIE['lokasi'];
   $nik=$_COOKIE['userLog'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-data-barang">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">History Sync Pembelian 
                        <!-- <button type="button" id="btn-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-sync"></i> Syncronize Data</button> -->
                        </h4>
                        <div class="table-responsive ">
                            <table id="table-barang" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>No Pembelian</td>
                                        <td>Nik Kasir</td>
                                        <td>Tanggal</td>
                                        <td>Kode Vendor</td>
                                        <td>Total</td>
                                        <td>Action</td>
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
    </div>           
    
    <script src="<?=$folderroot_js?>/inputmask.js"></script>
    <script>
    
    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-barang').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Sync.php?fx=getPmb',
            'data': {'kode_lokasi':kode_lokasi,'nik':'<?=$nik?>'},
            'async':false,
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
			{   'targets': 4, 
				'className': 'text-right',
				'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) 
			},
        ]
    });
    var $iconLoad = $('.preloader');

    $('#saku-data-barang').on('click', '.btn-sync', function(){
        var no_bukti = $(this).closest('tr').find('td').eq(0).html();
        $iconLoad.show();
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Sync.php?fx=syncPmbPerNo',
            dataType: 'json',
            async:false,
            data: {'no_bukti':no_bukti},
            success:function(result){
                // if(result.status){
                    alert('Syncronize Data '+result.message);
                    if(result.status){
                        dataTable.ajax.reload();
                    }
                // }
            }
        });
		
        $iconLoad.hide();
    });



    </script>