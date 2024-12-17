<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_proker extends BE_Controller {
    var $path       = 'transaction/budget_planner/kantor_pusat/';
    var $sub_menu   = 'transaction/budget_planner/sub_menu';
    var $detail_tahun;
    var $kode_anggaran;
    var $arr_sumber_data = array();
    var $anggaran;
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->anggaran       = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->row();
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran,
                'a.tahun'         => $this->anggaran->tahun_anggaran
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
    }
    
    function index($p1="") { 
        $a = get_access('plan_proker');
        $data = cabang_divisi();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['access_edit'] = $a['access_edit'];
        $data['detail_tahun']= $this->detail_tahun;
        render($data,'view:'.$this->path.'proker/index');
    }

    private  function check_sumber_data($sumber_data){
        $key = array_search($sumber_data, array_map(function($element){return $element->sumber_data;}, $this->detail_tahun));
        if(strlen($key)>0):
            array_push($this->arr_sumber_data,$sumber_data);
        endif;
    }

    function get_coa(){
        $ls             = get_data('tbl_m_biaya_rkf a',[
            'select'    => 'a.coa as glwnco, b.glwdes',
            'where'     => "a.is_active = 1",
            'join'      => 'tbl_m_coa b on a.coa = b.glwnco'
        ])->result();
        return $ls;
    }

    function data($anggaran="", $cabang="", $tipe = 'table') {
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('plan_proker');
        $access_edit = false;
        if($a['access_edit'] && $cabang == user('kode_cabang')):
            $access_edit = true;
        elseif($a['access_edit'] && $a['access_additional']):
            $access_edit = true;
        endif;
        $data['akses_ubah'] = $access_edit;

        $data['current_cabang'] = $cabang;

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
              
        $arr            = [
            'select'    => '
                a.*,
                b.nama as kebijakan_umum,
                c.glwnco,
                c.glwdes
            ',
        ];

        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }
        $arr['join'][] = 'tbl_kebijakan_umum b on b.id = a.id_kebijakan_umum';
        $arr['join'][] = 'tbl_m_coa c on c.glwnco = a.coa type left';
        $arr['order_by'] = 'a.id';
        $arr['group_by'] = 'a.id';
        $data['list'] = get_data('tbl_input_rkf a',$arr)->result();          
        $data['coa_list']  = $this->get_coa();
        $response   = array(
            'table'     => $this->load->view($this->path.'proker/table',$data,true),
            'access_edit'   => $access_edit
        );
       
        render($response,'json');
    }

    function save_perubahan() {       
        $data   = json_decode(post('json'),true);
        $arrKey = array();
        for ($i=1; $i <=12 ; $i++) {
            $field    = 'T_'.sprintf("%02d", $i);
            array_push($arrKey, $field);
        }
        foreach($data as $id => $record) {
            $dt = [];
            foreach ($record as $k => $v) {
                if(in_array($k, $arrKey)):
                    $dt[$k] = insert_view_report($v);
                else:
                    if($v == '') $v = '0';
                    $dt[$k] = $v;
                endif;
            }          
            update_data('tbl_input_rkf',$dt,'id',$id); 
        }
    }
}