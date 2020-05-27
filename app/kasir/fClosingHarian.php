<?php
     session_start();
     $root_lib=$_SERVER["DOCUMENT_ROOT"];
     if (substr($root_lib,-1)!="/") {
         $root_lib=$root_lib."/";
     }
     include_once($root_lib.'app/kasir/setting.php');
 
 
    $kode_lokasi=$_COOKIE['lokasi'];
    $nik=$_COOKIE['userLog'];
    $kode_pp=$_COOKIE['kodePP'];
?>
<style>

</style>
<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form class="form" id="web-form-pos" method='POST'>
                        <div class='row'>
                        <div class='col-md-4'>
                            <div class='row'>
                                <div class='col-md-4'>
                                    <div class='logo text-center'><img src="<?=$folder_assets?>/images/sai_icon/logo.png" width="40px" alt="homepage" class="light-logo" /><br/>
                                    <img src="<?=$folder_assets?>/images/sai_icon/logo-text.png" class="light-logo" alt="homepage" width="40px"/>
                                    </div>
                                </div>
                                <div class='col-md-8'>
                                    <div class='label-header'>
                                        <h6><?=date('Y-m-d H:i:s')?></h6>
                                        <h6 style="color:#007AFF"><i class='fa fa-user'></i> <?=$nik?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-2'>
                            <h5>Total Closing</h5>
                        </div>
                        <div class='col-md-6'>
                            <h3><input type='text' style='font-size: 40px;'  name="total" min="1" class='form-control currency' id='total' required readonly></h3>
                        </div>
                        <div class='col-md-12'>
                            <table class='table' style='margin-bottom: 5px'>
                                <tr>
                                    <td style='padding: 3px;width:25%' colspan='2'>
                                    <input type='date' class='form-control' placeholder="Tanggal" id="tgl" value="<?=date('Y-m-d')?>" >
                                    </td>
                                    <td style='padding: 0px;'><a href='#' style='color:white' class='btn btn-info mt-1 ml-2' id='loadData'><i class='fa fa-plus'></i> Load Data</a></td>
                                </tr>
                            </table>
                            <div class='col-xs-12' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                <table class="table table-striped table-bordered table-condensed" id="input-grid">
                                    <thead>
                                    <tr>
                                        <th>No Jual</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Periode</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class='row'>
                                <div class='col-md-12 mt-2'>
                                    <div class='form-group row float-right'>
                                        <div class='col-12'>
                                            <button class='btn btn-info' type="submit" id='btnBayar'><i class="fa fa-save"></i> Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

<script src="<?=$folderroot_js?>/inputmask.js"></script>
<script>
    var $submitBtn = false;
    function sepNum(x){
        var num = parseFloat(x).toFixed(0);
        var parts = num.toString().split(".");
        var len = num.toString().length;
        // parts[1] = parts[1]/(Math.pow(10, len));
        parts[0] = parts[0].replace(/(.)(?=(.{3})+$)/g,"$1.");
        return parts.join(",");
    }

    function toRp(num){
        if(num < 0){
            return "("+sepNum(num * -1)+")";
        }else{
            return sepNum(num);
        }
    }

    function toNilai(str_num){
        var parts = str_num.split('.');
        number = parts.join('');
        number = number.replace('Rp', '');
        // number = number.replace(',', '.');
        return +number;
    }

    // // $('#loadData').click(function(e){
    // //     e.preventDefault();
    // //     var tgl = $('#tgl').val();
    // //     alert(tgl);
        
    // // });
    // var Table = $("#input-grid").DataTable({
    //     data:[],
    //     // columns: [
    //     //             { "data": "no_jual"  },
    //     //             { "data": "tanggal" },
    //     //             { "data": "keterangan" },
    //     //             { "data": "nilai" },
    //     //             { "data": "periode" }
    //     // ],
    //     rowCallback: function (row, data) {},
    //     filter: false,
    //     info: false,
    //     ordering: false,
    //     processing: true,
    //     retrieve: true,   
    //     // ajax: {
    //     //     url: "<?=$root_ser?>/ClosingHarian.php?fx=getPnj",
    //     //     data: {'kode_lokasi': '<?=$kode_lokasi?>','nik_user':'<?=$nik?>','tanggal':tgl,'kode_pp':'<?=$kode_pp?>'},
    //     //     type: 'POST',
    //     // },
    //     columns: [
    //         { data: "no_jual"  },
    //         { data: "tanggal" },
    //         { data: "keterangan" },
    //         { data: "nilai" },
    //         { data: "periode" }
    //     ],   
    // });


    // $("#loadData").on("click", function (event) {
    //     event.preventDefault();
    //     var tgl = $('#tgl').val();
    //     $.ajax({
    //         url: "<?=$root_ser?>/ClosingHarian.php?fx=getPnj",
    //         type: "POST",
    //         data: { 'kode_lokasi': '<?=$kode_lokasi?>','nik_user':'<?=$nik?>','tanggal':tgl,'kode_pp':'<?=$kode_pp?>' }
    //     }).done(function (result) {
    //         Table.clear().draw();
    //         Table.rows.add(['test','test','test','test','test']).draw();
    //     }).fail(function (jqXHR, textStatus, errorThrown) { 
    //         // needs to implement if it fails
    //     });
    // });

    // var t = $('#input-grid').DataTable();
    var counter = 1;
    var table = $('#input-grid').DataTable({
        data: [],
        columns: [
            { data: 'no_jual' },
            { data: 'tanggal' },
            { data: 'keterangan' },
            { data: 'nilai' },
            { data: 'periode' },
        ]
    });
 
    $('#loadData').on('click', function (e) {
        e.preventDefault();
        var tgl = $('#tgl').val();
        $.ajax({
            url: "<?=$root_ser?>/ClosingHarian.php?fx=getPnj",
            type: "POST",
            data: { 'kode_lokasi': '<?=$kode_lokasi?>','nik_user':'<?=$nik?>','tanggal':tgl,'kode_pp':'<?=$kode_pp?>' }
        }).done(function (result) {
            table.clear().draw();
            table.rows.add(result.data).draw(false);
        }).fail(function (jqXHR, textStatus, errorThrown) { 
                // needs to implement if it fails
        });

    });
 
    // Automatically add a first row of data
    $('#loadData').click();
</script>