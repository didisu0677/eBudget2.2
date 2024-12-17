<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nett_scen_normal extends BE_Controller {
	var $controller = 'nett_scen_normal';
	var $path       = 'transaction/budget_nett/';
    var $sub_menu   = 'transaction/budget_nett/sub_menu';
	var $detail_tahun;
    var $kode_anggaran;
    var $arr_sumber_data = array();
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
        $this->check_sumber_data(2);
        $this->check_sumber_data(3);
	}
	private  function check_sumber_data($sumber_data){
        $key = array_search($sumber_data, array_map(function($element){return $element->sumber_data;}, $this->detail_tahun));
        if(strlen($key)>0):
            array_push($this->arr_sumber_data,$sumber_data);
        endif;
    }

	function index() {
		$data = data_cabang();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['detail_tahun']    = $this->detail_tahun;
        render($data,'view:'.$this->path.$this->controller.'/index');
	}

}