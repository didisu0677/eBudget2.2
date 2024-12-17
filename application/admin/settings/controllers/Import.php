<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends BE_Controller {
	var $controller = 'import';
	var $path = 'settings/import/';
	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['controller'] = $this->controller;
		render($data);
	}

	function data() {
		$config['access_view']   = false;
        $config['access_edit']  = false;
        $config['button'][]     = button_serverside('btn-info','btn-detail',['fa-search',lang('detil'),true],'act-detil');

        $data = data_serverside($config);
		render($data,'json');
	}

	function detail($id){
        $data = get_data('tbl_trx_import_core','id',$id)->row_array();
        if(isset($data['id'])) {
            render($data,'layout:false view:'.$this->path.'detail');
        } else echo lang('tidak_ada_data');
    }


	function import() {
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '100000');

		$this->load->dbforge();

		$bulan = post('periode_import');
		$tahun = post('tahun_import');
		$file = post('fileimport');
        $currency   = post('currency');

        $dt_currency = get_currency($currency);

		$periode  = $tahun . sprintf("%02d", $bulan);
		$coa = get_data('tbl_m_bottomup_besaran','is_active',1)->result();
        $id_usulan_bf1          = [];
        $glwnco = [];
        foreach($coa as $c) {
            $id_usulan_bf1[] = $c->id;
            $glwnco[] = $c->coa;
        }    

        $file = post('fileimport');

        delete_data('tbl_cek_data_cabang');

        $tahunSebelum = $tahun - 1;
        $tahunSebelum2 = $tahun - 2;
        $tahunSebelum3 = $tahun - 3;

        $dbTahun 	= "tbl_history_".$tahun;
        $dbTahun1	= "tbl_history_".$tahunSebelum;
        $dbTahun2	= "tbl_history_".$tahunSebelum2;
        $dbTahun3	= "tbl_history_".$tahunSebelum3;

        $columnNameLast1 = '';
        $columnNameLast2 = '';
        $columnNameLast3 = '';

        if($this->db->table_exists($dbTahun1)){
        	    $columnNameLast1 = $this->db->list_fields($dbTahun1);
        }
    
    	if($this->db->table_exists($dbTahun2)){
        	    $columnNameLast2 = $this->db->list_fields($dbTahun2);
        }
    
    	if($this->db->table_exists($dbTahun3)){
        	    $columnNameLast3 = $this->db->list_fields($dbTahun3);
        }
    


        if ($this->db->table_exists($dbTahun))
		{


			if($file){
        		delete_data($dbTahun,['tahun'=>$tahun,'bulan'=>$bulan]);
			}

		}else {

			$fields = array(
			        'tahun' => array(
			                'type' => 'INT',
			                'constraint' => 4,
			                'null' => TRUE,
			        ),
			        'coa' => array(
			                'type' => 'INT',
			                'constraint' => 6,
			                'null' => TRUE,
			        ),
			        'new_coa' => array(
			                'type' => 'INT',
			                'constraint' => 6,
			                'null' => TRUE,
			        ),
			        'gwlsbi' => array(
			                'type' => 'INT',
			                'constraint' => 6,
			                'null' => TRUE,
			        ),
			        'gwlnob' => array(
			                'type' => 'INT',
			                'constraint' => 6,
			                'null' => TRUE,
			        ),
			         'glwnco' => array(
			                'type' => 'INT',
			                'constraint' => 8,
			                'null' => TRUE,
			        ),
			         'glwcab' => array(
			                'type' => 'INT',
			                'constraint' => 6,
			                'null' => TRUE,
			        ),
			         'glwbtj' => array(
			                'type' => 'INT',
			                'constraint' => 14,
			                'null' => TRUE,
			        ),
			         'bulan' => array(
			                'type' => 'TINYINT',
			                'constraint' => 2,
			                'null' => TRUE,
			        ),
			         'glwdat' => array(
			                'type' => 'INT',
			                'constraint' => 7,
			                'null' => TRUE,
			        ),
			         'account_name' => array(
			                'type' => 'VARCHAR',
			                'constraint' => 20,
			                'null' => TRUE,
			        ),
			);

			$this->dbforge->add_field($fields);

			$this->dbforge->create_table($dbTahun);
		
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

				for($a=8; $a <= $colNumber; $a+=3){
                  	$kodeCabang = [
                  		'kode_cabang' => substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),4,3),
                  		'status'	=> 0
                  	];
                  	insert_data('tbl_cek_data_cabang',$kodeCabang);
               	}
               
			


				for($row=2; $row<=$highestRow; $row++){

					if($worksheet->getCellByColumnAndRow(0, 2)->getValue() != $periode) {
	             		echo $worksheet->getCellByColumnAndRow(0, 2)->getValue();
	             		echo "Preiode = ".$periode;
						$response = [
							'status' => 'failed',
							'message' => 'Data tidak sesuai dengan periode yang dipilih',
						];
						@unlink($file);
						render($response,'json');
						die;
					}


					$columnName = $this->db->list_fields($dbTahun);


					$tempData = [];
					$tempData['glwdat']                 = $worksheet->getCellByColumnAndRow(0, 2)->getValue();
                    $tempData['gwlsbi']                = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $tempData['coa']                = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $tempData['glwnco']                = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $tempData['bulan']                = substr($worksheet->getCellByColumnAndRow(0, 2)->getValue(),4,6);
                    $tempData['tahun']                = substr($worksheet->getCellByColumnAndRow(0, 2)->getValue(),0,4);
                    $tempData['new_coa']                = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $tempData['account_name']          = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

                  
                    
                      for($a=8; $a <= $colNumber; $a+=3){
                      		$b = $a - 1;
                   		   	$c = $a - 2;


                   	
                   			if(!empty(substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),4,3))){
               					$val = $worksheet->getCellByColumnAndRow($a,$row)->getCalculatedValue();
                   				$val = (float) $val * $dt_currency['nilai'];
               					$tempData['TOT_'.substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),4,3)] = $val;

               					$val = $worksheet->getCellByColumnAndRow($b,$row)->getCalculatedValue();
               					$val = (float) $val * $dt_currency['nilai'];
	            				$tempData['VAL_'.substr($worksheet->getCellByColumnAndRow($b,1)->getValue(),4,3)]	= $val;
	            				
	            				$val = $worksheet->getCellByColumnAndRow($c,$row)->getCalculatedValue();
	            				$val = (float) $val * $dt_currency['nilai'];
	            				$tempData['IDR_'.substr($worksheet->getCellByColumnAndRow($c,1)->getValue(),4,3)]	= $val;
                   			}
        
    					

            				$getColumn = array_search(substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),0,7), $columnName);
            				
         //    				if($this->db->table_exists($dbTahun1)){
        	// 					$getColumnLast1 = array_search(substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),0,7), $columnNameLast1);

        	// 					if(empty($getColumnLast1)){


	        //         				$fields1 = array(
								 //        'TOT_'.substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),4,3) => array(
								 //                'type' => 'BIGINT',
								 //                'constraint' => 20,
								 //                'null' => TRUE,
								 //        ),
								 //        'VAL_'.substr($worksheet->getCellByColumnAndRow($b,1)->getValue(),4,3) => array(
								 //                'type' => 'BIGINT',
								 //                'constraint' => 20,
								 //                'null' => TRUE,
								 //        ),
								 //        'IDR_'.substr($worksheet->getCellByColumnAndRow($c,1)->getValue(),4,3) => array(
								 //                'type' => 'BIGINT',
								 //                'constraint' => 20,
								 //                'null' => TRUE,
								 //        )
									// );

	                            				
									// $this->dbforge->add_column($dbTahun1,$fields1);

         //    					}
        	// 				}


        	// 				if($this->db->table_exists($dbTahun2)){
        	// 					$getColumnLast2 = array_search(substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),0,7), $columnNameLast2);

        	// 					if(empty($getColumnLast2)){


	        //         				$fields2 = array(
								 //        'TOT_'.substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),4,3) => array(
								 //                'type' => 'BIGINT',
								 //                'constraint' => 20,
								 //                'null' => TRUE,
								 //        ),
								 //        'VAL_'.substr($worksheet->getCellByColumnAndRow($b,1)->getValue(),4,3) => array(
								 //                'type' => 'BIGINT',
								 //                'constraint' => 20,
								 //                'null' => TRUE,
								 //        ),
								 //        'IDR_'.substr($worksheet->getCellByColumnAndRow($c,1)->getValue(),4,3) => array(
								 //                'type' => 'BIGINT',
								 //                'constraint' => 20,
								 //                'null' => TRUE,
								 //        )
									// );

	                            				
									// $this->dbforge->add_column($dbTahun2,$fields2);

         //    					}
        	// 				}


        	// 				if($this->db->table_exists($dbTahun3)){
        	// 		    		$columnNameLast3 = $this->db->list_fields($dbTahun3);
        	// 					$getColumnLast3 = array_search(substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),0,7), $columnNameLast3);

        	// 					if(empty($getColumnLast1)){


	        //         				$fields3 = array(
								 //        'TOT_'.substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),4,3) => array(
								 //                'type' => 'BIGINT',
								 //                'constraint' => 20,
								 //                'null' => TRUE,
								 //        ),
								 //        'VAL_'.substr($worksheet->getCellByColumnAndRow($b,1)->getValue(),4,3) => array(
								 //                'type' => 'BIGINT',
								 //                'constraint' => 20,
								 //                'null' => TRUE,
								 //        ),
								 //        'IDR_'.substr($worksheet->getCellByColumnAndRow($c,1)->getValue(),4,3) => array(
								 //                'type' => 'BIGINT',
								 //                'constraint' => 20,
								 //                'null' => TRUE,
								 //        )
									// );

	                            				
									// $this->dbforge->add_column($dbTahun3,$fields3);

         //    					}
        	// 				}
            				
            			

                			if(empty($getColumn) && !empty(substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),4,3))){
                				

                				$fields = array(
							        'TOT_'.substr($worksheet->getCellByColumnAndRow($a,1)->getValue(),4,3) => array(
							                'type' => 'BIGINT',
							                'constraint' => 20,
							                'null' => TRUE,
							        ),
							        'VAL_'.substr($worksheet->getCellByColumnAndRow($b,1)->getValue(),4,3) => array(
							                'type' => 'BIGINT',
							                'constraint' => 20,
							                'null' => TRUE,
							        ),
							        'IDR_'.substr($worksheet->getCellByColumnAndRow($c,1)->getValue(),4,3) => array(
							                'type' => 'BIGINT',
							                'constraint' => 20,
							                'null' => TRUE,
							        )
								);

                            				
								$this->dbforge->add_column($dbTahun,$fields);
                			
	            				
	            				
                			} // if

                			
                		} // for

                		$save = insert_data($dbTahun,$tempData);
                				// insert_data('tbl_cek_data_cabang',$kodeCabang);
								if($save) $d++;
	               
               		} // for row
            	} 
        
	        if($d > 1) {

	        	delete_data('tbl_trx_import_core','periode_import',$periode);

	        	$temp_file  = basename($file);
	            $temp_dir   = str_replace($temp_file, '', $file);
	            $e          = explode('.', $temp_file);
	            $ext        = $e[count($e)-1];
	            $new_name   = md5(uniqid()).'.'.$ext;
	            $dest       = dir_upload('import').$new_name;
	            if(!@copy($file,$dest))
	               $file = '';
	            else {
	                delete_dir(FCPATH . $temp_dir);
	                $file = $new_name;
	            }

	        	$data['periode_import'] = $periode;
				$data['tanggal_import'] = date('Y-m-d');
				$data['currency']       = $dt_currency['nama'];
            	$data['currency_value'] = $dt_currency['nilai'];
            	$data['file']      = $file;		
				$data['create_at'] = date('Y-m-d H:i:s');
				$data['create_by'] = user('nama');
				$data['update_by'] = user('nama');
				$data['update_at'] = date('Y-m-d H:i:s');
				$save = insert_data('tbl_trx_import_core',$data);
				// @unlink($file);
	        }

			$response = [
				'status' => 'success',
				'message' => $d.' '.lang('data_berhasil_disimpan').'.'
			];



			render($response,'json');
		}
	}

}