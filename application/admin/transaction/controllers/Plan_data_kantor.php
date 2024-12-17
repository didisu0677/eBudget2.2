<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_data_kantor extends BE_Controller {
    var $path       = 'transaction/budget_planner/kantor_pusat/';
    var $sub_menu   = 'transaction/budget_planner/sub_menu';
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
    
    function index($p1="") {
        $data = cabang_divisi();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['detail_tahun']    = $this->detail_tahun;
        render($data,'view:'.$this->path.'data_kantor/index');
    }

    function get_data($kode_cabang){
        $cabang = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row_array();
        $data = get_data('tbl_m_data_kantor',"kode_cabang",$kode_cabang)->row_array();
        if($data) $data['tgl_mulai_menjabat'] = date("d-m-Y", strtotime($data['tgl_mulai_menjabat']));
        else $data = array();
        $data['kode_cabang'] = $kode_cabang;
        $data['nama_kantor'] = $cabang['nama_cabang'];
        render($data,'json');
    }
    function save(){
        $response = save_data('tbl_m_data_kantor',post(),post(':validation'));
        render($response,'json');
    }
}