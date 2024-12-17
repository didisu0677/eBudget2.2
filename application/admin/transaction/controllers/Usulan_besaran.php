<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usulan_besaran extends BE_Controller {
	var $controller = 'usulan_besaran';
	var $kode_anggaran;
    var $anggaran;
    var $arr_tahun = array();
    var $table = 'tbl_bottom_up_form1';
	function __construct() {
		parent::__construct();
		$this->kode_anggaran  = user('kode_anggaran');
        $this->anggaran       = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->row();
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran,
            ],
            'order_by' => 'tahun,bulan'
        ])->result_array();
        $this->checkDetailTahun($this->detail_tahun);
	}

	private function checkDetailTahun($data){
		foreach ($data as $k => $v) {
			if(!in_array($v['tahun'],$this->arr_tahun)) array_push($this->arr_tahun,$v['tahun']);
		}
	}

	function index() {
		$access = get_access($this->controller);
        $data   = data_cabang();
        $data['access_additional']  = $access['access_additional'];
        $data['controller'] 		= $this->controller;
		render($data);
	}

	function data($kode_anggaran,$kode_cabang){
		$anggaran 	= get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
		$cabang 	= get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();

		$access = get_access($this->controller);
		$access_edit = false;
		if($access['access_edit'] && $kode_cabang == user('kode_cabang')):
			$access_edit = true;
		elseif($access['access_edit'] && $access['access_additional']):
			$access_edit = true;
		endif;


		$coa_besaran = explode(',', str_replace(' ', '', $anggaran->coa_besaran));

		$arr_group_giro = [];
		$arr_dpk 		= [];
		$arr_kredit 	= [];
		$arr_laba 		= [];
		$s_laba 		= false;
		$arr_other 	  	= [];
		foreach ($coa_besaran as $k => $v) {
			if(in_array($v, ['2100000','2101011','2101012'])) array_push($arr_group_giro,$v);
			if(in_array($v, ['2120011','2130000'])) array_push($arr_dpk,$v);
			if(in_array($v, ['122502','122506'])) array_push($arr_kredit,$v);
			if(in_array($v, ['59999','4570000','5580011'])) $s_laba = true;
		}

		if($s_laba):
			$arr_laba = ['59999','4570000','5580011'];
			$coa_besaran = array_merge($coa_besaran,$arr_laba);
		endif;
		foreach ($coa_besaran as $k => $v) {// get coa other
			if( !in_array($v,$arr_group_giro) && 
				!in_array($v,$arr_dpk) && 
				!in_array($v,$arr_kredit) &&
				!in_array($v,$arr_laba)):
				if(!in_array($v,$arr_other)) array_push($arr_other,$v);
			endif;
		}

		$dt_coa = get_data('tbl_m_coa','glwnco',$coa_besaran)->result_array();

		$tahun 			= (int) $anggaran->tahun_anggaran;
		$arr_tahun_core = [($tahun-3),($tahun-2),($tahun-1),($tahun)];
		$data_core  	= get_data_core($coa_besaran,$arr_tahun_core,'TOT_'.$cabang->kode_cabang);

		$list = get_data($this->table,[
			'where' => [
				'kode_cabang' 	=> $cabang->kode_cabang,
				'kode_anggaran'	=> $anggaran->kode_anggaran,
				'coa'			=> $coa_besaran,
				'data_core'		=> $this->arr_tahun,
			]
		])->result_array();

		$data['arr_group_giro'] = $arr_group_giro;
		$data['arr_dpk'] 		= $arr_dpk;
		$data['arr_kredit'] 	= $arr_kredit;
		$data['arr_laba'] 		= $arr_laba;
		$data['arr_other'] 		= $arr_other;
		$data['detail_tahun']	= $this->detail_tahun;
		$data['dt_coa']			= $dt_coa;
		$data['anggaran']		= $anggaran;
		$data['cabang']			= $cabang;
		$data['data_core']		= $data_core;
		$data['access_edit']	= $access_edit;
		$data['list']			= $list;
		$view =  $this->load->view('transaction/'.$this->controller.'/table',$data,true);

		// render($this->detail_tahun,'json');exit();

		render([
			'view' 			=> $view,
			'access_edit' 	=> $access_edit,

		],'json');
	}

	function save_perubahan($kode_anggaran,$kode_cabang){
		$anggaran 	= get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
		$cabang 	= get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();

		$data   = json_decode(post('json'),true);
		foreach($data as $k => $record) {
            $x 		= explode('-', $k);
            $coa 	= $x[0];
            $tahun 	= $x[1];

            foreach ($record as $k2 => $v2) {
                $value = filter_money($v2);
                $record[$k2] = insert_view_report($value);
            }

            $where = [
            	'kode_anggaran'	=> $anggaran->kode_anggaran,
            	'kode_cabang'	=> $cabang->kode_cabang,
            	'data_core'		=> $tahun,
            	'coa'			=> $coa
            ];

            $ck = get_data($this->table,['select' => 'id', 'where' => $where])->row();
            $dataSave = [];
            if($ck):
            	$dataSave = $record;
            	$dataSave['id'] = $ck->id;
            else:
            	$dataSave = $where;
            	$dataSave = array_merge($dataSave,$record);
            	$dataSave['keterangan_anggaran'] = $anggaran->keterangan;
            	$dataSave['tahun'] = $anggaran->tahun_anggaran;
            	$dataSave['cabang'] = $cabang->nama_cabang;
            	$dataSave['username'] = user('username');
            endif;
            save_data($this->table,$dataSave,[],true);
        }
	}

}