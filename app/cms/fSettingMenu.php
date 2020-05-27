
<?php
     session_start();
     $root_lib=$_SERVER["DOCUMENT_ROOT"];
     if (substr($root_lib,-1)!="/") {
         $root_lib=$root_lib."/";
     }
     include_once($root_lib.'app/cms/setting.php');
 
    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];

    $kode_klp=$_SESSION['kodeMenu'];
    
    $path = "http://".$_SERVER["SERVER_NAME"]."/";
?>
<!--Jquery Treegrid -->

<link href="<?=$folderroot_css?>/jquery.treegrid.css" rel="stylesheet">
<style>
.ui-selected{
    background: #4286f5;
    color:white;
}
td,th{
    padding:8px !important;
}
</style>
<div class="container-fluid mt-3">
    <div class="row" id="saku-data">
        <div class="col-12">
            <div class="card">
            <form id="menu-form">
                <div class='card-body'>
                    <div class='row'>
                        <div class='col-3'>
                            <select name='kode_klp' id='kode_klp' class='form-control selectize'>
                                <option value=''>Pilih Kelompok Menu</option>
                            </select>
                        </div>
                        <div class='col-9 text-right'>
                            <!-- <button type='submit' class='sai-treegrid-btn-save btn btn-success btn-sm' ><i class='fa fa-save'></i> Simpan</button> -->
                            
                            <a href='#' class='sai-treegrid-btn-root btn btn-secondary btn-sm' ><i class='fa fa-anchor'></i> Root</a>
                            <a href='#' class='sai-treegrid-btn-tb btn btn-success btn-sm' ><i class='fa fa-plus'></i> Tambah</a>
                            <a href='#' class='sai-treegrid-btn-ub btn btn-primary btn-sm' ><i class='fa fa-pencil-alt'></i> Edit</a>
                            <a href='#' class='sai-treegrid-btn-del btn btn-danger btn-sm'><i class='fa fa-times'></i> Hapus</a>
                            <a href='#' class='sai-treegrid-btn-down btn btn-secondary btn-sm' ><i class='fas fa-angle-down'></i> Turun</a>
                            <a href='#' class='sai-treegrid-btn-up btn btn-secondary btn-sm' ><i class='fas fa-angle-up'></i> Naik</a>
                            <button type='submit' class='sai-treegrid-btn-save btn btn-primary btn-sm' ><i class='fas fa-save'></i> Simpan</button>
                        </div>
                    </div>
                </div>
                <div id="detMenu" class="card-body table-responsive" style="height: 460px;">
                    
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="sai-treegrid-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="sai-treegrid-modal-form">
                <div class='modal-header'>
                    <h5 class='modal-title'>Form Setting Menu</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mt-40">
                        <label for="kode-set" class="col-3 col-form-label">Kode</label>
                        <div class="col-9">
                            <input type='text' name='kode_menu' maxlength='5' class='form-control' required id='kode-set' style='text-align:left'>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nama-set" class="col-3 col-form-label">Nama</label>
                        <div class="col-9">
                            <input type='text' name='nama' maxlength='100' class='form-control' required id='nama-set'>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link-set" class="col-3 col-form-label">Link</label>
                        <div class="col-9">
                            <select class='form-control selectize' name='link' id='link-set'>
                                <option value='' disabled>Pilih Link</option>
                            </select>    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="icon-set" class="col-3 col-form-label">Icon</label>
                        <div class="col-9">
                            <input type='text' name='icon' maxlength='100' class='form-control' required id='icon-set'> 
                        </div>
                    </div>
                    <div class="form-group row" style=''>
                        <label for="link-set" class="col-3 col-form-label">Level</label>
                        <div class="col-9">
                            <input type='number' name='level_menu' maxlength='5' class='form-control' readonly required id='lv-set'> 
                        </div>
                    </div>
                    <div class="form-group row" style='display:none'>
                        <!-- <label for="link-set" class="col-3 col-form-label">Urutan</label> -->
                        <div class="col-9">
                            <input type='hidden' name='nu' class='form-control' readonly required id='nu-set'>
                        </div>
                    </div>
                    <div class='form-group row' style='display:none'>
                        <!-- <label for="link-set" class="col-3 col-form-label">Row index</label> -->
                        <div class='col-9' style='margin-bottom:5px;'>
                        <input type='hidden' name='rowindex' class='form-control' readonly id='rowindex-set'>
                        </div>
                    </div>
                    <div class='form-group row' style='display:none'>                        
                        <!-- <label class='control-label col-3'>Kode Klp Menu</label> -->
                        <div class='col-9' style='margin-bottom:5px;'>
                        <input type='hidden' name='kode_klp' class='form-control' readonly id='klp-set'>
                        </div>
                    </div>
                    <div id='validation-box'></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="tb-set-index" style='margin-right:15px;'>Simpan</button>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal' aria-label='Close'> Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- JS Tree -->
<script src="<?=$folderroot_js?>/jquery.treegrid.js"></script>
<script src="<?=$folderroot_js?>/inputmask.js"></script>
<script src="<?=$folderroot_js?>/sai.js"></script>
<script type="text/javascript">

    function init(kode_klp=null){
        if(kode_klp == null){
            var kode =  '<?php echo $kode_klp; ?>';
        }else{
            var kode = kode_klp;
        }
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/SettingMenu.php?fx=getMenu',
            dataType: 'json',
            data: {'kode_lokasi':'<?php echo $kode_lokasi ?>','kode_klp':kode},
            success:function(result){    
                if(result.status){
                    // $('#sai-treegrid tbody').html('');
                    // $('.treegrid').treegrid('remove');
                    $('#detMenu').html('');
                    if(typeof result.html !== 'undefined'){
                        var html = `<table class='treegrid table' id='sai-treegrid'>
                            <thead><th>Kode Menu</th><th>Nama Menu</th><th>Kode Form</th></thead>
                            <tbody>
                            `+result.html+`
                            </tbody>
                        </table>`;
                        $('#detMenu').html(html);
                        $('.treegrid').treegrid({
                            enableMove: true, 
                            onMoveOver: function(item, helper, target, position) {
                                console.log(target);
                                console.log(position); 
                            }
                        });
                        // $('.treegrid').treegrid('add', [result.html]);
                        // $('.treegrid').treegrid('render');
                        // $('.treegrid').treegrid({
                        //     expanderExpandedClass: 'glyphicon glyphicon-minus',
                        //     expanderCollapsedClass: 'glyphicon glyphicon-plus'
                        // });
                    }
                }
            }
        });
    }
    
    // $('#kode_klp').selectize({
    //     selectOnTab:true,
    //     onChange: function (val){
    //         var id = val
    //         if (id != "" && id != null && id != undefined){
    //             init(id);
    //             // alert(id);
    //         }
    //     }
    // });

    $('#kode_klp').change(function(e){
        e.preventDefault();
        init($(this).val());
    })

    function getKlp(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/SettingMenu.php?fx=getKlp',
            dataType: 'json',
            data: {'kode_lokasi':'<?php echo $kode_lokasi ?>','kode_menu':'<?=$kode_klp?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_klp').selectize();
                        select = select[0];
                        var control = select.selectize;
                        control.clearOptions();
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_klp, value:result.daftar[i].kode_klp}]);  
                        }
                    }
                }
            }
        });
    }


    function getLink(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/SettingMenu.php?fx=getLink',
            dataType: 'json',
            data: {'kode_lokasi':'<?php echo $kode_lokasi ?>','kode_menu':'<?=$kode_klp?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#link-set').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].id + ' - ' + result.daftar[i].nama, value:result.daftar[i].id}]);  
                           
                        }
                    }
                }
            }
        });
    }

    $(document).ready(function(){
    
        // init();
        getLink();
        getKlp();

        $('#detMenu').on('click', 'tr', function(){
            $('#sai-treegrid tbody tr').removeClass('ui-selected');
            $(this).addClass('ui-selected');

            var this_index = $(this).index();
            var this_class = $("#sai-treegrid tbody tr:eq("+this_index+")").attr('class');
            var node_class = this_class.match(/^treegrid-[0-9]+/gm);

            var this_node = $("."+node_class).treegrid('getId');
            var this_parent = $("."+node_class).treegrid('getParent');
            var this_kode = $("."+node_class).find('.set_kd_mn').text();
            var this_icon = $("."+node_class).find('.set_icon').val();
            var this_nu = $("."+node_class).treegrid('getChildNodes').last().find('.set_nu').text();
            var this_rowindex = $("."+node_class).treegrid('getChildNodes').last().find('.set_index').text();


            if(this_nu == ""){
                var this_nu = $("."+node_class).find('.set_nu').text();
                var this_rowindex = $("."+node_class).find('.set_index').text();
            }

            
            var this_lv = $("."+node_class).treegrid('getDepth');
            var this_child_amount = $("."+node_class).treegrid('getChildNodes').length;
            var this_child_branch = $("."+node_class).treegrid('getBranch').length;

            var nu = parseInt(this_nu) + 1;
            var rowindex = parseInt(this_rowindex) + 1;

            if(nu == null || nu == '' || isNaN(nu)){
                nu = 101;
            }else{
                nu = nu;
            }

            if(rowindex == null || rowindex == '' || isNaN(rowindex)){
                rowindex = 1;
            }else{
                rowindex = rowindex;
            }
            
            // $('#kode-set').val(this_kode.concat(+this_child_amount + 1));
            $('#lv-set').val(this_lv);
            $('#nu-set').val(nu);
            $('#icon-set').val(this_icon);
            $('#rowindex-set').val(rowindex);
            
            var klp_menu = $('#kode_klp')[0].selectize.getValue();
            $('#klp-set').val(klp_menu);

        });

        $('.sai-treegrid-btn-root').click(function(){
            // clear
            $('#kode-set').val('');
            $('#nama-set').val('');
            $('#icon-set').val('');
            $('#link-set')[0].selectize.setValue('');
            // $('#induk-set').val('');

            $('#sai-treegrid tbody tr').removeClass('ui-selected');
            var root = $('#sai-treegrid').treegrid('getRoots').length;
            // if (root == 1){
            //     var kode=root;
            // }else{
                var kode=root+1;
            // }
            // alert(root);
            var klp_menu = $('#kode_klp')[0].selectize.getValue();
            // $('#kode-set').val(kode);
            $('#klp-set').val(klp_menu);
            $('#lv-set').val(0);
            var nu = parseInt($("#sai-treegrid tbody tr:last").find('.set_nu').text());
            if(nu == null || nu == '' || isNaN(nu)){
                nu = 100;
            }else{
                nu = nu;
            }
            $('#nu-set').val(nu + 1);
            var this_rowindex = parseInt($("#sai-treegrid tbody tr:last").find('.set_index').text());
            if(this_rowindex == null || this_rowindex == '' || isNaN(this_rowindex)){
                this_rowindex = 0;
            }else{
                this_rowindex = this_rowindex;
            }
            $('#rowindex-set').val(this_rowindex+1);
            $('.del-gl-index').attr('href', '#');
            getLink();
            
            $('#kode-set').val('');
            $('#nama-set').val('');
            $('#icon-set').val('');
            $('#link-set')[0].selectize.setValue('');
            $('#sai-treegrid-modal').modal('show');
        });

        $('.sai-treegrid-btn-up').click(function(){
            if($(".ui-selected").length != 1){
                alert('Harap pilih struktur yang akan dipindah terlebih dahulu');
                return false;
            }else{
                var prev_index = $(".ui-selected").closest('tr').index()-1;
                var prev_id = $(".treegrid-"+prev_index).treegrid('getId');
                var prev_class = $("#sai-treegrid tbody tr:eq("+prev_index+")").attr('class');
                var prev_node_class = prev_class.match(/^treegrid-[0-9]+/gm);
                var prev_node = $("."+prev_node_class).treegrid('getId');
                var prev_parent = $("."+prev_node_class).treegrid('getParent').index();
                var prt_class = $("#sai-treegrid tbody tr:eq("+prev_parent+")").attr('class');
                var prt_node_class = prt_class.match(/^treegrid-[0-9]+/gm);
                var prev_lvl = $("."+prev_node_class).find('.set_lvl').val();
                var prt_lvl = $("."+prt_node_class).find('.set_lvl').val();
                
                var this_index = $(".ui-selected").closest('tr').index();
                var this_id = $(".treegrid-"+this_index).treegrid('getId');
                var this_depth = $(".treegrid-"+this_index).treegrid('getDepth');

                var this_class = $("#sai-treegrid tbody tr:eq("+this_index+")").attr('class');
                var this_node_class = this_class.match(/^treegrid-[0-9]+/gm);
                
                var this_node = $("."+this_node_class).treegrid('getId');
                var this_parent = $("."+this_node_class).treegrid('getParent').index();
                var this_lvl = $("."+this_node_class).find('.set_lvl').val();
                
                if(this_lvl == 0){
                    var tmp = prev_class.split(' ');
                    if(tmp[1] == undefined){
                        prev_parent = -1;
                    }else{
                        var target = tmp[1].split('-');
                        prev_parent = target[2];
                    }
                    
                    if(prev_parent < 0){
                        var seb_node = 'treegrid-'+prev_node;
                        // var seb_node = prev_index;
                        prt_lvl = prev_lvl;
                    }else{
                        var seb_node = 'treegrid-'+prev_parent;
                        // var seb_node = prev_index;
                    }
                }else{
                    prt_lvl = prev_lvl;
                    var seb_node = 'treegrid-'+prev_node;
                }

                if(prev_index >= 0){
                    if(this_lvl == prt_lvl){
                        $('.treegrid-'+this_node).treegrid('move', $('.'+seb_node), 0);
                    }else{
                        return false;
                    }

                }

            }
        });

        $('.sai-treegrid-btn-down').click(function(){
            if($(".ui-selected").length != 1){
                alert('Harap pilih struktur yang akan dipindah terlebih dahulu');
                return false;
            }else{
                
                var this_index = $(".ui-selected").closest('tr').index();
                console.log('this_index:'+this_index);
                var this_id = $(".treegrid-"+this_index).treegrid('getId');
                var this_depth = $(".treegrid-"+this_index).treegrid('getDepth');

                var this_class = $("#sai-treegrid tbody tr:eq("+this_index+")").attr('class');
                var this_node_class = this_class.match(/^treegrid-[0-9]+/gm);
                
                var this_node = $("."+this_node_class).treegrid('getId');
                var this_parent = $("."+this_node_class).treegrid('getParent').index();
                var this_lvl = $("."+this_node_class).find('.set_lvl').val();
                var this_child_amount = $("."+this_node_class).treegrid('getChildNodes').length;
                var this_child_branch = $("."+this_node_class).treegrid('getBranch').length;

                var tambah = 0;
                if(this_child_amount > 0){
                    tambah = parseInt(this_child_amount)+1;
                }else{
                    tambah = 1;
                }
                var next_index = $(".ui-selected").closest('tr').index()+tambah;
                var next_id = $(".treegrid-"+next_index).treegrid('getId');
                var next_class = $("#sai-treegrid tbody tr:eq("+next_index+")").attr('class');
                var next_node_class = next_class.match(/^treegrid-[0-9]+/gm);
                var next_node = $("."+next_node_class).treegrid('getId');
                var next_parent = $("."+next_node_class).treegrid('getParent').index();
                var prt_class = $("#sai-treegrid tbody tr:eq("+next_parent+")").attr('class');
                var prt_node_class = prt_class.match(/^treegrid-[0-9]+/gm);
                var next_lvl = $("."+next_node_class).find('.set_lvl').val();
                var prt_lvl = $("."+prt_node_class).find('.set_lvl').val();

                console.log('next_index:'+next_index);
                console.log('next_id:'+next_id);
                console.log('next_class:'+next_class);
                console.log('next_node_class:'+next_node_class);
                console.log('next_parent:'+next_parent);
                console.log('prt_class:'+prt_class);
                console.log('prt_node_class:'+prt_node_class);
                console.log('next_lvl:'+next_lvl);
                console.log('prt_lvl:'+prt_lvl);
                
                
                if(this_lvl == 0){
                    var tmp = next_class.split(' ');
                    if(tmp[1] == undefined){
                        next_parent = -1;
                    }else{
                        var target = tmp[1].split('-');
                        next_parent = target[2];
                    }
                    
                    if(next_parent < 0){
                        var stlh_node = 'treegrid-'+next_node;
                        // var stlh_node = next_index;
                        prt_lvl = next_lvl;
                    }else{
                        var stlh_node = 'treegrid-'+next_parent;
                        // var stlh_node = next_index;
                    }
                }else{
                    prt_lvl = next_lvl;
                    var stlh_node = 'treegrid-'+next_node;
                }
                console.log('this_lvl:'+this_lvl);
                console.log('prt_lvl:'+prt_lvl);
                console.log('this_node:'+this_node);
                console.log('stlh_node:'+stlh_node);
                console.log('this_depth:'+this_depth);
                console.log('this_child_amount:'+this_child_amount);
                console.log('this_child_branch:'+this_child_branch);

                if(next_index >= 0){
                    if(this_lvl == prt_lvl){
                        $('.'+stlh_node).treegrid('move', $('.treegrid-'+this_node), 0);
                    }else{
                        return false;
                    }

                }

            }
        });

        $('.sai-treegrid-btn-tb').click(function(){
            
            if($(".ui-selected").length != 1){
                // clear
                $('#kode-set').val('');
                $('#nama-set').val('');
                $('#link-set')[0].selectize.setValue('');
                // $('#induk-set').val('');
                $('#sai-treegrid tbody tr').removeClass('ui-selected');

                // get prev code

                var root = $('#sai-treegrid tbody').treegrid('getRoots').length;
                if (root == 1){
                    var kode=root;
                }else{
                    var kode=root+1;
                }
                
                var klp_menu = $('#kode_klp')[0].selectize.getValue();
                // $('#kode-set').val(kode);
                $('#klp-set').val(klp_menu);
                $('#lv-set').val(0);
                var nu = parseInt($("#sai-treegrid tbody tr:last").find('.set_nu').text());
                if(nu == null || nu == '' || isNaN(nu)){
                    nu = 100;
                }else{
                    nu = nu;
                }
                $('#nu-set').val(nu+1);
                var rowindex = parseInt($("#sai-treegrid tbody tr:last").find('.set_index').text());

                if(rowindex == null || rowindex == '' || isNaN(rowindex)){
                    rowindex = 0;
                }else{
                    rowindex = rowindex;
                }

                $('#rowindex-set').val(rowindex+1);
                $('.del-gl-index').attr('href', '#');
            }
            getLink();
            $('#kode-set').val('');
            $('#nama-set').val('');
            $('#icon-set').val('');
            $('#link-set')[0].selectize.setValue('');
            $('#sai-treegrid-modal').modal('show');
        });

        $('.sai-treegrid-btn-del').click(function(){
            if($(".ui-selected").length != 1){
                alert('Harap pilih struktur yang akan dihapus terlebih dahulu');
                return false;
            }else{
                var sts = confirm("Apakah anda yakin ingin menghapus item ini?");
                if(sts){
                    var selected_id = $(".ui-selected").closest('tr').find('.set_kd_mn').text();
                    service_domain = '<?php echo $path; ?>';
                    lokasi = '<?php echo $kode_lokasi; ?>';
                    // window.location = "<?=$root_ser?>/SettingMenu.php?fx=delMenu&param="+selected_id+"&lok="+lokasi;
                    var kode_klp=$('#kode_klp')[0].selectize.getValue();
                    $.ajax({
                        type: 'GET',
                        url: '<?=$root_ser?>/SettingMenu.php?fx=delMenu',
                        dataType: 'json',
                        data: {'kode_klp':kode_klp,'kode_menu':selected_id,'kode_lokasi':'<?=$kode_lokasi?>'},
                        success:function(res){
                            alert(res.message);
                            if(res.status){
                                init(kode_klp);
                                
                            }
                        }
                    });

                }else{
                    return false;
                }
            }
        });

        $('.sai-treegrid-btn-ub').click(function(){
            if($(".ui-selected").length == 1){
                var sel_index = $(".ui-selected").closest('tr').index();
                var sel_node = $(".treegrid-"+sel_index).treegrid('getId');
                var sel_depth = $(".treegrid-"+sel_index).treegrid('getDepth');
                // alert(sel_index);
                
                var sel_class = $("#sai-treegrid tbody tr:eq("+sel_index+")").attr('class');
                var node_class = sel_class.match(/^treegrid-[0-9]+/gm);

                var this_node = $("."+node_class).treegrid('getId');
                var this_parent = $("."+node_class).treegrid('getParent');
                var this_kode = $("."+node_class).find('.set_kd_mn').text();
                var this_nama = $("."+node_class).find('.set_nama').text();
                var this_link = $("."+node_class).find('.set_link').text();
                var this_nu = parseInt($("."+node_class).find('.set_nu').text());
                var this_rowindex = parseInt($("."+node_class).find('.set_index').text());
                var this_lv = $("."+node_class).treegrid('getDepth');
                var this_child_amount = $("."+node_class).treegrid('getChildNodes').length;
                var this_child_branch = $("."+node_class).treegrid('getBranch').length;
                var this_induk = $("."+node_class).treegrid('getParent').find('.set_kd_mn').text();
            
                
                var klp_menu = $('#kode_klp')[0].selectize.getValue();
                $('#kode-set').val(this_kode);
                $('#klp-set').val(klp_menu);
                $('#nama-set').val(this_nama);
                $('#link-set')[0].selectize.setValue(this_link);
                $('#lv-set').val(this_lv-1);
                
                $('#nu-set').val(this_nu);
                $('#rowindex-set').val(this_rowindex);
                // $('#induk-set').val(this_induk);
                getLink();
                $('#sai-treegrid-modal').modal('show');


            }else{
                alert('Harap pilih struktur yang akan diubah terlebih dahulu');
                return false;
            }
        });
        
        $("#sai-treegrid-modal-form").on("submit", function(event){
            event.preventDefault();
            var sel_index = $(".ui-selected").closest('tr').index();
            var sel_node = $(".treegrid-"+sel_index).treegrid('getId');
            var sel_depth = $(".treegrid-"+sel_index).treegrid('getDepth');
            
            var sel_class = $("#sai-treegrid tbody tr:eq("+sel_index+")").attr('class');
            var node_class = sel_class.match(/^treegrid-[0-9]+/gm);

            var new_node = $("#sai-treegrid tbody tr:last").index() + 1;
            var kode_str = $('#kode-set').val();
            var nama_str = $('#nama-set').val();
            var nu_str = $('#nu-set').val();
            var rowindex_str = $('#rowindex-set').val();
            var kode_klp = $('#klp-set').val();
            var link_str = $('#link-set')[0].selectize.getValue();

            var nik='<?php echo $nik; ?>';
            var lokasi='<?php echo $kode_lokasi; ?>';
            var formData = new FormData(this);
            formData.append('username', nik);
            formData.append('kode_lokasi', lokasi);

            for(var pair of formData.entries()) {
                    console.log(pair[0]+ ', '+ pair[1]); 
                }

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/SettingMenu.php?fx=simpanMenu',
                dataType: 'json',
                data: formData,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(res){
                    alert(res.message);
                    if(res){
                        
                        init(kode_klp);
                        $('#sai-treegrid-modal').modal('hide');
                        // $('#sai-treegrid tr').removeClass('ui-selected');
                        $('#validation-box').text('');
                    }
                }
            });

        });
    
    });

    $('#saku-data').on('submit', '#menu-form', function(e){
    e.preventDefault();
        
        
        var formData = new FormData(this);
        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        var kode_klp = $('#kode_klp')[0].selectize.getValue();
        
        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        
        
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/SettingMenu.php?fx=simpanMove',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                alert('Perubahan '+result.message);
                if(result.status){
                    init(kode_klp);
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:'+textStatus);
            }
        });
    });

</script>
