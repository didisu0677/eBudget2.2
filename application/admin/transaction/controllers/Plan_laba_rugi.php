<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_laba_rugi extends BE_Controller {
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
        render($data,'view:'.$this->path.'laba_rugi/index');
    }

    function data($anggaran="", $cabang=""){
        $tahun = 'tbl_history_'.substr($anggaran, 0,4);     
        $select = 'TOT_'.$cabang;

        if($this->db->field_exists($select, $tahun)):
            $data['nameA'] = get_data($tahun,[
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
                'where'     => " bulan not in(0) and account_name like '%PENDAPATAN DAN BEBAN BUNGA%' group by account_name"
            ])->result_array();  

            $data['nameB'] = get_data($tahun,[
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
                'where'     => " bulan not in(0) and account_name like '% PENDAPATAN DAN BEBAN OPERASIONAL LAIN%' group by account_name"
            ])->result_array();  


            $data['A'] = get_data($tahun,[
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
                'where'     => " bulan not in(0) and glwnco like '41%' or glwnco like '51%' or glwnco like '51%' group by account_name order by SUBSTRING(glwnco,1,5)  "
            ])->result();  

            $data['B'] = get_data($tahun,[
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

                'where'     => " bulan not in(0) and glwnco like '45%' or glwnco like '55%' or glwnco like '56%' or glwnco like '57%'  group by account_name order by SUBSTRING(glwnco,1,5) "
            ])->result();  

            $data['C'] = get_data($tahun,[
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
                'where'     => " bulan not in(0) and glwnco like '48%' or glwnco like '58%'  group by account_name order by SUBSTRING(glwnco,1,5) "
            ])->result();  
        else:
            $data['A'] = array();
            $data['B'] = array();
        endif;

        $response   = array(
            'table'     => $this->load->view($this->path.'laba_rugi/table',$data,true),
        );
        render($response,'json');
    }
}