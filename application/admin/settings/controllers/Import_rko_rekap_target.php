<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_rko_rekap_target extends BE_Controller {

	function __construct() {
		parent::__construct();
         $this->kode_anggaran  = user('kode_anggaran');
	}

	function index() {
		render();
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_history_import_target_rekap','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_history_import_target_rekap',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_history_import_target_rekap','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['nama' => 'nama','kode_anggaran' => 'kode_anggaran','tahun' => 'tahun','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_import_rko_rekap_target',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '100000');

        $this->load->dbforge();

        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->result_array(); 
        $getBulanReal = $tahun_anggaran[0]['bulan_terakhir_realisasi'] + 1;

        $file = post('fileimport');

        // $kode_anggaran = post('kode_anggaran');
        $kode_anggaran = $tahun_anggaran[0]['kode_anggaran'];
        $ket_anggaran = $tahun_anggaran[0]['keterangan'];
        $jutaan = post('jutaan');

        $kali = 1000000;

        $data = array();
        $this->load->library('PHPExcel');
        
        if($file){

                $excelreader = new PHPExcel_Reader_Excel2007();
                $loadexcel = $excelreader->load($file); 
                $d = 0;

                // echo $loadexcel->getSheetCount();

                foreach($loadexcel->getWorksheetIterator() as $worksheet){
                
            

                $highestRow = $worksheet->getHighestRow();

                $highestColumn = $worksheet->getHighestColumn();
                $colNumber = PHPExcel_Cell::columnIndexFromString($highestColumn);
                
                
                $tahun = $worksheet->getCellByColumnAndRow(0, 2)->getValue();
                $tempData = array();

                for($row=3; $row<=$highestRow; $row++){

                    if(!empty($worksheet->getCellByColumnAndRow(0, 3)->getValue()) && is_numeric($worksheet->getCellByColumnAndRow(1, $row)->getValue())) {
                        $tempData = [];
                        $tempData['kode_cabang']            = sprintf("%03d",$worksheet->getCellByColumnAndRow(1, $row)->getValue());
                        $tempData['kode_anggaran']          = $kode_anggaran;
                        $tempData['keterangan_anggaran']    = $ket_anggaran;
                        $tempData['nama']                   = $worksheet->getCellByColumnAndRow(0, 1)->getValue();
                        $tempData['tahun']                  = $worksheet->getCellByColumnAndRow(0, 2)->getValue();
                        $string = explode("/", $worksheet->getCellByColumnAndRow(3, 4)->getValue());
                        $tempData['tahun_core']            = "20".$string[1];

                        $b = 4;
                        for($a=1;$a <=12; $a++){
                            $b++;
                            $c = sprintf("%02d",$a);
                            $tempData['B_'.$c] =  $worksheet->getCellByColumnAndRow($b, $row)->getValue() * $kali;
                        }
                    
                    $cek = get_data('tbl_rko_target_rekap',[
                        'select'            => 'id',
                        'where'             => [
                            'kode_cabang'   =>  sprintf("%03d",$worksheet->getCellByColumnAndRow(1, $row)->getValue()),
                            'kode_anggaran' => $kode_anggaran,
                            'nama'          => $worksheet->getCellByColumnAndRow(0, 1)->getValue(),
                            'tahun'         => $worksheet->getCellByColumnAndRow(0, 2)->getValue()
                        ]
                    ])->result_array();    

                    if(empty($cek)){
                        $save = insert_data('tbl_rko_target_rekap',$tempData);
                       
                        // @unlink($file);
                        if($save) $d++;
                    }else {
                         $save = update_data('tbl_rko_target_rekap',$tempData,[
                            'id'    => $cek[0]['id']
                         ]);
                        if($save) $d++;
                    }                    
                    }   

                }

                if($d > 0){
                        $data['nama']           = $worksheet->getCellByColumnAndRow(0,1)->getValue();
                        $data['kode_anggaran']  = $kode_anggaran;        
                        $data['tahun']          = $tahun;        
                        $data['create_at']      = date('Y-m-d H:i:s');
                        $data['create_by']      = user('nama');
                        $data['update_by']      = user('nama');
                        $data['update_at']      = date('Y-m-d H:i:s');
                        $save = insert_data('tbl_history_import_target_rekap',$data);
                }


            }

            @unlink($file);
            $response = [
                'status' => 'success',
                'message' => $d.' '.lang('data_berhasil_disimpan').'.'
            ];

            render($response,'json');
        }
    }

}