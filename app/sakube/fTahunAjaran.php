<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/sakube/setting2.php');
    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];
    $kode_pp=$_SESSION['kodePP'];
?>


<section id="layout-breadcrumb">
    <div class="card" style="margin-top: 2.5px;">
        <div class="card-body">
            <div class="row">
                <div class="col-8 align-self-center">
                    <span class="parent-menu">
                    <?=$_SESSION['breadcrumb']?>
                    <!-- Nama Menu > Anak Menu > -->
                    </span>
                    <span class="active-menu">
                        Cucu Menu
                    </span>
                </div>
                <div class="col-4">
                    <button type="button" id="btn-tambah" class="btn btn-primary btn-sm ml-2" style="float:right;">
                    <!-- <i class="fa fa-plus-circle"></i> -->
                     Tambah</button>
                    <button type="button" class="btn btn-primary btn-sm ml-2"  style="float:right;display:none;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-secondary btn-sm ml-2" id="btn-kembali" style="float:right;display:none;"><i class="fa fa-undo"></i> Kembali</button>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="layout-data">
    <div class="card" style="margin-top:5px;">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <span class="layout-title">
                        Data Tahun Ajaran
                    </span>
                </div>
                <div class="col-4 text-right">
                    <span class="total-table-data">
                        11.300 Data
                    </span>
                </div>
            </div>
            <hr class="title-underline">
            <table id="table-data" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Kode TA</th>
                    <th>Kode PP</th>
                    <th>Nama</th>
                    <th>Tgl Mulai</th>
                    <th>Tgl Selesai</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            
        </table>
            <!-- <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col-3">
                            Tampil 
                        </div>
                        <div class="col-9">
                            <select class="form-control  sakube-select">
                            <option>10</option>
                            <option>20</option>
                            <option>50</option>
                            <option>100</option>
                            <option>500</option>
                            <option>1000</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    searching
                </div>
            </div> -->
        </div>
    </div>


</section>

<nav id="sidebar-filter" class="active">
    <section id="right-sidebarFilter">
        <div class="container" style="padding:25px;">
            <b>
            Filter   
            </b>
            <hr style="border-color:white;margin-top:10px;margin-bottom:10px;">
            <form action="" id="form-filter">
                <div class="form-group">
                    <label for="filter-periode">Periode</label>
                    <select class="form-control form-control-sm" id="filter-periode">
                    
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter-pp">PP</label>
                    <select class="form-control form-control-sm" id="filter-pp">
                    
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter-akun">Akun</label>
                    <select class="form-control form-control-sm" id="filter-akun">
                    
                    </select>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-info btn-sm" id="btn-filter">Terapkan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</nav>

<section id="layout-form" style="display: none;margin-top:5px;">
    <div class="card">
        <div class="card-body">
            <span class="layout-title">
                Form Tahun Ajaran
            </span>
            <hr class="title-underline">
            <form class="form" id="form-tambah" style='margin-bottom:100px'>
                <div class="form-group" id="row-id">
                    <div class="col-9">
                        <input class="form-control" type="hidden" id="id_edit" name="id">
                    </div>
                </div>
                <div class="form-group row align-items-center justify-content-center">
                    <div class="col-1 text-center">
                            <!-- <label for="kode_ta" class="">Kode</label> -->
                            Kode
                    </div>
                    <div class="col-5">
                        <input class="form-control form-control-sm" type="text" placeholder="Kode Tahun Ajaran" id="kode_ta" name="kode_ta" required >
                    </div>
                    <div class="col-1">
                            <!-- <label for="kode_pp" class="">Kode PP</label> -->
                            Kode PP
                    </div>
                    <div class="col-5">
                        <select class='form-control form-control-sm' id="kode_pp" name="kode_pp">
                        <option value='' disabled>--- Pilih PP ---</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row align-items-center justify-content-center">
                    <div class="col-1 text-center">
                            Nama
                    </div>
                    <div class="col-5">
                        <input class="form-control form-control-sm" type="text" placeholder="Nama Tahun Ajaran" id="nama" name="nama">
                    </div>
                    <div class="col-1">
                            Status Aktif
                    </div>
                    <div class="col-5">
                        <select class='form-control selectize form-control-sm' id="flag_aktif" name="flag_aktif">
                            <option value='' disabled>--- Pilih Status Aktif ---</option>
                            <option value='1'>Aktif</option>
                            <option value='0'>Non Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row align-items-center justify-content-center">
                    <div class="col-1 text-center">
                            <!-- <label for="kode_ta" class="">Kode</label> -->
                            Tanggal Mulai
                    </div>
                    <div class="col-5">
                        <input class="form-control form-control-sm" type="date" id="tgl_mulai" name="tgl_mulai">
                    </div>
                    <div class="col-1">
                            <!-- <label for="kode_pp" class="">Kode PP</label> -->
                            Tanggal Akhir
                    </div>
                    <div class="col-5">
                        <input class="form-control form-control-sm" type="date" id="tgl_akhir" name="tgl_akhir">
                    </div>
                </div>
                <div class="form-group row align-items-center justify-content-center">
                    <div class="col-1 text-center">
                            <!-- <label for="kode_ta" class="">Kode</label> -->
                            Generate
                    </div>
                    <div class="col-5">
                        <input class="form-control form-control-sm generate" type="input" id="generate" name="generate">
                        <img src="<?=$folder_assets?>/assets/icons/refresh.svg" alt="generate">
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- <div class="row" id="saku-form">
        <div class="col-sm-12" style="height: 90px;">
            <div class="card">
                <div class="card-body pb-0">
                    <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Form Tahun Ajaran
                    </h4>
                </div>
                <div class="card-body table-responsive pt-0" style='height:460px'>
                        <form class="form" id="form-tambah" style='margin-bottom:100px'>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="hidden" id="id_edit" name="id">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_ta" class="col-3 col-form-label">Kode</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode Tahun Ajaran" id="kode_ta" name="kode_ta" required >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Tahun Ajaran" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_mulai" class="col-3 col-form-label">Tanggal Mulai</label>
                                <div class="col-9">
                                    <input class="form-control" type="date" id="tgl_mulai" name="tgl_mulai">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_akhir" class="col-3 col-form-label">Tanggal Akhir</label>
                                <div class="col-9">
                                    <input class="form-control" type="date" id="tgl_akhir" name="tgl_akhir">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="flag_aktif" class="col-3 col-form-label">Status Aktif</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="flag_aktif" name="flag_aktif">
                                    <option value='' disabled>--- Pilih Status Aktif ---</option>
                                    <option value='1'>Aktif</option>
                                    <option value='0'>Non Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_pp" class="col-3 col-form-label">Kode PP</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_pp" name="kode_pp">
                                    <option value='' disabled>--- Pilih PP ---</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div> -->
</section>

<?php
    $root_ser=$root."/server/sakube";
?>

    <script src="<?=$folderroot_js?>/sai.js"></script>
    <script src="<?=$folderroot_js?>/inputmask.js"></script>
    <script>
        $( window ).resize(function() {
            // console.log('what');
            if ($(window).width<=768) {
                $('.sidebarCollapseHide').hide();
                $('.sidebarCollapseShow').show();
            }else{
                $('.sidebarCollapseHide').show();
                $('.sidebarCollapseShow').hide();
            }
        });
        if ($(window).width<=768) {
                $('.sidebarCollapseHide').hide();
                $('.sidebarCollapseShow').show();
            }
        // $(document).ready(function(){
        // });
    
    // function openFilter() {
    //     var element = $('#mySidepanel');
        
    //     var x = $('#mySidepanel').attr('class');
    //     var y = x.split(' ');
    //     if(y[1] == 'close'){
    //         element.removeClass('close');
    //         element.addClass('open');
    //     }else{
    //         element.removeClass('open');
    //         element.addClass('close');
    //     }
    // }

    var action_html = "<a href='#' title='Lihat' class='mr-2' id='btn-see' data-toggle='tooltip' data-placement='bottom'><img src='<?=$folder_assets?>/assets/icons/See.svg' width='25px' height='25px' alt='lihat'></a><a href='#' title='Edit' class='mr-2' id='btn-edit' data-toggle='tooltip' data-placement='bottom'><img src='<?=$folder_assets?>/assets/icons/Edit.svg' width='25px' height='25px' alt='edit'></a><a href='#' title='Delete' id='btn-delete' class='mr-2' data-toggle='tooltip' data-placement='bottom'><img src='<?=$folder_assets?>/assets/icons/Delete.svg' width='25px' height='25px' alt='delete'></a>";
    // var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var kode_pp = '<?php echo $kode_pp ?>';
    var dataTable = $('#table-data').DataTable({
        language: {
            searchPlaceholder: "Cari Data",
            paginate: {
            "first": "<<",
            "last": ">>",
            "next": ">",
            "previous": "<"
            }
        },
        scrollY:        '50vh',
        scrollCollapse: true,
        pagingType: "full_numbers",
        bFilter:true,
        responsive: true,
        oLanguage: {
            "sSearch": "",
            "sEmptyTable": "Tidak ada data",
            "sLengthMenu": 'Tampil _MENU_',
            "sZeroRecords": "Tidak ada data untuk dimunculkan",
            "sInfo": "Menampilkan _START_ dari _END_ data",
            "sInfoEmpty": "Menampilkan 0 dari 0 data",
            "sInfoFiltered": "(disaring dari _MAX_ data)"
            // "sInfo": "Menampilkan _TOTAL_ dari (_START_ to _END_)"
        },
        
        // 'fnDrawCallback': function (oSettings) {
		// $('.dataTables_filter').each(function () {
		// });
    // }

        'processing': true,
        'serverSide': true,
        "ordering": true,
        "order": [[0, "desc"]],
        'ajax': {
            'url': '<?=$root_ser?>/TahunAjaran.php?fx=getView2',
            'data': {'kode_lokasi':kode_lokasi,'kode_pp':kode_pp},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
                // console.log(json.data);
            }
        },
        // columns: [
        //       { data: 'kode_ta' },
        //       { data: 'pp' },
        //       { data: 'nama' },
        //       { data: 'tgl_mulai' },
        //       { data: 'tgl_akhir' },
        //       { data: 'sts' },
        //       { data: 'action' }
        // ],
        'columnDefs': [
            {'targets': 6, data: null, 'defaultContent': action_html }
        ]
        // dom: 'lBfrtip',
        // buttons: [
        //     {
        //         text: '<i class="fa fa-filter"></i> Filter',
        //         action: function ( e, dt, node, config ) {
        //             openFilter();
        //         },
        //         className: 'btn btn-default ml-2' 
        //     }
        // ]
    });
    // dataTable.responsive.recalc();
    // new $.fn.dataTable.FixedHeader( dataTable );

	$('.dataTables_filter').append("<img src='http://saiweb.simkug.com/vendor/sakube/assets/icons/filter.svg' id='right-sidebarCollapseFilter' class='pull-left filter-trigger' data-toggle='tooltip' data-placement='bottom' title='Filter' width='25px' height='25px' style='padding-top:.25rem!important;cursor:pointer;' alt='filter'>");

    // $('input[type=search]').css('background-image', 'url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB2ZXJzaW9uPSIxLjEiICAgaWQ9InN2ZzQ0ODUiICAgdmlld0JveD0iMCAwIDIxLjk5OTk5OSAyMS45OTk5OTkiICAgaGVpZ2h0PSIyMiIgICB3aWR0aD0iMjIiPiAgPGRlZnMgICAgIGlkPSJkZWZzNDQ4NyIgLz4gIDxtZXRhZGF0YSAgICAgaWQ9Im1ldGFkYXRhNDQ5MCI+ICAgIDxyZGY6UkRGPiAgICAgIDxjYzpXb3JrICAgICAgICAgcmRmOmFib3V0PSIiPiAgICAgICAgPGRjOmZvcm1hdD5pbWFnZS9zdmcreG1sPC9kYzpmb3JtYXQ+ICAgICAgICA8ZGM6dHlwZSAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4gICAgICAgIDxkYzp0aXRsZT48L2RjOnRpdGxlPiAgICAgIDwvY2M6V29yaz4gICAgPC9yZGY6UkRGPiAgPC9tZXRhZGF0YT4gIDxnICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLC0xMDMwLjM2MjIpIiAgICAgaWQ9ImxheWVyMSI+ICAgIDxnICAgICAgIHN0eWxlPSJvcGFjaXR5OjAuNSIgICAgICAgaWQ9ImcxNyIgICAgICAgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNjAuNCw4NjYuMjQxMzQpIj4gICAgICA8cGF0aCAgICAgICAgIGlkPSJwYXRoMTkiICAgICAgICAgZD0ibSAtNTAuNSwxNzkuMSBjIC0yLjcsMCAtNC45LC0yLjIgLTQuOSwtNC45IDAsLTIuNyAyLjIsLTQuOSA0LjksLTQuOSAyLjcsMCA0LjksMi4yIDQuOSw0LjkgMCwyLjcgLTIuMiw0LjkgLTQuOSw0LjkgeiBtIDAsLTguOCBjIC0yLjIsMCAtMy45LDEuNyAtMy45LDMuOSAwLDIuMiAxLjcsMy45IDMuOSwzLjkgMi4yLDAgMy45LC0xLjcgMy45LC0zLjkgMCwtMi4yIC0xLjcsLTMuOSAtMy45LC0zLjkgeiIgICAgICAgICBjbGFzcz0ic3Q0IiAvPiAgICAgIDxyZWN0ICAgICAgICAgaWQ9InJlY3QyMSIgICAgICAgICBoZWlnaHQ9IjUiICAgICAgICAgd2lkdGg9IjAuODk5OTk5OTgiICAgICAgICAgY2xhc3M9InN0NCIgICAgICAgICB0cmFuc2Zvcm09Im1hdHJpeCgwLjY5NjQsLTAuNzE3NiwwLjcxNzYsMC42OTY0LC0xNDIuMzkzOCwyMS41MDE1KSIgICAgICAgICB5PSIxNzYuNjAwMDEiICAgICAgICAgeD0iLTQ2LjIwMDAwMSIgLz4gICAgPC9nPiAgPC9nPjwvc3ZnPg==)');
    // $('input[type=search]').css('background-repeat', 'no-repeat');
    // $('input[type=search]').css('background-position', 'left center');

    // $('input[type=search]').on('search', function () {
    //     // search logic here
    //     // this function will be executed on click of X (clear button)
    // });
// ===================================================================
    // FILTER FUNCTION

    $('#right-sidebarCollapseFilter').on('click',function(){
        $('#sidebar-filter').removeClass('active');
        $('.overlay').addClass('active');
    });
    

    // $('#btn-filter-close').on('click',function(){
    //     $('#sidebar-filter').addClass('active');
    //     $('.overlay').toggleClass('active');
    // });

    $('#btn-filter').on('click',function(){
        $('#sidebar-filter').addClass('active');
        $('.overlay').removeClass('active');
        $('#form-filter').submit();
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('#sidebar-filter').length&&!$(event.target).is($('#right-sidebarCollapseFilter'))&&!$('#sidebar-filter').hasClass('active')) {
            // &&!$('#sidebar-right').hasClass('active')&&!$('#right-sidebarCollapseFilter').click)
            $('#sidebar-filter').addClass('active');
            $('.overlay').removeClass('active');
        }
    });

    // $('div.toolbar').html(" <button type='button' id='btn-lanjut' class='btn btn-secondary ml-2'><i class='fa fa-filter'></i> Filter</button>");
    // dataTable.dom().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    $('[data-toggle="tooltip"]').tooltip();

    function filterColumn ( period, pp, akun) {
        // console.log(pp);
    $('#table-data').DataTable().column( 1 ).search(
        pp,
        false, //ini regex
        true // ini smart search
        ).draw();
}

    $('#sidebar-filter').on('submit', '#form-filter', function(e){
        e.preventDefault();
        var kode_pp= $('#filter-pp')[0].selectize.getValue();
        filterColumn( 'periode',kode_pp,'akun' );
        // dataTable.search(kode_pp).draw();
        // dataTable.column(2).search(kode_pp).draw();
    });
    
 
    // $('.sidepanel').on('click', '#btnClose', function(e){
    //     e.preventDefault();
    //     openFilter();
    // });

   function getPP(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/TahunAjaran.php?fx=getPP',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_pp').selectize();
                        select = select[0];
                        var control = select.selectize;

                        var select2 = $('#filter-pp').selectize();
                        select2 = select2[0];
                        var control2 = select2.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp}]);
                            control2.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp + '-' + result.daftar[i].nama}]);
                        }
                    }
                }
            }
        });
    }

    getPP();

    $('#btn-tambah').on('click', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id_edit').val('');
        $('#kode_ta').attr('readonly', false);
        $('#layout-data').hide();
        $('#layout-form').show();
        $('#btn-tambah').hide();
        $('#btn-kembali').show();
        $('#btn-save').show();
    });

    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });

    $('#layout-data').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();
        var tmp= $(this).closest('tr').find('td').eq(1).html().split('-');
        var kode_pp = tmp[0];

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/TahunAjaran.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_ta':id,'kode_lokasi':kode_lokasi,'kode_pp':kode_pp},
            success:function(result){
                if(result.status){
                    $('#id_edit').val('edit');
                    $('#kode_ta').val(id);
                    $('#kode_ta').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#tgl_mulai').val(result.daftar[0].tgl_mulai);
                    $('#tgl_akhir').val(result.daftar[0].tgl_akhir);
                    $('#kode_pp')[0].selectize.setValue(result.daftar[0].kode_pp);
                    $('#flag_aktif')[0].selectize.setValue(result.daftar[0].flag_aktif);
                    $('#row-id').show();
                    $('#layout-data').hide();
                    $('#layout-form').show();
                    $('#btn-tambah').hide();
        $('#btn-kembali').show();
        $('#btn-save').show();
                }
            }
        });
    });


    $('#btn-kembali').on('click', function(){
        $('#layout-data').show();
        $('#layout-form').hide();
        $('#btn-tambah').show();
        $('#btn-kembali').hide();
        $('#btn-save').hide();
    });

    $('#table-data').on('click','#btn-delete',function(e){
        e.preventDefault();
        Swal.fire({
        title: 'Yakin Data Akan Dihapus?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Dihapus!'
        }).then((result) => {
            if (result.value) {
                var kode = $(this).closest('tr').find('td:eq(0)').html(); 
                var tmp= $(this).closest('tr').find('td:eq(1)').html().split('-');
                var kode_pp = tmp[0];
                var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
                
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/TahunAjaran.php',
                    dataType: 'json',
                    async:false,
                    data: {'kode_ta':kode,'kode_lokasi':kode_lokasi,'kode_pp':kode_pp},
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
                            title: 'Error',
                            text: result.message
                            })
                        }
                    }
                });
                
            }else{
                return false;
            }
        })
    });

    $('#layout-form').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id_edit').val();
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
                url: '<?=$root_ser?>/TahunAjaran.php?fx=simpan',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    // alert('Input data '+result.message);
                    var pesan=result.message;
                    if(result.status){
                        // location.reload();
                        dataTable.ajax.reload();
                        Swal.fire(
                            'Great Job!',
                            'Data Berhasil Disimpan',
                            'success'
                        )
                        $('#layout-data').show();
                        $('#layout-form').hide();
                        $('#btn-tambah').show();
        $('#btn-kembali').hide();
        $('#btn-save').hide();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message
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

            var nik='<?php echo $nik; ?>' ;
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

            formData.append('nik_user', nik);
            formData.append('kode_lokasi', kode_lokasi);
            
            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/TahunAjaran.php?fx=ubah',
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
                            'Data Berhasil di Update',
                            'success'
                        )
                        $('#layout-data').show();
                        $('#layout-form').hide();
                        $('#btn-tambah').show();
        $('#btn-kembali').hide();
        $('#btn-save').hide();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message
                        })
                    }
                }
            });
        }
        
    });

    // $('#kode_ta,#nama,#tgl_mulai,#tgl_akhir,#flag_aktif,#kode_pp').keydown(function(e){
    //     var code = (e.keyCode ? e.keyCode : e.which);
    //     var nxt = ['kode_ta','nama','tgl_mulai','tgl_akhir','flag_aktif','kode_pp'];
    //     if (code == 13 || code == 40) {
    //         e.preventDefault();
    //         var idx = nxt.indexOf(e.target.id);
    //         idx++;
    //         if(idx == 5 || idx == 6){
    //             $('#'+nxt[idx])[0].selectize.focus();
    //         }else{
                
    //             $('#'+nxt[idx]).focus();
    //         }
    //     }else if(code == 38){
    //         e.preventDefault();
    //         var idx = nxt.indexOf(e.target.id);
    //         idx--;
    //         if(idx != -1){ 
    //             $('#'+nxt[idx]).focus();
    //         }
    //     }
    // });
    </script>