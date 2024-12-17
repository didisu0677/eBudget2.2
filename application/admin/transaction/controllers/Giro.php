<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Giro extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'giro';
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
                'a.kode_cabang' => $kode_cabang,
                'a.kode_cabang !=' => 'G001'
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
        render($data,'view:'.$this->path.'giro/index');
    }

     function data($kode_anggaran="", $kode_cabang="") {
        $check_first_data = count(get_data('tbl_budget_plan_giro',['select' => 'id','where' => ['kode_cabang' => $kode_cabang, 'kode_anggaran' => $kode_anggaran, 'coa' => '2101011']])->result());
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
                    ];

                    $arr_w1 = [
                     'where' => [
                        'tahun' => $v_,
                        'glwnco' => '2100000',
                        ]
                    ];

                    $x = array_merge($arr,$arr_w1); 

                    $core = get_data($tabel,$x)->row_array();
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

                    $arr_rate            = [
                    'select'    => ''.$TOT_cab.' as rate',
                     'where' => [
                        'no_coa' => '2100000',
                        ],
                    ];

                    $field_tabel    = get_field('tbl_rate','name');

                    if (in_array($TOT_cab, $field_tabel)) $rate = get_data('tbl_rate',$arr_rate)->row();

                 //   debug($rate);die;

                    $v_rate = 0;
                    if(isset($rate->rate)) $v_rate = $rate->rate;


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
                        'rate' => $v_rate,
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
                'a.parent_id'   => 0,
            ],
            'sort_by' => 'tahun_core'
        ];
            

        $data_view['item_ba']  = get_data('tbl_budget_plan_giro a',$arr)->result_array();
        $data_view['sub_item'] = get_data('tbl_subacc_budget_plan','is_active',1)->result_array();

        $view   = $this->load->view('transaction/budget_planner/giro/data',$data_view,true);
     
        $data = [
            'data'              => $view,     
            'item'              => $data_view['item_ba'],
            'check_first_data'  => $check_first_data,
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


        $last_real = get_data('tbl_detail_tahun_anggaran',[
            'select' => 'tahun,bulan',    
            'where' => [
                'kode_anggaran' => $kode_anggaran,
                'sumber_data'   => 2,
            ],
            'sort_by' => 'bulan',
            'sort' => 'ASC',
        ])->row();

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
                        'select' => 'distinct tahun,bulan,sumber_data',
                        'where'  => [
                            'tahun' => $v_,
                            'kode_anggaran' => $kode_anggaran,
                        ]   
                    ])->result();

                    if($bln_anggaran) {
                        foreach ($bln_anggaran as $bln) {
                            $v_bln = 'B_' . sprintf("%02d", $bln->bulan) ; 
                            $v_hsl = 'hasil' . $bln->bulan ;   
                            $vd = 'C_'. sprintf("%02d", $bln->bulan);
                         //   $$vd = 0;
                            
                            if($bln->sumber_data ==2) {
                                $val = get_data('tbl_indek_besaran',[
                                    'select' => 'kode_cabang, sum('.$v_hsl.') as total ',
                                    'where'  => [
                                        'kode_anggaran' => $kode_anggaran,
                                        'coa'   => '2100000',
                                        'parent_id !=' => 0,
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
                        $v_hsl = 'hasil' . $i ;   
                        $vd = 'C_'. sprintf("%02d", $i);
                        $$vd = 0;

                        $val2 = get_data('tbl_indek_besaran',[
                            'select' => 'kode_cabang, sum('.$v_hsl.') as total ',
                            'where'  => [
                                'kode_anggaran' => $kode_anggaran,
                                'coa'   => '2100000',
                                'parent_id' => 0,
                                'kode_cabang' => $kode_cabang
                        ]    
                        ])->row();
                        if(isset($val2->kode_cabang)) {
                            $$vd = $val2->total;   
                        }
                    }  

                    $bln_anggaran = get_data('tbl_detail_tahun_anggaran',[
                        'select' => 'distinct tahun,bulan,sumber_data',
                        'where'  => [
                            'tahun' => $v_,
                            'kode_anggaran' => $kode_anggaran,
                            'sumber_data' => 1
                        ]   
                    ])->result();

                    if($bln_anggaran) {
                        foreach ($bln_anggaran as $bln) {
                            $vd = 'C_'. sprintf("%02d", $bln->bulan);
                            if($core){
                                $$vd = $core[$vd];
                            }

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


                    //        

           
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
                ];

                $arr_w1 = [
                 'where' => [
                    'tahun' => $v_,
                    'glwnco' => '2101012',
                    ]
                ];

                $arr_w0 = [
                 'where' => [
                    'tahun' => $v_ - 1,
                    'glwnco' => '2101012',
                    ]
                ];

                $x = array_merge($arr_kasda,$arr_w1); 


                $v_ks = 'kasda' . $v_ ;
                if(table_exists($tabel)) {
                    $data_view[$v_ks] = get_data($tabel,$x)->result_array();
                }

        //        debug($data_view['kasda2020']);die;
                //kasda_akhir dan tahun sebelumnya
                $x0 = array_merge($arr_kasda,$arr_w0); 

                if($v_ == $anggaran->tahun_anggaran) {
                    $tabel0 = 'tbl_history_' . ($v_ - 1);
                    $ks_akhir = 'C_' . $last_real->bulan;
                    $data_view['ks1'] = get_data($tabel0,$x0)->row_array();
                }else{
                    $tabel0 = 'tbl_history_' . ($v_ - 1);
                    $ks_akhir = 'C_' . $last_real->bulan;
                    $data_view['ks0'] = get_data($tabel0,$x0)->row_array();
                }

                $TOT_cab = 'TOT_' . $kode_cabang ;    

                $arr_w2 = [
                 'where' => [
                    'tahun' => $v_,
                    'glwnco' => '2101011',
                    ]
                ];

                $arr_w20 = [
                 'where' => [
                    'tahun' => $v_ - 1,
                    'glwnco' => '2101011',
                    ]
                ];

                $x2 = array_merge($arr_kasda,$arr_w2); 

                $v_nonks = 'nonkasda' . $v_ ;
                if(table_exists($tabel)) {
                    $data_view[$v_nonks] = get_data($tabel,$x2)->result_array();
                }

                //non kasda_akhir dan tahun sebelumnya
                $xnon0 = array_merge($arr_kasda,$arr_w20); 

                if($v_ == $anggaran->tahun_anggaran) {
                    $tabel0 = 'tbl_history_' . ($v_ - 1);
                    $ks_nonakhir = 'C_' . $last_real->bulan;
                    $data_view['ksnon1'] = get_data($tabel0,$xnon0)->row_array();
                }else{
                    $tabel0 = 'tbl_history_' . ($v_ - 1);
                    $ks_nonakhir = 'C_' . $last_real->bulan;
                    $data_view['ksnon0'] = get_data($tabel0,$xnon0)->row_array();
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
                'a.parent_id'  => 0 
            ],
            'sort_by' => 'tahun_core'
        ];


        $arr_0            = [
            'select'    => 'a.*',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.tahun_core' => $tahun_anggaran - 2,
                'a.parent_id'  => 0 
            ],
            'sort_by' => 'tahun_core'
        ];
            

        $data_view['item_ba2']  = get_data('tbl_budget_plan_giro a',$arr)->result_array();
        $data_view['item_ba0']  = get_data('tbl_budget_plan_giro a',$arr_0)->result_array();

        $arr            = [
            'select'    => 'a.*',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.parent_id'   => 0
            ],
            'sort_by' => 'tahun_core'
        ];

        $data_view['item_chart']  = get_data('tbl_budget_plan_giro a',$arr)->result_array();


        $field_tabel    = get_field('tbl_rate','name');

        if (in_array($TOT_cab, $field_tabel)) {


            $sub_item = get_data('tbl_subacc_budget_plan a',[
                'select' => 'a.*,'.$TOT_cab.' as rate',
                'join'   => ["tbl_rate b on a.sub_coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",],
                'where' => [
                    'a.is_active' => 1,
                    'a.grup_coa'  => ['2100000','2101011']
                ]
            ])->result_array();

            ///input sub item //

            $t =[];
            $data['detail_tahun']   = get_data('tbl_detail_tahun_anggaran a',[
                'select'    => 'a.id_tahun_anggaran,a.bulan,a.tahun,a.sumber_data,b.singkatan',
                'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
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
            if(count($sub_item) > 0) {
                foreach ($sub_item as $key =>$l) {
                $l_coa[] = $l['sub_coa'];
                    foreach ($t as $key => $value) {
                        $cek        = get_data('tbl_budget_plan_giro',[
                            'where'         => [
                                'kode_anggaran' => $kode_anggaran,
                                'kode_cabang'   => $kode_cabang,
                                'coa' => $l['sub_coa'],
                                'tahun_core' => $key
                                ],
                        ])->row();

                    $p_id = get_data('tbl_budget_plan_giro',[
                        'select' => 'id',
                        'where'  => [
                            'kode_anggaran' => $kode_anggaran,
                            'kode_cabang'   => $kode_cabang,
                            'tahun_core' => $key
                        ],   
                    ])->row();    

                    $pid = 0;
                    if(isset($p_id->id)) $pid = $p_id->id;

                    $v_awal        = get_data('tbl_budget_plan_giro',[
                        'where'         => [
                            'kode_anggaran' => $kode_anggaran,
                            'kode_cabang'   => $kode_cabang,
                            'parent_id' => 0,
                            'tahun_core' => $key
                            ],
                    ])->row();

                    if(!isset($cek->id)) {
                        $data2 = array(
                            'kode_anggaran' => $kode_anggaran,
                            'keterangan_anggaran' => $anggaran->keterangan, 
                            'tahun_anggaran'  => $anggaran->tahun_anggaran,
                            'kode_cabang'   => $kode_cabang,
                            'nama_cabang'        => $nama_cabang,
                            'coa'      => $l['sub_coa'],
                            'account_name'  => $l['nama'],
                            'parent_id' => $pid,
                            'tahun_core'  => $key,
                            'keterangan'  =>  $l['nama'],
                        );

                        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result_array();

                        foreach ($data['detail_tahun'] as $k => $val) {
                            if($val['sumber_data']==2 && $val['tahun'] == $key){
                                if($l['sub_coa'] == '2101012') {
                                    $P = 'P_' . sprintf("%02d", $val['bulan']);

                                    $TOT_cab = 'TOT_' . $kode_cabang ;   
                                    $tahun = 'tbl_history_'.($val['tahun'] -1);
                                    
                                    $field_tabel    = get_field($tahun,'name');
                                    
                                    if (in_array($TOT_cab, $field_tabel)) {
                                        $select = 'TOT_'.$kode_cabang;
                                    }else{
                                        $select = 0;
                                    }    
                                    
                                    $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result_array();
 
                                    $getMinBulan = $data['tahun'][0]['bulan_terakhir_realisasi'];

                                    $K = get_data($tahun,[

                                        'select'    => 
                                                "glwnco,
                                                coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil00",
                                        'where'     => [
                                        'glwnco' => '2101012',       
                                        ],
                                        'group_by' => 'glwnco',
                                    ])->row(); 


                                    $B = get_data($tahun,[

                                        'select'    => 
                                                "glwnco,coalesce(sum(case when bulan = '".$val['bulan']."'  then ".$select." end), 0) as hasil10,
                                                coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9",
                                        'where'     => [
                                        'glwnco' => '2100000',       
                                        ],
                                        'group_by' => 'glwnco',
                                    ])->row(); 

                                    if(isset($B->glwnco)) {
                                        $data2[$P] = ($B->hasil10 / $B->hasil9) * $K->hasil00 ;
                                    }


                                }

                                if($l['sub_coa'] == '2101011') {
                                    $P = 'P_' . sprintf("%02d", $val['bulan']);

                                    $TOT_cab = 'TOT_' . $kode_cabang ;   
                                    $tahun = 'tbl_history_'.($val['tahun'] -1);
                                    
                                    $field_tabel    = get_field($tahun,'name');
                                    
                                    if (in_array($TOT_cab, $field_tabel)) {
                                        $select = 'TOT_'.$kode_cabang;
                                    }else{
                                        $select = 0;
                                    }    
                                    
                                    $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result_array();
 
                                    $getMinBulan = $data['tahun'][0]['bulan_terakhir_realisasi'];

                                    $K = get_data($tahun,[

                                        'select'    => 
                                                "glwnco,
                                                coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil00",
                                        'where'     => [
                                        'glwnco' => '2101011',       
                                        ],
                                        'group_by' => 'glwnco',
                                    ])->row(); 


                                    $B = get_data($tahun,[

                                        'select'    => 
                                                "glwnco,coalesce(sum(case when bulan = '".$val['bulan']."'  then ".$select." end), 0) as hasil10,
                                                coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9",
                                        'where'     => [
                                        'glwnco' => '2101011',       
                                        ],
                                        'group_by' => 'glwnco',
                                    ])->row(); 

                                    if(isset($B->glwnco)) {
                                        $data2[$P] = ($B->hasil10 / $B->hasil9) * $K->hasil00 ;
                                    }
                                }
                            }                            
                        }


                        $response = insert_data('tbl_budget_plan_giro',$data2);
                        }else{


                            $data_update = array(
                                'account_name'  => $l['nama'],
                                'parent_id' => $pid,
                                'tahun_core'  => $key,
                                'keterangan'  =>  $l['nama'],
                            );


                        foreach ($data['detail_tahun'] as $k => $val) {
                            if($val['sumber_data']==2 && $val['tahun'] == $key){
                                if($l['sub_coa'] == '2101012') {
                                    $P = 'P_' . sprintf("%02d", $val['bulan']);

                                    $TOT_cab = 'TOT_' . $kode_cabang ;   
                                    $tahun = 'tbl_history_'.($val['tahun'] -1);
                                    
                                    $field_tabel    = get_field($tahun,'name');
                                    $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result_array();
                                    
                                    if (in_array($TOT_cab, $field_tabel)) {
                                        $select = 'TOT_'.$kode_cabang;
                                    }else{
                                        $select = 0;
                                    }    
                                    
                                    $getMinBulan = $data['tahun'][0]['bulan_terakhir_realisasi'];

                                    $K = get_data($tahun,[

                                        'select'    => 
                                                "glwnco,
                                                coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil00",
                                        'where'     => [
                                        'glwnco' => '2101012',       
                                        ],
                                        'group_by' => 'glwnco',
                                    ])->row(); 


                                    $B = get_data($tahun,[

                                        'select'    => 
                                                "glwnco,coalesce(sum(case when bulan = '".$val['bulan']."'  then ".$select." end), 0) as hasil10,
                                                coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9",
                                        'where'     => [
                                        'glwnco' => '2100000',       
                                        ],
                                        'group_by' => 'glwnco',
                                    ])->row(); 

                                    if(isset($B->glwnco)) {
                                        $data_update[$P] = ($B->hasil10 / $B->hasil9) * $K->hasil00 ;
                                    }


                                }

                                if($l['sub_coa'] == '2101011') {
                                    $P = 'P_' . sprintf("%02d", $val['bulan']);

                                    $TOT_cab = 'TOT_' . $kode_cabang ;   
                                    $tahun = 'tbl_history_'.($val['tahun'] -1);
                                    
                                    $field_tabel    = get_field($tahun,'name');
                                    
                                    if (in_array($TOT_cab, $field_tabel)) {
                                        $select = 'TOT_'.$kode_cabang;
                                    }else{
                                        $select = 0;
                                    }    
                                    
                                    $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result_array();
 
                                    $getMinBulan = $data['tahun'][0]['bulan_terakhir_realisasi'];

                                    $K = get_data($tahun,[

                                        'select'    => 
                                                "glwnco,
                                                coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil00",
                                        'where'     => [
                                        'glwnco' => '2101011',       
                                        ],
                                        'group_by' => 'glwnco',
                                    ])->row(); 


                                    $B = get_data($tahun,[

                                        'select'    => 
                                                "glwnco,coalesce(sum(case when bulan = '".$val['bulan']."'  then ".$select." end), 0) as hasil10,
                                                coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9",
                                        'where'     => [
                                        'glwnco' => '2101011',       
                                        ],
                                        'group_by' => 'glwnco',
                                    ])->row(); 

                                    if(isset($B->glwnco)) {
                                        $data_update[$P] = ($B->hasil10 / $B->hasil9) * $K->hasil00 ;
                                    }
                                }
                            }                     
                        }

                            $response = update_data('tbl_budget_plan_giro',$data_update,['kode_cabang' => $kode_cabang,'kode_anggaran'=>$kode_anggaran,'tahun_core'=>$key,'coa'=>$l['sub_coa']]);


                        }  
                    }
                }
            }        

        }   

        $TOT_cab = 'TOT_' . $kode_cabang ;   
        $field_tabel    = get_field('tbl_rate','name');
        if (in_array($TOT_cab, $field_tabel)) {
            $arr            = [
                'select'    => 'a.*,b.'.$TOT_cab.' as rate, c.grup_coa',
                'join'   => ["tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",
                             'tbl_subacc_budget_plan c on a.coa = c.sub_coa type LEFT'
                ],
                'where'     => [
                    'a.kode_anggaran' => $kode_anggaran,
                    'a.kode_cabang' => $kode_cabang,
                    'a.parent_id !='  => 0, 
                ],
            ];
        }else{
            $arr            = [
                'select'    => 'a.*,0 as rate,b.grup_coa',
                'join'      => 'tbl_subacc_budget_plan b on a.coa = b.sub_coa type LEFT',
                'where'     => [
                    'a.kode_anggaran' => $kode_anggaran,
                    'a.kode_cabang' => $kode_cabang,
                    'a.parent_id !='  => 0, 
                ],
            ];
        }   

        $data_view['sub_item']  = get_data('tbl_budget_plan_giro a',$arr)->result_array();
        if(isset($data['detail_tahun'])) $data_view['detail_tahun'] = $data['detail_tahun'];
        $data_view['cabang'] = $kode_cabang;
        $data_view['bulan_terakhir'] = ($last_real->bulan) - 1;
        $data_view['tahun_terakhir'] = $last_real->tahun;
        $access                      = get_access($this->controller);
        $data_view['access_edit']    = $access['access_edit'];
        $data_view['anggaran']       = $anggaran;
        $view   = $this->load->view('transaction/budget_planner/giro/data2',$data_view,true);
     
        $data = [
            'data'              => $view,     
            'item2'             => $data_view['item_ba2'],
            'item_chart'        => $data_view['item_chart'],
        ];


        render($data,'json');
    }

    function data3_lama($kode_anggaran="", $kode_cabang="") {
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
        $data_view['detail_tahun']   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.id_tahun_anggaran,a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => user('kode_anggaran'),
                'a.sumber_data'   => array(1,2,3)
            ],
            'order_by' => 'tahun,bulan'
        ])->result_array();


        foreach ($data_view['detail_tahun'] as $k => $v) {
            $t[$v['tahun']][] = $v['bulan'];
        }
  
        $sub_item = get_data('tbl_subacc_budget_plan',[
            'where' => [
                'is_active' => 1,
                'grup_coa'  => '2100000',
                'sub_coa != '   => '2101011'
            ]
        ])->result_array();


        $data_view['sub_item'] = $sub_item;

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

                        $jmlrek1 = 0;
                        if($rek) $jmlrek1 = $rek->jumlah ;

                        $n = 0;
                        $akhir = 0;
                        foreach ($data_view['detail_tahun'] as $k => $v) {

                            $jml = get_data('tbl_jumlah_rekening',[
                                'select' => 'index_kali as jumlah, is_edit',
                                'where'  => [
                                    'kode_anggaran'=>$kode_anggaran,
                                    'kode_cabang'=>$kode_cabang,
                                    'coa'=>$s['sub_coa'],
                                    'tahun_core'=>$v['tahun']
                                ]
                            ])->row();


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
                                'is_edit' => '[]',
                            );


                            $data_update = array(
                                'account_name'  => $s['nama'],
                                'tahun_core'  => $v['tahun'],
                                'keterangan'  =>  $s['nama'],
                            );


                            $n++;
                            $xjml = 0;
                            if($v['sumber_data'] == 2 || $v['sumber_data'] == 1){
                                $field = 'P_'. sprintf("%02d", $v['bulan']);

                                if(isset($jml->jumlah)) $xjml = $jml->jumlah;

                                $T = 'Jumlah' . $v['tahun'] . sprintf("%02d", $v['bulan']);
                                if($n>1){
                                    $T0 = 'Jumlah' . $v['tahun'] . sprintf("%02d", $v['bulan']-1);
                                    $$T = $$T0 + $xjml;    
                                }else{
                                    $$T = $jmlrek1 + $xjml ; //$rek->jumlah + $xjml;
           
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

                                
                                if(!isset($cek->id)){
                                    $response = insert_data('tbl_jumlah_rekening',$data2);    
                                }else{
                                    $edited = json_decode($jml->is_edit,true);

                                    $jml = get_data('tbl_jumlah_rekening',[
                                        'select' => 'index_kali as jumlah, is_edit',
                                        'where'  => [
                                            'kode_anggaran'=>$kode_anggaran,
                                            'kode_cabang'=>$kode_cabang,
                                            'coa'=>$s['sub_coa'],
                                            'tahun_core'=>$v['tahun']
                                        ]
                                    ])->row();
                                    
                                    if(isset($jml->jumlah)) $xjml = $jml->jumlah;


                                    if(count($edited) > 0) {
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

                                if(isset($jml->jumlah)) $xjml = $jml->jumlah;
                                if($v['bulan']!=1){
                                    $T0 = 'Jumlah' . $v['tahun'] . sprintf("%02d", $v['bulan']-1);
                                    $$T = $$T0 + $xjml; 
                                }else{
                                    $T0 = 'Jumlah' . ($v['tahun']-1) . sprintf("%02d", '12');
                                    $$T = $$T0 + $xjml;
                                }
                          
                                $$T = $$T ;
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

                                if(isset($jml->jumlah)) $xjml = $jml->jumlah;
                                 
                                if(!isset($cek->id)) {
                                    $response = insert_data('tbl_jumlah_rekening',$data2);
                                }else{

                                    $jml = get_data('tbl_jumlah_rekening',[
                                        'select' => 'index_kali as jumlah, is_edit',
                                        'where'  => [
                                            'kode_anggaran'=>$kode_anggaran,
                                            'kode_cabang'=>$kode_cabang,
                                            'coa'=>$s['sub_coa'],
                                            'tahun_core'=>$v['tahun']
                                        ]
                                    ])->row();

                                    $edited = json_decode($jml->is_edit,true);
                                    if(count($edited) > 0) {
                                        foreach ($edited as $x => $v_edited) {
                                            $tahun   = substr($x,0,4);
                                            $bulan   = substr($x,4);
                                            if($v['tahun'] == $tahun && $v['bulan'] == $bulan) {
                                                $$T = $v_edited;
                                                $$T0 = $v_edited;
                                                $xjml = 0;

                                                $$T = $$T0 + $xjml;
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

        $arr_now            = [
            'select'    => 'a.*',
            'where'     => [
                'a.kode_anggaran' => $kode_anggaran,
                'a.kode_cabang' => $kode_cabang,
                'a.tahun_core' => $tahun_anggaran,          
                'a.parent_id'  => 0 

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
                'coa'   => ['2101012','2101011']
            ],
            'group_by' => 'a.tahun_core',
            'sort_by'  => 'a.tahun_core'
        ];


        $data_view['sum_rek']  = get_data('tbl_jumlah_rekening a',$arr_sum)->result();  
        $data_view['jml_akhir_rek']  = get_data('tbl_import_jumlah_rekening',$arr_jmlrek)->result();
        $data_view['list_gr']  = get_data('tbl_jumlah_rekening a',$arr)->result();

        $data_view['cabang'] = $kode_cabang;
        $data_view['jml_plangiro']  = get_data('tbl_budget_plan_giro a',$arr_now)->result_array();

        $view   = $this->load->view('transaction/budget_planner/giro/data3',$data_view,true);
     
        $data = [
            'data'              => $view,
            'autorun'           => call_autorun($kode_anggaran,$kode_cabang,'dpk'),
        ];


        render($data,'json');
    }

    function data3($kode_anggaran,$kode_cabang){
        $anggaran   = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
        $cabang     = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();
        $a = get_access('giro');
        $access_edit = false;
        if($a['access_edit']):
            $access_edit = true;
        endif;

        $detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $anggaran->kode_anggaran,
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
        $arr_tahun_core = [];
        foreach ($detail_tahun as $k => $v) {
            if(!in_array($v->tahun, $arr_tahun_core)) array_push($arr_tahun_core, $v->tahun);
        }

        $arr_coa = ['2101012','211','212'];
        $giro = get_data('tbl_jumlah_rekening',[
            'where' => [
                'kode_anggaran' => $anggaran->kode_anggaran,
                'kode_cabang'   => $cabang->kode_cabang,
                'coa'           => $arr_coa,
                'tahun_core'    => $arr_tahun_core,
            ]
        ])->result_array();

        $status_column = false;
        $column = 'TOT_'.$cabang->kode_cabang;
        if ($this->db->field_exists($column, 'tbl_import_jumlah_rekening')):
            $status_column = true;
        endif;
        $import_jum_rek = [];
        if($status_column):
            $import_jum_rek = get_data('tbl_import_jumlah_rekening',[
                'select' => 'no_coa as coa,'.$column.' as total',
                'where'  => [
                    'kode_anggaran' => $anggaran->kode_anggaran,
                    'no_coa'        => $arr_coa,
                ]
            ])->result_array();
        endif;

        $data['no']             = (1);
        $data['detail_tahun']   = $detail_tahun;
        $data['access_edit']    = $access_edit;
        $data['cabang']         = $cabang;
        $data['anggaran']       = $anggaran;
        $data['giro']           = $giro;
        $data['import_jum_rek'] = $import_jum_rek;
        $view   = $this->load->view('transaction/budget_planner/giro/jum_segment',$data,true);
        $data = [
            'data'              => $view,
            'autorun'           => call_autorun($kode_anggaran,$kode_cabang,'dpk'),
        ];

        render($data,'json');
    }

    function save_perubahan($kode_anggaran,$kode_cabang){ 
        $data   = json_decode(post('json'),true);
        $res = array();
        foreach($data as $id => $record) {
            $arr_id = explode('-', $id);
            if(count($arr_id)>1):
                foreach ($record as $k => $v) {
                    $value = str_replace('.', '', $v);
                    $value = str_replace(',', '.', $value);
                    $record[$k] = $value;
                }
                $kode_anggaran = post('kode_anggaran');
                $coa = $arr_id[0];
                $tb  = $arr_id[1];
                if($tb == 'rate'):
                    $ck = get_data('tbl_rate',[
                        'select' => 'id',
                        'where'  => [
                            'kode_anggaran' => $kode_anggaran,
                            'no_coa'        => $coa,
                        ],
                    ])->row();
                    if($ck):
                        update_data('tbl_rate',$record,'id',$ck->id);
                    else:
                        insert_data('tbl_rate',$record);
                    endif;
                endif;
            else:
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
                    $_v[$arrkeys[2]]  = $value;
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
            endif;
        }

        foreach ($res as $r => $r1) {


            $field = 'P_' . sprintf("%02d", $r1['bulan']);
            $anggaran = get_data('tbl_tahun_anggaran','id',$r1['id_tahun_anggaran'])->row();

            $x = 'IP_' . sprintf("%02d", $r1['bulan']);


            $data2 = array(
                $field => insert_view_report($r1['value']),
            );


            switch ($r1['table']) {
              case 'table3':

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

                   // debug($is_edit0);die;
                    $data2_ = array(
                        $field => $r1['value'],
                        'is_edit' => json_encode($is_edit0),
                    );


                    update_data('tbl_jumlah_rekening',$data2_,['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>$r1['coa'],'tahun_core'=>$r1['tahun']]);
                    break;
              case 'table2':

                    $girok = get_data('tbl_budget_plan_giro',[
                        'select' => $field,
                        'where'  => [
                            'kode_anggaran' => $anggaran->kode_anggaran,
                            'kode_cabang' => $r1['kode_cabang'],
                            'coa'   => '2100000',
                            'parent_id' => 0,
                            'tahun_core' => $r1['tahun'],                     
                        ]
                    ])->row(); 

                    
                    
                    $gironk = get_data('tbl_budget_plan_giro',[
                        'select' => $field,
                        'where'  => [
                            'kode_anggaran' => $anggaran->kode_anggaran,
                            'kode_cabang' => $r1['kode_cabang'],
                            'coa'   => '2101011',
                            'parent_id !=' => 0,
                            'tahun_core' => $r1['tahun'],                     
                        ]
                    ])->row(); 


                    $xn0 = 0 ;
                    if(isset($gironk->$field))  $xn0 = $gironk->$field ;
                    

                    $x0 = 0 ;
                    if(isset($girok->$field))  $x0 = $girok->$field ;
            

                  
                    $data3 = array(
                        $field => $x0 - insert_view_report($r1['value']),
                    );

                    $data4 = array(
                        $field => $xn0 - insert_view_report($r1['value']),
                    );



                    update_data('tbl_budget_plan_giro',$data2,['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>$r1['coa'],'tahun_core'=>$r1['tahun']]);
 

                    if($r1['coa'] == '2101012'){
                        update_data('tbl_budget_plan_giro',$data3,['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>'2101011','tahun_core'=>$r1['tahun']]);
                    }

                    
                    if($r1['coa'] == '2101011|B'){
                        update_data('tbl_budget_plan_giro',$data4,['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>'2101011|A','tahun_core'=>$r1['tahun']]);
                    }
                    
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
                case 'tbl_segment':
                    $data4 = array(
                        $field => insert_view_report($r1['value']),
                        'is_edit' => json_encode($is_edit0),
                    );
                    $where = ['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>$r1['coa'],'tahun_core'=>$r1['tahun']];

                    $ck = get_data('tbl_budget_plan_deposito',[
                        'select' => 'id',
                        'where'  => $where,
                    ])->row();
                    if($ck):
                        update_data('tbl_budget_plan_deposito',$data4,$where);
                    else:
                        $data4 = $where;
                        $data4['tahun_anggaran'] = $anggaran->id;
                        $data4[$field] = insert_view_report($r1['value']);
                        $data4['is_edit'] = json_encode($is_edit0);
                        insert_data('tbl_budget_plan_deposito',$data4);
                    endif;
                    break;
                case 'tbl_jum_segment':
                    $where = ['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>$r1['coa'],'tahun_core'=>$r1['tahun']];
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

                    $data2_ = array(
                        $field => $r1['value'],
                        'is_edit' => json_encode($is_edit0),
                    );

                    if($old_data):
                        update_data('tbl_jumlah_rekening',$data2_,$where);
                    else:
                        $data2_ = $where;
                        $data2_['tahun_anggaran'] = $anggaran->id;
                        $data2_[$field]      = $r1['value'];
                        $data2_['is_edit']   = json_encode($is_edit0);
                        insert_data('tbl_jumlah_rekening',$data2_);
                    endif;
                    break;
                case 'tbl_index_kali':
                    $where = ['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$r1['kode_cabang'],'coa'=>$r1['coa']];
                
                    $ck = get_data('tbl_jumlah_rekening',[
                        'select' => 'id',
                        'where' => $where,
                    ])->row();
                    if($ck):
                        $data4 = array(
                            'index_kali' => $r1['value'],
                        );
                        update_data('tbl_jumlah_rekening',$data4,$where);
                    else:
                        $data4 = $where;
                        $data4['tahun_anggaran'] = $anggaran->id;
                        $data4['index_kali']     = $r1['value'];
                        $data4['tahun_core']     = $anggaran->tahun_anggaran;
                        insert_data('tbl_jumlah_rekening',$data4);
                    endif;
                    break;   

            }        

        }
        create_autorun($kode_anggaran,$kode_cabang,'dpk');
        echo json_encode($res); 
    }
}