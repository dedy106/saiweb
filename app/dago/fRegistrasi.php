<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/dago/setting.php');
    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];
    $kode_pp=$_SESSION['kodePP'];
    $periode=$_SESSION['periode'];
?>
<style>
.form-group{
    margin-bottom:15px !important;
}

.dataTables_wrapper{
    padding:5px
}
</style>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-data-reg">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Registrasi 
                        <button type="button" id="btn-reg-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <hr>
                        <div class="table-responsive ">
                            <table id="table-reg" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No Registrasi</th>
                                        <th>No Peserta</th>
                                        <th>Nama</th>
                                        <th>Tanggal</th>
                                        <th>Paket</th>
                                        <th>Jadwal</th>
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
        <div class="row" id="form-tambah-reg" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title">Form Registrasi
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-reg-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row mt-5">
                                <label for="tgl_input" class="col-3 col-form-label">Tanggal</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="tgl_input" name="tgl_input" required value="<?=date('Y-m-d')?>">
                                </div>
                                <label for="periode" class="col-3 col-form-label">Periode</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" id="periode" name="periode2"  readonly value="<?=date('Ym')?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_input" class="col-3 col-form-label">No Registrasi</label>
                                <div class="col-3">
                                    <input class="form-control" readonly type="text" placeholder="No Register" id="no_reg" name="no_reg" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_peserta" class="col-3 col-form-label">No Jamaah</label>
                                <div class="col-3">
                                    <select class='form-control' id="no_peserta" name="no_peserta" required>
                                    <option value=''>--- Pilih No Jamaah ---</option>
                                    </select>
                                </div>
                                <!-- <label for="no_peserta_ref" class="col-3 col-form-label">No Jamaah Ref</label>
                                <div class="col-3">
                                    <select class='form-control' id="no_peserta_ref" name="no_peserta_ref" >
                                    <option value=''>--- Pilih No Jamaah Ref ---</option>
                                    </select>
                                </div> -->
                                <label for="flag_group" class="col-3 col-form-label">Status Group</label>
                                <div class="col-3">
                                    <select class='form-control' id="flag_group" name="flag_group" >
                                    <option value='0'>Tidak</option>
                                    <option value='1'>Ya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="paket" class="col-3 col-form-label">Paket</label>
                                <div class="col-3">
                                    <select class='form-control' id="paket" name="paket" required>
                                    <option value=''>--- Pilih Paket ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="jadwal" class="col-3 col-form-label">Jadwal</label>
                                <div class="col-3">
                                    <select class='form-control' id="jadwal" name="jadwal" required>
                                    <option value=''>--- Pilih Jadwal ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_pp" class="col-3 col-form-label">PP/Cabang</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_pp" name="kode_pp2" required>
                                    <option value=''>--- Pilih Kode PP ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jenis_paket" class="col-3 col-form-label">Jenis Paket</label>
                                <div class="col-3">
                                    <select class='form-control' id="jenis_paket" name="jenis_paket" required>
                                    <option value=''>--- Pilih Jenis paket ---</option>
                                    <option value='STANDAR'>STANDAR</option>
                                    <option value='SEMI'>SEMI</option>
                                    <option value='EKSEKUTIF'>EKSEKUTIF</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="jenis_promo" class="col-3 col-form-label">Jenis Promo</label>
                                <div class="col-3">
                                    <select class='form-control' id="jenis_promo" name="jenis_promo" required>
                                    <option value=''>--- Pilih Jenis Promo ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="quota" class="col-3 col-form-label">Sisa Quota</label>
                                <div class="col-3">
                                    <input class="form-control currency" readonly type="text" id="quota" name="quota" >
                                </div>
                                <label for="tgl_berangkat" class="col-3 col-form-label">Tgl Berangkat</label>
                                <div class="col-3">
                                    <input class="form-control" readonly type="date" id="tgl_berangkat" name="tgl_berangkat" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lama_hari" class="col-3 col-form-label">Lama Hari</label>
                                <div class="col-3">
                                    <input class="form-control currency" readonly type="text" id="lama_hari" name="lama_hari" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_curr" class="col-3 col-form-label">Currency</label>
                                <div class="col-3">
                                    <input class="form-control" readonly type="text" id="kode_curr" name="kode_curr" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="harga_paket" class="col-3 col-form-label">Harga Paket</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" id="harga_paket" name="harga_paket" >
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="type_room" class="col-3 col-form-label">Type Room</label>
                                <div class="col-3">
                                    <select class='form-control' id="type_room" name="type_room" required>
                                    <option value=''>--- Pilih Type Room ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="harga_room" class="col-3 col-form-label">Harga Room</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text" id="harga_room" name="harga_room" >
                                </div>
                                <label for="referal" class="col-3 col-form-label">Referal</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" id="referal" name="referal" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="brkt_dgn" class="col-3 col-form-label">Berangkat dengan</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" id="brkt_dgn" name="brkt_dgn" >
                                </div>
                                <label for="hubungan" class="col-3 col-form-label">Hubungan</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" id="hubungan" name="hubungan" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ukuran_pakaian" class="col-3 col-form-label">Ukuran Pakaian</label>
                                <div class="col-3">
                                    <select class='form-control' id="ukuran_pakaian" name="ukuran_pakaian" required>
                                    <option value=''>--- Pilih Ukuran ---</option>
                                    <option value='S'>S</option>
                                    <option value='XS'>XS</option>
                                    <option value='L'>L</option>
                                    <option value='2L'>2L</option>
                                    <option value='3L'>3L</option>
                                    <option value='4L'>4L</option>
                                    <option value='5L'>5L</option>
                                    <option value='6L'>6L</option>
                                    <option value='7L'>7L</option>
                                    <option value='8L'>8L</option>
                                    <option value='9L'>9L</option>
                                    <option value='10'>10</option>
                                    </select>
                                </div>
                                <label for="ket_diskon" class="col-3 col-form-label">Keterangan Diskon</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" max-length="100" id="ket_diskon" name="ket_diskon" required >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="agen" class="col-3 col-form-label">Agen</label>
                                <div class="col-3">
                                    <select class='form-control' id="agen" name="agen" required>
                                    <option value=''>--- Pilih Agen ---</option>
                                    </select>
                                </div>
                                <label for="diskon" class="col-3 col-form-label">Diskon Biaya</label>
                                <div class="col-3">
                                    <input class="form-control currency" type="text"  id="diskon" name="diskon" required value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="marketing" class="col-3 col-form-label">Marketing</label>
                                <div class="col-3">
                                    <select class='form-control' id="marketing" name="marketing" required>
                                    <option value=''>--- Pilih Marketing ---</option>
                                    </select>
                                </div>
                                
                                <label for="tot_tambah" class="col-3 col-form-label">Total Tambahan</label>
                                <div class="col-3">
                                    <input class="form-control text-right" type="text"  id="tot_tambah" name="tot_tambah" required readonly value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sumber" class="col-3 col-form-label">Sumber Informasi</label>
                                <div class="col-3">
                                    <select class='form-control' id="sumber" name="sumber" required>
                                    <option value=''>--- Pilih Sumber Informasi ---</option>
                                    <option value='Brosur'>Brosur</option>
                                    <option value='Internet (Web)'>Internet (Web)</option>
                                    <option value='Baligho'>Baligho</option>
                                    <option value='Surat Kabar'>Surat Kabar</option>
                                    <option value='Marketing'>Marketing</option>
                                    <option value='Agen Umroh'>Agen Umroh</option>
                                    </select>
                                </div>
                                <label for="tot_dokumen" class="col-3 col-form-label">Total Dokumen</label>
                                <div class="col-3">
                                    <input class="form-control text-right" type="text"  id="tot_dokumen" name="tot_dokumen" required readonly value="0">
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#btambah" role="tab" aria-selected="true"><span class="hidden-xs-down">Biaya Tambahan</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#bdok" role="tab" aria-selected="true"><span class="hidden-xs-down">Biaya Dokumen</span></a> </li>
                                <!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#dok" role="tab" aria-selected="true"><span class="hidden-xs-down">Data Dokumen</span></a> </li> -->
                            </ul>
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="btambah" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                        th,td{
                                            padding:8px !important;
                                            vertical-align:middle !important;
                                        }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="table-btambah">
                                            <thead>
                                            <tr>
                                            <th width="5%">No</th>
                                            <th width="10%">Kode</th>
                                            <th width="35%">Nama Biaya</th>
                                            <th width="20%">Tarif</th>
                                            <th width="10%">Jumlah</th>
                                            <th width="20%">Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="bdok" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                        th,td{
                                            padding:8px !important;
                                            vertical-align:middle !important;
                                        }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="table-bdok">
                                            <thead>
                                            <tr>
                                            <th width="5%">No</th>
                                            <th width="10%">Kode</th>
                                            <th width="35%">Nama Biaya</th>
                                            <th width="20%">Tarif</th>
                                            <th width="10%">Jumlah</th>
                                            <th width="20%">Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="dok" role="tabpanel" hidden>
                                    <table class="table table-striped table-bordered table-condensed mt-3" id="table-dok">
                                        <thead>
                                        <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Kode Dokumen</th>
                                        <th width="45%">Nama Dokumen</th>
                                        <th width="30%">Jenis</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- UPLOAD DOK -->
        <div class="row" id="form-upload-reg" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title">Form Upload Dokumen
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-upload-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <div class="form-group row" id="row-id_upload">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3">
                                    <input class="form-control" type="hidden" placeholder="No Bukti" id="upload_no_bukti" name="upload_no_bukti" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="upload_no_reg" class="col-3 col-form-label">No Registrasi</label>
                                <div class="col-6">
                                    <input class="form-control" type="text" readonly id="upload_no_reg" name="upload_no_reg">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="upload_jamaah" class="col-3 col-form-label">Jamaah</label>
                                <div class="col-6">
                                    <input class="form-control" type="text" readonly id="upload_jamaah" name="upload_jamaah">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="upload_paket" class="col-3 col-form-label">Paket</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" readonly  id="upload_paket" name="upload_paket"  required>
                                </div>
                                <label for="upload_jadwal" class="col-3 col-form-label">Jadwal</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" readonly id="upload_jadwal" name="upload_jadwal" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="upload_alamat" class="col-3 col-form-label">Alamat</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" readonly id="upload_alamat" name="upload_alamat"  required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="upload_tgl_terima" class="col-3 col-form-label">Tgl Terima</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="upload_tgl_terima" name="upload_tgl_terima" required value='<?=date('Y-m-d')?>'>
                                </div>
                            </div>
                            
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#detDok" role="tab" aria-selected="true"><span class="hidden-xs-down">Detail Dokumen</span></a> </li>
                                
                            </ul>
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="detDok" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                            th,td{
                                                padding:8px !important;
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="input-dok">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="10%">Kode Jenis</th>
                                                <th width="20%">Jenis Dokumen</th>
                                                <th width="20%">Path File</th>
                                                <th width="20%">User</th>
                                                <th width="20%">Upload File</th>
                                                <th width="5%">Download</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- FORM GROUPING -->
         <div class="row" id="form-group-reg" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title">Form Grouping Registrasi
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-group-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <div class="form-group row" id="row-id_group">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3">
                                    <input class="form-control" type="hidden" placeholder="No Bukti" id="group_no_bukti" name="group_no_bukti" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="group_no_reg" class="col-3 col-form-label">No Registrasi</label>
                                <div class="col-6">
                                    <input class="form-control" type="text" readonly id="group_no_reg" name="group_no_reg">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="group_jamaah" class="col-3 col-form-label">Jamaah</label>
                                <div class="col-6">
                                    <input class="form-control" type="text" readonly id="group_jamaah" name="group_jamaah">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="group_paket" class="col-3 col-form-label">Paket</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" readonly  id="group_paket" name="group_paket"  required>
                                </div>
                                <label for="group_jadwal" class="col-3 col-form-label">Jadwal</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" readonly id="group_jadwal" name="group_jadwal" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="group_alamat" class="col-3 col-form-label">Alamat</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" readonly id="group_alamat" name="group_alamat"  required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="group_tgl_terima" class="col-3 col-form-label">Tgl Terima</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="group_tgl_terima" name="group_tgl_terima" required value='<?=date('Y-m-d')?>'>
                                </div>
                            </div>
                            
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#detAnggota" role="tab" aria-selected="true"><span class="hidden-xs-down">Daftar Anggota</span></a> </li>
                                
                            </ul>
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="detAnggota" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                            th,td{
                                                padding:8px !important;
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="input-anggota">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="90%">Peserta</th>
                                                <th width="5%"><button type="button" href="#" id="add-row-anggota" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
       
        <!-- PRINT FORM REG -->
        <div class="row" id="slide-print" style="display:none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-secondary ml-2" id="btn-reg-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        <button type="button" class="btn btn-info ml-2" id="btn-reg-print" style="float:right;"><i class="fa fa-print"></i> Print</button>
                        <div id="print-area" class="mt-5" width='100%' style='border:none;min-height:480px;padding:10px;font-size:12pt !important'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>     
    
    <script src="<?=$folderroot_js?>/printThis/printThis.js"></script>
    <script src="<?=$folderroot_js?>/inputmask.js"></script>
    <script>
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

    function hitungTotal(){
        $('#total').val(0);
        total= 0;
        $('.row-tgh').each(function(){
            var sub = toNilai($(this).closest('tr').find('.inp-nil').val());
            var this_val = sub;
            total += +this_val;
            
            $('#total').val(sepNum(total));
        });
    }

    // function hitungTambah(){
    //     $('#tot_tambah').val(0);
    //     // var diskon= $('#diskon').val();
    //     total= 0;
    //     $('.row-btambah').each(function(){
    //         var nil = $(this).closest('tr').find('.inp-btambah_nilai').val();
    //         var jum = $(this).closest('tr').find('.inp-btambah_jumlah').val();
    //         var sub = toNilai(nil)*toNilai(jum);
    //         $(this).closest('tr').find('.inp-btambah_total').val(sub);
    //         var this_val = sub;
    //         total += +this_val;
            
    //     });
    //     var tot=total;
    //     $('#tot_tambah').val(sepNum(tot));
    // }

    function hitungTambah2(){
        $('#tot_tambah').val(0);
        // var diskon= $('#diskon').val();
        total= 0;
        $('.row-btambah').each(function(){
            var tmp = $(this).closest('tr').find('.inp-btambah_total').val();
            var sub = toNilai(tmp);
            total += +sub;
            
        });
        var tot=total;
        $('#tot_tambah').val(sepNum(tot));
    }

    // function hitungDok(){
    //     $('#tot_dokumen').val(0);
    //     total= 0;
    //     $('.row-bdok').each(function(){
    //         var nil = $(this).closest('tr').find('.inp-bdok_nilai').val();
    //         var jum = $(this).closest('tr').find('.inp-bdok_jumlah').val();
    //         var sub = toNilai(nil)*toNilai(jum);
    //         $(this).closest('tr').find('.inp-bdok_total').val(sub);
    //         var this_val = sub;
    //         total += +this_val;
            
    //         $('#tot_dokumen').val(sepNum(total));
    //     });
    // }

    function hitungDok2(){
        $('#tot_dokumen').val(0);
        total= 0;
        $('.row-bdok').each(function(){
            var tmp = $(this).closest('tr').find('.inp-bdok_total').val();
            var sub = toNilai(tmp)
            total += +sub;
            
            $('#tot_dokumen').val(sepNum(total));
        });
    }

    function terbilang(int){
        angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        int = Math.floor(int);
        if (int < 12)
            return " " + angka[int];
        else if (int < 20)
            return terbilang(int - 10) + " belas ";
        else if (int < 100)
            return terbilang(int / 10) + " puluh " + terbilang(int % 10);
        else if (int < 200)
            return "seratus" + terbilang(int - 100);
        else if (int < 1000)
            return terbilang(int / 100) + " ratus " + terbilang(int % 100);
        else if (int < 2000)
            return "seribu" + terbilang(int - 1000);
        else if (int < 1000000)
            return terbilang(int / 1000) + " ribu " + terbilang(int % 1000);
        else if (int < 1000000000)
            return terbilang(int / 1000000) + " juta " + terbilang(int % 1000000);
        else if (int < 1000000000000)
            return terbilang(int / 1000000) + " milyar " + terbilang(int % 1000000000);
        else if (int >= 1000000000000)
            return terbilang(int / 1000000) + " trilyun " + terbilang(int % 1000000000000);
    }

    function getNamaBulan(no_bulan){
        switch (no_bulan){
            case 1 : case '1' : case '01': bulan = "Januari"; break;
            case 2 : case '2' : case '02': bulan = "Februari"; break;
            case 3 : case '3' : case '03': bulan = "Maret"; break;
            case 4 : case '4' : case '04': bulan = "April"; break;
            case 5 : case '5' : case '05': bulan = "Mei"; break;
            case 6 : case '6' : case '06': bulan = "Juni"; break;
            case 7 : case '7' : case '07': bulan = "Juli"; break;
            case 8 : case '8' : case '08': bulan = "Agustus"; break;
            case 9 : case '9' : case '09': bulan = "September"; break;
            case 10 : case '10' : case '10': bulan = "Oktober"; break;
            case 11 : case '11' : case '11': bulan = "November"; break;
            case 12 : case '12' : case '12': bulan = "Desember"; break;
            default: bulan = null;
        }

        return bulan;
    }

    var jadwal = $('#jadwal').selectize({
        selectOnTab: true,
        onChange: function (){
            setQuota();
        }
    });
    $('#paket').selectize({
        selectOnTab: true,
        onChange: function (){
            var id = $('#paket')[0].selectize.getValue();
            $.ajax({
                type: 'GET',
                url: '<?=$root_ser?>/Registrasi.php?fx=getJadwal',
                dataType: 'json',
                async:false,
                data: {'kode_lokasi':'<?=$kode_lokasi?>','no_paket':id},
                success:function(result){    
                    if(result.status){
                        if(typeof result.daftar !== 'undefined'){
                            var select = jadwal[0];
                            var control = select.selectize;
                            for(i=0;i<result.daftar.length;i++){
                                control.addOption([{text:result.daftar[i].no_jadwal + ' - ' + result.daftar[i].tgl_berangkat, value:result.daftar[i].no_jadwal}]);
                            }
                            
                        }
                    }
                    setHarga();
                    setQuota();
                }
            });
        }
    });

    function getPaket(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getPaket',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#paket').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].no_paket + ' - ' + result.daftar[i].nama, value:result.daftar[i].no_paket}]);
                        }
                    }
                }
            }
        });
    }

    $('#agen').selectize({
        selectOnTab: true,
        onChange: function (){
            var id = $('#agen')[0].selectize.getValue();
            $.ajax({
                type: 'GET',
                url: '<?=$root_ser?>/Registrasi.php?fx=getNoMarket',
                dataType: 'json',
                async:false,
                data: {'kode_lokasi':'<?=$kode_lokasi?>','no_agen':id},
                success:function(result){    
                    if(result.status){
                        if(typeof result.marketing !== 'undefined'){
                               
                            $('#marketing')[0].selectize.setValue(result.marketing);
                            
                        }
                    }
                }
            });
        }
    });

    function getAgen(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getAgen',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#agen').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].no_agen + ' - ' + result.daftar[i].nama_agen, value:result.daftar[i].no_agen}]);
                        }
                    }
                }
            }
        });
    }

    function getPeserta(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getPeserta',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#no_peserta').selectize();
                        select = select[0];
                        var control = select.selectize;

                        // var select2 = $('#no_peserta_ref').selectize();
                        // select2 = select2[0];
                        // var control2 = select2.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].no_peserta + ' - ' + result.daftar[i].nama, value:result.daftar[i].no_peserta}]);
                            // control2.addOption([{text:result.daftar[i].no_peserta + ' - ' + result.daftar[i].nama, value:result.daftar[i].no_peserta}]);
                        }
                    }
                }
            }
        });
    }

    function getMarketing(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getMarketing',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#marketing').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].no_marketing + ' - ' + result.daftar[i].nama_marketing, value:result.daftar[i].no_marketing}]);
                        }
                    }
                }
            }
        });
    }

    function getPP(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getPP',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_pp').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp}]);
                        }
                        if(result.kodePP != undefined || result.kodePP != ""){
                            control.setValue(result.kodePP);
                        }
                    }
                }
            }
        });
    }

    $('#flag_group').selectize();
    $('#jenis_promo').selectize({
        selectOnTab: true,
        onChange: function (){
            setHarga();
            setQuota()
        }
    });


    $('#jenis_paket').selectize({
        selectOnTab: true,
        onChange: function (){
            setHarga();
            setQuota()
        }
    });

    function getJenisPromo(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getJPaket',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#jenis_promo').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_harga + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_harga}]);
                        }
                    }
                }
            }
        });
    }

    $('#type_room').selectize({
        selectOnTab: true,
        onChange: function (){
            setHargaRoom();
        }
    });

    function getRoom(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getRoom',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#type_room').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].no_type + ' - ' + result.daftar[i].nama, value:result.daftar[i].no_type}]);
                        }
                    }
                }
            }
        });
    }

    function setHarga(){
        var no_paket = $('#paket')[0].selectize.getValue();
        var jpaket = $('#jenis_promo')[0].selectize.getValue();
        var jenis = $('#jenis_paket')[0].selectize.getValue();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getHarga',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_paket':no_paket,'jpaket':jpaket,'jenis':jenis},
            success:function(result){    
                if(result.status){
                    if(typeof result.harga !== 'undefined'){
                        $('#harga_paket').val(result.harga);
                    }
                }
            }
        });
    }

    function setHargaRoom(){
        var type_room = $('#type_room')[0].selectize.getValue();
        var kode_curr = $('#kode_curr').val();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getHargaRoom',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','type_room':type_room,'kode_curr':kode_curr},
            success:function(result){    
                if(result.status){
                    if(typeof result.harga_room !== 'undefined'){
                        $('#harga_room').val(result.harga_room);
                    }
                }
            }
        });
    }

    function setQuota(){
        var no_paket = $('#paket')[0].selectize.getValue();
        var jpaket = $('#jenis_promo')[0].selectize.getValue();
        var jenis = $('#jenis_paket')[0].selectize.getValue();
        // var jadwal1 = $('#jadwal option:selected').text();
        // var tmp = jadwal1.split("-");
        // var jadwal = tmp[1];
        var jadwal = $('#jadwal')[0].selectize.getValue();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getQuota',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_paket':no_paket,'jpaket':jpaket,'jenis':jenis,'jadwal':jadwal},
            success:function(result){    
                if(result.status){
                    if(typeof result.tgl_berangkat !== 'undefined'){
                        $('#tgl_berangkat').val(result.tgl_berangkat);
                    }
                    if(typeof result.kode_curr !== 'undefined'){
                        $('#kode_curr').val(result.kode_curr);
                    }
                    if(typeof result.lama_hari !== 'undefined'){
                        $('#lama_hari').val(result.lama_hari);
                    }
                    if(typeof result.quota !== 'undefined'){
                        $('#quota').val(result.quota);
                    }
                }
            }
        });
    }

    function getBTambah(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getBTambah',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(result.daftar.length > 0){
                        var html ='';
                        var no=1;
                        for(var i=0;i<result.daftar.length;i++){
                            var line =result.daftar[i];
                            html+=`<tr class='row-btambah'>
                            <td width='5%' class='no-btambah'>`+no+`</td>
                            <td width='10%'>`+line.kode_biaya+`<input type='hidden' name='btambah_kode_biaya[]' class='form-control inp-btambah_kode_biaya' value='`+line.kode_biaya+`' readonly></td>
                            <td width='35%' style='text-align:right'>`+line.nama+`<input type='hidden' name='btambah_nama[]' class='form-control inp-btambah_nama'  value='`+line.nama+`' readonly required></td>
                            <td width='20%' style='text-align:right'>`+toRp(line.nilai)+`<input type='hidden' name='btambah_nilai[]' class='form-control inp-btambah_nilai currency'  value='`+line.nilai+`' readonly required></td>
                            <td width='10%' style='text-align:right'><input type='text' name='btambah_jumlah[]' class='form-control inp-btambah_jumlah currency'  value='0' required></td>
                            <td width='20%' style='text-align:right'><input type='text' name='btambah_total[]' class='form-control inp-btambah_total currency'  value='0' required></td>
                            </tr>`;
                            no++;
                        }
                        $('#table-btambah tbody').html(html);
                        $('.currency').inputmask("numeric", {
                            radixPoint: ",",
                            groupSeparator: ".",
                            digits: 2,
                            autoGroup: true,
                            rightAlign: true,
                            oncleared: function () { self.Value(''); }
                        });
                    }
                }
            }
        });
    }

    function getBDok(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getBDok',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(result.daftar.length > 0){
                        var html ='';
                        var no=1;
                        for(var i=0;i<result.daftar.length;i++){
                            var line =result.daftar[i];
                            html+=`<tr class='row-bdok'>
                            <td width='5%' class='no-bdok'>`+no+`</td>
                            <td width='10%'>`+line.kode_biaya+`<input type='hidden' name='bdok_kode_biaya[]' class='form-control inp-bdok_kode_biaya' value='`+line.kode_biaya+`' readonly></td>
                            <td width='35%' style='text-align:right'>`+line.nama+`<input type='hidden' name='bdok_nama[]' class='form-control inp-bdok_nama'  value='`+line.nama+`' readonly required></td>
                            <td width='20%' style='text-align:right'>`+toRp(line.nilai)+`<input type='hidden' name='bdok_nilai[]' class='form-control inp-bdok_nilai currency2'  value='`+line.nilai+`' readonly required></td>
                            <td width='10%' style='text-align:right'><input type='text' name='bdok_jumlah[]' class='form-control inp-bdok_jumlah currency2'  value='0' required></td>
                            <td width='20%' style='text-align:right'><input type='text' name='bdok_total[]' class='form-control inp-bdok_total currency2'  value='0' required></td>
                            </tr>`;
                            no++;
                        }
                        $('#table-bdok tbody').html(html);
                        $('.currency2').inputmask("numeric", {
                            radixPoint: ",",
                            groupSeparator: ".",
                            digits: 2,
                            autoGroup: true,
                            rightAlign: true,
                            oncleared: function () { self.Value(''); }
                        });
                    }
                }
            }
        });
    }

    function getDokumen(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getDokumen',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(result.daftar.length > 0){
                        var html ='';
                        var no=1;
                        for(var i=0;i<result.daftar.length;i++){
                            var line =result.daftar[i];
                            html+=`<tr class='row-dok'>
                            <td width='5%' class='no-dok'>`+no+`</td>
                            <td width='10%'>`+line.no_dokumen+`<input type='hidden' name='dok_no_dokumen[]' class='form-control inp-dok_no_dokumen' value='`+line.no_dokumen+`' readonly></td>
                            <td width='35%' style='text-align:right'>`+line.deskripsi+`<input type='hidden' name='dok_deskripsi[]' class='form-control inp-dok_deskripsi'  value='`+line.deskripsi+`' readonly required></td>
                            <td width='20%' style='text-align:right'>`+line.jenis+`<input type='hidden' name='dok_jenis[]' class='form-control inp-dok_jenis'  value='`+line.jenis+`' required readonly></td>
                            </tr>`;
                            no++;
                        }
                        $('#table-dok tbody').html(html);
                    }
                }
            }
        });
    }

    var $iconLoad = $('.preloader');
    // var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>&nbsp;<a href='#' title='Upload Dokumen' class='badge badge-success' id='btn-upload'><i class='ti-upload'></i></a>&nbsp;<a href='#' title='Grouping Anggota' class='badge badge-primary' id='btn-group'><i class='fas fa-user-plus' style='color: white;'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-reg').DataTable({
        'processing': true,
        'serverSide': true,
        "ordering": true,
        "order": [[0, "desc"]],
        'ajax': {
            'url': '<?=$root_ser?>/Registrasi.php?fx=getReg',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        // 'columnDefs': [
        //     // {   'targets': 5, 
        //     //     'className': 'text-right',
        //     //     'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) 
        //     // },
        //     {'targets': 6, data: null, 'defaultContent': action_html }
           
        // ]
    });
    getAgen();
    getMarketing();
    getPeserta();
    getPP();
    getPaket();
    getJenisPromo();
    getRoom();
    $('#sumber').selectize();
    $('#ukuran_pakaian').selectize();
    $('#saku-data-reg').on('click', '#btn-reg-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('0');
        $('#dFile').hide();
        getBTambah();
        getBDok();
        getDokumen();
        // getAgen();
        // getMarketing();
        // getPeserta();
        // getPP();
        // getPaket();
        // getJenisPromo();
        // getRoom();
        $('#saku-data-reg').hide();
        $('#form-tambah-reg').show();
    });

    $('#saku-data-reg').on('click','#btn-print',function(e){
        var id = $(this).closest('tr').find('td').eq(0).html();
        printReg(id);
    });

    $('#form-tambah-reg').on('click', '#btn-reg-kembali', function(){
        $('#saku-data-reg').show();
        $('#form-tambah-reg').hide();
        $('#slide-history').hide();
    });

    $('#form-tambah-reg').on('change', '#tgl_input', function(){
        var tgl = $('#tgl_input').val();
        var per = tgl.substr(0,4)+''+tgl.substr(5,2);
        $('#periode').val(per);
    });

    // $('#form-tambah-reg').on('change', '#diskon', function(){
    //     hitungTambah();
    // });

    $('#form-tambah-reg').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id').val();
        var total = $('#total').val();
        if(total == 0){
            alert('Total pengajuan tidak boleh 0');
        }else{
            // tambah
            $iconLoad.show();
            console.log('parameter:tambah');
            var formData = new FormData(this);
            for(var pair of formData.entries()) {
                    console.log(pair[0]+ ', '+ pair[1]); 
                }

            var nik='<?php echo $nik; ?>' ;
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
            var kode_pp='<?php echo $kode_pp; ?>' ;
            var periode='<?php echo $periode; ?>' ;

            // var jadwal1 = $('#jadwal option:selected').text();
            // var tmp = jadwal1.split("-");
            // var no_jadwal = tmp[1];

            formData.append('nik_user', nik);
            formData.append('kode_lokasi', kode_lokasi);
            formData.append('kode_pp', kode_pp);
            formData.append('periode', periode);
            // formData.append('no_jadwal', no_jadwal);

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Registrasi.php?fx=simpan',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    if(result.status){
                        dataTable.ajax.reload();
                        Swal.fire(
                            'Great Job!',
                            'Your data has been saved.'+result.message,
                            'success'
                            )
                        printReg(result.no_reg);
                        $iconLoad.hide();
                        
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

    $('#slide-print').on('click', '#btn-reg-kembali', function(){
        $('#saku-data-reg').show();
        $('#form-tambah-reg').hide();
        $('#slide-print').hide();
    });

    $('#saku-data-reg').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();
       
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'no_reg':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#no_reg').val(id);
                    $('#tgl_input').val(result.daftar[0].tgl_input);
                    $('#paket')[0].selectize.setValue(result.daftar[0].no_paket);
                    $('#jadwal')[0].selectize.setValue(result.daftar[0].no_jadwal);
                    $('#kode_pp')[0].selectize.setValue(result.daftar[0].kode_pp);
                    $('#jenis_paket')[0].selectize.setValue(result.daftar[0].jenis);
                    $('#jenis_promo')[0].selectize.setValue(result.daftar[0].kode_harga);
                    $('#quota').val(result.daftar[0].no_quota);
                    $('#lama_hari').val(result.daftar[0].lama_hari);
                    $('#kode_curr').val(result.daftar[0].kode_curr);
                    $('#harga_paket').val(result.daftar[0].harga);
                    $('#type_room')[0].selectize.setValue(result.daftar[0].no_type);
                    $('#harga_room').val(result.daftar[0].harga_room);
                    $('#tgl_berangkat').val(result.daftar[0].tgl_berangkat);
                    $('#no_peserta')[0].selectize.setValue(result.daftar[0].no_peserta);
                    // $('#no_peserta_ref')[0].selectize.setValue(result.daftar[0].no_peserta_ref);
                    $('#referal').val(result.daftar[0].referal);
                    $('#brkt_dgn').val(result.daftar[0].brkt_dgn);
                    $('#hubungan').val(result.daftar[0].hubungan);
                    $('#ket_diskon').val(result.daftar[0].ket_diskon);

                    $('#flag_group')[0].selectize.setValue(result.daftar[0].flag_group);
                    $('#ukuran_pakaian')[0].selectize.setValue(result.daftar[0].uk_pakaian);
                    $('#agen')[0].selectize.setValue(result.daftar[0].no_agen);
                    $('#marketing')[0].selectize.setValue(result.daftar[0].no_marketing);
                    $('#sumber')[0].selectize.setValue(result.daftar[0].info);
                    $('#diskon').val(result.daftar[0].diskon);
                    // $('#tot_tambah').val(toRp(result.daftar[0].total_tambah));
                    // $('#tot_dokumen').val(toRp(result.daftar[0].total_dokumen));

                    var input="";
                    var no=1;
                    var tot=0;
                    if(result.daftar2.length>0){
                        for(var x=0;x<result.daftar2.length;x++){
                            var line = result.daftar2[x];
                            input+=`<tr class='row-btambah'>
                            <td width='5%' class='no-btambah'>`+no+`</td>
                            <td width='10%'>`+line.kode_biaya+`<input type='hidden' name='btambah_kode_biaya[]' class='form-control inp-btambah_kode_biaya' value='`+line.kode_biaya+`' readonly></td>
                            <td width='35%' style='text-align:right'>`+line.nama+`<input type='hidden' name='btambah_nama[]' class='form-control inp-btambah_nama'  value='`+line.nama+`' readonly required></td>
                            <td width='20%' style='text-align:right'>`+toRp(line.tarif)+`<input type='hidden' name='btambah_nilai[]' class='form-control inp-btambah_nilai currency'  value='`+line.tarif+`' readonly required></td>
                            <td width='10%' style='text-align:right'><input type='text' name='btambah_jumlah[]' class='form-control inp-btambah_jumlah currency'  value='`+line.jml+`' required></td>
                            <td width='20%' style='text-align:right'><input type='text' name='btambah_total[]' class='form-control inp-btambah_total currency'  value='`+line.nilai+`' required></td>
                            </tr>`;
                            no++;
                            tot+=parseFloat(line.nilai);
                        }
                    }
                    $('#tot_tambah').val(tot);
                    var no=1;
                    var input2='';
                    var tot2=0;
                    if(result.daftar3.length>0){
                        for(var i=0;i< result.daftar3.length;i++){
                            var line = result.daftar3[i];
                            input2+=`<tr class='row-bdok'>
                            <td width='5%' class='no-bdok'>`+no+`</td>
                            <td width='10%'>`+line.kode_biaya+`<input type='hidden' name='bdok_kode_biaya[]' class='form-control inp-bdok_kode_biaya' value='`+line.kode_biaya+`' readonly></td>
                            <td width='35%' style='text-align:right'>`+line.nama+`<input type='hidden' name='bdok_nama[]' class='form-control inp-bdok_nama'  value='`+line.nama+`' readonly required></td>
                            <td width='20%' style='text-align:right'>`+toRp(line.tarif)+`<input type='hidden' name='bdok_nilai[]' class='form-control inp-bdok_nilai currency2'  value='`+line.tarif+`' readonly required></td>
                            <td width='10%' style='text-align:right'><input type='text' name='bdok_jumlah[]' class='form-control inp-bdok_jumlah currency2'  value='`+line.jml+`' required></td>
                            <td width='20%' style='text-align:right'><input type='text' name='bdok_total[]' class='form-control inp-bdok_total currency2'  value='`+line.nilai+`' required></td>
                            </tr>`;
                            no++;
                            
                            tot2+=parseFloat(line.nilai);
                        }
                    }
                    
                    $('#tot_dokumen').val(tot2);
                    var no=1;
                    var input3='';
                    if(result.daftar4.length>0){
                        for(var i=0;i< result.daftar4.length;i++){
                            var line = result.daftar4[i];
                            input3+=`<tr class='row-dok'>
                            <td width='5%' class='no-dok'>`+no+`</td>
                            <td width='10%'>`+line.no_dok+`<input type='hidden' name='dok_no_dokumen[]' class='form-control inp-dok_no_dokumen' value='`+line.no_dok+`' readonly></td>
                            <td width='35%' style='text-align:right'>`+line.ket+`<input type='hidden' name='dok_deskripsi[]' class='form-control inp-dok_deskripsi'  value='`+line.ket+`' readonly required></td>
                            <td width='20%' style='text-align:right'>`+line.jenis+`<input type='hidden' name='dok_jenis[]' class='form-control inp-dok_jenis'  value='`+line.jenis+`' required readonly></td>
                            </tr>`;
                            no++;
                        }
                    }

                    $('#table-btambah tbody').html(input);
                    $('#table-bdok tbody').html(input2);
                    $('#table-dok tbody').html(input3);
                    $('.currency').inputmask("numeric", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 2,
                        autoGroup: true,
                        rightAlign: true,
                        oncleared: function () { self.Value(''); }
                    });
                    $('.currency2').inputmask("numeric", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 2,
                        autoGroup: true,
                        rightAlign: true,
                        oncleared: function () { self.Value(''); }
                    });
                    // hitungTambah();
                    // hitungDok();
                    $('#saku-data-reg').hide();
                    $('#form-tambah-reg').show();
                }
            }
        });
    });

    $('#saku-data-reg').on('click','#btn-print',function(e){
        var id = $(this).closest('tr').find('td').eq(0).html();
        printReg(id);
    });

    
    $('#saku-data-reg').on('click','#btn-delete',function(e){
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
                var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
                
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/Registrasi.php',
                    dataType: 'json',
                    async:false,
                    data: {'no_reg':kode,'kode_lokasi':kode_lokasi,'kode_pp':'<?=$kode_pp?>'},
                    success:function(result){
                        if(result.status){
                            dataTable.ajax.reload();
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
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

    $('#table-btambah').on('keydown', '.inp-btambah_jumlah', function(e){
        if (e.which == 13 || e.which == 9) {
            // hitungTambah();
            var nil = $(this).closest('tr').find('.inp-btambah_nilai').val();
            var jum = $(this).closest('tr').find('.inp-btambah_jumlah').val();
            var sub = toNilai(nil)*toNilai(jum);
            $(this).closest('tr').find('.inp-btambah_total').val(sub);
            hitungTambah2();

        }
    });

    $('#table-btambah').on('change', '.inp-btambah_jumlah', function(e){
        e.preventDefault();
        // hitungTambah();
            var nil = $(this).closest('tr').find('.inp-btambah_nilai').val();
            var jum = $(this).closest('tr').find('.inp-btambah_jumlah').val();
            var sub = toNilai(nil)*toNilai(jum);
            $(this).closest('tr').find('.inp-btambah_total').val(sub);
            hitungTambah2();
    });

    $('#table-btambah').on('keydown', '.inp-btambah_total', function(e){
        if (e.which == 13 || e.which == 9) {
            hitungTambah2();
        }
    });

    $('#table-btambah').on('change', '.inp-btambah_total', function(e){
        e.preventDefault();
        hitungTambah2();
    });

    $('#table-bdok').on('keydown', '.inp-bdok_jumlah', function(e){
        if (e.which == 13 || e.which == 9) {
            // hitungDok();
            var nil = $(this).closest('tr').find('.inp-bdok_nilai').val();
            var jum = $(this).closest('tr').find('.inp-bdok_jumlah').val();
            var sub = toNilai(nil)*toNilai(jum);
            $(this).closest('tr').find('.inp-bdok_total').val(sub);
            hitungDok2();
        }
    });

    $('#table-bdok').on('change', '.inp-bdok_jumlah', function(e){
        e.preventDefault();
        // hitungDok();
        // console.log($(this).val());
        var nil = $(this).closest('tr').find('.inp-bdok_nilai').val();
        var jum = $(this).closest('tr').find('.inp-bdok_jumlah').val();
        var sub = toNilai(nil)*toNilai(jum);
        $(this).closest('tr').find('.inp-bdok_total').val(sub);
        hitungDok2();
    });
   

    $('#table-bdok').on('keydown', '.inp-bdok_total', function(e){
        if (e.which == 13 || e.which == 9) {
            hitungDok2();
        }
    });

    $('#table-bdok').on('change', '.inp-bdok_total', function(e){
        e.preventDefault();
        hitungDok2();
    });
   

    $('#no_ktp,#nama_wali,#alamat,#no_telp,#nis,#nama_siswa,#tgl_bayar').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['no_ktp','nama_wali','alamat','no_telp','nis','nama_siswa','tgl_bayar'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
           
            $('#'+nxt[idx]).focus();
            
        }else if(code == 38){
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx--;
            if(idx != -1){ 
                $('#'+nxt[idx]).focus();
            }
        }
    });

    $('#slide-print').on('click','#btn-reg-print',function(e){
        e.preventDefault();
        // var w=window.open();
        // var html =`<html><head>
        //         <meta charset="utf-8">
        //         <meta http-equiv="X-UA-Compatible" content="IE=edge">
        //         <meta name="viewport" content="width=device-width, initial-scale=1">
        //         <meta name="description" content="">
        //         <meta name="author" content="">
        //         <title>SAKU | Sistem Akuntansi Keuangan Digital</title>
        //         <link href="<?php echo $folder_css?>/style.min.css" rel="stylesheet">
        //         <!-- Dashboard 1 Page CSS -->
        //         <link href="<?php echo $folder_css?>/pages/dashboard1.css" rel="stylesheet">
        //         <link rel="stylesheet" type="text/css" href="<?php echo $folder_assets?>/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
        //         <!-- SAI CSS -->
        //         <link href="<?php echo $folder_css?>/sai.css" rel="stylesheet">
                
        //     </head>
        //     <!--
        //     <body class="skin-default fixed-layout" >-->
        //         <div id="main-wrapper" style='color:black'>
        //             <div class="page-wrapper" style='min-height: 674px;margin: 0;padding: 10px;background: white;color: black !important;'>
        //                 <section class="content" id='ajax-content-section' style='color:black !important'>
        //                     <div class="container-fluid mt-3">
        //                         <div class="row" id="slide-print">
        //                             <div class="col-md-12">
        //                                 <div class="card">
        //                                     <div class="card-body">`+$('#print-area').html()+`
        //                                     </div>
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </section>
        //             </div>
        //         </div>
        //     <!--</body></html>-->
        //     `;
        //     w.document.write(html);
        //     setTimeout(function(){
        //         w.print();
        //         w.close();
        //     }, 1500);
        $('#print-area').printThis();
    });


    function printReg(id){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getPrint',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_reg':id},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var line =result.daftar[0];
                        var html=`<div align='center'>
                        <table width='100%' class='table table-bordered table-striped' cellspacing='1' cellpadding='2'>
                        <style>
                            th,td{
                                padding:5px !important;
                            }
                        </style>
                        <tr>
                        <td colspan='2' align='center' style='font-weight:bold;'>FORMULIR PENDAFTARAN UMROH </td>
                        </tr>
                        <tr>
                        <td colspan='2'>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan='2' style='font-weight:bold;'>DATA PRIBADI </td>
                        </tr>
                        <tr>
                        <td width='30%' style='font-weight:bold;'>NO REGISTRASI </td>
                        <td width='70%'>:&nbsp;`+line.no_reg+`</td>
                        </tr>
                        <tr>
                        <td width='30%' style='font-weight:bold;'>NO PESERTA </td>
                        <td width='70%'>:&nbsp;`+line.no_peserta+`</td>
                        </tr>
                        <tr>
                        <td width='30%' style='font-weight:bold;'>NAMA LENGKAP </td>
                        <td width='70%'>:&nbsp;`+line.peserta+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>NO ID CARD </td>
                        <td>:&nbsp;`+line.id_peserta+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>STATUS</td>
                        <td>:&nbsp;`+line.status+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>JENIS KELAMIN </td>
                        <td>:&nbsp;`+line.jk+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>TEMPAT &amp; TGL LAHIR </td>
                        <td>:&nbsp;`+line.tempat+` `+line.tgl_lahir+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>BERANGKAT DENGAN </td>
                        <td>:&nbsp;`+line.brkt_dgn+`<br> Hubungan : `+line.hubungan+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>PERNAH UMROH / HAJI </td>
                        <td>:&nbsp;`+line.th_umroh+`/`+line.th_haji+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>PEKERJAAN</td>
                        <td>:&nbsp;`+line.nama_pekerjaan+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>NO PASSPORT </td>
                        <td>:&nbsp;`+line.nopass+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>KANTOR IMIGRASI </td>
                        <td>:&nbsp;`+line.kantor_mig+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold'>HP</td>
                        <td>:&nbsp;`+line.hp+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>TELEPON</td>
                        <td>:&nbsp;`+line.telp+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>EMAIL</td>
                        <td>:&nbsp;`+line.email+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>ALAMAT</td>
                        <td>:&nbsp;`+line.alamat+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>NO EMERGENCY </td>
                        <td>:&nbsp;`+line.ec_telp+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>MARKETING</td>
                        <td>:&nbsp;`+line.nama_marketing+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>AGEN</td>
                        <td>:&nbsp;`+line.nama_agen+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>REFERAL</td>
                        <td>&nbsp;`+line.referal+`</td>
                        </tr>
                        <tr>
                        <td colspan='2'>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan='2' style='font-weight:bold;'>DATA KELANGKAPAN PERJALANAN </td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>PAKET</td>
                        <td>:&nbsp;`+line.namapaket+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>PROGRAM UMROH / HAJI </td>
                        <td>:&nbsp;`+line.jenis_paket+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>TYPE ROOM </td>
                        <td>:&nbsp;`+line.type+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>BIAYA PAKET </td>
                        <td>:&nbsp;`+toRp(line.harga)+`</td>
                        </tr>
                        <tr>
                            <td style='font-weight:bold;'>BIAYA ROOM </td>
                            <td>:&nbsp;`+sepNum(line.harga_room)+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>DISKON</td>
                        <td>:&nbsp;`+toRp(line.diskon)+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>TGL KEBERANGKATAN </td>
                        <td>:&nbsp;`+line.tgl_berangkat+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>UKURAN PAKAIAN </td>
                        <td>:&nbsp;`+line.uk_pakaian+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>SUMBER INFORMASI </td>
                        <td>:&nbsp;`+line.info+`</td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td align='center'>Calon Jamaah</td>
                        <td align='center'>MO</td>
                        </tr>
                        <tr>
                        <td height='60'>&nbsp;</td>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td style='text-align:center'>(..............................................)</td>
                        <td style='text-align:center'>(..............................................)</td>
                        </tr>
                        </table>
                        </div>
                        
                        <br><DIV style='page-break-after:always'></DIV>`;
                        $('#print-area').html(html);
                        $('#slide-print').show();
                        $('#saku-data-reg').hide();
                        $('#form-tambah-reg').hide();
                    }
                }
            }
        });
    }

    // UPLOAD DOKUMEN
    $('#form-upload-reg').on('click', '#btn-upload-kembali', function(){
        $('#saku-data-reg').show();
        $('#form-tambah-reg').hide();
        $('#form-upload-reg').hide();
    });
    
    $('#saku-data-reg').on('click','#btn-upload',function(e){
        var id = $(this).closest('tr').find('td').eq(0).html();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getUpload',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_reg':id},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var line = result.daftar[0];
                        $('#upload_no_reg').val(line.no_reg);
                        $('#upload_jamaah').val(line.no_peserta+' - '+line.nama_peserta);
                        $('#upload_paket').val(line.nama_paket);
                        $('#upload_jadwal').val(line.tgl_berangkat);
                        $('#upload_alamat').val(line.alamat);
                        if(typeof result.daftar2 !== 'undefined' && result.daftar2.length>0){
                            var html='';
                            var no=1;
                            for(var i=0;i<result.daftar2.length;i++){
                                var line2 = result.daftar2[i];
                                
                                html+= `<tr class='row-upload-dok'>"
                                <td width='5%'  class='no-dok'>`+no+`</td>
                                <td width='10%' >`+line2.no_dokumen+`<input type='hidden' name='upload_no_dokumen[]' class='form-control upload_no_dokumen' value='`+line2.no_dokumen+`' required></td>
                                <td width='20%'  class='upload_deskripsi'>`+line2.deskripsi+`</td>
                                <td width='20%'  class='upload_path'>`+line2.fileaddres+`</td>
                                <td width='20%' ><input type='text' name='upload_nik[]' class='form-control upload_nik' value='`+line2.nik+`' required></td>`;
                                if(line2.fileaddres == "-" || line2.fileaddres == ""){

                                    html+=`
                                    <td width='20%'>
                                    <input type='file' name='file_dok[]'>
                                    </td>`;

                                }else{
                                    
                                    html+=`
                                    <td width='20%'>
                                    <input type='file' name='file_dok[]'>
                                    </td>`;
                                }
                                html+=`
                                <td width='5%'>`;
                                if(line2.fileaddres != "-"){

                                   var link =`<a class='btn btn-success btn-sm download-dok' style='font-size:8px' href='<?=$root?>/upload/`+line2.fileaddres+`'target='_blank'><i class='fa fa-download fa-1'></i></a>`;
                                   
                                }else{
                                    var link =``;
                                }
                                html+=link+`</td>
                                </tr>`;
                                no++;
                            }
                            $('#input-dok tbody').html(html);
                        }
                        $('#form-upload-reg').show();
                        $('#saku-data-reg').hide();
                        $('#form-tambah-reg').hide();
                    }

                    
                }
            }
        });
    });

    $('#form-upload-reg').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id').val();
        $iconLoad.show();
        console.log('parameter:tambah');
        var formData = new FormData(this);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        
        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        var kode_pp='<?php echo $kode_pp; ?>' ;
        var periode='<?php echo $periode; ?>' ;
        
        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);
        formData.append('kode_pp', kode_pp);
        formData.append('periode', periode);
        // formData.append('no_jadwal', no_jadwal);
        
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Registrasi.php?fx=simpanUpload',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                if(result.status){
                    dataTable.ajax.reload();
                    Swal.fire(
                        'Great Job!',
                        'Your data has been saved.'+result.message,
                        'success'
                        )
                        $iconLoad.hide();
                        
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: '<a href>'+result.message+'</a>'
                        })
                    }
                    $('#form-upload-reg').hide();
                    $('#saku-data-reg').show();
                    $('#form-tambah-reg').hide();
                },
                fail: function(xhr, textStatus, errorThrown){
                    alert('request failed:'+textStatus);
                }
            });      
    });

    // GROUPING ANGGOTA

    function getAnggota(param){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getPeserta',
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
                            control.addOption([{text:result.daftar[i].no_peserta + ' - ' + result.daftar[i].nama, value:result.daftar[i].no_peserta}]);
                        }
                    }
                }
            }
        });
    }

    $('#form-group-reg').on('click', '#btn-group-kembali', function(){
        $('#saku-data-reg').show();
        $('#form-tambah-reg').hide();
        $('#form-upload-reg').hide();
        $('#form-group-reg').hide();
    });

    $('#input-anggota').on('click', '.hapus-item', function(){
        $(this).closest('tr').hide();
        no=1;
        $('.row-anggota').each(function(){
            var nom = $(this).closest('tr').find('.no-anggota');
            nom.html(no);
            no++;
        });
        $(this).closest('tr').find('.group_sts_reg').val('D');
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });

    $('#form-group-reg').on('click', '#add-row-anggota', function(){
      
      var no=$('#input-anggota .row-anggota:last').index();
      no=no+2;
      var input = "";
      input += "<tr class='row-anggota'>";
      input += "<td width='5%' class='no-anggota'>"+no+"</td>";
      input += "<td width='60%'><select name='group_no_anggota[]' class='form-control group-anggota ke"+no+"' value='' required></select><input type='hidden' class='form-control group_sts_reg' name='group_sts_reg[]' value='0'><input type='hidden' name='no_reg_ref[]' class='group_no_reg_ref' value=''></td>";
      input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
      input += "</tr>";
      $('#input-anggota tbody').append(input);
      getAnggota('ke'+no);
      $('#input-anggota tbody tr:last').find('.group-anggota')[0].selectize.focus();
  });
    
    $('#saku-data-reg').on('click','#btn-group',function(e){
        var id = $(this).closest('tr').find('td').eq(0).html();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Registrasi.php?fx=getGroup',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_reg':id},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var line = result.daftar[0];
                        $('#group_no_reg').val(line.no_reg);
                        $('#group_jamaah').val(line.no_peserta+' - '+line.nama_peserta);
                        $('#group_paket').val(line.nama_paket);
                        $('#group_jadwal').val(line.tgl_berangkat);
                        $('#group_alamat').val(line.alamat);
                        $('#input-anggota tbody').html('');
                        if(typeof result.daftar2 !== 'undefined' && result.daftar2.length>0){
                            var no=1;
                            var input='';
                            for(var i=0;i<result.daftar2.length;i++){
                                var line2 = result.daftar2[i];
                                input += "<tr class='row-anggota'>";
                                input += "<td width='5%' class='no-anggota'>"+no+"</td>";
                                input += "<td width='60%'><select name='group_no_anggota[]' class='form-control group-anggota ke"+no+"' value='' required></select><input type='hidden' name='group_sts_reg[]' class='group_sts_reg' value='1'><input type='hidden' name='no_reg_ref[]' class='group_no_reg_ref' value='"+line2.no_reg_ref+"'></td>";
                                input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                                input += "</tr>";
                                no++;
                            }
                            $('#input-anggota tbody').html(input);
                            var no=1;
                            for(var x=0;x<result.daftar2.length;x++){
                                var line = result.daftar2[x];
                                getAnggota('ke'+no);
                                $('.ke'+no)[0].selectize.setValue(line.no_peserta);
                                no++;
                            }
                        }
                        
                        $('#form-group-reg').show();
                        $('#form-upload-reg').hide();
                        $('#saku-data-reg').hide();
                        $('#form-tambah-reg').hide();
                    }

                    
                }
            }
        });
    });

    $('#form-group-reg').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id').val();
        $iconLoad.show();
        console.log('parameter:tambah');
        var formData = new FormData(this);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        
        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        var kode_pp='<?php echo $kode_pp; ?>' ;
        var periode='<?php echo $periode; ?>' ;
        
        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);
        formData.append('kode_pp', kode_pp);
        formData.append('periode', periode);
        // formData.append('no_jadwal', no_jadwal);
        
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Registrasi.php?fx=simpanGroup',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                if(result.status){
                    dataTable.ajax.reload();
                    Swal.fire(
                        'Great Job!',
                        'Your data has been saved.'+result.message,
                        'success'
                        )
                        $iconLoad.hide();
                        
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: '<a href>'+result.message+'</a>'
                        })
                    }
                    $('#form-group-reg').hide();
                    $('#form-upload-reg').hide();
                    $('#saku-data-reg').show();
                    $('#form-tambah-reg').hide();
                },
                fail: function(xhr, textStatus, errorThrown){
                    alert('request failed:'+textStatus);
                }
            });      
    });

    

    </script>

    
