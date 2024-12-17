<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kredit extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'kredit';
    function __construct() {
        parent::__construct();
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('kredit')
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

        $data['detail_tahun']   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'distinct a.kode_anggaran, a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => user('kode_anggaran'),
                'a.sumber_data'   => array(1,2,3)
            ],
            'order_by' => 'tahun,bulan'
        ])->result_array();
        return $data;
    }
    
    function index($p1="") { 
        $access         = get_access($this->controller);
        $data = $this->data_cabang();
        $data['access_additional']  = $access['access_additional'];
        $data['controller']	= $this->controller;
        render($data,'view:'.$this->path.'kredit/index');

    }

    function data($kode_anggaran="", $kode_cabang="") {

        $nama_cabang ='';
        $cab = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();               
        if(isset($cab->nama_cabang)) $nama_cabang = $cab->nama_cabang;

        $anggaran = get_data('tbl_tahun_anggaran',[
            'select' => '*',
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
            ],
        ])->row();

        if(isset($anggaran)) $tahun_anggaran = $anggaran->tahun_anggaran;

        $kredit =[];
        $tcore =[];
        for($i = $tahun_anggaran-3; $i <= $tahun_anggaran-2; $i++){ 
            $kredit[] = 'TOTAL KREDIT ' . $i . ' (Realisasi)';
            $tcore[] = $i;  
        } 
                

        $ccore      = get_data('tbl_cek_data_cabang',[
            'select' => 'status_plan',
            'where'  => [
          //      'status_plan' => 0, 
                'kode_cabang' => $kode_cabang,
            ]
        ])->row();

        if(isset($ccore->status_plan)){
            foreach ($tcore as $g => $v_) {
                $tabel = 'tbl_history_' . $v_;    
                $g = 'kredit_' . $v_;
                if(table_exists($tabel)) {
                    $v = '';
                    for ($i = 1; $i <= 12; $i++) { 
                        $v = 'C_'. sprintf("%02d", $i);
                        $$v = 0;
                    }   

                    $TOT_cab = 'TOT_' . $kode_cabang ;    

                    $field_tabel    = get_field($tabel,'name');

                    if (in_array($TOT_cab, $field_tabel)) {
                        $TOT_cab = 'TOT_' . $kode_cabang ;    
                    }else{
                        $TOT_cab = 0 ;    
                    }

                    $arr            = [
                    'select'    => '
                        coalesce(sum(case when substr(glwdat,5,2) = "01" then '.$TOT_cab.' end), 0) as C_01,
                        coalesce(sum(case when substr(glwdat,5,2) = "02" then '.$TOT_cab.' end), 0) as C_02,
                        coalesce(sum(case when substr(glwdat,5,2) = "03" then '.$TOT_cab.' end), 0) as C_03,
                        coalesce(sum(case when substr(glwdat,5,2) = "04" then '.$TOT_cab.' end), 0) as C_04,
                        coalesce(sum(case when substr(glwdat,5,2) = "05" then '.$TOT_cab.' end), 0) as C_05,
                        coalesce(sum(case when substr(glwdat,5,2) = "06" then '.$TOT_cab.' end), 0) as C_06,
                        coalesce(sum(case when substr(glwdat,5,2) = "07" then '.$TOT_cab.' end), 0) as C_07,
                        coalesce(sum(case when substr(glwdat,5,2) = "08" then '.$TOT_cab.' end), 0) as C_08,
                        coalesce(sum(case when substr(glwdat,5,2) = "09" then '.$TOT_cab.' end), 0) as C_09,
                        coalesce(sum(case when substr(glwdat,5,2) = "10" then '.$TOT_cab.' end), 0) as C_10,
                        coalesce(sum(case when substr(glwdat,5,2) = "11" then '.$TOT_cab.' end), 0) as C_11,
                        coalesce(sum(case when substr(glwdat,5,2) = "12" then '.$TOT_cab.' end), 0) as C_12',
                    'where' => [
                        'tahun' => $v_,
                        'glwnco' => ["122502","122506"],
                        ],
                    ];

                    $core = get_data($tabel,$arr)->row_array();
                    if($core){
                        $C_01 = $core['C_01'] * -1;
                        $C_02 = $core['C_02'] * -1;
                        $C_03 = $core['C_03'] * -1;
                        $C_04 = $core['C_04'] * -1;
                        $C_05 = $core['C_05'] * -1;
                        $C_06 = $core['C_06'] * -1;
                        $C_07 = $core['C_07'] * -1;
                        $C_08 = $core['C_08'] * -1;
                        $C_09 = $core['C_09'] * -1;
                        $C_10 = $core['C_10'] * -1;
                        $C_11 = $core['C_11'] * -1;
                        $C_12 = $core['C_12'] * -1;
                    }

                    $data2 = array(
                        'kode_anggaran' => $kode_anggaran,
                        'keterangan_anggaran' => $anggaran->keterangan, 
                        'tahun_anggaran'  => $anggaran->tahun_anggaran,
                        'kode_cabang'   => $kode_cabang,
                        'nama_cabang'        => $nama_cabang,
                        'coa'      => '2100000',
                        'account_name'  => 'TOTAL KREDIT',
                        'parent_id' => 0,
                        'tahun_core'  => $v_,
                        'sumber_data' => 1,
                        'keterangan'  =>  'TOTAL KREDIT ' . $v_ . ' (Realisasi)',
                        'P_01'=> $C_01,
                        'P_02'=> $C_02,
                        'P_03'=> $C_03,
                        'P_04'=> $C_04,
                        'P_05'=> $C_05,
                        'P_06'=> $C_06,
                        'P_07'=> $C_07,
                        'P_08'=> $C_08,
                        'P_09'=> $C_09,
                        'P_10'=> $C_10,
                        'P_11'=> $C_11,
                        'P_12'=> $C_12,
                    );

                    $cek        = get_data('tbl_budget_plan_kredit',[
                        'where'         => [
                            'kode_anggaran' => $kode_anggaran,
                            'kode_cabang'   => $kode_cabang,
                            'tahun_core'    => $v_,
                            'coa' => '2100000',
                            'sumber_data'   =>1
                            ],
                    ])->row();


 

                    if(!isset($cek->id)) {
                        $response = insert_data('tbl_budget_plan_kredit',$data2);
                    }else{
                        $data_update = array(
                            'account_name'  => 'TOTAL KREDIT',
                            'parent_id' => 0,
                            'tahun_core'      => $v_,
                            'sumber_data'       => 1,
                            'keterangan'    =>  'TOTAL KREDIT ' . $v_ . ' (Realisasi)',
                            'P_01'=> $C_01,
                            'P_02'=> $C_02,
                            'P_03'=> $C_03,
                            'P_04'=> $C_04,
                            'P_05'=> $C_05,
                            'P_06'=> $C_06,
                            'P_07'=> $C_07,
                            'P_08'=> $C_08,
                            'P_09'=> $C_09,
                            'P_10'=> $C_10,
                            'P_11'=> $C_11,
                            'P_12'=> $C_12,
                        );

                        $response = update_data('tbl_budget_plan_kredit',$data_update,['kode_cabang' => $kode_cabang,'kode_anggaran'=>$kode_anggaran,'tahun_core'=>$v_,'coa'=>'2100000','sumber_data'=>1]);
                    }    
                }                    

            }

            if($response) {
                update_data('tbl_cek_data_cabang',['status_plan'=>1],['kode_cabang'=>$kode_cabang]);
            }
        }

        $arr            = [
            'select'    => 'a.*',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.tahun_core'  => $tcore,
            ],
            'sort_by' => 'tahun_core'
        ];
            

        $data_view['item_ba']  = get_data('tbl_budget_plan_kredit a',$arr)->result_array();;
        $data_view['sub_item'] = get_data('tbl_subacc_budget_plan','is_active',1)->result_array();

        $view   = $this->load->view('transaction/budget_planner/kredit/data',$data_view,true);
     
        $data = [
            'data'              => $view,     
            'item'         => $data_view['item_ba'],
        ];

        render($data,'json');
    }

    function data2($kode_anggaran="", $kode_cabang="") {
        $nama_cabang ='';
        $cab = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();               
        if(isset($cab->nama_cabang)) $nama_cabang = $cab->nama_cabang;

        $anggaran = get_data('tbl_tahun_anggaran',[
            'select' => '*',
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
            ],
        ])->row();
        if(isset($anggaran)) $tahun_anggaran = $anggaran->tahun_anggaran;

        $kredit =[];
        $tcore =[];
        for($i = $tahun_anggaran-1; $i <= $tahun_anggaran; $i++){ 
            $kredit[] = 'TOTAL KREDIT ' . $i . ' (Realisasi)';
            $tcore[] = $i;  
        } 
    

        $ccore      = get_data('tbl_cek_data_cabang',[
            'select' => 'status_plan',
            'where'  => [
       //         'status_plan' => 0, 
                'kode_cabang' => $kode_cabang,
            ]
        ])->row();
       
        // $v = '';
        // for ($i = 1; $i <= 12; $i++) { 
        //     $v = 'C_'. sprintf("%02d", $i);
        //     $$v = 0;
        // }   
        $sumber_data = 1;                
        if(isset($ccore->status_plan)){
            foreach ($tcore as $g => $v_) {
                $tabel = 'tbl_history_' . $v_;    
                $g = 'kredit_' . $v_;
                if(table_exists($tabel)) {
                    $v = '';
                    for ($i = 1; $i <= 12; $i++) { 
                        $v = 'C_'. sprintf("%02d", $i);
                        $$v = 0;
                    }   

                    $TOT_cab = 'TOT_' . $kode_cabang ;    

                    $field_tabel    = get_field($tabel,'name');

                    if (in_array($TOT_cab, $field_tabel)) {
                        $TOT_cab = 'TOT_' . $kode_cabang ;    
                    }else{
                        $TOT_cab = 0 ;    
                    }

                    $arr            = [
                    'select'    => '
                        coalesce(sum(case when substr(glwdat,5,2) = "01" then '.$TOT_cab.' end), 0) as C_01,
                        coalesce(sum(case when substr(glwdat,5,2) = "02" then '.$TOT_cab.' end), 0) as C_02,
                        coalesce(sum(case when substr(glwdat,5,2) = "03" then '.$TOT_cab.' end), 0) as C_03,
                        coalesce(sum(case when substr(glwdat,5,2) = "04" then '.$TOT_cab.' end), 0) as C_04,
                        coalesce(sum(case when substr(glwdat,5,2) = "05" then '.$TOT_cab.' end), 0) as C_05,
                        coalesce(sum(case when substr(glwdat,5,2) = "06" then '.$TOT_cab.' end), 0) as C_06,
                        coalesce(sum(case when substr(glwdat,5,2) = "07" then '.$TOT_cab.' end), 0) as C_07,
                        coalesce(sum(case when substr(glwdat,5,2) = "08" then '.$TOT_cab.' end), 0) as C_08,
                        coalesce(sum(case when substr(glwdat,5,2) = "09" then '.$TOT_cab.' end), 0) as C_09,
                        coalesce(sum(case when substr(glwdat,5,2) = "10" then '.$TOT_cab.' end), 0) as C_10,
                        coalesce(sum(case when substr(glwdat,5,2) = "11" then '.$TOT_cab.' end), 0) as C_11,
                        coalesce(sum(case when substr(glwdat,5,2) = "12" then '.$TOT_cab.' end), 0) as C_12',
                    'where' => [
                        'tahun' => $v_,
                        'glwnco' => ["122502","122506"],
                        ],
                    ];

                    $core = get_data($tabel,$arr)->row_array();

                    if($v_ != user('tahun_anggaran')){
                        /// cari per item kredit hasil core 
                        $TOT_cab = 'TOT_' . $kode_cabang ;    

                        $field_tabel    = get_field($tabel,'name');

                        if (in_array($TOT_cab, $field_tabel)) {
                            $TOT_cab = 'TOT_' . $kode_cabang ;    
                        }else{
                            $TOT_cab = 0 ;    
                        }

                        $arr            = [
                        'select'    => 'glwnco,
                            coalesce(sum(case when substr(glwdat,5,2) = "01" then '.$TOT_cab.' end), 0) as C_01,
                            coalesce(sum(case when substr(glwdat,5,2) = "02" then '.$TOT_cab.' end), 0) as C_02,
                            coalesce(sum(case when substr(glwdat,5,2) = "03" then '.$TOT_cab.' end), 0) as C_03,
                            coalesce(sum(case when substr(glwdat,5,2) = "04" then '.$TOT_cab.' end), 0) as C_04,
                            coalesce(sum(case when substr(glwdat,5,2) = "05" then '.$TOT_cab.' end), 0) as C_05,
                            coalesce(sum(case when substr(glwdat,5,2) = "06" then '.$TOT_cab.' end), 0) as C_06,
                            coalesce(sum(case when substr(glwdat,5,2) = "07" then '.$TOT_cab.' end), 0) as C_07,
                            coalesce(sum(case when substr(glwdat,5,2) = "08" then '.$TOT_cab.' end), 0) as C_08,
                            coalesce(sum(case when substr(glwdat,5,2) = "09" then '.$TOT_cab.' end), 0) as C_09,
                            coalesce(sum(case when substr(glwdat,5,2) = "10" then '.$TOT_cab.' end), 0) as C_10,
                            coalesce(sum(case when substr(glwdat,5,2) = "11" then '.$TOT_cab.' end), 0) as C_11,
                            coalesce(sum(case when substr(glwdat,5,2) = "12" then '.$TOT_cab.' end), 0) as C_12',
                        'where' => [
                            'tahun' => $v_,
                            'glwnco' => ["122502","122506"],
                            ],   
                        'group_by' => 'glwnco',     
                        ];

                        $data_view['hsl_core'] = get_data($tabel,$arr)->result_array();
                    }

                    if($core){
                        $C_01 = $core['C_01'] * -1;
                        $C_02 = $core['C_02'] * -1;
                        $C_03 = $core['C_03'] * -1;
                        $C_04 = $core['C_04'] * -1;
                        $C_05 = $core['C_05'] * -1;
                        $C_06 = $core['C_06'] * -1;
                        $C_07 = $core['C_07'] * -1;
                        $C_08 = $core['C_08'] * -1;
                        $C_09 = $core['C_09'] * -1;
                        $C_10 = $core['C_10'] * -1;
                        $C_11 = $core['C_11'] * -1;
                        $C_12 = $core['C_12'] * -1;
                    }

                    $bln_anggaran = get_data('tbl_detail_tahun_anggaran',[
                        'select' => 'distinct tahun,bulan,sumber_data',
                        'where'  => [
                            'tahun' => $v_,
                            'kode_anggaran' => $kode_anggaran,
                        ]   
                    ])->result();


                }    

                    $bln_anggaran1 = get_data('tbl_detail_tahun_anggaran',[
                        'select' => 'distinct tahun,bulan,sumber_data',
                        'where'  => [
                            'kode_anggaran' => $kode_anggaran,
                        ]   
                    ])->result();

                    $data_view['bln_anggaran'] = $bln_anggaran1;

                    if($bln_anggaran1) {
                        foreach ($bln_anggaran1 as $bln) {
                            $sumber_data = $bln->sumber_data;
                            $v_bln = 'B_' . sprintf("%02d", $bln->bulan) ; 
                            // masukan data budget estimasi tahun lalu dari index besaran
                            if($bln->sumber_data == 2 || $bln->sumber_data == 3) {
                                $v_hsl = 'hasil' . $bln->bulan ;  
                                $vd = 'C_'. sprintf("%02d", $bln->bulan);
                                $val = get_data('tbl_indek_besaran',[
                                    'select' => 'kode_cabang, sum('.$v_hsl.') as total ',
                                    'where'  => [
                                        'kode_anggaran' => $kode_anggaran,
                                        'coa'   => ['122502','122506'],
                                        'kode_cabang' => $kode_cabang
                                    ]    
                                ])->row();
                                if(isset($val->kode_cabang)) {
                                    $$vd = $val->total;   
                                }
                            }

                            if($bln->sumber_data == 1) {
                                foreach ($bln_anggaran as $bln) {
                                    $vd = 'C_'. sprintf("%02d", $bln->bulan);
                                    if($core){
                                        $$vd = $core[$vd] * -1;
                                    }

                                }
                            }    

                        }
                    }

                $ket_data = ' (Realisasi)';
            //    $sumber_data = 1;
                if($v_ == $anggaran->tahun_anggaran) $ket_data = ' (Rencana)'; 
            //    if($v_ == $anggaran->tahun_anggaran) $sumber_data = 3; 

                    $data2 = array(
                        'kode_anggaran' => $kode_anggaran,
                        'keterangan_anggaran' => $anggaran->keterangan, 
                        'tahun_anggaran'  => $anggaran->tahun_anggaran,
                        'kode_cabang'   => $kode_cabang,
                        'nama_cabang'        => $nama_cabang,
                        'coa'      => '2100000',
                        'account_name'  => 'TOTAL KREDIT',
                        'parent_id' => 0,
                        'tahun_core'  => $v_,
                        'sumber_data' => 1,
                        'keterangan'  =>  'TOTAL KREDIT ' . $v_ . $ket_data,
                        'P_01'=> $C_01,
                        'P_02'=> $C_02,
                        'P_03'=> $C_03,
                        'P_04'=> $C_04,
                        'P_05'=> $C_05,
                        'P_06'=> $C_06,
                        'P_07'=> $C_07,
                        'P_08'=> $C_08,
                        'P_09'=> $C_09,
                        'P_10'=> $C_10,
                        'P_11'=> $C_11,
                        'P_12'=> $C_12,
                    );

                    $cek        = get_data('tbl_budget_plan_kredit',[
                        'where'         => [
                            'kode_anggaran' => $kode_anggaran,
                            'kode_cabang'   => $kode_cabang,
                            'tahun_core'    => $v_,
                            'coa' => '2100000',
                            'sumber_data'   =>1
                            ],
                    ])->row();


                    if(!isset($cek->id)) {
                        $response = insert_data('tbl_budget_plan_kredit',$data2);
                    }else{
                        $data_update = array(
                            'account_name'  => 'TOTAL KREDIT',
                            'parent_id' => 0,
                            'tahun_core'      => $v_,
                            'sumber_data'       => 1,
                            'keterangan'    =>  'TOTAL KREDIT ' . $v_ . $ket_data,
                            'P_01'=> $C_01,
                            'P_02'=> $C_02,
                            'P_03'=> $C_03,
                            'P_04'=> $C_04,
                            'P_05'=> $C_05,
                            'P_06'=> $C_06,
                            'P_07'=> $C_07,
                            'P_08'=> $C_08,
                            'P_09'=> $C_09,
                            'P_10'=> $C_10,
                            'P_11'=> $C_11,
                            'P_12'=> $C_12,
                        );

                        $response = update_data('tbl_budget_plan_kredit',$data_update,['kode_cabang' => $kode_cabang,'kode_anggaran'=>$kode_anggaran,'tahun_core'=>$v_,'coa'=>'2100000','sumber_data'=>1]);
                    }    
                }                    

  

            if($response) {
                update_data('tbl_cek_data_cabang',['status_plan'=>1],['kode_cabang'=>$kode_cabang]);
            }
        }         
        
        $arr            = [
            'select'    => 'a.*',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.tahun_core' => $tcore,
                'a.parent_id'  => 0, 
            ],
            'sort_by' => 'tahun_core'
        ];

        $arr_0            = [
            'select'    => 'a.*',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.tahun_core' => $tahun_anggaran - 2,
                'a.parent_id'  => 0, 
            ],
            'sort_by' => 'tahun_core'
        ];
            

        $data_view['item_ba2']  = get_data('tbl_budget_plan_kredit a',$arr)->result_array();
        $data_view['item_ba0']  = get_data('tbl_budget_plan_kredit a',$arr_0)->result_array();


        $arr            = [
            'select'    => 'a.*',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.parent_id' => 0   
            ],
            'sort_by' => 'tahun_core'
        ];

        $data_view['item_chart']  = get_data('tbl_budget_plan_kredit a',$arr)->result_array();


        $field_tabel    = get_field('tbl_rate','name');
        $TOT_cab = 'TOT_' . $kode_cabang ;    
        if (in_array($TOT_cab, $field_tabel)) {
            $data_view['sub_item'] = get_data('tbl_subacc_budget_plan a',[
                    'select' => 'a.*,'.$TOT_cab.' as rate',
                    'join'   => ["tbl_rate b on a.sub_coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",
                    ],
                    'where' => [
                        'a.is_active' => 1,
                        'grup_coa'  => ['122502','122506']
                    ]
                ])->result_array();
        }

        $hsl_sub =  get_data('tbl_indek_besaran',[
                'select' => 'coa,hasil1 as hasil1,
                    hasil2 as hasil2, hasil3 as hasil3,
                    hasil4 as hasil4, hasil5 as hasil5,
                    hasil6 as hasil6, hasil7 as hasil7,
                    hasil8 as hasil8, hasil9 as hasil9,
                    hasil10 as hasil10, hasil11 as hasil11,
                    hasil12 as hasil12',
                'where'  => [
                    'kode_anggaran' => $kode_anggaran,
                    'coa'   => ['122502','122506'],
                    'parent_id' => 0,
                    'kode_cabang' => $kode_cabang
                ],
                'group_by' => 'coa',
            ])->result_array();
        
        $data_view['hsl_sub'] = $hsl_sub;

        $hsl_sub0 =  get_data('tbl_indek_besaran',[
                'select' => 'coa,hasil1 as hasil1,
                    hasil2 as hasil2, hasil3 as hasil3,
                    hasil4 as hasil4, hasil5 as hasil5,
                    hasil6 as hasil6, hasil7 as hasil7,
                    hasil8 as hasil8, hasil9 as hasil9,
                    hasil10 as hasil10, hasil11 as hasil11,
                    hasil12 as hasil12',
                'where'  => [
                    'kode_anggaran' => $kode_anggaran,
                    'coa'   => ['122502','122506'],
                    'parent_id !=' => 0,
                    'kode_cabang' => $kode_cabang
                ],
                'group_by' => 'coa',
            ])->result_array();
        
        $data_view['hsl_sub0'] = $hsl_sub0; 
   //     debug($hsl_sub0);die; 

        $view   = $this->load->view('transaction/budget_planner/kredit/data2',$data_view,true);
     
        $data = [
            'data'              => $view,     
            'item2'         => $data_view['item_ba2'],
            'item_chart'         => $data_view['item_chart'],
        ];
        render($data,'json');
    }

    function data3($kode_anggaran="", $kode_cabang=""){

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $nama_cabang ='';
        $cab = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();               
        if(isset($cab->nama_cabang)) $nama_cabang = $cab->nama_cabang;

        $anggaran = get_data('tbl_tahun_anggaran',[
            'select' => '*',
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
            ],
        ])->row();

        if(isset($anggaran)) $tahun_anggaran = $anggaran->tahun_anggaran;

        $TOT_cab = 'TOT_' . $kode_cabang ;   
        $field_tabel    = get_field('tbl_rate','name');

        if (in_array($TOT_cab, $field_tabel)) {
            $arr            = [
                'select'    => '
                    a.id,a.coa, a.grup, a.nama_produk_kredit as account_name,'.$TOT_cab.' as rate',
                'join'      => "tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",    
            ];
        }else{
            $arr            = [
                'select'    => '
                    a.id,a.coa, a.grup , a.nama_produk_kredit as account_name, 0 as rate',
                'join'      => "tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",    
            ];            
        }

        // if($anggaran) {
        //     $arr['where']['a.kode_anggaran']  = $kode_anggaran;
        // }
        
        // if($cabang) {
        //     $arr['where']['a.kode_cabang']  = $kode_cabang;
        // }
        $arr['where']['a.is_active'] = 1;
        $arr['order_by']  = 'a.nama_produk_kredit';
        $list_k = get_data('tbl_produk_kredit a',$arr)->result();

        $data['list_k'] = $list_k;
        $t =[];
        $data['detail_tahun']   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'distinct a.id_tahun_anggaran,a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data type LEFT',
            'where'     => [
                'a.kode_anggaran' => user('kode_anggaran'),
                'a.sumber_data'   => array(1,2,3)
            ],
            'order_by' => 'tahun,bulan'
        ])->result_array();



        foreach ($data['detail_tahun'] as $k => $v) {
            $t[$v['tahun']][] = $v['bulan'];
        }


        $l_coa = [];
        if(count($list_k) > 0) {
            foreach ($list_k as $l) {
                $l_coa[] = $l->coa;
                foreach ($t as $key => $value) {
                    $cek        = get_data('tbl_budget_plan_kredit',[
                        'where'         => [
                            'kode_anggaran' => $kode_anggaran,
                            'kode_cabang'   => $kode_cabang,
                            'coa' => $l->coa,
                            'tahun_core' => $key
                            ],
                    ])->row();

                $p_id = get_data('tbl_budget_plan_kredit',[
                    'select' => 'id',
                    'where'  => [
                        'kode_anggaran' => $kode_anggaran,
                        'kode_cabang'   => $kode_cabang,
                        'tahun_core' => $key
                    ],   
                ])->row();    

                $pid = 0;
                if(isset($p_id->id)) $pid = $p_id->id;

                if(!isset($cek->id)) {
                    $data2 = array(
                        'kode_anggaran' => $kode_anggaran,
                        'keterangan_anggaran' => $anggaran->keterangan, 
                        'tahun_anggaran'  => $anggaran->tahun_anggaran,
                        'kode_cabang'   => $kode_cabang,
                        'nama_cabang'        => $nama_cabang,
                        'coa'      => $l->coa,
                        'account_name'  => $l->account_name,
                        'parent_id' => $pid,
                        'tahun_core'  => $key,
                        'keterangan'  =>  $l->account_name,
                        'is_edit' => '[]',
                    );

                    $response = insert_data('tbl_budget_plan_kredit',$data2);
                    }else{
                        $data_update = array(
                            'account_name'  => $l->account_name,
                            'parent_id' => $pid,
                            'tahun_core'  => $key,
                            'keterangan'  =>  $l->account_name,

                        );

                        $response = update_data('tbl_budget_plan_kredit',$data_update,['kode_cabang' => $kode_cabang,'kode_anggaran'=>$kode_anggaran,'tahun_core'=>$key,'coa'=>$l->coa]);
                    }  

                }
            }      
        }         



      //  debug($data['list_kd']);die; 

        $data['cabang'] = $kode_cabang;

        $data['grup_kr'] = get_data('tbl_produk_kredit a', [
            'select' => 'distinct a.grup, b.glwdes as account_name',
            'join'   => 'tbl_m_coa b on a.grup = b.glwnco type LEFT',   
        ])->result_array();

        //debug($data['grup_kr']);die;

        $select = 'TOT_'.$kode_cabang;
        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result_array();

        $tahun = 'tbl_history_'.$data['tahun'][0]['tahun_terakhir_realisasi'];

        $field_tabel    = get_field($tahun,'name');

        if (in_array($select, $field_tabel)) {
            $select = 'TOT_' . $kode_cabang ;    
        }else{
            $select = 0 ;    
        }

        $getMinBulan = $data['tahun'][0]['bulan_terakhir_realisasi'] - 1;

        $data['awal_anggaran'] = $data['tahun'][0]['tahun_terakhir_realisasi'] . 
        sprintf("%02d", $data['tahun'][0]['bulan_terakhir_realisasi'] + 1);

        $data['B'] = get_data($tahun,[

            'select'    => 
                    "glwnco,coalesce(sum(case when bulan = '".$data['tahun'][0]['bulan_terakhir_realisasi']."'  then ".$select." end), 0) as hasil10,
                    coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9,
                    account_name,
                    coa,
                    gwlsbi,
                    glwnco",

            'where'     => [
            'glwnco' => $l_coa,       
            ],
            'group_by' => 'glwnco',
        ])->result();  


        $jum_produktif1 = get_data('tbl_indek_besaran',[
            'select' => 'sum(hasil1) as hasil1,sum(hasil2) as hasil2,sum(hasil3) as hasil3,sum(hasil4) as hasil4,sum(hasil5) as hasil5,sum(hasil6) as hasil6,sum(hasil7) as hasil7,sum(hasil8) as hasil8,sum(hasil9) as hasil9,sum(hasil10) as hasil10,sum(hasil11) as hasil11,sum(hasil12) as hasil12',
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
                'coa'   => ['122502'],
                'parent_id' => 0,
                'kode_cabang' => $kode_cabang
        ]    
        ])->row_array();

        $data['jum_produktif1'] =  $jum_produktif1;

        $jum_produktif0 = get_data('tbl_indek_besaran',[
            'select' => 'sum(hasil1) as hasil1,sum(hasil2) as hasil2,sum(hasil3) as hasil3,sum(hasil4) as hasil4,sum(hasil5) as hasil5,sum(hasil6) as hasil6,sum(hasil7) as hasil7,sum(hasil8) as hasil8,sum(hasil9) as hasil9,sum(hasil10) as hasil10,sum(hasil11) as hasil11,sum(hasil12) as hasil12',
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
                'coa'   => ['122502'],
                'parent_id !=' => 0,
                'kode_cabang' => $kode_cabang,
        ]    
        ])->row_array();
        
        $data['jum_produktif0'] = $jum_produktif0 ;

        $jum_konsumtif1 = get_data('tbl_indek_besaran',[
            'select' => 'sum(hasil1) as hasil1,sum(hasil2) as hasil2,sum(hasil3) as hasil3,sum(hasil4) as hasil4,sum(hasil5) as hasil5,sum(hasil6) as hasil6,sum(hasil7) as hasil7,sum(hasil8) as hasil8,sum(hasil9) as hasil9,sum(hasil10) as hasil10,sum(hasil11) as hasil11,sum(hasil12) as hasil12',
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
                'coa'   => ['122506'],
                'parent_id' => 0,
                'kode_cabang' => $kode_cabang,
        ]    
        ])->row_array();

        $data['jum_konsumtif1'] = $jum_konsumtif1;

        $jum_konsumtif0 = get_data('tbl_indek_besaran',[
            'select' => 'sum(hasil1) as hasil1,sum(hasil2) as hasil2,sum(hasil3) as hasil3,sum(hasil4) as hasil4,sum(hasil5) as hasil5,sum(hasil6) as hasil6,sum(hasil7) as hasil7,sum(hasil8) as hasil8,sum(hasil9) as hasil9,sum(hasil10) as hasil10,sum(hasil11) as hasil11,sum(hasil12) as hasil12',  
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
                'coa'   => ['122506'],
                'parent_id !=' => 0,
                'kode_cabang' => $kode_cabang,
        ]    
        ])->row_array();

        $data['jum_konsumtif0'] = $jum_konsumtif0;

        $arr_nonkup1            = [
            'select'    => 'sum(P_01) as P_01,sum(P_02) as P_02,sum(P_03) as P_03,sum(P_04) as P_04,sum(P_05) as P_05,sum(P_06) as P_06,sum(P_07) as P_07,sum(P_08) as P_08,sum(P_09) as P_09,sum(P_10) as P_10,sum(P_11) as P_11,sum(P_12) as P_12',
            'join' => 'tbl_produk_kredit b on a.coa = b.coa type LEFT',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.tahun_core' => $tahun_anggaran,
                'a.parent_id !='  => 0,
                'a.coa !=' =>  '1454321',
                'b.is_active' => 1,
                'b.grup' => '122502'
            ],
            'sort_by' => 'tahun_core'
        ];   


        $arr_nonkup0            = [
            'select'    => 'sum(P_01) as P_01,sum(P_02) as P_02,sum(P_03) as P_03,sum(P_04) as P_04,sum(P_05) as P_05,sum(P_06) as P_06,sum(P_07) as P_07,sum(P_08) as P_08,sum(P_09) as P_09,sum(P_10) as P_10,sum(P_11) as P_11,sum(P_12) as P_12',
            'join' => 'tbl_produk_kredit b on a.coa = b.coa type LEFT',            
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.tahun_core' => $tahun_anggaran -1,
                'a.parent_id !='  => 0,
                'a.coa !=' =>  '1454321',
                'b.is_active' => 1,
                'b.grup' => '122502'
            ],
            'sort_by' => 'tahun_core'
        ];      

        $arr_nonloan1            = [
            'select'    => 'sum(P_01) as P_01,sum(P_02) as P_02,sum(P_03) as P_03,sum(P_04) as P_04,sum(P_05) as P_05,sum(P_06) as P_06,sum(P_07) as P_07,sum(P_08) as P_08,sum(P_09) as P_09,sum(P_10) as P_10,sum(P_11) as P_11,sum(P_12) as P_12',
            'join' => 'tbl_produk_kredit b on a.coa = b.coa type LEFT',  
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.tahun_core' => $tahun_anggaran,
                'a.parent_id !='  => 0,          
                'a.coa !=' =>  '1454327',
                'b.is_active' => 1,  
                'b.grup' => '122506'                    
            ],
            'sort_by' => 'tahun_core'
        ];   

        $arr_nonloan0            = [
            'select'    => 'sum(P_01) as P_01,sum(P_02) as P_02,sum(P_03) as P_03,sum(P_04) as P_04,sum(P_05) as P_05,sum(P_06) as P_06,sum(P_07) as P_07,sum(P_08) as P_08,sum(P_09) as P_09,sum(P_10) as P_10,sum(P_11) as P_11,sum(P_12) as P_12',
            'join' => 'tbl_produk_kredit b on a.coa = b.coa type LEFT',              
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,        
                'a.tahun_core' => $tahun_anggaran -1,
                'a.parent_id !='  => 0,
                'a.coa !=' =>  '1454327',
                'b.is_active' => 1,      
                'b.grup' => '122506'                
            ],
            'sort_by' => 'tahun_core'
        ];  


        $jml_nonkup1 = get_data('tbl_budget_plan_kredit a',$arr_nonkup1)->row_array();


        $jml_nonkup0  = get_data('tbl_budget_plan_kredit a',$arr_nonkup0)->row_array();
        $jml_nonloan1 = get_data('tbl_budget_plan_kredit a',$arr_nonloan1)->row_array();
        $jml_nonloan0 = get_data('tbl_budget_plan_kredit a',$arr_nonloan0)->row_array();

            
        $data['jml_nonkup1']  = $jml_nonkup1 ;
        $data['jml_nonkup0']  = $jml_nonkup0 ;
        $data['jml_nonloan1']  = $jml_nonloan1 ;
        $data['jml_nonloan0']  = $jml_nonloan0 ;

        $arr            = [
            'select'    => 'a.*',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.parent_id !='  => 0, 
        //        'a.coa' => ['1454353','1454337']
            ],
            'sort_by' => 'tahun_core'
        ];

        $list_kd  = get_data('tbl_budget_plan_kredit a',$arr)->result();


        $i_bln=0 ; 
        $xxxx='';
        foreach ($data['detail_tahun'] as $d => $value) { 
            $xxx = 'JML_'.   $value['tahun'] . sprintf("%02d", $value['bulan']);
            $$xxx = 0;

        }
        
        foreach ($list_kd as $kd) {  
            foreach ($data['detail_tahun'] as $d => $value) { 
                $i_bln ++;

                $vfield = 'P_'. sprintf("%02d", $value['bulan']);
                $vfield1 = 'hasil'. $value['bulan'];

                $edited = json_decode($kd->is_edit,true);

                    if($value['tahun'] == $kd->tahun_core){  
                        $j1 = 'JML_'.   $value['tahun'] . sprintf("%02d", $value['bulan']);
                        $j0 = 'JML_'.   $value['tahun'] . sprintf("%02d", $value['bulan']-1);
                        $vfield = 'P_'. sprintf("%02d", $value['bulan']);
                          // cari netto untuk ditampilkan sebagai default
                            foreach ($data['B'] as $val) {
                                $vdefault = 0;
                                $vnetto_1 = 0;
                                $vnetto = 0;        
                                if($val->glwnco == $kd->coa ){                     
                                  
                                  $vnetto1  = (($val->hasil9 * -1) - ($val->hasil10 * -1));

                                  if(isset($kd->netto)) $vnetto1 = $kd->netto;

                                  if($vnetto1 != 0 ) {
                                    $vnetto = $vnetto1;
                                    $vdefault = $vnetto + ($val->hasil9 * -1) ;
                                    

                                  }else{
                                    $vdefault=($val->hasil9 * -1);

                                  }  

                                  if(!isset($$j0))  $$j0 = 0;

                                  if($data['awal_anggaran']  == substr($j1,4) && $vnetto !=0) {  

                                      $vnetto = $vnetto1;
                                      $$j1 = $vdefault ;//+ ($val->hasil9 * -1) ;
                                      

                                  }elseif ($vnetto==0) {
                                      $$j1 = $vdefault ;

                                        if(isset($edited) && count($edited) > 0) {
                                            foreach ($edited as $x => $v_edited) {
                                                $tahun   = substr($x,0,4);
                                                $bulan   = substr($x,4);
                                                $j1 = 'JML_'.   $tahun . sprintf("%02d", $bulan);
                                                $$j1 = $vdefault;
                                            }
                                        }

                                  }else{
                                      if($value['bulan'] == 1) {
                                            $$j1 = $vdefault;
                                      }else{
                                            $$j1 = (float) $vnetto + (float) $$j0;
                                      }
                                  }                                
                                }             


                            }


                                if($kd->coa =='1454321' && $value['tahun'] == user('tahun_anggaran')) {
                                      $$j1 = $jum_produktif1[$vfield1] - $jml_nonkup1[$vfield]; 
                                }

                                if($kd->coa =='1454321' && $value['tahun'] == user('tahun_anggaran') - 1) {
                                      $$j1 = $jum_produktif0[$vfield1] - $jml_nonkup0[$vfield]; 
                                }

                                if($kd->coa =='1454327' && $value['tahun'] == user('tahun_anggaran')) {
                                      $$j1 = $jum_konsumtif1[$vfield1] - $jml_nonloan1[$vfield]; 
                                }

                                if($kd->coa =='1454327' && $value['tahun'] == user('tahun_anggaran') - 1) {
                                      $$j1 = $jum_konsumtif0[$vfield1] - $jml_nonloan0[$vfield]; 
                                }


                                  $data_update = array(
                                        $vfield  => $$j1,
                                  );  

                                $vfield2 =''; 
                                if(isset($edited) && count($edited) > 0) {
                                   foreach ($edited as $x => $v_edited) {
                                       $tahun   = substr($x,0,4);
                                       $bulan   = substr($x,4);
                                       $j11 = 'JML_'.   $tahun . sprintf("%02d", $bulan);
                                       $vfield2 = 'P_' . sprintf("%02d", $bulan);
                                       $vedit = $v_edited ;
                                       $$j11  = $vedit ;
                                                                             
                                       $data_update = array(
                                            $vfield2  => $$j11,
                                       );  
                                   }
                                }


                                    if($vfield != $vfield2) {
                                        $j0 = 'JML_'.   $value['tahun'] . sprintf("%02d", $value['bulan']);
                                        $data_update = array(
                                            $vfield  => $$j0  ,
                                        );  
                                    }elseif ($vfield == '10' and $value['tahun']=='2020')  {
                                        $data_update = array(
                                            $vfield  => 0 ,
                                        );  
                                    }

                       

                              $response = update_data('tbl_budget_plan_kredit',$data_update,['kode_cabang' => $kode_cabang,'kode_anggaran'=>$kode_anggaran,'tahun_core'=>$kd->tahun_core,'coa'=>$kd->coa]);

                    }

             }        

        }    


        $arr_sum            = [
            'select'    => 'b.grup, a.tahun_core, sum(P_01) as P_01,sum(P_02) as P_02,sum(P_03) as P_03,sum(P_04) as P_04,sum(P_05) as P_05,sum(P_06) as P_06,sum(P_07) as P_07,sum(P_08) as P_08,sum(P_09) as P_09,sum(P_10) as P_10,sum(P_11) as P_11,sum(P_12) as P_12',
            'join' => 'tbl_produk_kredit b on a.coa = b.coa type LEFT',     
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.parent_id !='  => 0, 
                'b.is_active' => 1
            ],
            'group_by' => 'b.grup,a.tahun_core',
            'sort_by'  => 'a.tahun_core'
        ];



        $data['list_kd']  =  get_data('tbl_budget_plan_kredit a',$arr)->result();
        $data['list_sum']  =  get_data('tbl_budget_plan_kredit a',$arr_sum)->result();
        $view   = $this->load->view('transaction/budget_planner/kredit/data3',$data,true);
     
        $data = [
            'data'              => $view,
        ];


        render($data,'json');
    }

    function data4($kode_anggaran="", $kode_cabang="") {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $field_tabel    = get_field('tbl_rate','name');

        $nama_cabang ='';
        $cab = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();               
        if(isset($cab->nama_cabang)) $nama_cabang = $cab->nama_cabang;

        $anggaran = get_data('tbl_tahun_anggaran',[
            'select' => '*',
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
            ],
        ])->row();

        if(isset($anggaran)) $tahun_anggaran = $anggaran->tahun_anggaran;

        $t =[];
        $data['detail_tahun']   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'distinct a.id_tahun_anggaran,a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => user('kode_anggaran'),
                'a.sumber_data'   => array(1,2,3)
            ],
            'order_by' => 'tahun,bulan'
        ])->result_array();

 //       debug($data['detail_tahun']);die;

        foreach ($data['detail_tahun'] as $k => $v) {
            $t[$v['tahun']][] = $v['bulan'];
        }
  

        $sub_item = get_data('tbl_subacc_budget_plan',[
            'where' => [
                'is_active' => 1,
                'grup_coa'  =>  ['122502','122506'],
            ]
        ])->result_array();

        $data['sub_item'] = $sub_item;

        $s_item =[];
        if(count($sub_item) > 0) {
            $akhir = 0;
            foreach ($sub_item as $s) {
                $s_item[] = $s['sub_coa']; 

                        $TOT_cab = 'TOT_' . $kode_cabang ;   
                        $field_tabel    = get_field('tbl_rate','name');
                        
                        if (in_array($TOT_cab, $field_tabel)) {
                            $TOT_cab = 'TOT_' . $kode_cabang ;   
                        }else{
                            $TOT_cab = 0 ;
                        }  

                        $arr_jmlrek  = [
                            'select'    => ''.$TOT_cab.' as jumlah',
                            'where'     => [
                                'kode_anggaran' => $kode_anggaran,
                                'is_active' => 1,
                                'no_coa'    => $s['sub_coa'], 
                            ],
                        ];

                        $rek = get_data('tbl_import_jumlah_rekening',$arr_jmlrek)->row(); 

                        $jmlrek = 0;
                        if(isset($rek->jumlah)) $rek->jumlah ;

                        $n = 0;
                        $akhir = 0;
                        foreach ($data['detail_tahun'] as $k => $v) {

                            $jml = get_data('tbl_jumlah_rekening',[
                                'select' => 'index_kali as jumlah',
                                'where'  => [
                                    'kode_anggaran'=>$kode_anggaran,
                                    'kode_cabang'=>$kode_cabang,
                                    'coa'=>$s['sub_coa'],
                                    'tahun_core'=>$v['tahun']
                                ]
                            ])->row();

                            $xjml = 0;
                            if(isset($jml->jumlah)) $xjml = $jml->jumlah;

                            $data2 = array(
                                'kode_anggaran' => $kode_anggaran,
                                'keterangan_anggaran' => $anggaran->keterangan, 
                                'tahun_anggaran'  => $anggaran->tahun_anggaran,
                                'kode_cabang'   => $kode_cabang,
                                'nama_cabang'        => $nama_cabang,
                                'coa'      => $s['sub_coa'],
                                'account_name'  => $s['nama'],
                                'tahun_core'  => $v['tahun'],
                                'keterangan'  =>  $s['nama'],
                            );


                            $data_update = array(
                                'account_name'  => $s['nama'],
                                'tahun_core'  => $v['tahun'],
                                'keterangan'  =>  $s['nama'],
                            );


                            $n++;
                            if($v['sumber_data'] == 2 || $v['sumber_data'] == 1){
                                $field = 'P_'. sprintf("%02d", $v['bulan']);
                                $T = 'Jumlah' . $v['tahun'] . sprintf("%02d", $v['bulan']);
                                if($n>1){
                                    $T0 = 'Jumlah' . $v['tahun'] . sprintf("%02d", $v['bulan']-1);
                                    $$T = $$T0 ;    
                                }else{
                                    $$T = $jmlrek ; //$rek->jumlah;
                                }
                                $data2[$field] = $$T;
                                $akhir = $$T;

                                 $cek        = get_data('tbl_jumlah_rekening',[
                                    'where'         => [
                                        'kode_anggaran' => $kode_anggaran,
                                        'kode_cabang'   => $kode_cabang,
                                        'coa' => $s['sub_coa'],
                                        'tahun_core' => $v['tahun']
                                        ],
                                ])->row();

                                $vfield = 'P_' . sprintf("%02d", $v['bulan']);

                                $$T = $$T + $xjml ;

                                if(!isset($cek->id)){
                                    $response = insert_data('tbl_jumlah_rekening',$data2);    
                                }else{

                                    $jml = get_data('tbl_jumlah_rekening',[
                                        'select' => 'index_kali as jumlah, is_edit',
                                        'where'  => [
                                            'kode_anggaran'=>$kode_anggaran,
                                            'kode_cabang'=>$kode_cabang,
                                            'coa' => $s['sub_coa'],
                                            'tahun_core'=>$v['tahun']
                                        ]
                                    ])->row();

                                    $xjml =0;
                                    if(isset($jml->jumlah)) $xjml = $jml->jumlah;


                                    $edited =[];

                                    if(isset($jml->is_edit)) $edited = json_decode($jml->is_edit,true);
                               //     debug($edited);die;
                                                                        
                                    if(isset($edited) && count($edited) > 0) {
                                        foreach ($edited as $x => $v_edited) {
                                            $tahun   = substr($x,0,4);
                                            $bulan   = substr($x,4);
                                            if($v['tahun'] == $tahun && $v['bulan'] == $bulan) {
                                                $$T = $v_edited;
                                                $$T = $v_edited;
                                                $xjml = 0;

                                                $$T = $$T + $xjml;
                                            }else{
                                                $$T = $$T0 + $xjml; 
                                            }    
                                        }
                                    }

                                    $data_update[$field] = $$T;
                                    $response = update_data('tbl_jumlah_rekening',$data_update,['kode_cabang' => $kode_cabang,'kode_anggaran'=>$kode_anggaran,'tahun_core'=>$v['tahun'],'coa'=>$s['sub_coa']]);

                                } 

                            }else{
                                $field = 'P_'. sprintf("%02d", $v['bulan']);
                                $T = 'Jumlah' . $v['tahun'] . sprintf("%02d", $v['bulan']);
                                if($v['bulan']>1){
                                    $T0 = 'Jumlah' . $v['tahun'] . sprintf("%02d", $v['bulan']-1);
                                    $$T = $$T0 ;    
                                }else{
                                    $T0 = 'Jumlah' . ($v['tahun']-1) . sprintf("%02d", '12');
                                    $$T = $$T0 ;
                                }
                                $data2[$field] = $$T;


                                $cek        = get_data('tbl_jumlah_rekening',[
                                    'where'         => [
                                        'kode_anggaran' => $kode_anggaran,
                                        'kode_cabang'   => $kode_cabang,
                                        'coa' => $s['sub_coa'],
                                        'tahun_core' => $v['tahun']
                                        ],
                                ])->row();

                                $vfield = 'P_' . sprintf("%02d", $v['bulan']);

                                $jml = get_data('tbl_jumlah_rekening',[
                                    'select' => 'index_kali as jumlah',
                                    'where'  => [
                                        'kode_anggaran'=>$kode_anggaran,
                                        'kode_cabang'=>$kode_cabang,
                                        'coa'=>$s['sub_coa'],
                                        'tahun_core'=>$v['tahun']
                                    ]
                                ])->row();

                                $xjml = 0;
                                if(isset($jml->jumlah)) $xjml = $jml->jumlah;
                                $$T = $$T + $xjml ;
                                 
                                if(!isset($cek->id)) {
                                    $response = insert_data('tbl_jumlah_rekening',$data2);
                                }else{

                                    $jml = get_data('tbl_jumlah_rekening',[
                                        'select' => 'index_kali as jumlah, is_edit',
                                        'where'  => [
                                            'kode_anggaran'=>$kode_anggaran,
                                            'kode_cabang'=>$kode_cabang,
                                            'coa' => $s['sub_coa'],
                                            'tahun_core'=>$v['tahun']
                                        ]
                                    ])->row();
                                    
                                    
                                    $edited =[];

                                    if(isset($jml->is_edit)) $edited = json_decode($jml->is_edit,true);
                               //     debug($edited);die;
                                                                        
                                    if(isset($edited) && count($edited) > 0) {
                                        foreach ($edited as $x => $v_edited) {
                                            $tahun   = substr($x,0,4);
                                            $bulan   = substr($x,4);
                                            if($v['tahun'] == $tahun && $v['bulan'] == $bulan) {
                                                $$T = $v_edited;
                                                $$T = $v_edited;
                                                $xjml = 0;

                                                $$T = $$T + $xjml;
                                            }else{
                                                $$T = $$T0 + $xjml; 
                                            }    
                                        }
                                    }
                                    $data_update[$field] = $$T;
                                    $response = update_data('tbl_jumlah_rekening',$data_update,['kode_cabang' => $kode_cabang,'kode_anggaran'=>$kode_anggaran,'tahun_core'=>$v['tahun'],'coa'=>$s['sub_coa']]);
                                }    

                            }
                        }

            }      
        } 



        $arr            = [
            'select'    => 'a.*',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.coa'  => $s_item, 
            ],
            'sort_by' => 'tahun_core'
        ];
        
        $TOT_cab = 'TOT_' . $kode_cabang ;   
        $field_tabel    = get_field('tbl_rate','name');
        
        if (in_array($TOT_cab, $field_tabel)) {
            $TOT_cab = 'TOT_' . $kode_cabang ;   
        }else{
            $TOT_cab = 0 ;
        }    
        $arr_jmlrek  = [
            'select'    => 'no_coa as coa,'.$TOT_cab.' as jumlah',
            'where'     => [
                'kode_anggaran' => $kode_anggaran,
                'is_active' => 1 
            ],
        ];

        $arr_sum            = [
            'select'    => 'a.tahun_core, sum(P_01) as P_01,sum(P_02) as P_02,sum(P_03) as P_03,sum(P_04) as P_04,sum(P_05) as P_05,sum(P_06) as P_06,sum(P_07) as P_07,sum(P_08) as P_08,sum(P_09) as P_09,sum(P_10) as P_10,sum(P_11) as P_11,sum(P_12) as P_12',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'coa'   => ['122502','122506']
            ],
            'group_by' => 'a.tahun_core',
            'sort_by'  => 'a.tahun_core'
        ];


        $data['sum_rek']  = get_data('tbl_jumlah_rekening a',$arr_sum)->result();  
        $data['jml_akhir_rek']  = get_data('tbl_import_jumlah_rekening',$arr_jmlrek)->result();     
        $data['list_kd']  = get_data('tbl_jumlah_rekening a',$arr)->result();
        $data['cabang'] = $kode_cabang;



        $view   = $this->load->view('transaction/budget_planner/kredit/data4',$data,true);
     
        $data = [
            'data'              => $view,
            'autorun'           => call_autorun($kode_anggaran,$kode_cabang,'kredit'),
        ];


        render($data,'json');
    }

    function save_perubahan(){
        $kode_anggaran  = post('kode_anggaran');
        $kode_cabang    = post('kode_cabang');
        $data   = json_decode(post('json'),true);
 //       debug($data);die;
        $detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'distinct a.id_tahun_anggaran,a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => user('kode_anggaran'),
                'a.sumber_data'   => array(2,3)
            ],
            'order_by' => 'tahun,bulan'
        ])->result_array();


        $res = array();
        foreach($data as $id => $record) {
            $_v               = [];
            foreach ($record as $k => $v) {
                $arrkeys = explode('-', $k);
                $nama    = $arrkeys[0];
                $table   = $arrkeys[1];
                $tahun   = substr($arrkeys[2],0,4);
                $bulan   = substr($arrkeys[2],4);
                $coa     = $arrkeys[3];
                $id_tahun_anggaran   = $arrkeys[4];
                $kode_cabang     = $arrkeys[5];

                $value   = $v;
                $value = str_replace('.', '', $v);
                $value = str_replace(',', '.', $value);
                
                if($table=='table4'){
                    $_v[$arrkeys[2]]  = $value;
                }else{
                    $_v[$arrkeys[2]]  = insert_view_report($value);
                }
                array_push($res, array(
                    'nama' => $nama,
                    'table' => $table,
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'coa'   => $coa,
                    'id_tahun_anggaran' => $id_tahun_anggaran,
                    'kode_cabang'   => $kode_cabang,
                    'value' => $value,
                    'is_edit'   => $_v,
                ));
            }
        }

      
        foreach ($res as $r => $r1) {

            $field = 'P_' . sprintf("%02d", $r1['bulan']);
            $anggaran = get_data('tbl_tahun_anggaran','id',$r1['id_tahun_anggaran'])->row();
            $old_data = get_data('tbl_budget_plan_kredit',[
                'select' => 'is_edit',
                'where'  => [
                    'kode_anggaran' => $anggaran->kode_anggaran,
                    'kode_cabang'       => $r1['kode_cabang'],
                    'coa'               => $r1['coa'],
                    'tahun_core'        => $r1['tahun'],
                ]
            ])->row();

            $is_edit0 = [];
            if(isset($old_data->is_edit)) {
                $is_edit0 = json_decode($old_data->is_edit,true) ;
            } 
            foreach ($r1['is_edit'] as $k => $v) {
                $is_edit0[$k] = $v;
            }

            $data2 = array(
                $field => insert_view_report($r1['value']),
                'is_edit' => json_encode($is_edit0),
            );

            switch ($r1['table']) {
              case 'table1':
                    $cab = 'TOT_' . $kode_cabang ;
                    $data0 = array(
                        $cab => $r1['value'],
                    );


                    update_data('tbl_rate',$data0,['kode_anggaran'=>$anggaran->kode_anggaran,'no_coa'=>$r1['coa']]);
                    break;  
              case 'table3':

                    update_data('tbl_budget_plan_kredit',$data2,['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>$r1['coa'],'tahun_core'=>$r1['tahun']]);
                    break;
              case 'table4':

                    $old_data = get_data('tbl_jumlah_rekening',[
                        'select' => 'is_edit',
                        'where'  => [
                            'kode_anggaran' => $anggaran->kode_anggaran,
                            'kode_cabang'       => $r1['kode_cabang'],
                            'coa'               => $r1['coa'],
                            'tahun_core'        => $r1['tahun'],
                        ]
                    ])->row();

                    $is_edit0 = [];
                    if(isset($old_data->is_edit)) {
                        $is_edit0 = json_decode($old_data->is_edit,true) ;
                    } 
                    foreach ($r1['is_edit'] as $k => $v) {
                        $is_edit0[$k] = $v;
                    }

                    $data2a = array(
                        $field => $r1['value'],
                        'is_edit' => json_encode($is_edit0),
                    );


                    update_data('tbl_jumlah_rekening',$data2a,['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>$r1['coa'],'tahun_core'=>$r1['tahun']]);
                    break;

              case 'table5':
                    $data3 = array(
                        'P_akhir' => $r1['value'],
                    );

                    update_data('tbl_jumlah_rekening',$data3,['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>$r1['coa']]);
                    break;            
              
              case 'table6':
                    $data4 = array(
                        'index_kali' => $r1['value'],
                    );
 
                    update_data('tbl_jumlah_rekening',$data4,['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>$r1['coa']]);

                    break;    
              case 'table7';
                    $data4 = array(
                        'netto' => insert_view_report($r1['value']),
                    );
 
                    update_data('tbl_budget_plan_kredit',$data4,['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>$r1['coa']]);
                    break;                            
            } 


        }

        create_autorun($kode_anggaran,$kode_cabang,'kredit');

        echo json_encode($res); 
    }
}