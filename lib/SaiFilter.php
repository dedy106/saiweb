<?php

// defined('BASEPATH') OR exit('No direct script access allowed');
class SaiFilter{
    function __construct(){
        // $this->CI =& get_instance();
        // $this->db = $this->CI->db;
        // $this->input = $this->CI->input;
    }

    // ---------------------------------------- VIEW OBJECTS ----------------------------------
    // header
    public $v_title_text = 'Nama Laporan';
    public $v_title_icon = 'fa fa-file-text-o';

    // filter array
    public $v_form_id = '';
    public $v_filter_array = array();

    // page (untuk tracing view)
    public $v_page_count = 1;

    // remove overflow scroll
    public $v_overflow_scroll = TRUE;
    public $v_overflow_dir = 'all'; //x, y, all

    // --------------------------------------- SERVICE OBJECTS ---------------------------------
    // public 

    // -------------------------------------- METHODS FOR VIEW ----------------------------------

    public function setHeader($str, $icon='fa fa-file-text-o'){
        $this->v_title_text = $str;
        $this->v_title_icon = $icon;
    }

    // $identifier ditulis menggunakan identifier .nama-class atau #nama-id
    // akan menghasilkan array berupa $nama_var['id'], $nama_var['class'] atau $nama_var['attr']
    public function setAjaxForm($identifier){
        $this->v_form_id = parseIdentifier($identifier);
    }

    public function addFilter(
        // type : 0 untuk All, 1 untuk Range, 2 untuk Exact (=)
        // array : untuk populasi select option apabila input_type = 'select' [optional]
        // format penulisan array = array($variabel_array_daftar, 'index_value', 'Status tampil text [boolean]', 'index_text')
        // input_type diisi text, datalist, atau select
        $array=array(
            'label'=> null,
            'name'=> null,
            'type'=> array(0,1,2),
            'value' => '',
            'input_type' => 'text',
            'array'=> array(array(array('id'=>'', 'text'=>'')), 'id', TRUE, 'text'),
            'attr' => null
        )
    ){
        $this->v_filter_array[] = $array;
    }

    public function setPage($number_of_page){
        $this->v_page_count = $number_of_page;
    }

    public function draw(){
        // overflow scroll options
        if($this->v_overflow_scroll){
            if($this->v_overflow_dir == 'x'){
                $overflow_dir = 'sai-container-overflow-x';
            }else if($this->v_overflow_dir == 'y'){
                $overflow_dir = 'sai-container-overflow-y';
            }else{
                $overflow_dir = 'sai-container-overflow';
            }
        }else{
            $overflow_dir = '';
        }

        $filter_html = '';
        for($i=0; $i < count($this->v_filter_array); $i++){
            $input_attr = (ISSET($this->v_filter_array[$i]['attr']) ? $this->v_filter_array[$i]['attr'] : '');
            $input_value = (ISSET($this->v_filter_array[$i]['value']) ? $this->v_filter_array[$i]['value'] : '' );
            
            if($this->v_filter_array[$i]['input_type'] == 'select'){
                $option = "";
                foreach($this->v_filter_array[$i]['array'][0] as $data){
                    $option .= 
                    "<option value='".$data[$this->v_filter_array[$i]['array'][1]]."'".($data[$this->v_filter_array[$i]['array'][1]] == $this->v_filter_array[$i]['value'] ? 'selected' : '').">"
                    .$data[$this->v_filter_array[$i]['array'][1]].($this->v_filter_array[$i]['array'][2] ? " - ".$data[$this->v_filter_array[$i]['array'][3]] : '').
                    "</option>";
                }

                $filter_input_from = "
                <div class='col-xs-12'>
                    <select name='".$this->v_filter_array[$i]['name']."[]' class='form-control sai-rpt-filter-selectize ".$this->v_filter_array[$i]['name']."_from' ".$input_attr.">
                        ".$option."
                    </select>
                </div>";

                $filter_input_to = "
                <div class='col-xs-12'>
                    <select name='".$this->v_filter_array[$i]['name']."[]' class='form-control sai-rpt-filter-selectize ".$this->v_filter_array[$i]['name']."_to' ".$input_attr.">
                        ".$option."
                    </select>
                </div>";
            }else if($this->v_filter_array[$i]['input_type'] == 'text'){
                $filter_input_from = "
                <div class='col-xs-12'>
                    <input type='text' name='".$this->v_filter_array[$i]['name']."[]' class='form-control' id='".$this->v_filter_array[$i]['name']."_from' value='".$input_value."' ".$input_attr.">
                </div>";
                $filter_input_to = "
                <div class='col-xs-12'>
                    <input type='text' name='".$this->v_filter_array[$i]['name']."[]' class='form-control' id='".$this->v_filter_array[$i]['name']."_to' value='".$input_value."' ".$input_attr.">
                </div>";
            }else if($this->v_filter_array[$i]['input_type'] == 'datepicker-dmy' OR $this->v_filter_array[$i]['input_type'] == 'datepicker-ymd'){
                $filter_input_from = "
                <div class='col-xs-12'>
                    <input type='text' name='".$this->v_filter_array[$i]['name']."[]' class='form-control ".$this->v_filter_array[$i]['input_type']."' id='".$this->v_filter_array[$i]['name']."_from' value='".$input_value."' ".$input_attr.">
                </div>";
                $filter_input_to = "
                <div class='col-xs-12'>
                    <input type='text' name='".$this->v_filter_array[$i]['name']."[]' class='form-control' id='".$this->v_filter_array[$i]['name']."_to' value='".$input_value."' ".$input_attr.">
                </div>";
            }else{
                $filter_input_from = "
                <div class='col-xs-10'>
                    <input type='text' name='".$this->v_filter_array[$i]['name']."[]' class='form-control' id='".$this->v_filter_array[$i]['name']."_from'  value='".$input_value."' ".$input_attr.">
                </div>
                <div class='col-xs-2'>
                    <a class='sai-btn-circle'>
                        <i class='fa fa-search sai-rpt-data-list-search'></i>
                    </a>
                </div>";
                $filter_input_to = "
                <div class='col-xs-10'>
                    <input type='text' name='".$this->v_filter_array[$i]['name']."[]' class='form-control' id='".$this->v_filter_array[$i]['name']."_to'  value='".$input_value."' ".$input_attr.">
                </div>
                <div class='col-xs-2'>
                    <a class='sai-btn-circle'>
                        <i class='fa fa-search sai-rpt-data-list-search'></i>
                    </a>
                </div>";
            }
            
            $type_option = '';
            for($y=0; $y<count($this->v_filter_array[$i]['type']); $y++){
                if($this->v_filter_array[$i]['type'][$y] == 0){
                    $type_option .= "<option value='all' ".($y==0 ? 'selected' : '').">All</option>";
                }else if($this->v_filter_array[$i]['type'][$y] == 1){
                    $type_option .= "<option value='range' ".($y==0 ? 'selected' : '').">Range</option>";
                }else if($this->v_filter_array[$i]['type'][$y] == 2){
                    $type_option .= "<option value='exact' ".($y==0 ? 'selected' : '').">=</option>";
                }else{
                    $type_option .= "<option value='none' ".($y==0 ? 'selected' : '').">none</option>";
                }
            }

            $filter_html .= "
            <div class='row sai-rpt-filter-entry-row'>
                <div class='form-group'>
                    <div class='col-xs-4'>
                        <p class='sai-rpt-filter-entry-row-par' hidden>".$this->v_filter_array[$i]['name']."</p>
                        ".$this->v_filter_array[$i]['label']."
                    </div>
                    <div class='col-xs-2'>
                        <div class='row'>
                            <div class='col-xs-12'>
                                <select name='".$this->v_filter_array[$i]['name']."[]' class='form-control sai-rpt-filter-type' required>
                                    ".$type_option."
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='col-xs-3'>
                        <div class='row sai-rpt-filter-from' ".(($this->v_filter_array[$i]['type'][0] == 0) ? 'hidden' : '').">
                            ".$filter_input_from."
                        </div>
                    </div>
                    <div class='col-xs-3'>
                        <div class='row sai-rpt-filter-to' ".(($this->v_filter_array[$i]['type'][0] == 2 OR $this->v_filter_array[$i]['type'][0] == 0) ? 'hidden' : '').">
                            ".$filter_input_to."
                        </div>
                    </div>
                </div>
            </div>";
        }

        $page_html = "";
        for($x=0; $x<$this->v_page_count; $x++){
            $page_html .= "<div id='sai-rpt-filter-box-result-page-".($x+1)."' ".( $x > 0 ? 'hidden' : '')."></div>";
        }

        $html = "
        <div class='row'>
            <div class='col-md-12'>
                <div class='box box-default' id='sai-rpt-filter-box'>
                    <div class='box-header with-border'>
                        <h3 class='box-title'><i class='".$this->v_title_icon."'></i> ".$this->v_title_text."</h3>
                        <div class='box-tools pull-right'>
                            <a class='sai-btn-circle' id='sai-rpt-prev-page' title='Back'><i class='fa fa-arrow-circle-left'></i> 
                            </a>
                            <a class='sai-btn-circle' id='sai-rpt-export-excel' title='Excel'><i class='fa fa-file-excel-o'></i>
                            </a>
                            <a class='sai-btn-circle' id='sai-rpt-print' title='Print'><i class='fa fa-print'></i>
                            </a>
                            <a class='sai-btn-circle' data-widget='collapse'><i class='fa fa-minus'></i>
                            </a>
                        </div>
                    </div>
                    <div class='box-body' style=''>
                        <div class='row'>
                            <div class='col-md-12'>
                                <form id=".$this->v_form_id['id']."  class=".$this->v_form_id['class']."  ".$this->v_form_id['attr'].">
                                    ".$filter_html."
                                    <div class='row'>
                                        <div class='col-xs-4'>
                                        </div>
                                        <div class='col-xs-8'>
                                            <button type='submit' class='btn btn-primary' style='margin-left: 6px;'><i class='fa fa-search'></i> Preview</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12 ".$overflow_dir."'>
                            <p id='sai-rpt-active-page-number' hidden>0</p>
                            <p id='sai-rpt-number-of-page' hidden>".$this->v_page_count."</p>
                            ".$page_html."
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class='modal fade' id='sai-rpt-data-list-modal' tabindex='-1' role='dialog' aria-hidden='true'>
            <div class='modal-dialog' role='document' style='min-width:700px;'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>×</span></button>
                        <h4 class='modal-title'>Report Data List</h4>
                    </div>
                    <div class='modal-body'>
                        <div style='overflow-y: auto; max-height: 600px; overflow-x:hidden; padding-right:10px;' id='sai-rpt-data-list-modal-body'>
                        </div>
                    </div>
                </div>
            </div>
        </div>";

        echo $html;
    }

    public function debugFilter(){
        print_r($this->v_filter_array);
    }

    // ------------------------------------ METHODS FOR SERVICE --------------------------------

    public function getFilterStr($col_name_array, $db_col_name_array){
        $where = "";

        for($i = 0; $i<count($col_name_array); $i++){
            if(ISSET($_POST[$col_name_array[$i]][0])){
                if($_POST[$col_name_array[$i]][0] == 'range' AND ISSET($_POST[$col_name_array[$i]][1]) AND ISSET($_POST[$col_name_array[$i]][2])){
                    $where .= " ('".$db_col_name_array[$i]."' between '".$_POST[$col_name_array[$i]][1]."' AND '".$_POST[$col_name_array[$i]][2]."') ";
                }else if($_POST[$col_name_array[$i]][0] == 'exact' AND ISSET($_POST[$col_name_array[$i]][1])){
                    $where .= "'".$db_col_name_array[$i]."' = '".$_POST[$col_name_array[$i]][1];
                }
            }
        }

        return ($where != "" ? $where : null);

        // return $where;
    }

    public function disableOverflow(){
        $this->v_overflow_scroll = FALSE;
    }

    public function setOverflowDir($dir){
        $this->v_overflow_dir = $dir;
    }
}