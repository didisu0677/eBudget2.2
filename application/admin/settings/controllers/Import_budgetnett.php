<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class import_budgetnett extends BE_Controller {
    var $controller = 'import_budgetnett';
	var $path = 'settings/adj_budget_nett/';
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
                            'join'   => 'tbl_adj_budget_nett b on b.coa = a.glwnco',
                        ])->result();
        // $coa            = get_data('tbl_m_coa','id', $id_coa)->result();
        $data['tahun']  = $tahun_anggaran;
        $data['coa']    = $coa;
        $data['detail_tahun']    = $this->detail_tahun;
        $data['controller']     = $this->controller;
       
        
        render($data,'view:'.$this->path.'index');
       
	}

    function data() {
        $config['access_view']   = false;
        $config['access_edit']  = false;
        $config['button'][]     = button_serverside('btn-info','btn-detail',['fa-search',lang('detil'),true],'act-detil');

        $data = data_serverside($config);
        render($data,'json');
    }

    function detail($id){
        $data = get_data('tbl_history_import_budgetnett','id',$id)->row_array();
        if(isset($data['id'])) {
            render($data,'layout:false view:'.$this->path.'detail');
        } else echo lang('tidak_ada_data');

    }

	function dataCoa($anggaran="", $coa="") {
        $data['cabang'][0] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.id as getId ,a.nama_cabang, b.*',
            'join'      => "tbl_adj_budget_nett b on a.kode_cabang = b.kode_cabang  and b.coa ='".$coa."' and b.kode_anggaran = '".$anggaran."'  type LEFT",
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
            'table'     => $this->load->view('settings/adj_budget_nett/table',$data,true),
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
            $cek  = get_data('tbl_adj_budget_nett a',[
                'select'    => 'a.id',
                'where'     => [
                    'a.coa'             => $cekId,
                    'a.kode_anggaran'   => $anggaran,
                ]
            ])->result_array();
     
            if(count($cek) > 0){
                update_data('tbl_adj_budget_nett_biaya', $record,'id',$cek[0]['id']);
	        }else {
	                $record['coa'] = $cekId;
	                $record['kode_anggaran'] = $anggaran;
	                insert_data('tbl_adj_budget_nett',$record);
	        } 
         } 
    }

	function get_data() {
		$data = get_data('tbl_adj_budget_nett','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_adj_budget_nett',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_adj_budget_nett','id',post('id'));
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
		$data = get_data('tbl_adj_budget_nett')->result_array();
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
        $currency   = post('currency');

        $dt_currency = get_currency($currency);
        $kode_anggaran = $this->kode_anggaran;

        $data = array();
        $this->load->library('PHPExcel');
        
        $table = 'tbl_budget_nett';
        $arr_kode_cabang = [];
        if($file){

                $excelreader = new PHPExcel_Reader_Excel2007();
                $loadexcel = $excelreader->load($file); 
                $d = 0;

                // echo $loadexcel->getSheetCount();

                foreach($loadexcel->getWorksheetIterator() as $worksheet){
                
            

                $highestRow = $worksheet->getHighestRow();

                $highestColumn = $worksheet->getHighestColumn();
                $colNumber = PHPExcel_Cell::columnIndexFromString($highestColumn);
                
                

                $tempData = array();

                for($row=3; $row<=$highestRow; $row++){

                    if(!empty($worksheet->getCellByColumnAndRow(0, 3)->getValue()) && is_numeric($worksheet->getCellByColumnAndRow(1, $row)->getValue())) {
                        $kode_cabang = sprintf("%03d",$worksheet->getCellByColumnAndRow(1, $row)->getValue());
                        $tempData = [];
                        $tempData['kode_cabang']         = $kode_cabang;
                        $tempData['kode_anggaran']     = $kode_anggaran;
                        $tempData['coa']               = $worksheet->getCellByColumnAndRow(0, 3)->getValue();
                        $tempData['status_adjs_import']= 1;

                        if(!in_array($kode_cabang, $arr_kode_cabang) and count($arr_kode_cabang)):
                            array_push($arr_kode_cabang, $kode_cabang);
                        endif;

                        $b = 3;
                        for($a=1;$a <=12; $a++){
                            $b++;
                            $field = 'B_'.sprintf("%02d",$a);
                            $value = $worksheet->getCellByColumnAndRow($b, $row)->getCalculatedValue();
                            $value = (float) $value * $dt_currency['nilai'];
                            $tempData[$field] = $value;
                        }
                    
                    $cek = get_data($table,[
                        'select'    => 'id',
                        'where'     => [
                            'kode_cabang'     =>  sprintf("%03d",$worksheet->getCellByColumnAndRow(1, $row)->getValue()),
                            'kode_anggaran' => $kode_anggaran,
                            'coa'           => $worksheet->getCellByColumnAndRow(0,3)->getValue()
                        ]
                    ])->result_array();    

                    if(empty($cek)){
                        $save = insert_data($table,$tempData);
                       
                        // @unlink($file);
                        if($save) $d++;
                    }else {
                         $save = update_data($table,$tempData,[
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
            $dest       = dir_upload('import_budgetnett').$new_name;
            if(!@copy($file,$dest))
               $file = '';
            else {
                delete_dir(FCPATH . $temp_dir);
                $file = $new_name;
            }



            $data = [];
            $data['kode_anggaran'] = $kode_anggaran;
            $data['currency']       = $dt_currency['nama'];
            $data['currency_value'] = $dt_currency['nilai'];
            $data['file']      = $file;          
            $data['create_at'] = date('Y-m-d H:i:s');
            $data['create_by'] = user('nama');
            $data['update_by'] = user('nama');
            $data['update_at'] = date('Y-m-d H:i:s');
            $save = insert_data('tbl_history_import_budgetnett',$data);

            // summary coa cabang level terendah ke tertinggi
            if(count($arr_kode_cabang)>0):
                // $this->db->query('CALL stored_budget_nett_sum_coa("'.$kode_anggaran.'","'.$v.'")');
                foreach ($arr_kode_cabang as $v) {
                    $this->db->query('CALL stored_budget_nett_sum_coa("'.$kode_anggaran.'","'.$v.'")');
                }

                // summary coa gab cabang level terendah ke tertinggi
               $cab_gab = get_data('tbl_m_cabang a',[
                    'select' => 'b.kode_cabang as kode_cabang',
                    'join'   => [
                        'tbl_m_cabang b on b.id = a.parent_id'
                    ],
                    'where'  => "b.kode_cabang like 'G%' and a.kode_cabang in (".implode(",", $arr_kode_cabang).")",
                    'group_by' => 'b.kode_cabang'
                ])->result();
                foreach ($cab_gab as $v) {
                    $this->db->query("CALL stored_budget_nett_cron('".$kode_anggaran."', '".$v->kode_cabang."', 1)");
                }
            endif;

            


            $response = [
                'status' => 'success',
                'message' => $d.' '.lang('data_berhasil_disimpan').'.'
            ];

            render($response,'json');
        }
    }
}



