<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_neraca extends BE_Controller {
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
        $data = data_cabang();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['detail_tahun']    = $this->detail_tahun;
        render($data,'view:'.$this->path.'neraca/index');
    }

    function data($anggaran="", $cabang="", $tipe = 'table'){
        $tahun = 'tbl_history_'.substr($anggaran, 0,4);     
        $select = 'TOT_'.$cabang;
        if($this->db->field_exists($select, $tahun)):
            $data['data'] = get_data($tahun,[
                'select'    => 
                       "coalesce(sum(case when bulan = '1' then ".$select." end), 0) as b_1,
                        coalesce(sum(case when bulan = '2' then ".$select." end), 0) as b_2,
                        coalesce(sum(case when bulan = '3' then ".$select." end), 0) as b_3,
                        coalesce(sum(case when bulan = '4' then ".$select." end), 0) as b_4,
                        coalesce(sum(case when bulan = '5' then ".$select." end), 0) as b_5,
                        coalesce(sum(case when bulan = '6' then ".$select." end), 0) as b_6,
                        coalesce(sum(case when bulan = '7' then ".$select." end), 0) as b_7,
                        coalesce(sum(case when bulan = '8' then ".$select." end), 0) as b_8,
                        coalesce(sum(case when bulan = '9' then ".$select." end), 0) as b_9,
                        coalesce(sum(case when bulan = '10' then ".$select." end), 0) as b_10,
                        coalesce(sum(case when bulan = '11' then ".$select." end), 0) as b_11,
                        coalesce(sum(case when bulan = '12' then ".$select." end), 0) as b_12,
                        account_name,
                        coa,
                        gwlsbi,
                        glwnco",

                'where'     => " bulan not in(0) and (glwnco like '1%' or glwnco like '2%' or glwnco like '3%')  group by account_name order by SUBSTRING(glwnco,1,5)"
            ])->result();
        else:
            $data['data'] = array();
        endif;  

        $response   = array(
            'table'     => $this->load->view($this->path.'neraca/table',$data,true),
        );
        render($response,'json');
    }
}