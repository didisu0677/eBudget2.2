<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index_besaran extends BE_Controller {
    var $controller = 'index_besaran';
	var $path = 'settings/index_besaran/';
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
     //           'a.sumber_data'   => array(2,3)
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
    }

    private function kolom_index_besaran($anggaran){
        $data            = [];
        $arr_sumber_data = [1];

        $detail_tahun = json_encode($this->detail_tahun);
        $detail_tahun = json_decode($detail_tahun,true);

        $tahun = ($anggaran[0]->tahun_anggaran - 1);
        for ($i=10; $i <=12 ; $i++) { 
            $sumber_data = 1;
            $dt = [
                'bulan' => $i,
                'tahun' => $tahun,
                'sumber_data'   => $sumber_data,
                'singkatan'     => 'Real',
            ];
            $key = multidimensional_search($detail_tahun, [
                'bulan'         => $i,
                'tahun'         => $tahun,
            ]);
            if(strlen($key)>0):
                $dt_tahun = $detail_tahun[$key];
                $dt['sumber_data']  = $dt_tahun['sumber_data'];
                $dt['singkatan']    = $dt_tahun['singkatan'];
                $sumber_data = $dt_tahun['sumber_data'];
            endif;
            array_push($data,$dt);
            if(!in_array($sumber_data, $arr_sumber_data)) array_push($arr_sumber_data, $sumber_data);
        }

        $tahun = ($anggaran[0]->tahun_anggaran);
        for ($i=1; $i <=12 ; $i++) { 
            $sumber_data = 1;
            $dt = [
                'bulan' => $i,
                'tahun' => $tahun,
                'sumber_data'   => $sumber_data,
                'singkatan'     => 'Real',
            ];
            $key = multidimensional_search($detail_tahun, [
                'bulan'         => $i,
                'tahun'         => $tahun,
            ]);
            if(strlen($key)>0):
                $dt_tahun = $detail_tahun[$key];
                $dt['sumber_data']  = $dt_tahun['sumber_data'];
                $dt['singkatan']    = $dt_tahun['singkatan'];
                $sumber_data = $dt_tahun['sumber_data'];
            endif;
            array_push($data,$dt);
            if(!in_array($sumber_data, $arr_sumber_data)) array_push($arr_sumber_data, $sumber_data);
        }
        $data = json_encode($data);
        $res = [
            'data'          => json_decode($data),
            'sumber_data'   => $arr_sumber_data,
        ];
        return $res;
    }

	function index() {
		$tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->result(); 
        $kolom          = $this->kolom_index_besaran($tahun_anggaran);
        $id_coa         = json_decode($tahun_anggaran[0]->id_coa_besaran);
        // $coa            = get_data('tbl_m_coa a',[
        //                     'select' => 'distinct b.coa, a.glwdes, a.glwnco',
        //                     'join'   => 'tbl_indek_besaran b on b.coa = a.glwnco',
        //                 ])->result();
        $coa            = get_data('tbl_m_coa',[
            'where' => [
                'id' => $id_coa,
                'is_active' => 1,
            ],
            'order_by' => 'urutan'
        ])->result();
        $data['tahun']  = $tahun_anggaran;
        $data['coa']    = $coa;
        $data['detail_tahun']    = $this->detail_tahun;
        $data['kolom']           = $kolom['data'];
        $data['sub_menu'] = $this->path.'sub_menu';
        $data['controller'] = $this->controller;
        
        $page = $this->input->get('page');
        if(!$page):
        	render($data,'view:'.$this->path.'index');
        else:
        	render($data,'view:'.$this->path.$page);
        endif;
	}

	function data($anggaran="", $coa="") {
        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->result(); 
        $kolom          = $this->kolom_index_besaran($tahun_anggaran);

        $data['cabang'][0] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>0, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
        foreach($data['cabang'][0] as $m0) {
            $data['cabang'][$m0->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m0->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
            foreach($data['cabang'][$m0->id] as $m1) {
                $data['cabang'][$m1->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m1->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
                foreach($data['cabang'][$m1->id] as $m2) {
                    $dataLevel4 = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m2->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
                    $data['cabang'][$m2->id] = $dataLevel4;
                }
            }
        }

        $dSum = get_data('tbl_indek_besaran',[
            'select' => 
                'id,parent_id,kode_cabang,coa,tahun_core,
                bulan1,bulan2,bulan3,bulan4,bulan5,bulan6,bulan7,bulan8,bulan9,bulan10,bulan11,bulan12',
            'where' => [
                'kode_anggaran' => $anggaran,
                'coa'           => $coa,
            ],
        ])->result_array();

        $access         = get_access($this->controller);

        $data['detail_tahun']   = $this->detail_tahun;
        $data['dSum']           = $dSum;
        $data['kolom']          = $kolom['data'];
        $data['anggaran']       = $tahun_anggaran[0];
        $data['edit']           = $access['access_edit'];
        $data['coa']            = $coa;

        $response   = array(
            'table'     => $this->load->view('settings/index_besaran/table',$data,true),
        );
        render($response,'json');
	}


    function dataHasil($anggaran="", $coa="") {
        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->result(); 
        $kolom          = $this->kolom_index_besaran($tahun_anggaran);

        $data['cabang'][0] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>0, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
        foreach($data['cabang'][0] as $m0) {
            $data['cabang'][$m0->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m0->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
            foreach($data['cabang'][$m0->id] as $m1) {
                $data['cabang'][$m1->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m1->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
                foreach($data['cabang'][$m1->id] as $m2) {
                    $dataLevel4 = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m2->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
                    $data['cabang'][$m2->id] = $dataLevel4;
                }
            }
        }

        $dSum = get_data('tbl_indek_besaran',[
            'where' => [
                'kode_anggaran' => $anggaran,
                'coa'           => $coa,
            ],
        ])->result_array();

        $dSumOri = get_data('tbl_bottom_up_form1',[
            'select' => 
                'sum(B_01) as B_01,sum(B_02) as B_02,sum(B_03) as B_03,sum(B_04) as B_04,sum(B_05) as B_05,sum(B_06) as B_06,sum(B_07) as B_07,sum(B_08) as B_08,sum(B_09) as B_09,sum(B_10) as B_10,sum(B_11) as B_11,sum(B_12) as B_12, kode_cabang,sumber_data,data_core,id',
            'where' => [
                'sumber_data'   => $kolom['sumber_data'],
                'kode_anggaran' => $anggaran,
                'coa'           => $coa,
            ],
            'group_by' => 'kode_cabang,sumber_data,data_core'
        ])->result_array();

        $access         = get_access($this->controller);

        $data['detail_tahun']   = $this->detail_tahun;
        $data['dSum']           = $dSum;
        $data['dSumOri']        = $dSumOri;
        $data['kolom']          = $kolom['data'];
        $data['anggaran']       = $tahun_anggaran[0];
        $data['edit']           = $access['access_edit'];
        $data['coa']            = $coa;

        $response   = array(
            'table'     => $this->load->view('settings/index_besaran/tabel_hasil',$data,true),
            'data'     => $data,
        );
        render($response,'json');
    }

	function dataOri($anggaran="", $coa=""){
        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->result(); 
        $kolom          = $this->kolom_index_besaran($tahun_anggaran);
        
        $data['cabang'][0] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>0, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
        $arrCodeCabang = array();
        foreach($data['cabang'][0] as $m0) {
            if(!in_array($m0->kode_cabang,$arrCodeCabang)):
                array_push($arrCodeCabang, $m0->kode_cabang);
            endif;
            $data['cabang'][$m0->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m0->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
            foreach($data['cabang'][$m0->id] as $m1) {
                if(!in_array($m1->kode_cabang,$arrCodeCabang)):
                    array_push($arrCodeCabang, $m1->kode_cabang);
                endif;
                $data['cabang'][$m1->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m1->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
                foreach($data['cabang'][$m1->id] as $m2) {
                    if(!in_array($m2->kode_cabang,$arrCodeCabang)):
                        array_push($arrCodeCabang, $m2->kode_cabang);
                    endif;

                    $dataLevel4 = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m2->id, 'is_active' => 1, 'list_kanpus' => 1),'order_by' => 'urutan'))->result();
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
                'sumber_data'   => $kolom['sumber_data'],
                'kode_anggaran' => $anggaran,
                'coa'   => $coa,
                'kode_cabang' => $arrCodeCabang
            ],
            'group_by' => 'kode_cabang,sumber_data,data_core'
        ])->result_array();
        // render($dSum,'json');exit();
        $data['dSum'] = $dSum;
        $data['detail_tahun'] = $this->detail_tahun;
        $data['kolom']        = $kolom['data'];
        $data['anggaran']     = $tahun_anggaran[0];
        $data['coa']          = $coa;

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

    function save_perubahan($anggaran, $coa) {
        $data   = json_decode(post('json'),true);
        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();
        foreach($data as $getId => $record) {
			$dt           = explode('-', $getId);
            $kode_cabang  = $dt[0];
            $tahun        = $dt[1];

            $ck = get_data('tbl_indek_besaran',[
                'select' => 'id',
                'where'  => [
                    'coa'           => $coa,
                    'kode_anggaran' => $tahun_anggaran->kode_anggaran,
                    'kode_cabang'   => $kode_cabang,
                    'tahun_core'    => $tahun
                ]
            ])->row();
            if($ck):
                update_data('tbl_indek_besaran',$record,'id',$ck->id);
            else:
                $parent_id = '0';
                if($tahun != $tahun_anggaran->tahun_anggaran):
                    $parent_id = $cabang;
                endif;
                $record['kode_cabang']  = $kode_cabang;
                $record['tahun']        = $tahun_anggaran->tahun_anggaran;
                $record['kode_anggaran']= $tahun_anggaran->kode_anggaran;
                $record['tahun_core']   = $tahun;
                $record['coa']          = $coa;
                $record['parent_id']    = $parent_id;
                insert_data('tbl_indek_besaran',$record);
            endif;
         } 
    }


    function save_perubahan_hasil($anggaran="",$coa="") {       

        $data   = json_decode(post('json'),true);
        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();
        foreach($data as $getId => $record) {
            $dt           = explode('-', $getId);
            $kode_cabang  = $dt[0];
            $tahun        = $dt[1];

            $record = insert_view_report_arr($record);

            $ck = get_data('tbl_indek_besaran',[
                'select' => 'id',
                'where'  => [
                    'coa'           => $coa,
                    'kode_anggaran' => $tahun_anggaran->kode_anggaran,
                    'kode_cabang'   => $kode_cabang,
                    'tahun_core'    => $tahun
                ]
            ])->row();
            if($ck):
                update_data('tbl_indek_besaran',$record,'id',$ck->id);
            else:
                $parent_id = '0';
                if($tahun != $tahun_anggaran->tahun_anggaran):
                    $parent_id = $cabang;
                endif;
                $record['kode_cabang']  = $kode_cabang;
                $record['tahun']        = $tahun_anggaran->tahun_anggaran;
                $record['kode_anggaran']= $tahun_anggaran->kode_anggaran;
                $record['tahun_core']   = $tahun;
                $record['coa']          = $coa;
                $record['parent_id']    = $parent_id;
                insert_data('tbl_indek_besaran',$record);
            endif;
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
        
        $kode_anggaran_txt  = post('kode_anggaran_txt');
        $kode_cabang_txt    = post('kode_cabang_txt');

        $neraca_header = json_decode(post('header'));
        $neraca = json_decode(post('data'));

        render($neraca_header,'json');exit();

        $data = [];
        foreach ($neraca as $k => $v) {
            if(count($v)>2):
               if($k == 0):
                    $data[$k] = $v;
                else:
                    $detail = [
                        $v[0],
                        $v[1],
                        $v[2],
                        $v[3],
                    ];
                    foreach ($v as $k2 => $v2) {
                        if($k2>3):
                            if(strlen($v2)>0):
                                $v2 = (float) filter_money($v2);
                            endif;
                            $detail[] = $v2;
                        endif;
                    }
                    $data[$k] = $detail;
                endif;
            else:
                $data[$k] = [];
                for($i=1;$i<=count($neraca_header[0]);$i++){
                    $data[$k][] = '';
                }
            endif;
        }

        $page = post('page');
        $config[] = [
            'title' => 'Indek Besaran '.$page,
            'header' => $neraca_header[0],
            'data'  => $data,
        ];
        
        $this->load->library('simpleexcel',$config);
        $filename = 'indek_besaran_'.$page.'_'.str_replace(' ', '_', $kode_anggaran_txt).'_'.date('YmdHis');
        $this->simpleexcel->filename($filename);
        $this->simpleexcel->export();
	}

    function import() {
        error_reporting(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '100000');

        $this->load->dbforge();

        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->result_array(); 
        $tahun          = $tahun_anggaran[0]->tahun_anggaran;
        $getBulanReal = $tahun_anggaran[0]['bulan_terakhir_realisasi'] + 1;

        $file = post('fileimport');
        $currency   = post('currency');

        $dt_currency = get_currency($currency);

        $kode_anggaran = user('kode_anggaran');

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
                        $tempData['is_import']         = 1;

                        $tempData2['kode_cabang']       = substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3);
                        $tempData2['kode_anggaran']     = $kode_anggaran;
                        $tempData2['coa']               = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $tempData2['parent_id']         = substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3);
                        $tempData2['is_import']         = 1;

                        $b = 6;
                        for($a=10;$a <=12; $a++){
                            $b++;
                            $tempData2['hasil'.$a] =  $worksheet->getCellByColumnAndRow($b, $row)->getValue() * $dt_currency['nilai'];
                        }
                        $b = $b + 1;
                        for($c=1;$c<=12;$c++){
                            $b++;
                             $tempData['hasil'.$c]    = $worksheet->getCellByColumnAndRow($b, $row)->getValue() * $dt_currency['nilai'];
                        }
                    
                    $cek = get_data('tbl_indek_besaran',[
                        'select'    => 'id',
                        'where'     => [
                            'kode_cabang'     => substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3),
                            'kode_anggaran' => $kode_anggaran,
                            'coa'           => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                            'parent_id'     => '0',
                        ]
                    ])->result_array();    

                     $cek2 = get_data('tbl_indek_besaran',[
                        'select'    => 'id',
                        'where'     => [
                            'kode_cabang'     => substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3),
                            'kode_anggaran' => $kode_anggaran,
                            'coa'           => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                            'parent_id'     => substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(),4,3)
                        ]
                    ])->result_array();    

                    $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->row();
                    if(empty($cek)){
                        $tempData['tahun_core'] = $anggaran->tahun_anggaran;
                        $save = insert_data('tbl_indek_besaran',$tempData);
                        if($save) $d++;
                    }else {
                         $save = update_data('tbl_indek_besaran',$tempData,[
                            'id'    => $cek[0]['id']
                         ]);
                        if($save) $d++;
                    }

                    if(empty($cek2)){
                        $tempData2['tahun_core'] = ($anggaran->tahun_anggaran - 1);
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



