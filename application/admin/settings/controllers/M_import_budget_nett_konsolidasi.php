<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_import_budget_nett_konsolidasi extends BE_Controller {
	var $controller 	= 'm_import_budget_nett_konsolidasi';
	var $arr_not_value  = ['#REF!'];
	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['controller'] = $this->controller;
		render($data);
	}

	function data() {
		$config['access_view']	= false;
		$config['access_edit']	= false;
		$config['button'][]		= button_serverside('btn-info','btn-detail',['fa-search',lang('detil'),true],'act-detil');

        $data = data_serverside($config);
        render($data,'json');
    }

    function detail($id){
    	$data = get_data('tbl_history_import_budget_nett_konsolidasi','id',$id)->row_array();
    	if(isset($data['id'])) {
			render($data,'layout:false');
		} else echo lang('tidak_ada_data');

    }

    function import() {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '100000');

        $this->load->dbforge();

        $kode_anggaran = user('kode_anggaran');
        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->result_array(); 
        $getBulanReal = $tahun_anggaran[0]['bulan_terakhir_realisasi'] + 1;

        $file       = post('fileimport');
        $currency   = post('currency');

        $dt_currency = get_currency($currency);

        $data = array();
        $this->load->library('PHPExcel');
        
        $kode_cabang_temp = '';
        $table = 'tbl_budget_nett';
        if($file){

                $excelreader = new PHPExcel_Reader_Excel2007();
                $loadexcel = $excelreader->load($file); 
                $d = 0;

                // echo $loadexcel->getSheetCount();

                foreach($loadexcel->getWorksheetIterator() as $worksheet){
                	$highestRow = $worksheet->getHighestRow();
                	$highestColumn = $worksheet->getHighestColumn();
                	$colNumber = PHPExcel_Cell::columnIndexFromString($highestColumn);

					for($row=3; $row<=$highestRow; $row++){

                    if(!empty($worksheet->getCellByColumnAndRow(0, 2)->getValue()) && is_numeric($worksheet->getCellByColumnAndRow(1, $row)->getValue())) {
                    	
                    	$kode_cabang = $worksheet->getCellByColumnAndRow(0, 2)->getValue();
                        $kode_cabang_temp = $kode_cabang;
                    	$coa 		 = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                    	$data_save = [
                    		'kode_cabang' 	=> $kode_cabang,
                    		'coa'			=> $coa,
                    		'kode_anggaran'	=> $kode_anggaran,
                    	];

                    	$b = 2;
                        for($a=1;$a <=12; $a++){
                            $b++;
                            $field = 'B_'.sprintf("%02d",$a);
                            $value = $worksheet->getCellByColumnAndRow($b, $row)->getCalculatedValue();
                            if(in_array($value, $this->arr_not_value)):
                            	$value = 0;
                            endif;
                            $value = (float) $value * $dt_currency['nilai'];
                            $data_save[$field] =  $value;
                        }

                        $cek = get_data($table,[
	                        'select'    => 'id',
	                        'where'     => [
	                            'kode_cabang'   => $kode_cabang,
	                            'kode_anggaran' => $kode_anggaran,
	                            'coa'           => $coa,
	                        ]
	                    ])->result_array();

	                    if(empty($cek)){
	                        $save = insert_data($table,$data_save);
	                        if($save) $d++;
	                    }else {
	                        $save = update_data($table,$data_save,[
	                            'id'    => $cek[0]['id']
	                        ]);
	                        if($save) $d++;
	                    }
                    }   

                }

            }


            $temp_file  = basename($file);
            $temp_dir   = str_replace($temp_file, '', $file);
            $e          = explode('.', $temp_file);
            $ext        = $e[count($e)-1];
            $new_name   = md5(uniqid()).'.'.$ext;
            $dest       = dir_upload('m_import_budget_nett_konsolidasi').$new_name;
            if(!@copy($file,$dest))
               $file = '';
            else {
                delete_dir(FCPATH . $temp_dir);
                $file = $new_name;
            }

            $data = [];
            $data['kode_anggaran']  = $kode_anggaran;
            $data['kode_cabang']    = $kode_cabang_temp;
            $data['file']           = $file;
            $data['currency']       = $dt_currency['nama'];
            $data['currency_value'] = $dt_currency['nilai'];       
            $data['create_at'] = date('Y-m-d H:i:s');
            $data['create_by'] = user('nama');
            $data['update_by'] = user('nama');
            $data['update_at'] = date('Y-m-d H:i:s');
            $save = insert_data('tbl_history_import_budget_nett_konsolidasi',$data);


            $response = [
                'status' => 'success',
                'message' => $d.' '.lang('data_berhasil_disimpan').'.'
            ];
            render($response,'json');
        }
    }

}