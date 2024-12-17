<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laba_rugi_op extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'laba_rugi_op';
    function __construct() {
        parent::__construct();
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('Neraca_laba_rugi')
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
            'select'    => 'distinct a.kode_cabang,a.nama_cabang,a.level_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cab->id,
                'a.kode_cabang' => $kode_cabang
            ]
        ])->result_array();

        $data['cabang_input'] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang,a.level_cabang',
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
        $access         = get_access($this->controller);
        $data = $this->data_cabang();
        $data['access_additional']  = $access['access_additional'];
        render($data,'view:'.$this->path.'laba_rugi/index');
    }

     function data ($kode_anggaran="", $cabang=""){

         $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
         
         $tbl_history = 'tbl_history_'.($anggaran->tahun_anggaran-1);  
         $select = 'TOT_'.$cabang;

         $or_neraca  = "(b.glwnco like '4%' or b.glwnco like '5%')";
         $dt = get_data('tbl_m_coa as b',[
            'select'    =>
                    "coalesce(sum(case when a.bulan = '1' then a.".$select." end), 0) as b_1,
                    coalesce(sum(case when a.bulan = '2' then a.".$select." end), 0) as b_2,
                    coalesce(sum(case when a.bulan = '3' then a.".$select." end), 0) as b_3,
                    coalesce(sum(case when a.bulan = '4' then a.".$select." end), 0) as b_4,
                    coalesce(sum(case when a.bulan = '5' then a.".$select." end), 0) as b_5,
                    coalesce(sum(case when a.bulan = '6' then a.".$select." end), 0) as b_6,
                    coalesce(sum(case when a.bulan = '7' then a.".$select." end), 0) as b_7,
                    coalesce(sum(case when a.bulan = '8' then a.".$select." end), 0) as b_8,
                    coalesce(sum(case when a.bulan = '9' then a.".$select." end), 0) as b_9,
                    coalesce(sum(case when a.bulan = '10' then a.".$select." end), 0) as b_10,
                    coalesce(sum(case when a.bulan = '11' then a.".$select." end), 0) as b_11,
                    coalesce(sum(case when a.bulan = '12' then a.".$select." end), 0) as b_12,
                    b.glwdes,
                    b.glwnob,
                    b.glwsbi,
                    b.glwnco,
                    b.kali_minus,
                    b.level0,b.level1,b.level2,b.level3,b.level4,b.level5,
                    ",
            'join'  => $tbl_history.' a on a.glwnco = b.glwnco type LEFT',  
            'where'     => "b.is_active = 1 and ".$or_neraca,
            'group_by'  => 'b.glwnco'     

         ])->result();
        $dt = $this->get_list_coa($dt);

        $data['coa']    = $dt['coa'];
        $data['detail'] = $dt['detail'];
        // $view = $this->get_view($data);
        $response   = array(
            'table'     => $this->load->view('transaction/budget_planner/laba_rugi/table',$data,true),
            // 'table' => $view,
        );
        render($response,'json');
    }

    private function get_list_coa($coa){
        $data = [];
        foreach ($coa as $k => $v) {
            // level 0
            if(!$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $data['coa'][] = $v;
            endif;

            // level 1
            if($v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $data['detail']['1'][$v->level1][] = $v;
            endif;

            // level 2
            if(!$v->level1 && $v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $data['detail']['2'][$v->level2][] = $v;
            endif;

            // level 3
            if(!$v->level1 && !$v->level2 && $v->level3 && !$v->level4 && !$v->level5):
                $data['detail']['3'][$v->level3][] = $v;
            endif;

            // level 4
            if(!$v->level1 && !$v->level2 && !$v->level3 && $v->level4 && !$v->level5):
                $data['detail']['4'][$v->level4][] = $v;
            endif;

            // level 5
            if(!$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && $v->level5):
                $data['detail']['5'][$v->level5][] = $v;
            endif;
        }
        return $data;
    }

}