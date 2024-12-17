<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Valas_laba_rugi extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'valas_laba_rugi';
    var $kode_anggaran;
    var $anggaran;
    var $detail_tahun;
    var $arr_sumber_data = array();
    var $arr_tahun_core = array();
    var $history_status = false;
    var $arr_coa = array();
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
        $this->check_sumber_data();
    }

    private  function check_sumber_data($sumber_data=""){
        foreach ($this->detail_tahun as $k => $v) {
            if(!in_array($v->sumber_data,$this->arr_sumber_data)):
                array_push($this->arr_sumber_data,$v->sumber_data);
            endif;
            if(!in_array($v->tahun, $this->arr_tahun_core)):
                array_push($this->arr_tahun_core,$v->tahun);
            endif;
            if($v->singkatan == arrSumberData()['real']):
                $this->history_status = true;
            endif;
        }
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('neraca_new')
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
            'select'    => 'distinct a.kode_cabang,a.nama_cabang,level_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cab->id,
                'a.kode_cabang' => $kode_cabang
            ]
        ])->result_array();

        $data['cabang_input'] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang,level_cabang',
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
        $data['detail_tahun']   = $this->detail_tahun;
        $data['bulan_terakhir'] = month_lang($data['tahun'][0]->bulan_terakhir_realisasi);
        render($data,'view:'.$this->path.'valas_laba_rugi/index');
    }

     function dataLaba ($anggaran1="", $cabang=""){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran1)->row();

        $bln_trakhir = $anggaran->bulan_terakhir_realisasi;
        $thn_trakhir = $anggaran->tahun_terakhir_realisasi;
        $tbl_history = 'tbl_history_'.$thn_trakhir;

        $or_neraca  = "(a.glwnco like '4%' or a.glwnco like '5%')";
        $select     = 'level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus';
        $coa = get_data('tbl_m_coa a',[
            'select' => $select.',b.VAL_'.$cabang,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => "$tbl_history b on b.bulan = '$bln_trakhir' and a.glwnco = b.glwnco type left"
        ])->result();
        $coa = $this->get_list_coa($coa);

        $data['save'] = get_data('tbl_valas_labarugi',[
            'where' => "kode_cabang =  '".$cabang."' and kode_anggaran = '".$anggaran1."'"
        ])->result_array();

        $data['coa']    = $coa['coa'];
        $data['detail'] = $coa['detail'];
        $data['cabang'] = $cabang;
        $data['bulan_terakhir'] = $bln_trakhir;

        $response   = array(
            'table'     => $this->load->view($this->path.'valas_laba_rugi/tableLaba',$data,true),
            'data' => $data,
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


    function save_perubahan($anggaran="",$cabang="") {       

        $data   = json_decode(post('json'),true);

        // echo post('json');
        foreach($data['bulan'] as $getId => $record) {
            $cekId = $getId;

            $cekExp = explode("|", $getId);
            $cekId = $cekExp[0];


            // $data = insert_view_report_arr($record);
            $record['last_edit'] = '1';
            $cek  = get_data('tbl_valas_labarugi a',[
                'select'    => 'a.id',
                'where'     => [
                    'a.glwnco'             => $cekId,
                    'a.kode_anggaran'   => $anggaran,
                    'a.kode_cabang'   => $cabang,
                ]
            ])->result_array();
     
            if(count($cek) > 0){
                update_data('tbl_valas_labarugi', $record,'id',$cek[0]['id']);
            }else {
                    // echo $cekId."<br>";
                    // echo $anggaran."<br>";
                    // echo $cabang."<br>";
                    $record['glwnco'] = $cekId;
                    $record['kode_anggaran'] = $anggaran;
                    $record['kode_cabang'] = $cabang;
                    insert_data('tbl_valas_labarugi',$record);
            } 
         } 
         if(!empty($data['perbulan'])){
                foreach($data['perbulan'] as $getId => $record) {
                    $cekId = $getId;

                    $cekExp = explode("|", $getId);
                    $cekId = $cekExp[0];

                    for($a=1;$a<=12;$a++){
                        $record['bulan_'.$a] = $record['perbulan'];
                    }
                    // $data = insert_view_report_arr($record);

                    $cek  = get_data('tbl_valas_labarugi a',[
                        'select'    => 'a.id',
                        'where'     => [
                            'a.glwnco'             => $cekId,
                            'a.kode_anggaran'   => $anggaran,
                            'a.kode_cabang'   => $cabang,
                        ]
                    ])->result_array();
             
                    if(count($cek) > 0){
                        $record['last_edit'] = '2';
                        update_data('tbl_valas_labarugi', $record,'id',$cek[0]['id']);
                    }else {
                            // echo $cekId."<br>";
                            // echo $anggaran."<br>";
                            // echo $cabang."<br>";
                            $record['last_edit'] = '2';
                            $record['glwnco'] = $cekId;
                            $record['kode_anggaran'] = $anggaran;
                            $record['kode_cabang'] = $cabang;
                            insert_data('tbl_valas_labarugi',$record);
                    } 
                 } 
             }
       
    }

  
}