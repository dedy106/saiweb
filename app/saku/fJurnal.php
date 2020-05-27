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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Jurnal 
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
                                        <th>No Bukti</th>
                                        <th>Tanggal</th>
                                        <th>No Dokumen</th>
                                        <th>Deskripsi</th>
                                        <th>Nilai</th>
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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Form Data Jurnal
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:441px'>
                        <form class="form" id="form-tambah" style=''>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal" class="col-2 col-form-label">Tanggal</label>
                                <div class="col-3">
                                    <input class='form-control' type="date" id="tanggal" name="tanggal" value="<?=date('Y-m-d')?>">
                                </div>
                                <div class="col-2">
                                </div>
                                <label for="jenis" class="col-2 col-form-label">Jenis</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="jenis" name="jenis">
                                    <option value=''>--- Pilih Jenis ---</option>
                                    <option value='MI' selected>MI</option>
                                    </select>
                                </div>
                                <div class="col-3" style="display:none">
                                    <input class="form-control" type="text" placeholder="No Bukti" id="no_bukti" name="no_bukti" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_dokumen" class="col-2 col-form-label">No Dokumen</label>
                                <div class="col-4">
                                    <input class="form-control" type="text" placeholder="No Dokumen" id="no_dokumen" name="no_dokumen">
                                </div>
                                <div class="col-1">
                                </div>
                                <label for="total_debet" class="col-2 col-form-label">Total Debet</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Total Debet" id="total_debet" name="total_debet" value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="deskripsi" class="col-2 col-form-label">Deskripsi</label>
                                <div class="col-4">
                                    <input class="form-control" type="text" placeholder="Deskripsi" id="deskripsi" name="deskripsi">
                                </div>
                                <div class="col-1">
                                </div>
                                <label for="total_kredit" class="col-2 col-form-label">Total Kredit</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" placeholder="Total Kredit" id="total_kredit" name="total_kredit" value="0">
                                </div>
                            </div>
                            <div class='col-xs-12' style='overflow-y: scroll; height:250px; margin:0px; padding:0px;'>
                                <table class="table table-striped table-bordered table-condensed gridexample" id="input-jurnal" width="100%">
                                <style>
                                    th{
                                        vertical-align:middle !important;
                                    }
                                </style>
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Akun</th>
                                        <th width="5%">DC</th>
                                        <th width="25%">Keterangan</th>
                                        <th width="20%">Nilai</th>
                                        <th width="20">Kode PP</th>
                                        <th  width="5%"><button type="button" href="#" id="add-row" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    
    <script src="<?=$folderroot_js?>/jquery.formnavigation.js"></script>         
    <script>
    function getPP(param){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Jurnal.php?fx=getPP',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('.'+param).selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp}]);
                        }
                    }
                }
            }
        });
    }
    function getDC(id,param){
        
        // var select = $('.'+param).selectize();
        // select = select[0];
        // var control = select.selectize;
        // control.addOption([{text:'D', value:'D'}]);
        // control.addOption([{text:'C', value:'C'}]);
                   
    }

    function getAkun(param,param2){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Jurnal.php?fx=getAkun',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('.'+param).selectize({
                            selectOnTab: true,
                            onChange: function (val){
                                var id = val;
                                getDC(id,param2);
                            }
                        });

                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_akun + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_akun}]);
                        }

                    }
                }
            }
        });
    }
    $('.gridexample').formNavigation();
    $('.selectize').selectize();

    $('#saku-datatable').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#input-jurnal tbody').html('');
        $('#saku-datatable').hide();
        $('#saku-form').show();
    });

    function hitungTotal(){
        
        var total_d = 0;
        var total_k = 0;

        $('.row-jurnal').each(function(){
            var dc = $(this).closest('tr').find('.inp-dc option:selected').val();
            var nilai = $(this).closest('tr').find('.inp-nilai').val();
            if(dc == "D"){
                total_d += +toNilai(nilai);
            }else{
                total_k += +toNilai(nilai);
            }
        });

        $('#total_debet').val(total_d);
        $('#total_kredit').val(total_k);
        
    }

    $('#form-tambah').on('click', '#add-row', function(){
        var no=$('#input-jurnal .row-jurnal:last').index();
        no=no+2;
        var input = "";
        input += "<tr class='row-jurnal'>";
        input += "<td width='3%' class='no-jurnal text-center'>"+no+"</td>";
        input += "<td width='20%'><select name='kode_akun[]' class='form-control inp-kode akunke"+no+"' value='' required></select></td>";
        input += "<td width='10%'><select name='dc[]' class='form-control inp-dc dcke"+no+"' value='' required><option value='D'>D</option><option value='C'>C</option></select></td>";
        input += "<td width='20%'><input type='text' name='keterangan[]' class='form-control inp-ket'  value='' required></td>";
        input += "<td width='17%'><input type='text' name='nilai[]' class='form-control inp-nilai'  value='' required></td>";
        input += "<td width='20%'><select name='kode_pp[]' class='form-control inp-pp ppke"+no+"' value='' required></select></td>";
        input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
        input += "</tr>";
        $('#input-jurnal tbody').append(input);
        getAkun('akunke'+no,'dcke'+no);
        getPP('ppke'+no);
        $('.dcke'+no).selectize();
        $('.inp-nilai').inputmask("numeric", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            rightAlign: true,
            oncleared: function () { self.Value(''); }
        });
        $('.gridexample').formNavigation();
        $('#input-jurnal tbody tr:last').find('.inp-kode')[0].selectize.focus();
    });

    $('.currency').inputmask("numeric", {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });

    $('#input-jurnal').on('click', '.hapus-item', function(){
        $(this).closest('tr').remove();
        no=1;
        $('.row-jurnal').each(function(){
            var nom = $(this).closest('tr').find('.no-jurnal');
            nom.html(no);
            no++;
        });
        hitungTotal();
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });

    $('#input-jurnal').on('change', '.inp-nilai', function(){
        hitungTotal();
    });

    $('#input-jurnal').on('change', '.inp-dc', function(){
        hitungTotal();
    });

    $('#input-jurnal').on('keydown', '.inp-kode', function(e){
        if (e.which == 13) {
            e.preventDefault();
            if($.trim($(this).closest('tr').find('.inp-kode')[0].selectize.getValue()).length){
                $(this).closest('tr').find('.inp-dc')[0].selectize.focus();
            }else{
                alert('Akun yang dimasukkan tidak valid');
                return false;
            }
        }
    });

    $('#input-jurnal').on('keydown', '.inp-dc', function(e){
        if (e.which == 13) {
            e.preventDefault();
            if($(this).closest('tr').find('.inp-dc')[0].selectize.getValue() == 'D' || $(this).closest('tr').find('.inp-dc')[0].selectize.getValue() == 'C'){
                $(this).closest('tr').find('.inp-ket').focus();
            }else{
                alert('Posisi yang dimasukkan tidak valid');
                return false;
            }
        }
    });

    $('#input-jurnal').on('keydown', '.inp-ket', function(e){
        if (e.which == 13) {
            e.preventDefault();
            if($.trim($('.inp-ket').val()).length){
                $(this).closest('tr').find('.inp-nilai').focus();
            }else{
                alert('Keterangan yang dimasukkan tidak valid');
                return false;
            }
        }
    });

    $('#input-jurnal').on('focus', '.inp-ket', function(e){
        var this_index = $(this).closest('tbody tr').index();
        
        if($("#input-jurnal tbody tr:eq("+(this_index - 1)+")").find('.inp-ket').val() != undefined){
            $(this).val($("#input-jurnal tbody tr:eq("+(this_index - 1)+")").find('.inp-ket').val());
        }else{
            $(this).val('');
        }
    });

    $('#input-jurnal').on('focus', '.inp-nilai', function(e){
        var dc = $(this).closest('tr').find('.inp-dc')[0].selectize.getValue();
        if(dc == 'D' || dc == 'C'){
            var selisih = Math.abs(toNilai($('#total_debet').val()) - toNilai($('#total_kredit').val()));
            $(this).val(selisih);
            // $('#inp-nilai').focus();
        }else{
            alert('Posisi tidak valid, harap pilih posisi akun');
            $(this).closest('tr').find('.inp-dc')[0].selectize.focus();
        }
    });

    $('#input-jurnal').on('keydown', '.inp-nilai', function(e){
        if (e.which == 13) {
            e.preventDefault();
            if($(this).closest('tr').find('.inp-nilai').val() != "" && $(this).closest('tr').find('.inp-nilai').val() != 0){
                $(this).closest('tr').find('.inp-pp')[0].selectize.focus();
                hitungTotal();
            }else{
                alert('Nilai yang dimasukkan tidak valid');
                return false;
            }
        }
    });

    $('#input-jurnal').on('keydown', '.inp-pp', function(e){
        if (e.which == 13) {
            e.preventDefault();
            if($.trim($(this).closest('tr').find('.inp-pp')[0].selectize.getValue()).length){
                $('#add-row').click();
                hitungTotal();
            }else{
                alert('PP yang dimasukkan tidak valid');
                return false;
            }
        }
    });

   
    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();
        
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Jurnal.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'no_bukti':id},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#no_bukti').val(id);
                    $('#no_bukti').attr('readonly', true);
                    $('#tanggal').val(result.daftar[0].tanggal);
                    $('#deskripsi').val(result.daftar[0].deskripsi);
                    $('#no_dokumen').val(result.daftar[0].no_dokumen);
                    $('#total_debet').val(result.daftar[0].nilai1);
                    $('#total_kredit').val(result.daftar[0].nilai1);
                    $('#jenis').val(result.daftar[0].jenis);
                    if(result.daftar2.length > 0){
                        var input = '';
                        var no=1;
                        for(var i=0;i<result.daftar2.length;i++){
                            var line =result.daftar2[i];
                            input += "<tr class='row-jurnal'>";
                            input += "<td width='3%' class='no-jurnal text-center'>"+no+"</td>";
                            input += "<td width='20%'><select name='kode_akun[]' class='form-control inp-kode akunke"+no+"' value='' required></select></td>";
                            input += "<td width='10%'><select name='dc[]' class='form-control inp-dc dcke"+no+"' value='' required><option value='D'>D</option><option value='C'>C</option></select></td>";
                            input += "<td width='20%'><input type='text' name='keterangan[]' class='form-control inp-ket'  value='"+line.keterangan+"' required></td>";
                            input += "<td width='17%'><input type='text' name='nilai[]' class='form-control inp-nilai'  value='"+line.nilai+"' required></td>";
                            input += "<td width='20%'><select name='kode_pp[]' class='form-control inp-pp ppke"+no+"' value='' required></select></td>";
                            input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                            input += "</tr>";
                            no++;
                        }
                        $('#input-jurnal tbody').html(input);

                        $('.inp-nilai').inputmask("numeric", {
                            radixPoint: ",",
                            groupSeparator: ".",
                            digits: 2,
                            autoGroup: true,
                            rightAlign: true,
                            oncleared: function () { self.Value(''); }
                        });
                        $('.gridexample').formNavigation();
                        var no=1;
                        for(var i=0;i<result.daftar2.length;i++){
                            var line =result.daftar2[i];
                            getAkun('akunke'+no,'dcke'+no);
                            getPP('ppke'+no);
                            $('.dcke'+no).selectize();
                            $('.akunke'+no)[0].selectize.setValue(line.kode_akun);
                            $('.ppke'+no)[0].selectize.setValue(line.kode_pp);
                            $('.dcke'+no)[0].selectize.setValue(line.dc);
                            no++;
                        }
                    }
                    hitungTotal();
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
            'url': '<?=$root_ser?>/Jurnal.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {   'targets': 4, 
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) 
            },
            {'targets': 5, data: null, 'defaultContent': action_html }
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
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/Jurnal.php',
                    dataType: 'json',
                    async:false,
                    data: {'no_bukti':kode},
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
        var formData = new FormData(this);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        var total_d = $('#total_debet').val();
        var total_k = $('#total_kredit').val();
        var jumdet = $('#input-jurnal tr').length;

        if(total_d != total_k){
            alert('Transaksi tidak valid. Total Debet dan Total Kredit tidak sama');
        }else if( total_d <= 0 || total_k <= 0){
            alert('Transaksi tidak valid. Total Debet dan Total Kredit tidak boleh sama dengan 0 atau kurang');
        }else if(jumdet <= 1){
            alert('Transaksi tidak valid. Detail jurnal tidak boleh kosong ');
        }else{

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Jurnal.php?fx=simpan',
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