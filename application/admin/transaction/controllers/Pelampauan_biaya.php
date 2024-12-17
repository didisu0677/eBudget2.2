<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pelampauan_biaya extends BE_Controller {
    var $path = 'transaction/budget_control/';
    function __construct() {
        parent::__construct();
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('biaya')
            ]
        ])->result();
        
        $kode_cabang          = [];
        foreach($cabang_user as $c) $kode_cabang[] = $c->kode_cabang;

        $id = user('id_struktur');
        if($id){
            $cab = get_data('tbl_m_cabang','id',$id)->row();
        }else{
            $id = user('kode_cabang');
            $cab = get_data('tbl_m_cabang','kode_cabang',$id)->row();
        }

        $x ='';
        for ($i = 1; $i <= 4; $i++) { 
            $field = 'level' . $i ;

            if($cab->id == $cab->$field) {
                $x = $field ; 
            }    
        }    

        $data['cabang']            = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cab->id,
                'a.kode_cabang' => $kode_cabang
            ]
        ])->result_array();

        $data['cabang_input'] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.kode_cabang' => user('kode_cabang')
            ]
        ])->result_array();

        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result();
        $data['path'] = $this->path;
        return $data;
    }
    
    function index($p1="") { 
        $data = $this->data_cabang();
        render($data,'view:'.$this->path.'pelampauan_biaya/index');
    }

    function data($anggaran="", $cabang="") {
        $select = 'TOT_'.$cabang;
        $tahun = 'tbl_history_'.substr($anggaran, 0,4); 

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

            'where'     => " bulan not in(0) and coa like '527%' or coa like '53%' or coa like '54%' or coa like '55%' or coa like '56%' group by account_name order by coa  "
        ])->result();  
     


        $response   = array(
            'table'     => $this->load->view('transaction/budget_planner/biaya/table',$data,true),
        );
        render($response,'json');
    }

    

}