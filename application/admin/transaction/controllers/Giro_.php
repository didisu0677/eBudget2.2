<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Giro extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    function __construct() {
        parent::__construct();
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('giro')
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
        render($data,'view:'.$this->path.'giro/index');
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

        $giro =[];
        $tcore =[];
        for($i = $tahun_anggaran-3; $i <= $tahun_anggaran-2; $i++){ 
            $giro[] = 'GIRO ' . $i . ' (Realisasi)';
            $tcore[] = $i;  
        } 
                

        $ccore      = get_data('tbl_cek_data_cabang',[
            'select' => 'status_plan',
            'where'  => [
   //             'status_plan' => 0, 
                'kode_cabang' => $kode_cabang,
            ]
        ])->row();

        if(isset($ccore->status_plan)){
            foreach ($tcore as $g => $v_) {
                $tabel = 'tbl_history_' . $v_;    
                $g = 'giro_' . $v_;
                if(table_exists($tabel)) {
                    $v = '';
                    for ($i = 1; $i <= 12; $i++) { 
                        $v = 'C_'. sprintf("%02d", $i);
                        $$v = 0;
                    }   

                    $TOT_cab = 'TOT_' . $kode_cabang ;    
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
                        'glwnco' => '2100000',
                        ],
                    ];

                    $core = get_data($tabel,$arr)->row_array();
                    if($core){
                        $C_01 = $core['C_01'];
                        $C_02 = $core['C_02'];
                        $C_03 = $core['C_03'];
                        $C_04 = $core['C_04'];
                        $C_05 = $core['C_05'];
                        $C_06 = $core['C_06'];
                        $C_07 = $core['C_07'];
                        $C_08 = $core['C_08'];
                        $C_09 = $core['C_09'];
                        $C_10 = $core['C_10'];
                        $C_11 = $core['C_11'];
                        $C_12 = $core['C_12'];
                    }

                    $data2 = array(
                        'kode_anggaran' => $kode_anggaran,
                        'keterangan_anggaran' => $anggaran->keterangan, 
                        'tahun_anggaran'  => $anggaran->tahun_anggaran,
                        'kode_cabang'   => $kode_cabang,
                        'nama_cabang'        => $nama_cabang,
                        'coa'      => '2100000',
                        'account_name'  => 'GIRO',
                        'parent_id' => 0,
                        'tahun_core'  => $v_,
                        'sumber_data' => 1,
                        'keterangan'  =>  'GIRO ' . $v_ . ' (Realisasi)',
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

                    $cek        = get_data('tbl_budget_plan_giro',[
                        'where'         => [
                            'kode_anggaran' => $kode_anggaran,
                            'kode_cabang'   => $kode_cabang,
                            'tahun_core'    => $v_,
                            'coa' => '2100000',
                            'sumber_data'   =>1
                            ],
                    ])->row();

 

                    if(!isset($cek->id)) {
                        $response = insert_data('tbl_budget_plan_giro',$data2);
                    }else{
                        $data_update = array(
                            'account_name'  => 'GIRO',
                            'parent_id' => 0,
                            'tahun_core'      => $v_,
                            'sumber_data'       => 1,
                            'keterangan'    =>  'GIRO ' . $v_ . ' (Realisasi)',
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

                        $response = update_data('tbl_budget_plan_giro',$data_update,['kode_cabang' => $kode_cabang,'kode_anggaran'=>$kode_anggaran,'tahun_core'=>$v_,'coa'=>'2100000','sumber_data'=>1]);
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
            

        $data_view['item_ba']  = get_data('tbl_budget_plan_giro a',$arr)->result_array();;
        $data_view['sub_item'] = get_data('tbl_subacc_budget_plan','is_active',1)->result_array();

        $view   = $this->load->view('transaction/budget_planner/giro/data',$data_view,true);
     
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

        $giro =[];
        $tcore =[];
        for($i = $tahun_anggaran-1; $i <= $tahun_anggaran; $i++){ 
            $giro[] = 'GIRO ' . $i . ' (Realisasi)';
            $tcore[] = $i;  
        } 
    

        $ccore      = get_data('tbl_cek_data_cabang',[
            'select' => 'status_plan',
            'where'  => [
       //         'status_plan' => 0, 
                'kode_cabang' => $kode_cabang,
            ]
        ])->row();
       
        if(isset($ccore->status_plan)){
            foreach ($tcore as $g => $v_) {
                $tabel = 'tbl_history_' . $v_;    
                $g = 'giro_' . $v_;
                if(table_exists($tabel)) {
                    $v = '';
                    for ($i = 1; $i <= 12; $i++) { 
                        $v = 'C_'. sprintf("%02d", $i);
                        $$v = 0;
                    }   

                    $TOT_cab = 'TOT_' . $kode_cabang ;    
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
                        'glwnco' => '2100000',
                        ],
                    ];

                    $core = get_data($tabel,$arr)->row_array();
                    if($core){
                        $C_01 = $core['C_01'];
                        $C_02 = $core['C_02'];
                        $C_03 = $core['C_03'];
                        $C_04 = $core['C_04'];
                        $C_05 = $core['C_05'];
                        $C_06 = $core['C_06'];
                        $C_07 = $core['C_07'];
                        $C_08 = $core['C_08'];
                        $C_09 = $core['C_09'];
                        $C_10 = $core['C_10'];
                        $C_11 = $core['C_11'];
                        $C_12 = $core['C_12'];
                    }

                    $bln_anggaran = get_data('tbl_detail_tahun_anggaran',[
                        'select' => 'tahun,bulan',
                        'where'  => [
                            'tahun' => $v_,
                        ]   
                    ])->result();

                    if($bln_anggaran) {
                        foreach ($bln_anggaran as $bln) {
                            $v_bln = 'B_' . sprintf("%02d", $bln->bulan) ;   
                            $vd = 'C_'. sprintf("%02d", $bln->bulan);
                            $$vd = 0;

                            if($bln->tahun != $anggaran->tahun_anggaran) {
                                $val = get_data('tbl_bottom_up_form1',[
                                    'select' => 'kode_cabang, sum('.$v_bln.') as total ',
                                    'where'  => [
                                        'data_core' => $bln->tahun,
                                        'coa'   => '2100000',
                                        'sumber_data' => 1,
                                        'kode_cabang' => $kode_cabang
                                    ]    
                                ])->row();
                                if(isset($val->kode_cabang)) {
                                    $$vd = $val->total;   
                                }
                            }

                        }
                    }
                }    

                if($v_ == $anggaran->tahun_anggaran) {
                    for ($i = 1; $i <= 12; $i++) { 
                        $v_bln = 'B_' . sprintf("%02d", $i) ;   
                        $vd = 'C_'. sprintf("%02d", $i);
                        $$vd = 0;

                        $val2 = get_data('tbl_bottom_up_form1',[
                            'select' => 'kode_cabang, sum('.$v_bln.') as total ',
                            'where'  => [
                                'data_core' => $v_,
                                'coa'   => '2100000',
                                'sumber_data' => 3,
                                'kode_cabang' => $kode_cabang
                        ]    
                        ])->row();
                        if(isset($val2->kode_cabang)) {
                            $$vd = $val2->total;   
                        }
                    }                        
                }

                $ket_data = ' (Realisasi)';
                $sumber_data = 1;
                if($v_ == $anggaran->tahun_anggaran) $ket_data = ' (Rencana)'; 
                if($v_ == $anggaran->tahun_anggaran) $sumber_data = 3; 

                    $data2 = array(
                        'kode_anggaran' => $kode_anggaran,
                        'keterangan_anggaran' => $anggaran->keterangan, 
                        'tahun_anggaran'  => $anggaran->tahun_anggaran,
                        'kode_cabang'   => $kode_cabang,
                        'nama_cabang'        => $nama_cabang,
                        'coa'      => '2100000',
                        'account_name'  => 'GIRO',
                        'parent_id' => 0,
                        'tahun_core'  => $v_,
                        'sumber_data' => $sumber_data,
                        'keterangan'  =>  'GIRO ' . $v_ . $ket_data,
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

                    $cek        = get_data('tbl_budget_plan_giro',[
                        'where'         => [
                            'kode_anggaran' => $kode_anggaran,
                            'kode_cabang'   => $kode_cabang,
                            'tahun_core'    => $v_,
                            'coa' => '2100000',
                            'sumber_data'   => $sumber_data
                            ],
                    ])->row();

 

                    if(!isset($cek->id)) {
                        $response = insert_data('tbl_budget_plan_giro',$data2);
                    }else{
                        $data_update = array(
                            'account_name'  => 'GIRO',
                            'parent_id' => 0,
                            'tahun_core'      => $v_,
                            'sumber_data'       => $sumber_data,
                            'keterangan'  =>  'GIRO ' . $v_ . $ket_data,
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

                        $response = update_data('tbl_budget_plan_giro',$data_update,['kode_cabang' => $kode_cabang,'kode_anggaran'=>$kode_anggaran,'tahun_core'=>$v_,'coa'=>'2100000','sumber_data'=>$sumber_data]);
                    }                    

           
                $TOT_cab = 'TOT_' . $kode_cabang ;    
                $arr_kasda  = [
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
                    'glwnco' => '2101012',
                    ],
                ];


                if(table_exists($tabel)) {
                    $data_view['kasda'] = get_data($tabel,$arr_kasda)->row_array();
                }

                $TOT_cab = 'TOT_' . $kode_cabang ;    
                $arr_nonkasda  = [
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
                    'glwnco' => '2101011',
                    ],
                ];


                if(table_exists($tabel)) {
                    $data_view['nonkasda'] = get_data($tabel,$arr_nonkasda)->row_array();
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
                'a.tahun_core' => $tcore
            ],
            'sort_by' => 'tahun_core'
        ];
            

        $data_view['item_ba2']  = get_data('tbl_budget_plan_giro a',$arr)->result_array();


        $arr            = [
            'select'    => 'a.*',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
            ],
            'sort_by' => 'tahun_core'
        ];

        $data_view['item_chart']  = get_data('tbl_budget_plan_giro a',$arr)->result_array();

        $data_view['sub_item'] = get_data('tbl_subacc_budget_plan',[
            'where' => [
                'is_active' => 1,
                'grup_coa'  => '2100000'
            ]
        ])->result_array();


        $view   = $this->load->view('transaction/budget_planner/giro/data2',$data_view,true);
     
        $data = [
            'data'              => $view,     
            'item2'         => $data_view['item_ba2'],
            'item_chart'         => $data_view['item_chart'],
        ];



        render($data,'json');
    }

    function data3($kode_anggaran="", $kode_cabang="") {
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

  
        $data_view['sub_item'] = get_data('tbl_subacc_budget_plan',[
            'where' => [
                'is_active' => 1,
                'grup_coa'  => '2100000'
            ]
        ])->result_array();


        $view   = $this->load->view('transaction/budget_planner/giro/data3',$data_view,true);
     
        $data = [
            'data'              => $view
        ];


        render($data,'json');
    }
}