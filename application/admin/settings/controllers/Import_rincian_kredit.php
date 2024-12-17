<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_rincian_kredit extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_trx_import_rincian_kredit','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_trx_import_rincian_kredit',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_trx_import_rincian_kredit','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['tanggal_import' => 'tanggal_import','nama_db' => 'nama_db','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_import_rincian_kredit',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '100000');

		$this->load->dbforge();

		$kode_anggaran = user('kode_anggaran');

        $file = post('fileimport');

        $db 	= "tbl_m_rincian_kredit_".str_replace('-', '_', $kode_anggaran);


        if ($this->db->table_exists($db))
		{
        	$this->dbforge->drop_table($db);

        		$fields = array(
			        'kode' => array(
			                'type' => 'INT',
			                'constraint' => 4,
			                'null' => TRUE,
			        ),
			        'grup' => array(
			                'type' => 'VARCHAR',
			                'constraint' => 9,
			                'null' => TRUE,
			        ),
			        'keterangan' => array(
			                'type' => 'VARCHAR',
			                'constraint' => 60,
			                'null' => TRUE,
			        )
			);

			$this->dbforge->add_field($fields);

			$this->dbforge->create_table($db);


		}else {

			$fields = array(
			        'kode' => array(
			                'type' => 'INT',
			                'constraint' => 4,
			                'null' => TRUE,
			        ),
			        'grup' => array(
			                'type' => 'VARCHAR',
			                'constraint' => 9,
			                'null' => TRUE,
			        ),
			        'keterangan' => array(
			                'type' => 'VARCHAR',
			                'constraint' => 60,
			                'null' => TRUE,
			        )
			);

			$this->dbforge->add_field($fields);

			$this->dbforge->create_table($db);
		
		}
		
		
		

		$data = array();
		$this->load->library('PHPExcel');
		
		if($file){

				$excelreader = new PHPExcel_Reader_Excel2007();
				$loadexcel = $excelreader->load($file); 
				$d = 0;
				foreach($loadexcel->getWorksheetIterator() as $worksheet){
				
			

				$highestRow = $worksheet->getHighestRow();

				$highestColumn = $worksheet->getHighestColumn();
				$colNumber = PHPExcel_Cell::columnIndexFromString($highestColumn);
				
				

           		$tempData = array();              
			


				for($row=2; $row<=$highestRow; $row++){


					$columnName = $this->db->list_fields($db);


					$tempData = [];
					$tempData['kode']                 = remove_spaces($worksheet->getCellByColumnAndRow(0, $row)->getValue());
					$tempData['grup']                 = remove_spaces($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $tempData['keterangan']           = remove_spaces($worksheet->getCellByColumnAndRow(2, $row)->getValue());                  
                    
                    for($a=3; $a <= $colNumber; $a++){
                    	$val = remove_spaces($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    	if(strlen($val)>0){
                    		$tempData['TOT_'.sprintf("%03d",$worksheet->getCellByColumnAndRow($a,1)->getValue())] = $worksheet->getCellByColumnAndRow($a,$row)->getCalculatedValue();
                    	}
                    	
                    	$getColumn = array_search('TOT_'.sprintf("%03d",$worksheet->getCellByColumnAndRow($a,1)->getValue()), $columnName);
                    	if(empty($getColumn)){
                    		$fields = array(
						        'TOT_'.sprintf("%03d",$worksheet->getCellByColumnAndRow($a,1)->getValue()) => array(
						                'type' => 'DOUBLE',
						                'null' => TRUE,
						        ),
							);                    				
							$this->dbforge->add_column($db,$fields);
                    	}
                    }

                    $save = insert_data($db,$tempData);
						if($save) $d++;
        
    			} // for

        		
	               
            } // for row
        } 
        
	        if($d > 1) {

	        	delete_data('tbl_trx_import_rincian_kredit','nama_db',$db);

	        	$data['nama_db'] = $db;
				$data['tanggal_import'] = date('Y-m-d');				
				$data['create_at'] = date('Y-m-d H:i:s');
				$data['create_by'] = user('nama');
				$data['update_by'] = user('nama');
				$data['update_at'] = date('Y-m-d H:i:s');
				$save = insert_data('tbl_trx_import_rincian_kredit',$data);
				@unlink($file);
	        }

			$response = [
				'status' => 'success',
				'message' => $d.' '.lang('data_berhasil_disimpan').'.'
			];



			render($response,'json');
		}


	function export() {
		ini_set('memory_limit', '-1');
		$arr = ['tanggal_import' => '-dTanggal','nama_db' => 'Nama','is_active' => 'Aktif'];
		$data = get_data('tbl_trx_import_rincian_kredit')->result_array();
		$config = [
			'title' => 'data_import_rincian_kredit',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}