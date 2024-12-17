<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index_besaran extends BE_Controller {
	var $path = 'settings/index_besaran/';
    var $detail_tahun;
    var $kode_anggaran;
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->row();
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => "a.kode_anggaran = '".$this->kode_anggaran."' and a.tahun = '".$anggaran->tahun_anggaran."' ",   
            'order_by' => 'tahun,bulan'
        ])->result_array();
        $this->detail_tahun2   = get_data('tbl_detail_tahun_anggaran a',[
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
        $data['detail_tahun2']    = $this->detail_tahun2;
        $data['sub_menu'] = $this->path.'sub_menu';
        
        $page = $this->input->get('page');
        if(!$page):
        	render($data,'view:'.$this->path.'index');
        else:
        	render($data,'view:'.$this->path.$page);
        endif;
	}

	function data($anggaran="", $coa="") {
		$data['cabang'][0] = get_data('tbl_m_cabang a',[
			'select'    => 'distinct a.id as getId ,a.nama_cabang, b.*',
            'join'      => "tbl_indek_besaran b on a.kode_cabang = b.kode_cabang  and b.coa ='".$coa."' and b.kode_anggaran = '".$anggaran."'  type LEFT",
            'where'     => 'a.is_active = 1 and a.parent_id = 0 and list_kanpus = 1',
            'sort_by'  => 'a.kode_cabang'
		])->result();
		foreach($data['cabang'][0] as $m0) {
			$data['cabang'][$m0->getId] = $this->get_parent_cabang($anggaran,$coa,$m0->getId);
			foreach($data['cabang'][$m0->getId] as $m1) {
				$data['cabang'][$m1->getId] = $this->get_parent_cabang($anggaran,$coa,$m1->getId);
				foreach($data['cabang'][$m1->getId] as $m2) {
					$data['cabang'][$m2->getId] = $this->get_parent_cabang($anggaran,$coa,$m2->getId);
				}
			}
		}

        $data['detail_tahun'] = $this->detail_tahun;

        $response   = array(
            'table'     => $this->load->view('settings/index_besaran/table',$data,true),
            'data'     => $data,
        );
        render($response,'json');
	}


    function dataHasil($anggaran="", $coa="") {
        $arrayMerge = [];
        $data['cabang'][0] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.id as getId ,a.nama_cabang, b.*',
            'join'      => "tbl_indek_besaran b on a.kode_cabang = b.kode_cabang and and b.parent_id = 0 and b.coa ='".$coa."' and b.kode_anggaran = '".$anggaran."'   type LEFT",
            'where'     => 'a.is_active = 1 and a.parent_id = 0  and list_kanpus = 1',
            'sort_by'  => 'a.kode_cabang'
        ])->result();

        $data['cabangLast'][0] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.id as getId ,a.nama_cabang, b.*',
            'join'      => "tbl_indek_besaran b on a.kode_cabang = b.kode_cabang  and b.parent_id not in(0) and b.coa ='".$coa."' and b.kode_anggaran = '".$anggaran."'   type LEFT",
            'where'     => 'a.is_active = 1 and a.parent_id = 0  and list_kanpus = 1',
            'sort_by'  => 'a.kode_cabang'
        ])->result();

        foreach($data['cabang'][0] as $m0) {
            $data['cabang'][$m0->getId] = $this->get_parent_cabang($anggaran,$coa,$m0->getId);
            foreach($data['cabang'][$m0->getId] as $m1) {
                $data['cabang'][$m1->getId] = $this->get_parent_cabang($anggaran,$coa,$m1->getId);
                foreach($data['cabang'][$m1->getId] as $m2) {
                    $data['cabang'][$m2->getId] = $this->get_parent_cabang($anggaran,$coa,$m2->getId);

                    $data['cabang2'][$m2->getId] = $this->get_parent_cabang2($anggaran,$coa,$m2->getId);

                }
            }
        }



        

        $data['detail_tahun'] = $this->detail_tahun;
        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->result_array(); 

        // echo json_encode($data['cabang2']);

        $response   = array(
            'table'     => $this->load->view('settings/index_besaran/tabel_hasil',$data,true),
            'data'     => $data,
        );
        render($response,'json');
    }

	 function dataOri($anggaran="", $coa=""){
        $data['cabang'][0] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>0, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'nama_cabang'))->result();
        $arrCodeCabang = array();
        foreach($data['cabang'][0] as $m0) {
            $data['cabang'][$m0->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m0->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'kode_cabang'))->result();
            foreach($data['cabang'][$m0->id] as $m1) {
                $data['cabang'][$m1->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m1->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'kode_cabang'))->result();
                foreach($data['cabang'][$m1->id] as $m2) {
                    $dataLevel4 = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m2->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'kode_cabang'))->result();
                    $data['cabang'][$m2->id] = $dataLevel4;

                    foreach ($dataLevel4 as $v) {
                        if(!in_array($v->kode_cabang,$arrCodeCabang)):
                            array_push($arrCodeCabang, $v->kode_cabang);
                        endif;
                    }
                }
            }
        }

        $dSum = get_data('tbl_bottom_up_form1',[
            'select' => 
                'sum(B_01) as B_01,sum(B_02) as B_02,sum(B_03) as B_03,sum(B_04) as B_04,sum(B_05) as B_05,sum(B_06) as B_06,sum(B_07) as B_07,sum(B_08) as B_08,sum(B_09) as B_09,sum(B_10) as B_10,sum(B_11) as B_11,sum(B_12) as B_12, kode_cabang,sumber_data,data_core,id',
            'where' => [
                'sumber_data'   => array(2,3,1),
                'kode_anggaran' => $anggaran,
                'coa'   => $coa,
                'kode_cabang' => $arrCodeCabang
            ],
            'group_by' => 'kode_cabang,sumber_data,data_core'
        ])->result_array();
        $data['dSum'] = $dSum;
        $data['detail_tahun'] = $this->detail_tahun2;

        $response   = array(
            'table'     => $this->load->view($this->path.'tabel_ori',$data,true),
        );
        render($response,'json');
    }

	function get_parent_cabang($anggaran="", $coa="", $parent_id){
		$data = get_data('tbl_m_cabang a',[
			'select'    => 'distinct a.id as getId ,a.nama_cabang, b.*',
            'join'      => "tbl_indek_besaran b on a.kode_cabang = b.kode_cabang and b.coa ='".$coa."' and b.kode_anggaran = '".$anggaran."' and b.parent_id = 0  type LEFT",
            'where'     => "a.is_active = 1 and a.list_kanpus = 1 and a.parent_id = '".$parent_id."'",
            'sort_by'  => 'a.kode_cabang'
		])->result();
		return $data;
	}

    function get_parent_cabang2($anggaran="", $coa="", $parent_id){
        $data = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.id as getId ,a.nama_cabang, b.*',
            'join'      => "tbl_indek_besaran b on a.kode_cabang = b.kode_cabang and b.coa ='".$coa."' and b.kode_anggaran = '".$anggaran."'  and b.parent_id not in(0)  type LEFT",
            'where'     => "a.is_active = 1 and a.list_kanpus = 1 and a.parent_id = '".$parent_id."'",
            'sort_by'  => 'a.kode_cabang'
        ])->result_array();
        return $data;
    }

    function save_perubahan($anggaran="", $coa = "") {       

        $data   = json_decode(post('json'),true);
        // $data   = json_encod(post('json'),true);

        echo post('json');

        foreach($data as $getId => $record) {
			$cekId = explode("-",$getId);
			$id = $cekId[0];

			// echo $id." - ".$cekId[1]."<br>";
            $cek  = get_data('tbl_indek_besaran a',[
                'select'    => 'a.id',
                'where'     => [
                    'a.coa'             => $coa,
                    'a.kode_anggaran'   => $anggaran,
                    'a.kode_cabang'       => $id,
                    'a.parent_id'		=> "not in('0')"
                ]
            ])->result_array();

            $cek2  = get_data('tbl_indek_besaran a',[
                'select'    => 'a.id',
                'where'     => [
                    'a.coa'             => $coa,
                    'a.kode_anggaran'   => $anggaran,
                    'a.kode_cabang'       => $id,
                    'a.parent_id'		=> "not in('".$id."')"
                ]
            ])->result_array();

            if(isset($cekId[1])):
                if($cekId[1] == 3){
                   
                    if(count($cek2) > 0){
                    update_data('tbl_indek_besaran', $record,'id',$cek[0]['id']);
                    }else {
                        $record['parent_id'] = "0";
                        $record['coa'] = $coa;
                        $record['kode_anggaran'] = $anggaran;
                        $record['kode_cabang'] = $id;
                        insert_data('tbl_indek_besaran',$record);
                    }
                }else {
                    
                    if(count($cek) > 0){
                    update_data('tbl_indek_besaran', $record,'id',$cek[0]['id']);
                    }else {
                        $record['parent_id'] = $id;
                        $record['coa'] = $coa;
                        $record['kode_anggaran'] = $anggaran;
                        $record['kode_cabang'] = $id;
                        insert_data('tbl_indek_besaran',$record);
                    }
                } 
            endif;
         } 
    }


    function save_perubahan_hasil($anggaran="",$glwnco="") {       

        $data   = json_decode(post('json'),true);

        // echo post('json');
        foreach($data as $getId => $record) {
            $cekId = explode("-",$getId);
            $sumber_data = $cekId[0];
            $cabang = $cekId[1];


            if($sumber_data == '2'){
                 $cek  = get_data('tbl_indek_besaran a',[
                    'select'    => 'a.id',
                    'where'     => [
                        'a.coa'             => $glwnco,
                        'a.kode_anggaran'   => $anggaran,
                        'a.kode_cabang'   => $cabang,
                        'a.parent_id'   => $cabang,
                    ]
                ])->result_array();
         
                if(count($cek) > 0){
                    update_data('tbl_indek_besaran', $record,'id',$cek[0]['id']);
                }else {
                        $record['coa'] = $glwnco;
                        $record['kode_anggaran'] = $anggaran;
                        $record['kode_cabang'] = $cabang;
                        $record['parent_id'] = $cabang;
                        insert_data('tbl_indek_besaran',$record);
                } 
            }else {

                 $cek  = get_data('tbl_indek_besaran a',[
                    'select'    => 'a.id',
                    'where'     => [
                        'a.coa'             => $glwnco,
                        'a.kode_anggaran'   => $anggaran,
                        'a.kode_cabang'   => $cabang,
                        'a.parent_id'   => '0',
                    ]
                ])->result_array();
         
                if(count($cek) > 0){
                    update_data('tbl_indek_besaran', $record,'id',$cek[0]['id']);
                }else {
                        $record['coa'] = $glwnco;
                        $record['kode_anggaran'] = $anggaran;
                        $record['kode_cabang'] = $cabang;
                        $record['parent_id'] = '0';
                        insert_data('tbl_indek_besaran',$record);
                } 

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
        error_reporting(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '100000');

        $this->load->dbforge();

        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->result_array(); 
        $getBulanReal = $tahun_anggaran[0]['bulan_terakhir_realisasi'] + 1;

        $file = post('fileimport');

        $kode_anggaran = $this->kode_anggaran;
        // $kode_anggaran = "2020-01";
        // $jutaan = post('jutaan');

        $kali = post('pilihan');

        // echo $kali;

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

                        // Parent Tahun anggaran
                        // $tempData2['kode_cabang']       = substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3);
                        // $tempData2['kode_anggaran']     = $kode_anggaran;
                        // $tempData2['coa']               = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        // $tempData2['parent_id']         = substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3);

                        $b = 5;
                        // for($a=$getBulanReal;$a <=12; $a++){
                        //     $b++;
                        //     $tempData2['hasil'.$a] =  $worksheet->getCellByColumnAndRow($b, $row)->getValue() * $kali;
                        // }

                        // Cek Jutaan
                        // if($jutaan == 1){
                        //     $b = $b + 1;
                        //     for($c=1;$c<=12;$c++){
                        //         $b++;
                        //          $tempData['hasil'.$c]    = $worksheet->getCellByColumnAndRow($b, $row)->getValue() * $kali;
                        //     }
                        // }else{
                            $b = $b + 1;
                            for($c=1;$c<=12;$c++){
                                $b++;
                                 $tempData['hasil'.$c]    = $worksheet->getCellByColumnAndRow($b, $row)->getValue() * $kali;
                            }
                        // Tutup cek jutaan
                        // }

                    
                    $cek = get_data('tbl_indek_besaran',[
                        'select'    => 'id',
                        'where'     => [
                            'kode_cabang'     => substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3),
                            'kode_anggaran' => $kode_anggaran,
                            'coa'           => $worksheet->getCellByColumnAndRow(0, $row)->getValue()
                        ]
                    ])->result_array();    

                    // parent [tahun anggaran]

                    //  $cek2 = get_data('tbl_indek_besaran',[
                    //     'select'    => 'id',
                    //     'where'     => [
                    //         'kode_cabang'     => substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3),
                    //         'kode_anggaran' => $kode_anggaran,
                    //         'coa'           => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                    //         'parent_id'     => substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3)
                    //     ]
                    // ])->result_array();    

                    if(empty($cek)){
                         $save = insert_data('tbl_indek_besaran',$tempData);
                        if($save) $d++;
                    }else {
                         $save = update_data('tbl_indek_besaran',$tempData,[
                            'id'    => $cek[0]['id']
                         ]);
                        if($save) $d++;
                    }

                    // parent [tahun anggaran]


                    // if(empty($cek2)){
                    //     $save2 = insert_data('tbl_indek_besaran',$tempData2);
                    //     // if($save) $d++;
                    // }else {
                    //      $save = update_data('tbl_indek_besaran',$tempData2,[
                    //         'id'    => $cek2[0]['id']
                    //      ]);
                    //     // if($save) $d++;
                    // }


                    
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



