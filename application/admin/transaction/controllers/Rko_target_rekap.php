<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rko_target_rekap extends BE_Controller {
	var $controller = 'rko_target_rekap';
	var $path       = 'transaction/';
    var $tipe       = 1;
	var $detail_tahun;
    var $kode_anggaran;
    var $tahun_anggaran;
    var $arr_sumber_data = array();
    var $arrWeekOfMonth = array();
	function __construct() {
		parent::__construct();
		$this->kode_anggaran  = user('kode_anggaran');
        $this->tahun_anggaran = user('tahun_anggaran');
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
        $this->arrWeekOfMonth = arrWeekOfMonth($this->tahun_anggaran);
	}
	private  function check_sumber_data($sumber_data){
        $key = array_search($sumber_data, array_map(function($element){return $element->sumber_data;}, $this->detail_tahun));
        if(strlen($key)>0):
            array_push($this->arr_sumber_data,$sumber_data);
        endif;
    }

	function index() {
		$data = data_cabang('usulan_besaran');
        $data['path']     = $this->path;
        $data['detail_tahun']    = $this->detail_tahun;
        $data['controller']     = $this->controller;
        $a  = get_access($this->controller);
        render($data,'view:'.$this->path.$this->controller.'/index');
	}

    function import() {
        ini_set('memory_limit', '-1');
        $file = post('fileimport');
        $this->load->library('simpleexcel');
        $this->simpleexcel->define_column();
        $jml = $this->simpleexcel->read($file);
        $c = 0;
        $dt = [];
        foreach($jml as $i => $k) {
            if($i==0) {
                for($j = 2; $j <= $k; $j++) {
                    $data = $this->simpleexcel->parsing($i,$j);
                    $data['create_at'] = date('Y-m-d H:i:s');
                    $data['create_by'] = user('nama');

                    $dt[] = $data;
                }
            }
        }
        @unlink($file);
        $response = [
            'status' => 'success',
            'data'   => $dt,
        ];
        render($response,'json');
    }
}