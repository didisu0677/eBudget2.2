<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget_nett_rekap extends BE_Controller {
	var $path 		= 'transaction/budget_nett_rekap';
	var $controller = 'budget_nett_rekap';
	var $page_rekaprasio = 'rekaprasio';
	var $page_neraca 	 = 'neraca';
	var $page_labarugi 	 = 'labarugi';
	var $submenu 	= '';
	var $tahun 		= [];
	var $detail_tahun;
	var $anggaran;
	function __construct() {
		parent::__construct();
		$this->submenu 	= $this->path.'/submenu';
		$this->tahun    = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result();
		$this->anggaran = $this->tahun[0];
		$this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => user('kode_anggaran'),
                'a.tahun'         => $this->anggaran->tahun_anggaran
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
	}

	function export(){
        ini_set('memory_limit', '-1');
        
        $kode_anggaran_txt  = post('kode_anggaran_txt');
        $kode_cabang_txt    = post('kode_cabang_txt');
        $page 				= post('page');
        $coa 				= post('coa');

        $arr_data_header = json_decode(post('arr_data_header'));
        $arr_data = json_decode(post('arr_data'));

        $data = [];
        foreach ($arr_data as $k => $v) {
            if(count($v)>2):
                $detail = [
                	$v[0],
                	$v[1],
                ];
                foreach ($v as $k2 => $v2) {
                    if($k2>1):
                        if(strlen($v2)>0):
                            $v2 = (float) filter_money($v2);
                        endif;
                        $detail[] = $v2;
                    endif;
                }
                $data[$k] = $detail;
            else:
                $data[$k] = [];
                for($i=1;$i<=count($arr_data_header[0]);$i++){
                    $data[$k][] = '';
                }
            endif;
        }

        $title = '';
        if($page == $this->page_labarugi):
    		$title = 'Laba Rugi';
		elseif($page == $this->page_rekaprasio):
			$title = 'Rekap Rasio';
		else:
			$title = 'Neraca';
		endif;

        $config[] = [
            'title' => 'Rekap Budget Nett '.$title,
            'header' => $arr_data_header[1],
            'data'  => $data,
        ];

        
        $this->load->library('simpleexcel',$config);
        $filename = 'Rekap_Budget_Nett_'.$page.'_'.$coa.'_'.str_replace(' ', '_', $kode_anggaran_txt).'_'.str_replace(' ', '_', $kode_cabang_txt).'_'.date('YmdHis');
        $this->simpleexcel->filename($filename);
        $this->simpleexcel->export();
        // render($neraca_header,'json');
    }

	private function data_parameter(){
		$data['controller'] 		= $this->controller;
		$data['path']				= $this->path;
		$data['submenu'] 			= $this->submenu;
		$data['tahun'] 				= $this->tahun;
		$data['detail_tahun'] 		= $this->detail_tahun;
		return $data;
	}

	function get_content(){
		$anggaran 	= post('tahun');
		$coa 		= post('coa');
		$page 		= post('page');

    	

    	$data['tahun'] 			= $this->tahun[0];
    	$data['detail_tahun'] 	= $this->detail_tahun;
    	if($page == $this->page_labarugi):
    		$data['title'] = 'Laba Rugi';
    		$coa   = get_data('tbl_m_coa','glwnco',$coa)->row();
		elseif($page == $this->page_rekaprasio):
			$data['title'] = 'Rekap Rasio';
			$coa   = get_data('tbl_keterangan_rekaprasio',['select' => 'kode as glwnco, keterangan as glwdes','where' => "kode = '$coa'"])->row();
		else:
			$data['title'] = 'Neraca';
			$coa   = get_data('tbl_m_coa','glwnco',$coa)->row();
		endif;
		$data['coa'] = $coa;
    	$view 	= $this->load->view($this->path.'/content',$data,true);

    	render([
    		'view' => $view,
    	],'json');
	}

	function data(){
		$page 		= post('page');
		if($page == $this->page_labarugi):
			$this->data_labarugi();
		elseif($page == $this->page_rekaprasio):
			$this->data_rekaprasio();
		else:
			$this->data_neraca();
		endif;
	}
	private function dt_data($parentID,$coa,$tahun){
        $select = [
            'select'    => 
                'a.id,a.kode_cabang,a.nama_cabang,a.level1,a.level2,a.level3,a.level4,a.struktur_cabang,'.
                'b.B_01,b.B_02,b.B_03,b.B_04,b.B_05,b.B_06,b.B_07,b.B_08,b.B_09,b.B_10,b.B_11,b.B_12',
            'where'     => "a.is_active = '1' and a.parent_id = '$parentID'",
            'join'      => [
                "tbl_budget_nett b on b.coa = '$coa' and b.kode_cabang = a.kode_cabang and b.kode_anggaran = '$tahun->kode_anggaran' TYPE LEFT"
            ],
            'order_by' => 'a.urutan'
        ];

        $cabang = get_data('tbl_m_cabang a',$select)->result_array();
        return $cabang;
    }
    private function dt_konsolidasi($coa,$tahun){
    	$dt = get_data('tbl_budget_nett',[
    		'select' => 'kode_cabang,B_01,B_02,B_03,B_04,B_05,B_06,B_07,B_08,B_09,B_10,B_11,B_12',
    		'where'	 => "kode_cabang in ('KONV','KONS') and kode_anggaran = '$tahun->kode_anggaran' and coa = '$coa'"
    	])->result_array();
    	return $dt;
    }


	// neraca
	function index() {
		$data = $this->data_parameter();
		$data['page']  = $this->page_neraca;
		$data['title'] = 'Neraca';
		$data['coa']   = $this->coa_neraca();
		render($data,'view:'.$this->path.'/neraca/index');
	}
	private function coa_neraca(){
		$or_neraca  = "(b.glwnco like '1%' or b.glwnco like '2%' or b.glwnco like '3%' or b.glwnco LIKE '41%' AND b.level1 = '2120011' or b.glwnco = '602')";
        $coa = get_data('tbl_m_coa b',[
            'select' => 'DISTINCT b.glwnco as coa,b.glwdes AS name, b.glwsbi, b.glwnob, b.level0,b.level1,b.level2,b.level3,b.level4,b.level5',
            'where' => "is_active = 1 and ".$or_neraca,
        ])->result();

        return $coa;
	}
	private function data_neraca(){
		$coa 	= post('coa');
		$anggaran = $this->anggaran;

		$cabang['l1'] = $this->dt_data(0,$coa,$anggaran);
        foreach ($cabang['l1'] as $k => $v) {
            $id = $v['id'];
            $cabang['l2'][$id] = $this->dt_data($id,$coa,$anggaran);
            foreach ($cabang['l2'][$id] as $k2 => $v2) {
                $id2 = $v2['id'];
                $cabang['l3'][$id2] = $this->dt_data($id2,$coa,$anggaran);
                foreach ($cabang['l3'][$id2] as $k3 => $v3) {
                    $id3 = $v3['id'];
                    $cabang['l4'][$id3] = $this->dt_data($id3,$coa,$anggaran);
                }
            }
        }

        $data['cabang'] 		= $cabang;
        $data['konsolidasi']	= $this->dt_konsolidasi($coa,$anggaran);
        $data['detail_tahun'] 	= $this->detail_tahun;
        $view 	            	= $this->load->view($this->path.'/'.$this->page_neraca.'/table',$data,true);
        render([
    		'view' 		=> $view,
    		'status' 	=> true,
    	],'json');
	}



	// laba rugi
	function labarugi(){
		$data = $this->data_parameter();
		$data['page']  = $this->page_labarugi;
		$data['title'] = 'Laba Rugi';
		$data['coa']   = $this->coa_labarugi();
		render($data,'view:'.$this->path.'/labarugi/index');
	}
	private function coa_labarugi(){
		$or_labarugi  = "(b.glwnco like '4%' or b.glwnco like '5%')";
        $coa = get_data('tbl_m_coa b',[
            'select' => 'DISTINCT b.glwnco as coa,b.glwdes AS name, b.glwsbi, b.glwnob, b.level0,b.level1,b.level2,b.level3,b.level4,b.level5',
            'where' => "is_active = 1 and ".$or_labarugi
        ])->result();

        return $coa;
	}
	private function data_labarugi(){
		$coa 	= post('coa');
		$anggaran = $this->anggaran;

		$cabang['l1'] = $this->dt_data(0,$coa,$anggaran);
        foreach ($cabang['l1'] as $k => $v) {
            $id = $v['id'];
            $cabang['l2'][$id] = $this->dt_data($id,$coa,$anggaran);
            foreach ($cabang['l2'][$id] as $k2 => $v2) {
                $id2 = $v2['id'];
                $cabang['l3'][$id2] = $this->dt_data($id2,$coa,$anggaran);
                foreach ($cabang['l3'][$id2] as $k3 => $v3) {
                    $id3 = $v3['id'];
                    $cabang['l4'][$id3] = $this->dt_data($id3,$coa,$anggaran);
                }
            }
        }

        $data['cabang'] 		= $cabang;
        $data['konsolidasi']	= $this->dt_konsolidasi($coa,$anggaran);
        $data['detail_tahun'] 	= $this->detail_tahun;
        $view 	            	= $this->load->view($this->path.'/'.$this->page_labarugi.'/table',$data,true);
        render([
    		'view' 		=> $view,
    		'status' 	=> true,
    	],'json');
	}


	// rekap rasio
	function rekaprasio(){
		$data = $this->data_parameter();
		$data['page']  = $this->page_rekaprasio;
		$data['title'] = 'Rekap Rasio';
		$data['coa']   = $this->coa_rekaprasio();
		render($data,'view:'.$this->path.'/rekaprasio/index');
	}
	private function coa_rekaprasio(){
		$coa = get_data('tbl_keterangan_rekaprasio b',[
            'select' => 'DISTINCT b.kode as coa,b.keterangan AS name,',
        ])->result();
		return $coa;
	}
	private function data_rekaprasio(){
		$coa 	= post('coa');
		$anggaran = $this->anggaran;

		$cabang['l1'] = $this->dt_cabang(0);
        foreach ($cabang['l1'] as $k => $v) {
            $id = $v['id'];
            $cabang['l2'][$id] = $this->dt_cabang($id);
            foreach ($cabang['l2'][$id] as $k2 => $v2) {
                $id2 = $v2['id'];
                $cabang['l3'][$id2] = $this->dt_cabang($id2);
                foreach ($cabang['l3'][$id2] as $k3 => $v3) {
                    $id3 = $v3['id'];
                    $cabang['l4'][$id3] = $this->dt_cabang($id3);
                }
            }
        }
        $data['anggaran'] 		= $anggaran;
        $data['coa'] 			= $coa;
        $data['cabang'] 		= $cabang;
        $data['detail_tahun'] 	= $this->detail_tahun;
        $view 	            	= $this->load->view($this->path.'/'.$this->page_rekaprasio.'/table',$data,true);
        render([
    		'view' 		=> $view,
    		'status' 	=> true,
    	],'json');
	}
	private function dt_cabang($parentID){
        $select = [
            'select'    => 
                'a.id,a.kode_cabang,a.nama_cabang,a.level1,a.level2,a.level3,a.level4,a.struktur_cabang,',
            'where'     => "a.is_active = '1' and a.parent_id = '$parentID'",
            'order_by' => 'a.urutan'
        ];

        $cabang = get_data('tbl_m_cabang a',$select)->result_array();
        return $cabang;
    }

}