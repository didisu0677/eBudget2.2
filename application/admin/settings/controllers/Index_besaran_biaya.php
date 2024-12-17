<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class index_besaran_biaya extends BE_Controller {
	var $path = 'settings/index_besaran_biaya/';
    var $detail_tahun;
    var $kode_anggaran;
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran,
                'a.sumber_data'   => array(2,3)
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
    }

	function index() {
		$tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->result(); 
        $id_coa         = json_decode($tahun_anggaran[0]->id_coa_besaran);
        $coa            = get_data('tbl_m_coa a',[
                            'select' => 'distinct b.coa, a.glwdes, a.glwnco',
                            'join'   => 'tbl_indek_besaran b on b.coa = a.glwnco',
                        ])->result();
        // $coa            = get_data('tbl_m_coa','id', $id_coa)->result();
        $data['tahun']  = $tahun_anggaran;
        $data['coa']    = $coa;
        $data['detail_tahun']    = $this->detail_tahun;
       
        
        $page = $this->input->get('page');
        if(!$page):
        	render($data,'view:'.$this->path.'index');
        else:
        	render($data,'view:'.$this->path.$page);
        endif;
	}

	function data($anggaran="") {
		$data['coa'] = get_data('tbl_m_coa a',[
			'select'    => 'distinct a.id as getId ,a.glwnco,a.glwdes, b.*',
            'join'      => "tbl_indek_besaran_biaya b on a.glwnco = b.coa and b.kode_anggaran = '".$anggaran."'  type LEFT",
            'where'     => "a.glwnco like '56%' or a.glwnco like '57%' or a.glwnco like '58%'",
            'sort_by'  => 'a.glwnco'
		])->result();

        $data['detail_tahun'] = $this->detail_tahun;

        $response   = array(
            'table'     => $this->load->view('settings/index_besaran_biaya/table',$data,true),
            'data'     => $data,
        );
        render($response,'json');
	}



    function save_perubahan($anggaran="") {       

        $data   = json_decode(post('json'),true);

        // echo post('json');

        foreach($data as $getId => $record) {
			$cekId = $getId;

			// echo $id." - ".$cekId[1]."<br>";
            $cek  = get_data('tbl_indek_besaran_biaya a',[
                'select'    => 'a.id',
                'where'     => [
                    'a.coa'             => $cekId,
                    'a.kode_anggaran'   => $anggaran,
                ]
            ])->result_array();
     
            if(count($cek) > 0){
                update_data('tbl_indek_besaran_biaya', $record,'id',$cek[0]['id']);
	        }else {
	                $record['coa'] = $cekId;
	                $record['kode_anggaran'] = $anggaran;
	                insert_data('tbl_indek_besaran_biaya',$record);
	        } 
         } 
    }

	function get_data() {
		$data = get_data('tbl_indek_besaran','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_indek_besaran',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_indek_besaran','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode_cabang' => 'kode_cabang','coa' => 'coa','bulan1' => 'bulan1','bulan2' => 'bulan2','bulan3' => 'bulan3','bulan4' => 'bulan4','bulan5' => 'bulan5','bulan6' => 'bulan6','bulan7' => 'bulan7','bulan8' => 'bulan8','bulan9' => 'bulan9','bulan10' => 'bulan10','bulan11' => 'bulan11','bulan12' => 'bulan12','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_index_besaran',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}



	function export() {
		ini_set('memory_limit', '-1');
		$arr = ['kode_cabang' => 'Kode Cabang','coa' => 'Coa','bulan1' => 'Bulan1','bulan2' => 'Bulan2','bulan3' => 'Bulan3','bulan4' => 'Bulan4','bulan5' => 'Bulan5','bulan6' => 'Bulan6','bulan7' => 'Bulan7','bulan8' => 'Bulan8','bulan9' => 'Bulan9','bulan10' => 'Bulan10','bulan11' => 'Bulan11','bulan12' => 'Bulan12','is_active' => 'Aktif'];
		$data = get_data('tbl_indek_besaran')->result_array();
		$config = [
			'title' => 'data_index_besaran',
			'data' => $data,
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
        $kode_anggaran = "2020-01";
        $jutaan = post('jutaan');

        $kali = 1000000;

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

                    if(!empty($worksheet->getCellByColumnAndRow(5, $row)->getValue())) {
                        $tempData = [];
                        $tempData2 = [];
                        $tempData['kode_cabang']         = substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3);
                        $tempData['kode_anggaran']     = $kode_anggaran;
                        $tempData['coa']               = $worksheet->getCellByColumnAndRow(0, $row)->getValue();

                        $tempData2['kode_cabang']       = substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3);
                        $tempData2['kode_anggaran']     = $kode_anggaran;
                        $tempData2['coa']               = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $tempData2['parent_id']         = substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3);

                        $b = 6;
                        for($a=$getBulanReal;$a <=12; $a++){
                            $b++;
                            $tempData2['hasil'.$a] =  $worksheet->getCellByColumnAndRow($b, $row)->getValue() * $kali;
                        }
                        if($jutaan == 1){
                            $b = $b + 1;
                            for($c=1;$c<=12;$c++){
                                $b++;
                                 $tempData['hasil'.$c]    = $worksheet->getCellByColumnAndRow($b, $row)->getValue() * $kali;
                            }
                        }else{
                            $b = $b + 1;
                            for($c=1;$c<=12;$c++){
                                $b++;
                                 $tempData['hasil'.$c]    = $worksheet->getCellByColumnAndRow($b, $row)->getValue();
                            }
                        }
                    
                    $cek = get_data('tbl_indek_besaran',[
                        'select'    => 'id',
                        'where'     => [
                            'kode_cabang'     => substr($worksheet->getCellByColumnAndRow(4, $row)->getValue(),4,3),
                            'kode_anggaran' => $kode_anggaran,
                            'coa'           => $worksheet->getCellByColumnAndRow(18, $row)->getValue()
                        ]
                    ])->result_array();    

                     $cek2 = get_data('tbl_indek_besaran',[
                        'select'    => 'id',
                        'where'     => [
                            'kode_cabang'     => substr($worksheet->getCellByColumnAndRow(4, $row)->getValue(),4,3),
                            'kode_anggaran' => $kode_anggaran,
                            'coa'           => $worksheet->getCellByColumnAndRow(18, $row)->getValue(),
                            'parent_id'     => substr($worksheet->getCellByColumnAndRow(4, $row)->getValue(),4,3)
                        ]
                    ])->result_array();    

                    if(empty($cek)){
                         $save = insert_data('tbl_indek_besaran',$tempData);
                        if($save) $d++;
                    }else {
                         $save = update_data('tbl_indek_besaran',$tempData,[
                            'id'    => $cek[0]['id']
                         ]);
                        if($save) $d++;
                    }

                    if(empty($cek2)){
                        $save2 = insert_data('tbl_indek_besaran',$tempData2);
                        // if($save) $d++;
                    }else {
                         $save = update_data('tbl_indek_besaran',$tempData2,[
                            'id'    => $cek2[0]['id']
                         ]);
                        // if($save) $d++;
                    }


                    
                    }   

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



