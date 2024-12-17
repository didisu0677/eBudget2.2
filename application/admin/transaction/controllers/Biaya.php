<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biaya extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'biaya';
    var $detail_tahun;
    var $kode_anggaran;
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->row();
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => "a.kode_anggaran = '".$this->kode_anggaran."' and a.tahun = '".$anggaran->tahun_anggaran."' ",
            //     'a.kode_anggaran' => $this->kode_anggaran,
            //     'a.sumber_data'   => array(2,3)
            // ],
            'order_by' => 'tahun,bulan'
        ])->result_array();
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

        $data['detail_tahun'] = $this->detail_tahun;
        $data['path'] = $this->path;
        return $data;
    }
    
    function index($p1="") { 
        $access         = get_access($this->controller);
        $data = $this->data_cabang();
        $data['access_additional']  = $access['access_additional'];
        render($data,'view:'.$this->path.'biaya/index');
    }

    function data ($anggaran1="", $cabang=""){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran1)->row();


        $getAkses = get_access('biaya');

        $bln_trakhir = $anggaran->bulan_terakhir_realisasi;
        $getMinBulan = $anggaran->bulan_terakhir_realisasi - 1;
        $thn_trakhir = $anggaran->tahun_terakhir_realisasi;
        $tbl_history = 'tbl_history_'.$thn_trakhir;

        $or_neraca  = "(a.glwnco like '557%')";
        $select     = "level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus";

        $select2    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";

        $select3    = "coalesce(sum(case when b.bulan = '1' then b.TOT_".$cabang." end), 0) as core1,
                    coalesce(sum(case when b.bulan = '2' then b.TOT_".$cabang." end), 0) as core2,
                    coalesce(sum(case when b.bulan = '3' then b.TOT_".$cabang." end), 0) as core3,
                    coalesce(sum(case when b.bulan = '4' then b.TOT_".$cabang." end), 0) as core4,
                    coalesce(sum(case when b.bulan = '5' then b.TOT_".$cabang." end), 0) as core5,
                    coalesce(sum(case when b.bulan = '6' then b.TOT_".$cabang." end), 0) as core6,
                    coalesce(sum(case when b.bulan = '7' then b.TOT_".$cabang." end), 0) as core7,
                    coalesce(sum(case when b.bulan = '8' then b.TOT_".$cabang." end), 0) as core8,
                    coalesce(sum(case when b.bulan = '9' then b.TOT_".$cabang." end), 0) as core9,
                    coalesce(sum(case when b.bulan = '10' then b.TOT_".$cabang." end), 0) as core10,
                    coalesce(sum(case when b.bulan = '11' then b.TOT_".$cabang." end), 0) as core11,
                    coalesce(sum(case when b.bulan = '12' then b.TOT_".$cabang." end), 0) as core12";

        // MW 20210426
        // sebelum diubah terdapat b.bulan not in(0) di where
        $coa = get_data('tbl_m_coa a',[
            'select' => 'd.*, '.$select.',b.TOT_'.$cabang.', c.*, '.$select2.','.$select3,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => [
                "$tbl_history b on and a.glwnco = b.glwnco type left",
                "tbl_indek_besaran_biaya c on c.coa = a.glwnco and c.kode_anggaran = '$anggaran1' type left",
                "tbl_biaya d on d.glwnco = a.glwnco and d.kode_anggaran = '$anggaran1' and d.kode_cabang = '".$cabang."' type left"
            ],
             'where'     => " a.glwnco like '557%' group by b.account_name",
        ])->result();
        // print_r($this->db->last_query());
        $coa = $this->get_coa($coa);

        $data['coa']    = $coa['coa'];
        $data['detail'] = $coa['detail'];
        $data['cabang'] = $cabang;
        $data['bulan_terakhir'] = $bln_trakhir;
        $data['detail_tahun'] = $this->detail_tahun;
        $data['akses_ubah'] = $getAkses['access_edit'];
        $data['anggaran'] = $anggaran;


        $selectB     = "level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus";

        $select2B    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";

        $coaB = get_data('tbl_m_coa a',[
            'select' => 'd.*,'.$selectB.',b.TOT_'.$cabang.', c.*, '.$select2B.','.$select3,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => [
                "$tbl_history b on and a.glwnco = b.glwnco type left",
                "tbl_indek_besaran_biaya c on c.coa = a.glwnco and c.kode_anggaran = '$anggaran1' type left",
                "tbl_biaya d on d.glwnco = a.glwnco and d.kode_anggaran = '$anggaran1' and d.kode_cabang = '".$cabang."' type left"],
             'where'     => " a.glwnco like '567%' group by b.account_name",
        ])->result();
        $coaB = $this->get_coa($coaB);

        $dataB['coa']    = $coaB['coa'];
        $dataB['detail'] = $coaB['detail'];
        $dataB['cabang'] = $cabang;
        $dataB['bulan_terakhir'] = $bln_trakhir;
        $dataB['detail_tahun'] = $this->detail_tahun;
        $dataB['anggaran'] = $anggaran;

        $dataB['akses_ubah'] = $getAkses['access_edit'];


        $selectC     = "level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus";

        $select2C    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";


        $coaC = get_data('tbl_m_coa a',[
            'select' => 'd.*,'.$selectC.',b.TOT_'.$cabang.', c.*, '.$select2C.','.$select3,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => [
                "$tbl_history b on and a.glwnco = b.glwnco type left",
                "tbl_indek_besaran_biaya c on c.coa = a.glwnco and c.kode_anggaran = '$anggaran1' type left",
                "tbl_biaya d on d.glwnco = a.glwnco and d.kode_anggaran = '$anggaran1' and d.kode_cabang = '".$cabang."' type left"],
             'where'     => " a.glwnco like '568%' group by b.glwnco",
        ])->result();
        $coaC = $this->get_coa($coaC);

        $dataC['coa']    = $coaC['coa'];
        $dataC['detail'] = $coaC['detail'];
        $dataC['cabang'] = $cabang;
        $dataC['bulan_terakhir'] = $bln_trakhir;
        $dataC['akses_ubah'] = $getAkses['access_edit'];
        $dataC['detail_tahun'] = $this->detail_tahun;
        $dataC['anggaran'] = $anggaran;


        $selectD     = "level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus";

        $select2D    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";

        $coaD = get_data('tbl_m_coa a',[
            'select' => 'd.*,'.$selectD.',b.TOT_'.$cabang.', c.*, '.$select2D.','.$select3,
            'order_by' => 'a.id',
            'join' => [
                "$tbl_history b on and a.glwnco = b.glwnco type left",
                "tbl_indek_besaran_biaya c on c.coa = a.glwnco and c.kode_anggaran = '$anggaran1' type left",
                "tbl_biaya d on d.glwnco = a.glwnco and d.kode_anggaran = '$anggaran1' and d.kode_cabang = '".$cabang."' type left"],
             'where'     => " a.glwnco like '57%' group by b.glwnco",
        ])->result();
        $coaD = $this->get_coa($coaD);

        $dataD['promosi'] = get_data('tbl_biaya_promosi',[
            'where' => "kode_anggaran = '".$anggaran1."' and kode_cabang = '".$cabang."'"
        ])->result_array();

        $selectSum = "";
        for($a=1;$a<=12;$a++){
            $selectSum .= "sum(bulan_".$a.") as bulan_b".$a.",";
        }

        $dataD['sumPromosi'] = get_data('tbl_biaya_promosi',[
            'select' => $selectSum,
            'where' => "kode_anggaran = '".$anggaran1."' and kode_cabang = '".$cabang."' group by kode_cabang"
        ])->result_array();

        $dataD['coa']    = $coaD['coa'];
        $dataD['detail'] = $coaD['detail'];
        $dataD['cabang'] = $cabang;
        $dataD['bulan_terakhir'] = $bln_trakhir;
        $dataD['akses_ubah'] = $getAkses['access_edit'];
        $dataD['anggaran'] = $anggaran;


         $selectE     = "level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus";

        $select2E    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";

        $coaE = get_data('tbl_m_coa a',[
            'select' => 'd.*, '.$selectE.',b.TOT_'.$cabang.', c.*, '.$select2E.','.$select3,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => [
                "$tbl_history b on and a.glwnco = b.glwnco type left",
                "tbl_indek_besaran_biaya c on c.coa = a.glwnco and c.kode_anggaran = '$anggaran1' type left",
                "tbl_biaya d on d.glwnco = a.glwnco and d.kode_anggaran = '$anggaran1' and d.kode_cabang = '".$cabang."' type left"],
             'where'     => " a.glwnco like '580%' group by b.account_name",
        ])->result();
        $coaE = $this->get_coa($coaE);

        $dataE['coa']    = $coaE['coa'];
        $dataE['detail'] = $coaE['detail'];
        $dataE['cabang'] = $cabang;
        $dataE['bulan_terakhir'] = $bln_trakhir;
        $dataE['akses_ubah'] = $getAkses['access_edit'];
        $dataE['anggaran'] = $anggaran;


        // echo json_encode($dataD['sumPromosi']);

        $view = '';

        $view .= $this->load->view($this->path.'biaya/tableA',$data,true);


        $view .= $this->load->view($this->path.'biaya/tableB',$dataB,true);

        $view .= $this->load->view($this->path.'biaya/tableB',$dataC,true);

        $view .= $this->load->view($this->path.'biaya/tableB',$dataD,true);
        $view .= $this->load->view($this->path.'biaya/tableB',$dataE,true);

        $response   = array(
            'table'     => $view,
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


    // function save_perubahan($anggaran="",$cabang="") {       

    //     $data   = json_decode(post('json'),true);

    //     // echo post('json');
    //     foreach($data as $getId => $record) {
    //         $cekId = $getId;

    //         $record = insert_view_report_arr($record);
    //         // echo $id." - ".$cekId[1]."<br>";
    //         $cek  = get_data('tbl_biaya a',[
    //             'select'    => 'a.id',
    //             'where'     => [
    //                 'a.glwnco'             => $cekId,
    //                 'a.kode_anggaran'   => $anggaran,
    //                 'a.kode_cabang'   => $cabang,
    //             ]
    //         ])->result_array();
     
    //         if(count($cek) > 0){
    //             update_data('tbl_biaya', $record,'id',$cek[0]['id']);
    //         }else {
    //                 $record['glwnco'] = $cekId;
    //                 $record['kode_anggaran'] = $anggaran;
    //                 $record['kode_cabang'] = $cabang;
    //                 insert_data('tbl_biaya',$record);
    //         } 
    //      } 
    // }


      function save_perubahan($anggaran="",$cabang="") {       

        $data   = json_decode(post('json'),true);

        foreach($data['bulan'] as $getId => $record) {
            $cekId = $getId;

            $cekExp = explode("|", $getId);
            $cekId = $cekExp[0];


            $dataRecord = insert_view_report_arr($record);
            $dataRecord ['last_edit'] = '1';
            $cek  = get_data('tbl_biaya a',[
                'select'    => 'a.id',
                'where'     => [
                    'a.glwnco'             => $cekId,
                    'a.kode_anggaran'   => $anggaran,
                    'a.kode_cabang'   => $cabang,
                ]
            ])->result_array();
     
            if(count($cek) > 0){
                  update_data('tbl_biaya', $dataRecord,'id',$cek[0]['id']);
            }else {
                    // echo $cekId."<br>";
                    // echo $anggaran."<br>";
                    // echo $cabang."<br>";
                    $dataRecord['glwnco'] = $cekId;
                    $dataRecord['kode_anggaran'] = $anggaran;
                    $dataRecord['kode_cabang'] = $cabang;
                    insert_data('tbl_biaya',$dataRecord);
            } 
         } 
         if(!empty($data['perbulan'])){

                // print_r($data['perbulan']);
                foreach($data['perbulan'] as $getId => $record) {
                    $cekId = $getId;

                    $cekExp = explode("|", $getId);
                    $cekId = $cekExp[0];

                    
                    $dataRecord  = insert_view_report_arr($record);

                    for($a=1;$a<=12;$a++){
                        $dataRecord['bulan_b'.$a] = $dataRecord['biaya_bulan'];
                    }

                    $cek  = get_data('tbl_biaya a',[
                        'select'    => 'a.id',
                        'where'     => [
                            'a.glwnco'             => $cekId,
                            'a.kode_anggaran'   => $anggaran,
                            'a.kode_cabang'   => $cabang,
                        ]
                    ])->result_array();
             
                    if(count($cek) > 0){
                        $dataRecord['last_edit'] = '2';
                        update_data('tbl_biaya', $dataRecord,'id',$cek[0]['id']);
                    }else {
                            // echo $cekId."<br>";
                            // echo $anggaran."<br>";
                            // echo $cabang."<br>";
                            $dataRecord['last_edit'] = '2';
                            $dataRecord['glwnco'] = $cekId;
                            $dataRecord['kode_anggaran'] = $anggaran;
                            $dataRecord['kode_cabang'] = $cabang;
                            insert_data('tbl_biaya',$dataRecord);
                    } 
                 } 
             }
       
    }



     function save_promosi($anggaran="",$cabang="") {       

        $data   = json_decode(post('json'),true);

        // echo post('json');
        foreach($data as $getId => $record) {

            // if($record['keterangan'])
            // $record = insert_view_report_arr($record);
            // echo $id." - ".$cekId[1]."<br>";
            $cek  = get_data('tbl_biaya_promosi a',[
                'select'    => 'a.id',
                'where'     => [
                    'a.kode_anggaran'   => $anggaran,
                    'a.kode_cabang'   => $cabang,
                    'a.no'  => $getId,
                ]
            ])->result_array();
     
            if(count($cek) > 0){
                update_data('tbl_biaya_promosi', $record,'id',$cek[0]['id']);
            }else {
                    $record['kode_anggaran'] = $anggaran;
                    $record['kode_cabang'] = $cabang;
                    $record['no'] = $getId;
                    insert_data('tbl_biaya_promosi',$record);
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