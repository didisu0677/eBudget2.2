<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biaya extends BE_Controller {
    var $path = 'transaction/budget_planner/';
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
        render($data,'view:'.$this->path.'biaya/index');
    }


     function data ($anggaran="", $cabang=""){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();

        $bln_trakhir = $anggaran->bulan_terakhir_realisasi;
        $getMinBulan = $anggaran->bulan_terakhir_realisasi - 1;
        $thn_trakhir = $anggaran->tahun_terakhir_realisasi;
        $tbl_history = 'tbl_history_'.$thn_trakhir;

        $or_neraca  = "(a.glwnco like '557%')";
        $select     = "level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus";

        $select2    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";

        $coa = get_data('tbl_m_coa a',[
            'select' => $select.',b.TOT_'.$cabang.', c.*,d.*, '.$select2,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => ["$tbl_history b on and a.glwnco = b.glwnco type left","tbl_indek_besaran_biaya c on c.coa = a.glwnco type left","tbl_biaya d on d.glwnco = a.glwnco type left"],
             'where'     => " b.bulan not in(0) and a.glwnco like '557%' group by b.account_name",
        ])->result();
        $coa = $this->get_coa($coa);

        $data['coa']    = $coa['coa'];
        $data['detail'] = $coa['detail'];
        $data['cabang'] = $cabang;
        $data['bulan_terakhir'] = $bln_trakhir;


        $selectB     = "level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus";

        $select2B    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";

        $coaB = get_data('tbl_m_coa a',[
            'select' => $selectB.',b.TOT_'.$cabang.', c.*, '.$select2B,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => ["$tbl_history b on and a.glwnco = b.glwnco type left","tbl_indek_besaran_biaya c on c.coa = a.glwnco type left"],
             'where'     => " b.bulan not in(0) and a.glwnco like '567%' group by b.account_name",
        ])->result();
        $coaB = $this->get_coa($coaB);

        $dataB['coa']    = $coaB['coa'];
        $dataB['detail'] = $coaB['detail'];
        $dataB['cabang'] = $cabang;
        $dataB['bulan_terakhir'] = $bln_trakhir;


        $selectC     = "level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus";

        $select2C    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";

        $coaC = get_data('tbl_m_coa a',[
            'select' => $selectC.',b.TOT_'.$cabang.', c.*, '.$select2C,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => ["$tbl_history b on and a.glwnco = b.glwnco type left","tbl_indek_besaran_biaya c on c.coa = a.glwnco type left"],
             'where'     => " b.bulan not in(0) and a.glwnco like '568%' group by b.account_name",
        ])->result();
        $coaC = $this->get_coa($coaC);

        $dataC['coa']    = $coaC['coa'];
        $dataC['detail'] = $coaC['detail'];
        $dataC['cabang'] = $cabang;
        $dataC['bulan_terakhir'] = $bln_trakhir;


        $selectD     = "level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus";

        $select2D    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";

        $coaD = get_data('tbl_m_coa a',[
            'select' => $selectD.',b.TOT_'.$cabang.', c.*, '.$select2D,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => ["$tbl_history b on and a.glwnco = b.glwnco type left","tbl_indek_besaran_biaya c on c.coa = a.glwnco type left"],
             'where'     => " b.bulan not in(0) and a.glwnco like '57%' group by b.account_name",
        ])->result();
        $coaD = $this->get_coa($coaD);

        $dataD['coa']    = $coaD['coa'];
        $dataD['detail'] = $coaD['detail'];
        $dataD['cabang'] = $cabang;
        $dataD['bulan_terakhir'] = $bln_trakhir;


         $selectE     = "level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus";

        $select2E    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";

        $coaE = get_data('tbl_m_coa a',[
            'select' => $selectE.',b.TOT_'.$cabang.', c.*, '.$select2E,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => ["$tbl_history b on and a.glwnco = b.glwnco type left","tbl_indek_besaran_biaya c on c.coa = a.glwnco type left"],
             'where'     => " b.bulan not in(0) and a.glwnob like '570%' group by b.account_name",
        ])->result();
        $coaE = $this->get_coa($coaE);

        $dataE['coa']    = $coaE['coa'];
        $dataE['detail'] = $coaE['detail'];
        $dataE['cabang'] = $cabang;
        $dataE['bulan_terakhir'] = $bln_trakhir;



        $view = '';

        $view .= $this->load->view($this->path.'biaya/tableA',$data,true);


        $view .= $this->load->view($this->path.'biaya/tableB',$dataB,true);

        $view .= $this->load->view($this->path.'biaya/tableB',$dataC,true);

        $view .= $this->load->view($this->path.'biaya/tableB',$dataD,true);
        $view .= $this->load->view($this->path.'biaya/tableB',$dataE,true);

        $response   = array(
            'table'     => $view,
            // 'data' => $data,
        );
        render($response,'json');
    }


      private function get_coa($coa){
        $data = [];
        foreach ($coa as $k => $v) {
            // level 0
            if($v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $data['coa'][] = $v;
            endif;

            // level 1
            // if($v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
            //     $data['detail']['1'][$v->level1][] = $v;
            // endif;

            // level 2
            if(!$v->level1 && $v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $data['detail']['1'][$v->level2][] = $v;
            endif;

            // level 3
            if(!$v->level1 && !$v->level2 && $v->level3 && !$v->level4 && !$v->level5):
                $data['detail']['2'][$v->level3][] = $v;
            endif;

            // level 4
            if(!$v->level1 && !$v->level2 && !$v->level3 && $v->level4 && !$v->level5):
                $data['detail']['3'][$v->level4][] = $v;
            endif;

            // level 5
            // if(!$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && $v->level5):
            //     $data['detail']['5'][$v->level5][] = $v;
            // endif;
        }
        return $data;
    }


    function save_perubahan($anggaran="",$cabang="") {       

        $data   = json_decode(post('json'),true);

        // echo post('json');
        foreach($data as $getId => $record) {
            $cekId = $getId;

            // echo $id." - ".$cekId[1]."<br>";
            $cek  = get_data('tbl_biaya a',[
                'select'    => 'a.id',
                'where'     => [
                    'a.glwnco'             => $cekId,
                    'a.kode_anggaran'   => $anggaran,
                    'a.kode_cabang'   => $cabang,
                ]
            ])->result_array();
     
            if(count($cek) > 0){
                update_data('tbl_biaya', $record,'id',$cek[0]['id']);
            }else {
                    $record['glwnco'] = $cekId;
                    $record['kode_anggaran'] = $anggaran;
                    $record['kode_cabang'] = $cabang;
                    insert_data('tbl_biaya',$record);
            } 
         } 
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