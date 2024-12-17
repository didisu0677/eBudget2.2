<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monthly_performance_pkf extends BE_Controller {

	var $path       = 'transaction/monthly_performance/monthly_performance_kantor_pusat/';
	var $controller = 'monthly_performance_pkf/';
	var $kode_anggaran;
	function __construct() {
		parent::__construct();
		$this->kode_anggaran  = user('kode_anggaran');
	}

	function index() {
		$data = data_cabang('plan_rencana_kerja_fungsi');
		render($data,'view:'.$this->path.$this->controller.'index');
	}

	function data($anggaran="", $cabang="", $tipe = 'table'){
		$menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('monthly_performance_pkf');
        $data['akses_ubah'] = $a['access_edit'];

        $arr = ['select'    => '
                    a.*,
                    b.nama as kebijakan_umum,
                    c.nama as perspektif,
                    d.nama as skala_program,
                ',];
        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }

        $arr['join'][] = 'tbl_kebijakan_umum b on b.id = a.id_kebijakan_umum';
        $arr['join'][] = 'tbl_perspektif c on c.id = a.id_perspektif';
        $arr['join'][] = 'tbl_skala_program d on d.id = a.id_skala_program';
        $list = get_data('tbl_input_rkf a',$arr)->result();
        $data['list']     = $list;
        $data['current_cabang'] = $cabang;
 
        $response   = array(
            'table' => $this->load->view($this->path.$this->controller.'table',$data,true),
        );
       
        render($response,'json');
	}

}