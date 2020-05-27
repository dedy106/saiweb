<?php

    /*
    | UTILITY HELPER
    | Berfungsi untuk komponen-komponen tambahan, seperti konversi tanggal, separasi angka, dan format Rupiah
    */

	function random_string($type = 'alnum', $len = 8)
	{
		switch ($type)
		{
			case 'basic':
				return mt_rand();
			case 'alnum':
			case 'numeric':
			case 'nozero':
			case 'alpha':
				switch ($type)
				{
					case 'alpha':
						$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'alnum':
						$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'numeric':
						$pool = '0123456789';
						break;
					case 'nozero':
						$pool = '123456789';
						break;
				}
				return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
			case 'unique': // todo: remove in 3.1+
			case 'md5':
				return md5(uniqid(mt_rand()));
			case 'encrypt': // todo: remove in 3.1+
			case 'sha1':
				return sha1(uniqid(mt_rand(), TRUE));
		}
	}

    // mengubah #id atau .class menjadi array id dan class 
    function parseIdentifier($identifier){
        $class = '';
        $id = '';
        $attr = '';
        $x = explode(",", $identifier);
        for($i=0; $i<count($x); $i++){
            if(substr($x[$i], 0, 1) == '.'){
                $class .= ' '.substr($x[$i], 1, strlen($x[$i]));
            }else if(substr($x[$i], 0, 1) == '#'){
                $id = substr($x[$i], 1, strlen($x[$i]));
            }else{
                $attr = $x[$i];
            }
        }

        return array('id'=>$id, 'class'=>$class, 'attr'=>$attr);
    }

    function prePrint($arr){
        echo "<pre>"; print_r($arr); echo "</pre>";
    }

    function pesanRollback($url){
        echo "<script>alert('Transaksi gagal disimpan'); window.location='".site_url($url)."';</script>";
    }

    function jsRedirect($url, $pesan){
        echo "<script>alert('$pesan'); window.location='".site_url($url)."';</script>";
    }

    function generateNotifColour($num){
        switch($num){
            case $num <= 10: $colour = "aqua"; break;
            case $num <= 20: $colour = "yellow"; break;
            case $num <= 40: $colour = "green"; break;
            case $num <= 80: $colour = "blue"; break;
            case $num > 80: $colour = "red"; break;
        }

        return $colour;
    }

    function rataKanan($str){
        return "<p align='right'>$str</p>";
    }

    function toRp($num, $kurung=false, $right=false){
		if(!is_numeric($num))return NULL;
		$jumlah_desimal ="2";
		$pemisah_desimal =",";
		$pemisah_ribuan =".";
        $style= '';
        if($right == TRUE){
            $style = "style='text-align:right'";
        }
        if($num < 0){
            $num = $num * -1;
            if($kurung){
                $angka = "<p $style>(Rp". number_format($num, $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan). ")</p>";
            }else{
                $angka = "<p $style>Rp". number_format($num, $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan). "</p>";
            }
        }else{
            $angka = "<p $style>Rp". number_format($num, $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan). "</p>";
        }

		return $angka;
    }

    function sepNum($num, $dec=0){
        // separated number (contoh: 10.000.000,00)
        return number_format($num, $dec, ',', '.');
    }
    
    function sepNum2($num, $dec=2){
        // separated number (contoh: 10.000.000,00)
        return number_format($num, $dec, ',', '.');
    }

    function joinNum($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }

    // mengkonversi tanggal dengan format Y-m-d dan waktu dengan format H:i:s
    // menjadi dd M Y dan waktu
    // bisa menggunakan datetime/date saja
    function convertDate($datetime){
        $str = explode(' ', $datetime);
        $tanggal = explode('-', $str[0]);

        if(ISSET($str[1])){
            $waktu = explode(':', $str[1]);
            $waktu_no_ms = explode('.', $str[1]);
        }
        
        $bulan = getNamaBulan($tanggal[1]);

        $tanggal = $tanggal[2]." ".$bulan." ".$tanggal[0];

        return $tanggal;
    }

    function convertDateTime($datetime){
        $str = explode(' ', $datetime);
        $tanggal = explode('-', $str[0]);
        $waktu = explode(':', $str[1]);
        
        $waktu_no_ms = explode('.', $str[1]);
        $bulan = getNamaBulan($tanggal[1]);

        $tanggal = $tanggal[2]." ".$bulan." ".$tanggal[0];

        return $tanggal." ".$waktu_no_ms[0];
    }

    function cutTime($datetime){
        $str = explode(' ', $datetime);
        $time = explode('.', $str[1]);
        return $time[0];
    }

    // memisahkan date dari datetime dengan format Y-m-d
    function cutDate($datetime){
        $str = explode(' ', $datetime);
        return $str[0];
    }

    function cutDate2($datetime){
        $str = explode(' ', $datetime);
        $tanggal = explode('-', $str[0]);
        $tanggal = $tanggal[2]."-".$tanggal[1]."-".$tanggal[0];
        return $tanggal;
    }

    function getNamaBulan($no_bulan){
        switch ($no_bulan){
            case 1 : case '1' : case '01': $bulan = "Januari"; break;
            case 2 : case '2' : case '02': $bulan = "Februari"; break;
            case 3 : case '3' : case '03': $bulan = "Maret"; break;
            case 4 : case '4' : case '04': $bulan = "April"; break;
            case 5 : case '5' : case '05': $bulan = "Mei"; break;
            case 6 : case '6' : case '06': $bulan = "Juni"; break;
            case 7 : case '7' : case '07': $bulan = "Juli"; break;
            case 8 : case '8' : case '08': $bulan = "Agustus"; break;
            case 9 : case '9' : case '09': $bulan = "September"; break;
            case 10 : case '10' : case '10': $bulan = "Oktober"; break;
            case 11 : case '11' : case '11': $bulan = "November"; break;
            case 12 : case '12' : case '12': $bulan = "Desember"; break;
            default: $bulan = null;
        }

        return $bulan;
    }

    // format thnbln = 201704
    function getPeriode($thnbln){
        return getNamaBulan(substr($thnbln, -2))." ".substr($thnbln, 0, 4);
    }
    
    // format $date = "2017-04-01"
    function tglToPeriode($date){
        $arr = explode('-', $date);
        return $arr[0].$arr[1];
    }

    // mengubah DMY menjadi DMY atau sebaliknya
    function reverseDate($ymd_or_dmy_date, $org_sep='-', $new_sep='-'){
        $arr = explode($org_sep, $ymd_or_dmy_date);
        return $arr[2].$new_sep.$arr[1].$new_sep.$arr[0];
    }

    function generateFormat($format, $str){
        switch ($format){
            case 'sepNum': $formated_str = rataKanan(sepNum($str)); break;
            case 'sepNum2': $formated_str = rataKanan(sepNum2($str)); break;
            case 'toRp': $formated_str = toRp($str); break;
            case 'convertDate': $formated_str = convertDate($str); break;
            case 'convertDateTime': $formated_str = convertDateTime($str); break;
            case 'cutDate': $formated_str = cutDate($str); break;
            // case 'rataKanan': $formated_str = rataKanan($str); break;
        }

        return $formated_str;
    }

    function filterChar($str){
        $filtered = str_replace(" ", "-", strtolower($str));
        $filtered = str_replace(array("(",")","'","/","'\'",':','"',',','?','%'), "", $filtered);
        return $filtered;
    }

    function generateSEO($id, $judul){
        $seo = str_replace(" ", "-", strtolower($judul));
        $seo = str_replace(array("(",")","'","/","'\'",':','"',',','?','%'), "", $seo);
        return "$id/$seo";
    }

    // template corlate only
    function generateWebPaging($sub_url, $data_array_count, $item_per_page=5, $active_page_number=1, $protection=TRUE){
        // protect
        if($protection){
            if($active_page_number > ceil($data_array_count/$item_per_page)){
                redirect($sub_url);
            }
        }

        // $list = '';
        $list = ($active_page_number > 1 ? "<li><a href='$sub_url/".($active_page_number - 1)."'><i class='fa fa-long-arrow-left'></i>Previous Page</a></li>" : '');

        for($i=1; $i<=ceil($data_array_count/$item_per_page); $i++){
            $list .= ($i == $active_page_number ? "<li class='active'><a href='#'>$i</a></li>" : "<li><a href='$sub_url/".$i."'>$i</a></li>");
        }

        $list .= ($active_page_number < ceil($data_array_count/$item_per_page) ? "<li><a href='$sub_url/".($active_page_number + 1)."'>Next Page<i class='fa fa-long-arrow-right'></i></a></li>" : '');

        echo "  <ul class='pagination pagination-lg'>$list</ul>";
    }

    function webPaging($template, $data_array_count, $item_per_page=5, $active_page_number=1, $protection=TRUE){
        switch($template){
            case 'corlate':
                // protect
                if($protection){
                    if($active_page_number > ceil($data_array_count/$item_per_page)){
                        redirect($sub_url);
                    }
                }
        
                // $list = '';
                $list = ($active_page_number > 1 ? "<li><a href='$sub_url/".($active_page_number - 1)."'><i class='fa fa-long-arrow-left'></i>Previous Page</a></li>" : '');
        
                for($i=1; $i<=ceil($data_array_count/$item_per_page); $i++){
                    $list .= ($i == $active_page_number ? "<li class='active'><a href='#'>$i</a></li>" : "<li><a href='$sub_url/".$i."'>$i</a></li>");
                }
        
                $list .= ($active_page_number < ceil($data_array_count/$item_per_page) ? "<li><a href='$sub_url/".($active_page_number + 1)."'>Next Page<i class='fa fa-long-arrow-right'></i></a></li>" : '');
        
                return "  <ul class='pagination pagination-lg'>$list</ul>";
            break;
            case 'academy':
                // protect
                if($protection){
                    if($active_page_number > ceil($data_array_count/$item_per_page)){
                        redirect($sub_url);
                    }
                }
        
                // $list = '';
                $list = ($active_page_number > 1 ? "<li class='page-item'><a class='page-link' href=''$sub_url/".($active_page_number - 1)."''><i class='fa fa-chevron-left'></i></a></li>" : '');
        
                for($i=1; $i<=ceil($data_array_count/$item_per_page); $i++){
                    $list .= ($i == $active_page_number ? "<li class='page-item active'><a class='page-link' href='#'>01</a></li>" : "<li class='page-item'><a class='page-link' href='$sub_url/".$i."'>$i</a></li>");
                }
        
                $list .= ($active_page_number < ceil($data_array_count/$item_per_page) ? "<li class='page-item'><a class='page-link' href='$sub_url/".($active_page_number + 1)."'><i class='fa fa-chevron-right'></i></a></li>" : '');
        
                echo 
                "<div class='academy-pagination-area wow fadeInUp' data-wow-delay='400ms'>
                    <nav>
                        <ul class='pagination'>
                            $list
                        </ul>
                    </nav>
                </div>";
            break;
        }
    }

    // sumber : https://gist.github.com/cahsowan/d315d54a59e4f14a6bab
    function terbilang($x) {
        $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        if ($x < 12)
            return " " . $angka[$x];
        elseif ($x < 20)
            return terbilang($x - 10) . " belas";
        elseif ($x < 100)
            return terbilang($x / 10) . " puluh" . terbilang($x % 10);
        elseif ($x < 200)
            return "seratus" . terbilang($x - 100);
        elseif ($x < 1000)
            return terbilang($x / 100) . " ratus" . terbilang($x % 100);
        elseif ($x < 2000)
            return "seribu" . terbilang($x - 1000);
        elseif ($x < 1000000)
            return terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
        elseif ($x < 1000000000)
            return terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
        elseif ($x < 1000000000000)
            return terbilang($x / 1000000) . " milyar" . terbilang($x % 1000000000);
        elseif ($x >= 1000000000000)
            return terbilang($x / 1000000) . " trilyun" . terbilang($x % 1000000000000);
    }

    function arrayKeyCheck($key_array, $array_series){
        return !array_diff_key(array_flip($key_array), $array_series);
    }

    function classIdStr($id_or_class, $id_or_class_val){
        if($id_or_class_val == null){
            $str = '';
        }else{
            $str = "$id_or_class='$id_or_class_val'";
        }
        return $str;
    }

    /*
    |  INPUT HELPER
    |  Berfungsi untuk menggenerate kode HTML untuk pembuatan form atau elemen input lainnya
    */
    function formOpen($action_url, $cancel_url, $method, $class=null, $id=null){
        $id_str = classIdStr('id', $id);
        $cls_str = classIdStr('class', $class);
        echo "<form action='".site_url($action_url)."' method=$method $cls_str $id_str>
                <div class='row'>
                    <div class='col-xs-12'>
                        <div class='box'>
                            <div class='box-body'>
                                <button type='submit' class='btn btn-success pull-right'><i class='fa fa-plus-circle'></i> Save</button>
                                <a class='btn btn-default pull-right' style='margin-right: 5px;' href='".site_url($cancel_url)."'><i class='fa fa-ban'></i> Cancel</a>
                            </div>
                        </div>
                        <div class='box box-warning'>
                            <div class='box-body pad'>
        ";
    }

    function formOpenMultipart($action_url, $cancel_url, $method, $class=null, $id=null){
        $id_str = classIdStr('id', $id);
        $cls_str = classIdStr('class', $class);
        echo "<form action='".site_url($action_url)."' method=$method  enctype='multipart/form-data' $cls_str $id_str>
                <div class='row'>
                    <div class='col-xs-12'>
                        <div class='box'>
                            <div class='box-body'>
                                <button type='submit' class='btn btn-success pull-right'><i class='fa fa-plus-circle'></i> Save</button>
                                <a class='btn btn-default pull-right' style='margin-right: 5px;' href='".site_url($cancel_url)."'><i class='fa fa-ban'></i> Cancel</a>
                            </div>
                        </div>
                        <div class='box box-warning'>
                            <div class='box-body pad'>
        ";
    }

    function formOpenAjax($cancel_url, $class=null, $id=null, $header_text=array()){
        $id_str = classIdStr('id', $id);
        $cls_str = classIdStr('class', $class);
        $header_html = (count($header_text) > 0 ? "
        <h3 class='box-title'><i class='".$header_text[1]."'></i> ".$header_text[0]."</h3>" : '' );
        echo "<form $cls_str $id_str>
                <div class='row'>
                    <div class='col-xs-12'>
                        <div class='box'>
                            <div class='box-header'>
                                $header_html
                                <button type='submit' class='btn btn-success pull-right'><i class='fa fa-floppy-o'></i> Save</button>
                                <a class='btn btn-default pull-right' style='margin-right: 5px;' href='".site_url($cancel_url)."'><i class='fa fa-ban'></i> Cancel</a>
                            </div>
                        </div>
                        <div class='box box-warning'>
                            <div class='box-body pad'>
        ";
    }

    function formOpenAjaxMultipart($cancel_url, $class=null, $id=null){
        $id_str = classIdStr('id', $id);
        $cls_str = classIdStr('class', $class);
        echo "<form enctype='multipart/form-data' $cls_str $id_str>
                <div class='row'>
                    <div class='col-xs-12'>
                        <div class='box'>
                            <div class='box-body'>
                                <button type='submit' class='btn btn-success pull-right'><i class='fa fa-plus-circle'></i> Save</button>
                                <a class='btn btn-default pull-right' style='margin-right: 5px;' href='".site_url($cancel_url)."'><i class='fa fa-ban'></i> Cancel</a>
                            </div>
                        </div>
                        <div class='box box-warning'>
                            <div class='box-body pad'>
        ";
    }

    function formClose(){
        echo "              </div>
                        </div>
                    </div>
                </div>
                </form>";
    }

    function formCloseAjax(){
        echo "              
                            <div id='validation-box'></div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>";
    }

    // ------------------------------------- Input Element In Use -----------------------------------------
    function saiInput($type, $label, $name, $maxlength=null, $value=null, $identifier=null, $additional_attribute='required', $placeholder_sts=TRUE, $div_attr=null){
        // identifier dapat diisi string identifier, contoh ".input-ctr, #input-ctr, .test"; tiap identifier dipisah koma (,)
        // additional attribute bisa diisi pattern, required, readonly, disabled dll.
        // div attribute dapat dicustom sesuai kebutuhan untuk div penampung (parent) inputnya
        $id = '';

        if($type == 'currency' OR $type == 'sepNum'){
            $type = '';
            $class = 'currency';
        }else if($type == 'datedmy'){
            $type = 'text';
            $class = 'datepicker-dmy';
        }else if($type == 'date'){
            $type = 'text';
            $class = 'datepicker';
        }else if($type == 'daterange'){
            $type = 'text';
            $class = 'daterangepicker';
        }else{
            $type = "type='$type'";
            $class = '';
        }

        if($identifier != null){
            $x = explode(",", $identifier);
            for($i=0; $i<count($x); $i++){
                if(substr($x[$i], 0, 1) == '.'){
                    $class .= ' '.substr($x[$i], 1, strlen($x[$i]));
                }else{
                    $id = substr($x[$i], 1, strlen($x[$i]));
                }
            }
        }

        $placeholder = ($placeholder_sts == TRUE ? "placeholder='Masukkan $label'" : null);

        echo "
                <div class='row' $div_attr>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input $type name='$name' class='form-control $class' id='$id' maxlength='$maxlength' value='$value' $placeholder $additional_attribute>
                        </div>
                    </div>
                </div>
        ";
    }

    function saiSelect($label, $name, $option_array, $val_col, $text_col=null, $selected_val=null, $show_id=TRUE, $identifier=null, $additional_attribute='required', $placeholder_sts=TRUE,  $div_attr=null){
        $class = '';
        $id = '';
        if($identifier != null){
            $x = explode(",", $identifier);
            for($i=0; $i<count($x); $i++){
                if(substr($x[$i], 0, 1) == '.'){
                    $class .= ' '.substr($x[$i], 1, strlen($x[$i]));
                }else{
                    $id = substr($x[$i], 1, strlen($x[$i]));
                }
            }
        }

        echo "
            <div class='row'>
                <div class='form-group' $div_attr>
                    <label class='control-label col-sm-3'>$label</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                        <select name='$name' class='form-control $class selectize' id='$id' $additional_attribute>";
        if($placeholder_sts){
            echo "<option value=''>--- Pilih $label ---</option>";
        }

        for($i=0; $i<count($option_array); $i++){
            $selected = "";
            if($selected_val != null AND $option_array[$i][$val_col] == $selected_val){
                $selected = "selected";
            }

            if($text_col != null){
                $id_text = ($show_id ? $option_array[$i][$val_col]." - " : "");
                echo "<option value='".$option_array[$i][$val_col]."' $selected>".$id_text.$option_array[$i][$text_col]."</option>";
            }else{
                echo "<option value='".$option_array[$i][$val_col]."' $selected>".$option_array[$i][$val_col]."</option>";
            }
        }

        echo "
                        </select>
                    </div>
                </div>
            </div>
        ";
    }
    
    function saiFile($label, $name, $datatype, $value=null, $identifier=null, $additional_attribute='required'){
        // datatype dapat diisi : 'image/jpg, image/jpeg, image/png' atau format lainnya
        $class = '';
        $id = '';
        if($identifier != null){
            $x = explode(",", $identifier);
            for($i=0; $i<count($x); $i++){
                if(substr($x[$i], 0, 1) == '.'){
                    $class .= ' '.substr($x[$i], 1, strlen($x[$i]));
                }else{
                    $id = substr($x[$i], 1, strlen($x[$i]));
                }
            }
        }
        echo "
                <div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input name='$name' type='file' accept='$datatype' value='$value' class='$class' id='$id' $additional_attribute>
                        </div>
                    </div>
                </div>
        ";
    }

    function saiTextArea($label, $name, $cols=null, $rows=null, $maxlength=null, $value=null, $identifier=null, $additional_attribute='required'){
        $class = '';
        $id = '';
        if($identifier != null){
            $x = explode(",", $identifier);
            for($i=0; $i<count($x); $i++){
                if(substr($x[$i], 0, 1) == '.'){
                    $class .= ' '.substr($x[$i], 1, strlen($x[$i]));
                }else{
                    $id = substr($x[$i], 1, strlen($x[$i]));
                }
            }
        }
        echo "
                <div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <textarea name='$name' cols='$cols' rows='$rows' maxlength='$maxlength' class='form-control $class' id='$id' $additional_attribute>$value</textarea>
                            ".form_error($name)."
                        </div>
                    </div>
                </div>
        ";
    }

    function saiRadio($label, $name, $option_array, $selected_val=null, $identifier=null, $additional_attribute='required'){
        // dimensi option_array = array('value'=>$var_value, 'text'=>$var_text)
        $class = '';
        $id = '';
        if($identifier != null){
            $x = explode(",", $identifier);
            for($i=0; $i<count($x); $i++){
                if(substr($x[$i], 0, 1) == '.'){
                    $class .= ' '.substr($x[$i], 1, strlen($x[$i]));
                }else{
                    $id = substr($x[$i], 1, strlen($x[$i]));
                }
            }
        }

        echo "
            <div class='row' style='margin-left: 0px;'>
                <div class='form-group'>
                    <div class='row'>
                        <label class='control-label col-xs-12'>$label</label>
                    </div>";
                    for($i=0; $i<count($option_array); $i++){
                        $checked = "";
                        if($selected_val != null AND $option_array[$i]['value'] == $selected_val){
                            $checked = "checked";
                        }
                        
                        echo "
                        <div class='row'>
                            <div class='col-xs-1'>
                                <input type='radio' name='$name' value='".$option_array[$i]['value']."' class='$class' $checked $additional_attribute>
                            </div>
                            <div class='col-xs-11'>
                                ".$option_array[$i]['text']."
                            </div>
                        </div>";
                    }

        echo "
                </div>
            </div>
        ";
    }

    function saiDate($type, $label, $name, $value=null, $identifier=null, $additional_attribute='required', $placeholder_sts=TRUE, $div_attr=null){
        // identifier dapat diisi string identifier, contoh ".input-ctr, #input-ctr, .test"; tiap identifier dipisah koma (,)
        // additional attribute bisa diisi pattern, required, readonly, disabled dll.
        // div attribute dapat dicustom sesuai kebutuhan untuk div penampung (parent) inputnya
        $type = ($type == 'ymd' ? '' : "-$type");
        $class = '';
        $id = '';
        if($identifier != null){
            $x = explode(",", $identifier);
            for($i=0; $i<count($x); $i++){
                if(substr($x[$i], 0, 1) == '.'){
                    $class .= ' '.substr($x[$i], 1, strlen($x[$i]));
                }else{
                    $id = substr($x[$i], 1, strlen($x[$i]));
                }
            }
        }

        $placeholder = ($placeholder_sts == TRUE ? "placeholder='Masukkan $label'" : null);

        echo "
                <div class='row' $div_attr>
                    <div class='form-group' style='margin-bottom:5px;'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='input-group date col-sm-9' style='padding-right:15px; padding-left:15px;'>
                            <div class='input-group-addon'>
                                <i class='fa fa-calendar'></i>
                            </div>
                            <input name='$name' class='form-control datepicker$type $class' id='$id' value='$value' $placeholder $additional_attribute>
                        </div>
                    </div>
                </div>
        ";
    }

    // function saiGrid($header_array, $row, ){
        // 
    // }

    function saiFilter($label_array, $name_filter_array){
        // penulisan label_array : array('Nama Kolom', 'Nama Kolom 2')
        // penulisan name_filter_array : array('nama_kolom'=>array(0,1,2), 'nama_kolom2'=>array(0,2))
        // 0 : All, 1 : Range (from ~ to), 2 : = (equal to), 3 : Semua Opsi

        $name_array = array_keys($name_filter_array);

        for($i=0; $i<count($label_array); $i++){
        echo    "<div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>".$label_array[$i]."
                            <input name='kolom[]' type='hidden' value='".$name_array[$i]."' required>
                        </label>
                        <div class='col-sm-3'>
                            <select name='filter[]' class='form-control' style='margin-bottom:5px;' required>
                    ";

            for($x=0; $x<count($name_filter_array[$name_array[$i]]); $x++){
                switch($name_filter_array[$name_array[$i]][$x]){
                    case 0: echo "<option>All</option>"; break;
                    case 1: echo "<option>Range</option>"; break;
                    case 2: echo "<option>=</option>"; break;
                    case 3: echo "<option>All</option><option>Range</option><option>=</option>"; break;
                }
            }

            echo "
                            </select>
                        </div>
                        <div class='col-sm-3'>
                            <input type='text' name='from[]' class='form-control col-sm-3' style='margin-bottom:5px;' placeholder='From'>
                        </div>
                        <div class='col-sm-3'>
                            <input type='text' name='to[]' class='form-control col-sm-3' style='margin-bottom:5px;' placeholder='To'>
                        </div>
                    </div>
                </div>";
        }
    }

    function saiImagePreview($label, $imgpath){
        echo "
            <div class='row'>
                <div class='form-group'>
                    <label class='control-label col-sm-3'>$label</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                        <img src='".base_url($imgpath)."' style='width:25%; height:25%; min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'>
                        <br><br>
                        <center><b>Url:</b> <i>".base_url($imgpath)."</i></center>
                    </div>
                </div>
            </div>";
    }

    function saiMediaPreview($label, $file_path, $jenis){
        $arr = explode('/', $jenis);

        echo "
            <div class='row'>
                <div class='form-group'>
                    <label class='control-label col-sm-3'>$label</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>".
                        ($arr[0] == 'video' ? "<video controls  style='min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'><source src='".base_url($file_path)."' type='".$jenis."'></video>"  : "<img src='".base_url($file_path)."' style='width:25%; height:25%; min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'>")
                        
                        ."
                        <br><br>
                        <center><b>Url:</b> <i>".base_url($file_path)."</i></center>
                    </div>
                </div>
            </div>";
    }

    function FormImagePreview($label, $imgpath){
        echo "
                <div class='row'>
                    <div class='form-group'>
                        <label>$label</label>
                        <div>
                            <img src='".base_url($imgpath)."' style='width:25%; height:25%; min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'>
                            <br><br>
                            <center><b>Url:</b> <i>".base_url($imgpath)."</i></center>
                        </div>
                    </div>
                </div>
        ";
    }

    function keteranganInput($label, $keterangan){
        echo "
            
                <div class='row'>
                    <div class='col-sm-12'>
                        <div class='form-group'>
                            <label class='control-label'>$label</label>
                            <div class='alert' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #31708f; border-color: #bce8f1; background-color: #d9edf7;'>
                                $keterangan
                            </div>
                        </div>
                    </div>
                </div>
        ";
    }

    // ------------------------------------- Deprecated Input Element --------------------------------------------------

    function inputText($label, $name, $maxlength, $value=null, $pattern=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null, $div_class=null, $div_show=null){
        echo "
                <div class='row'>
                    <div class='form-group $div_class' $div_show>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input type='text' name='$name' class='form-control $class' id='$id' maxlength='$maxlength' $pattern value='$value' placeholder='Masukkan $label' $readonly $required $disabled>
                            ".form_error($name)."
                        </div>
                    </div>
                </div>
        ";
    }
    
    function inputEmail($label, $name, $maxlength, $value=null, $pattern=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null, $div_class=null, $div_show=null){
        echo "fSH
                <div class='row'>
                    <div class='form-group $div_class' $div_show>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input type='email' name='$name' class='form-control $class' id='$id' maxlength='$maxlength' $pattern value='$value' placeholder='Masukkan $label' $readonly $required $disabled>
                            ".form_error($name)."
                        </div>
                    </div>
                </div>
        ";
    }

    function inputPass($label, $name, $maxlength, $value=null, $pattern=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null, $div_class=null, $div_show=null){
        echo "
                <div class='row'>
                    <div class='form-group $div_class' $div_show>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input type='password' name='$name' class='form-control $class' id='$id' maxlength='$maxlength' $pattern value='$value' placeholder='Masukkan $label' $readonly $required $disabled>
                            ".form_error($name)."
                        </div>
                    </div>
                </div>
        ";
    }

    function inputUrl($label, $name, $maxlength, $value=null, $pattern=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null){
        echo "
                <div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input type='url' name='$name' class='form-control $class' id='$id' $pattern maxlength='$maxlength' value='$value' placeholder='Masukkan $label' $readonly $required $disabled>
                            ".form_error($name)."
                        </div>
                    </div>
                </div>
        ";
    }

    function inputNum($label, $name, $min=null, $max=null, $step=null, $value=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null){
        echo "
                <div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input type='number' name='$name' min='$min' max='$max' step='$step' class='form-control $class' id='$id' value='$value' placeholder='Masukkan $label' $readonly $required $disabled>
                            ".form_error($name)."
                        </div>
                    </div>
                </div>
        ";
    }

    function inputSelect($label, $name, $option_array, $val_col, $text_col=null, $selected_val=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null, $div_class=null, $div_show=null){
        echo "
            
            <div class='row'>
                <div class='form-group $div_class' $div_show>
                    <label class='control-label col-sm-3'>$label</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                        <select name='$name' class='form-control $class' id='$id' $readonly $required $disabled>
                            <option value=''>--- Pilih $label ---</option>";
        

        for($i=0; $i<count($option_array); $i++){
            $selected = "";
            if($selected_val != null AND $option_array[$i][$val_col] == $selected_val){
                $selected = "selected";
            }

            if($text_col != null){
                echo "<option value='".$option_array[$i][$val_col]."' $selected>".$option_array[$i][$val_col]." - ".$option_array[$i][$text_col]."</option>";
            }else{
                echo "<option value='".$option_array[$i][$val_col]."' $selected>".$option_array[$i][$val_col]."</option>";
            }
        }

        echo "
                        </select>
                        ".form_error($name)."
                    </div>
                </div>
            </div>
        ";
    }

    function inputSelectize($label, $name, $option_array, $val_col, $text_col=null, $selected_val=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null, $div_class=null, $div_show=null){
        echo "
            <div class='row'>
                <div class='form-group $div_class' $div_show>
                    <label class='control-label col-sm-3'>$label</label>
                    <div class='col-sm-9'>
                        <select name='$name' class='form-control selectize $class' id='$id' $readonly $required $disabled>
                            <option value=''>--- Pilih $label ---</option>";
        

        for($i=0; $i<count($option_array); $i++){
            $selected = "";
            if($selected_val != null AND $option_array[$i][$val_col] == $selected_val){
                $selected = "selected";
            }

            if($text_col != null){
                echo "<option value='".$option_array[$i][$val_col]."' $selected>".$option_array[$i][$val_col]." - ".$option_array[$i][$text_col]."</option>";
            }else{
                echo "<option value='".$option_array[$i][$val_col]."' $selected>".$option_array[$i][$val_col]."</option>";
            }
        }

        echo "
                        </select>
                        ".form_error($name)."
                    </div>
                </div>
            </div>
        ";
    }

    function inputDate($label, $name, $value=null, $pattern=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null){
        echo "
                <div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input name='$name' class='form-control datepicker $class' id='$id' value='$value' $pattern placeholder='Pilih $label' $readonly $required $disabled onkeydown='return false'>
                            ".form_error($name)."
                        </div>
                    </div>
                </div>
        ";
    }

    function inputTextArea($label, $name, $cols, $rows, $maxlength=null, $value=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null){
        echo "
                <div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <textarea name='$name' cols='$cols' rows='$rows' maxlength='$maxlength' class='form-control $class' id='$id' $readonly $required $disabled>$value</textarea>
                            ".form_error($name)."
                        </div>
                    </div>
                </div>
        ";
    }

    function inputEditor($label, $name, $cols, $rows, $maxlength=null, $value=null, $required='required', $readonly=null, $disabled=null, $class=null){
        echo "
                <div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <textarea name='$name' cols='$cols' rows='$rows' maxlength='$maxlength' class='form-control $class' id='text-editor' $readonly $required $disabled>$value</textarea>
                            ".form_error($name)."
                        </div>
                    </div>
                </div>
        ";
    }

    function inputFile($label, $name, $datatype, $error_msg=null, $value=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null){
        echo "
                <div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input name='$name' type='file' accept='$datatype' value='$value' class='$class' id='$id' $readonly $required $disabled>
                            $error_msg
                        </div>
                    </div>
                </div>
        ";
    }

    function inputSepNum($label, $name, $maxlength=null, $value=null, $pattern=null, $required='required', $readonly=null, $disabled=null, $class=null, $id=null){
        echo "
                <div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>$label</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input type='text' name='$name' class='form-control currency $class' id='$id' $pattern maxlength='$maxlength' value='$value' placeholder='Masukkan $label' $readonly $required $disabled>
                            ".form_error($name)."
                        </div>
                    </div>
                </div>
        ";
    }

    /*
    |  PAGE CONTENT GENERATOR HELPER
    |  Berfungsi untuk menggenerate kode HTML untuk pembuatan konten
    */
    function generateDaftar($header_array, $col_name_array, $data_array, $par_col_array, $insert_url, $update_url, $del_url, $spec_format_array=null, $update_delete=TRUE, $view_url=null){
        $header = "";
        for($i=0; $i<count($header_array); $i++){
            $header .= "<th>".$header_array[$i]."</th>";
        }

        $header .= "<th></th>";

        echo "
        <div class='row'>
            <div class='col-xs-12'>
                <div class='box'>
                    <div class='box-header'>";
        
        if($update_delete==TRUE){
            echo "
                        <a href='".site_url($insert_url)."' class='btn btn-primary pull-right'>
                            <i class='fa fa-plus-circle'></i> Add
                        </a>
            ";
        }
        echo "
                    </div>
                    <div class='box-body'>
                        <table class='table table-bordered table-striped DataTable'>
                            <thead>
                                <tr>
                                    $header
                                </tr>
                            </thead>
                <tbody>";
                        for($i=0; $i<count($data_array); $i++){
                            echo "<tr>";
                            for($x=0; $x<count($col_name_array); $x++){
                                if(ISSET($spec_format_array[$col_name_array[$x]])){
                                    echo "<td>".generateFormat($spec_format_array[$col_name_array[$x]], $data_array[$i][$col_name_array[$x]])."</td>";
                                }else{
                                    echo "<td>".$data_array[$i][$col_name_array[$x]]."</td>";
                                }
                            }

                            // parcolarray
                            $par_link = "";
                            for($y=0; $y<count($par_col_array); $y++){
                                $par_link .= $data_array[$i][$par_col_array[$y]]."/";
                            }

                            if($update_delete == TRUE){
                                echo "<td> <a href='".site_url($update_url).$par_link."' class='btn btn-primary fa fa-pencil'> </a> <a href='".site_url($del_url).$par_link."' class='btn btn-danger fa fa-trash tbl-delete'> </a> </td>";
                            }else{
                                echo "<td><a href='".site_url($view_url).$par_link."' class='btn btn-primary fa fa-eye'></a></td>";
                            }
                            echo "</tr>";
                        }
        echo   "</tbody>
            </table>
        </div></div></div></div>
        ";
    }

    function generateDaftar2($header_array, $col_name_array, $data_array, $par_col_array, $insert_url=null, $update_url=null, $del_url=null, $view_url=null, $spec_format_array=null, $action=TRUE){
        $header = "";
        for($i=0; $i<count($header_array); $i++){
            $header .= "<th>".$header_array[$i]."</th>";
        }

        if($action){
            $header .= "<th></th>";
        }

        echo "
        <div class='row'>
            <div class='col-xs-12'>
                <div class='box'>
                    <div class='box-header'>";
        
        if($insert_url != null){
            echo "
                        <a href='".site_url($insert_url)."' class='btn btn-primary pull-right'>
                            <i class='fa fa-plus-circle'></i> Tambah
                        </a>
            ";
        }
        echo "
                    </div>
                    <div class='box-body sai-container-overflow-x'>
                        <table class='table table-bordered table-striped DataTable'>
                            <thead>
                                <tr>
                                    $header
                                </tr>
                            </thead>
                <tbody>";
                        for($i=0; $i<count($data_array); $i++){
                            echo "<tr>";
                            for($x=0; $x<count($col_name_array); $x++){
                                if(ISSET($spec_format_array[$col_name_array[$x]])){
                                    echo "<td>".generateFormat($spec_format_array[$col_name_array[$x]], $data_array[$i][$col_name_array[$x]])."</td>";
                                }else{
                                    echo "<td>".$data_array[$i][$col_name_array[$x]]."</td>";
                                }
                            }

                            if($action){
                                // parcolarray
                                $par_link = "";
                                for($y=0; $y<count($par_col_array); $y++){
                                    $par_link .= $data_array[$i][$par_col_array[$y]]."/";
                                }
                                
                                echo "<td>";
                                if($view_url != null){
                                    echo "<a href='".site_url($view_url).$par_link."' class='btn btn-success btn-sm'><i class='fa fa-eye'></i> Detail</a> &nbsp;";
                                }

                                if($update_url != null){
                                    echo "<a href='".site_url($update_url).$par_link."' class='btn btn-warning btn-sm'><i class='fa fa-pencil'></i> Edit</a> &nbsp;";
                                }

                                if($del_url != null){
                                    echo "<a href='".site_url($del_url).$par_link."' class='btn btn-danger btn-sm tbl-delete'><i class='fa fa-trash'></i> Hapus</a>";
                                }

                                echo "</td>";
                            }

                            echo "</tr>";
                        }
        echo   "</tbody>
            </table>
        </div></div></div></div>
        ";
    }

    function generateDaftarAjax($tbl_array, $table_selector){
        // format $tbl_array = array(array('Tambah', url, 'color', 'icon'))
        // apabila color tidak diisi (bukan ''), akan digunakan color default 'primary'
        // apabila icon tidak diisi (bukan ''), akan digunakan icon default 'fa fa-plus-circle'
        
        $tbl = '';
        for($i=0; $i<count($tbl_array); $i++){
            $color = ((count($tbl_array[$i]) > 2) ? $tbl_array[$i][2] : 'primary');
            $icon = ((count($tbl_array[$i]) > 3) ? $tbl_array[$i][3] : 'fa fa-plus-circle');
            $tbl .= "
                <a href='".$tbl_array[$i][1]."' class='btn btn-$color pull-right'>
                    <i class='$icon'></i> ".$tbl_array[$i][0]."
                </a>
            ";
        }

        $class = '';
        $id = '';
        if($table_selector != null){
            $x = explode(",", $table_selector);
            for($i=0; $i<count($x); $i++){
                if(substr($x[$i], 0, 1) == '.'){
                    $class .= ' '.substr($x[$i], 1, strlen($x[$i]));
                }else{
                    $id = substr($x[$i], 1, strlen($x[$i]));
                }
            }
        }

        echo "
            <div class='row'>
                <div class='col-xs-12'>
                    <div class='box'>
                        <div class='box-header'>
                            $tbl
                            <div id='$id' class='$class'></div>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

    function ubah_periode($periode)
	{
	  $bulan=substr($periode,4,2);
	  $tahun=substr($periode,0,4);
	  switch ($bulan) 
	  {
	    case "01":
	      $tmp="Januari";
	      break;
		case "02":
		  $tmp="Februari";
	      break;
		case "03":
	      $tmp="Maret";
	      break;
		case "04":
	      $tmp="April";
	      break;
		case "05":
	      $tmp="Mei";
	      break;
		case "06":
	      $tmp="Juni";
	      break;
		case "07":
	      $tmp="Juli";
	      break;
		case "08":
	      $tmp="Agustus";
	      break;  
		case "09":
	      $tmp="September";
	      break;  
		case "10":
	      $tmp="Oktober";
	      break;  
		case "11":
	      $tmp="November";
	      break;  
		case "12":
	      $tmp="Desember";
	      break;  
		case "13":
	      $tmp="Desember 2";
	      break;    
	     case "14":
	      $tmp="Desember 3";	      
	      break;    
	    case "15":
	      $tmp="Desember 4";	      
	      break;    
	    case "16":
	      $tmp="Desember 5";	      
	      break;    
	  }
	  return $tmp." ".$tahun;
	}

    /*
    function saiDaftar($header_col_array, $data_array, $par_col_array, $insert_url=null, $update_url=null, $del_url=null, $view_url=null, $spec_format_array=null, $action=TRUE){
        $header = "";
        for($i=0; $i<count($header_array); $i++){
            $header .= "<th>".$header_array[$i]."</th>";
        }

        if($action){
            $header .= "<th></th>";
        }

        echo "
        <div class='row'>
            <div class='col-xs-12'>
                <div class='box'>
                    <div class='box-header'>";
        
        if($insert_url != null){
            echo "
                        <a href='".site_url($insert_url)."' class='btn btn-primary pull-right'>
                            <i class='fa fa-plus-circle'></i> Tambah
                        </a>
            ";
        }
        echo "
                    </div>
                    <div class='box-body sai-container-overflow-x'>
                        <table class='table table-bordered table-striped DataTable'>
                            <thead>
                                <tr>
                                    $header
                                </tr>
                            </thead>
                <tbody>";
                        for($i=0; $i<count($data_array); $i++){
                            echo "<tr>";
                            for($x=0; $x<count($col_name_array); $x++){
                                if(ISSET($spec_format_array[$col_name_array[$x]])){
                                    echo "<td>".generateFormat($spec_format_array[$col_name_array[$x]], $data_array[$i][$col_name_array[$x]])."</td>";
                                }else{
                                    echo "<td>".$data_array[$i][$col_name_array[$x]]."</td>";
                                }
                            }

                            if($action){
                                // parcolarray
                                $par_link = "";
                                for($y=0; $y<count($par_col_array); $y++){
                                    $par_link .= $data_array[$i][$par_col_array[$y]]."/";
                                }
                                
                                echo "<td>";
                                if($view_url != null){
                                    echo "<a href='".site_url($view_url).$par_link."' class='btn btn-success btn-sm'><i class='fa fa-eye'></i> Detail</a> &nbsp;";
                                }

                                if($update_url != null){
                                    echo "<a href='".site_url($update_url).$par_link."' class='btn btn-warning btn-sm'><i class='fa fa-pencil'></i> Edit</a> &nbsp;";
                                }

                                if($del_url != null){
                                    echo "<a href='".site_url($del_url).$par_link."' class='btn btn-danger btn-sm tbl-delete'><i class='fa fa-trash'></i> Hapus</a>";
                                }

                                echo "</td>";
                            }

                            echo "</tr>";
                        }
        echo   "</tbody>
            </table>
        </div></div></div></div>
        ";
    }

        // function generateDaftarAjax($ajax_source, $col_name_array, $par_col_array, $table_id='DataTable-auto', $insert_url=null, $update_url=null, $del_url=null, $view_url=null, $spec_format_array=null){

                                // if($view_url != null){
                                //     echo "<a href='".site_url($view_url).$par_link."' class='btn btn-success btn-sm'><i class='fa fa-eye'></i> Detail</a> &nbsp;";
                                // }

                                // if($update_url != null){
                                //     echo "<a href='".site_url($update_url).$par_link."' class='btn btn-warning btn-sm'><i class='fa fa-pencil'></i> Edit</a> &nbsp;";
                                // }

                                // if($del_url != null){
                                //     echo "<a href='".site_url($del_url).$par_link."' class='btn btn-danger btn-sm tbl-delete'><i class='fa fa-trash'></i> Hapus</a>";
                                // }
        //     echo "
        //     <div class='row'>
        //         <div class='col-xs-12'>
        //             <div class='box'>
        //                 <div class='box-header'>";
            
        //     if($insert_url != null){
        //         echo "
        //                     <a href='".site_url($insert_url)."' class='btn btn-primary pull-right'>
        //                         <i class='fa fa-plus-circle'></i> Tambah
        //                     </a>
        //         ";
        //     }
        //     echo "
        //                 </div>
        //                 <div class='box-body'>
        //                     <table class='table table-bordered table-striped DataTable' id='$table_id'>
        //                     </table>
        //                 </div>
        //             </div>
        //         </div>
        //     </div>

        //     <script async type='text/javascript'>
        //         $('#$table_id').DataTable({
        //             // 'processing': true,
        //             // 'serverSide': true,
        //             'ajax': '".site_url($ajax_source_url)."',        
        //             'columns': [
        //                 { 'data': 'kode_klp' },
        //                 { 'data': 'nama' }
        //             ]
        //         });
        //     </script>
        //     ";
        // }

        function generateLaporanAjax(){

        }

    */

    function ubah_periode3($periode)
	{
      $tmpx=explode(" ",$periode);
	  $bulan=$tmpx[0];
	  $tahun=$tmpx[1];
	  switch ($bulan) 
	  {
	    case "Januari":
	      $tmp="01";
	      break;
		case "Februari":
		  $tmp="02";
	      break;
		case "Maret":
	      $tmp="03";
	      break;
		case "April":
	      $tmp="04";
	      break;
		case "Mei":
	      $tmp="05";
	      break;
		case "Juni":
	      $tmp="06";
	      break;
		case "Juli":
	      $tmp="07";
	      break;
		case "Agustus":
	      $tmp="08";
	      break;  
		case "September":
	      $tmp="09";
	      break;  
		case "Oktober":
	      $tmp="10";
	      break;  
		case "November":
	      $tmp="11";
	      break;  
		case "Desember":
	      $tmp="12";
	      break;  
		
	  }
	  return $tahun.$tmp;
    }
    
    function toMilyar($x) {
        $nil = $x / 1000000000;
        return number_format($nil,2,",",".") . " M";
    }

    function toJuta($x) {
        $nil = $x / 1000000;
        return number_format($nil,2,",",".") . " JT";
    }
?>