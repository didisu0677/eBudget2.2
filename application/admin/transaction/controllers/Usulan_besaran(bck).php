<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usulan_besaran extends BE_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('usulan_besaran')
            ]
        ])->result();

        $kode_cabang          = [];
        foreach($cabang_user as $c) $kode_cabang[] = $c->kode_cabang;

        $cab = get_data('tbl_m_cabang','id',user('id_struktur'))->row();

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

        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result();   

        $data['bulan'] = get_data('tbl_detail_tahun_anggaran a',[
            'select' => 'a.*,b.singkatan',
            'join'  => ['tbl_m_data_budget b on a.sumber_data = b.id type LEFT',
            ],
            'where' => [
                'a.kode_anggaran' => user('kode_anggaran')
                ],
            'sort_by'   => 'a.tahun,a.bulan',
            'sort'      => 'ASC'
        ])->result();


        $data['anggaran'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->row();

        render($data);
    }
    
    function sortable() {
        render();
    }

    function data($anggaran="", $cabang="", $tipe = 'table') {
      //  $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;
        $a = get_access('usulan_besaran');
        $data['akses_ubah'] = $a['access_edit'];
        $data['current_cabang'] = $cabang;
        $nama_cabang ='';
        $cab = get_data('tbl_m_cabang','kode_cabang',$ckode_cabang)->row();               
        if(isset($cab->nama_cabang)) $nama_cabang = $cab->nama_cabang;

        $a = get_access('usulan_besaran');


        $x ='';
        for ($i = 1; $i <= 4; $i++) { 
            $field = 'level' . $i ;

            if($cab->id == $cab->$field) {
                $x = $field ; 
            }    
        }    

        $sub_cabang      = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cab->id
            ]
        ])->result();
        

        $sub          = [];
        foreach($sub_cabang as $c) $sub[] = $c->kode_cabang;

        $coa = get_data('tbl_m_bottomup_besaran',[
            'where' => [
                'kode_anggaran' => $ckode_anggaran, 
                'is_active'=> 1
            ]
        ])->result();
        $id_usulan_bf1          = [];
        $glwnco = [];
        foreach($coa as $c) {
            $id_usulan_bf1[] = $c->id;
            $glwnco[] = $c->coa;
        }    

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();

/*
            $rc = array("2", "3");
            $data['rencana'] = get_data('tbl_detail_tahun_anggaran',[
                'where' => [
                    'kode_anggaran' => $ckode_anggaran,
                    'sumber_data' => $rc,

                ],
            ])->result();
*/
        $rc = array("2", "3");    
        $bulan = get_data('tbl_detail_tahun_anggaran a',[
            'select' => 'a.tahun,a.bulan',
            'join'  => ['tbl_m_data_budget b on a.sumber_data = b.id type LEFT',
            ],
            'where' => [
                'a.kode_anggaran' => user('kode_anggaran'),
                'a.sumber_data not' => $rc
                ],
            'sort_by'   => 'a.tahun,a.bulan',
            'sort'      => 'ASC'
        ])->result();

        $bln          = [];
        $thn          = [];
        foreach($bulan as $c){
            $bln[] = $c->bulan;            
            $thn[] = $c->tahun;
        } 


        $data['anggaran'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->row();

	    $arr            = [
	        'select'	=> 'distinct grup',
	        'where'     => [
	            'a.is_active' => 1,
                'a.kode_anggaran' => $ckode_anggaran
	        ],
	        'sort_by'   => 'a.urutan',
	    ];
	    
    
	    $data['grup'][0]= get_data('tbl_m_bottomup_besaran a',$arr)->result();
	   	

	   	foreach($data['grup'][0] as $m0) {	       

	   	    $arr            = [
                'select'	=> 'a.*',
                'where'     => [
                    'a.grup' => $m0->grup,
                    'a.kode_anggaran' => $ckode_anggaran,
                ],
                'sort_by' => 'urutan'
            ];
            
            $produk 	= get_data('tbl_m_bottomup_besaran a',$arr)->result();
                            $v = '';
                for ($i = 1; $i <= 12; $i++) { 
                    $v = 'C_'. sprintf("%02d", $i);
                    $$v = 0;
                }       
            $tabel ='';
            $tabel_0 ='';
            foreach ($produk as $m1) {
            	if($a['access_edit'] == 1 && $cab->kode_cabang == user('kode_cabang')) {

                $tabel = 'tbl_history_' . $m1->data_core;    
                $tabel_0 = 'tbl_history_' . ($m1->data_core - 1);

            if(table_exists($tabel)) {
            
                $v = '';
                for ($i = 1; $i <= 12; $i++) { 
                    $v = 'C_'. sprintf("%02d", $i);
                    $$v = 0;
                }   

                $TOT_cab = 'TOT_' . $ckode_cabang ;    
                $arr            = [
                'select'    => '
    coalesce(sum(case when substr(glwdat,5,2) = "01" then TOT_002 end), 0) as C_01,
    coalesce(sum(case when substr(glwdat,5,2) = "02" then TOT_002 end), 0) as C_02,
    coalesce(sum(case when substr(glwdat,5,2) = "03" then TOT_002 end), 0) as C_03,
    coalesce(sum(case when substr(glwdat,5,2) = "04" then TOT_002 end), 0) as C_04,
    coalesce(sum(case when substr(glwdat,5,2) = "05" then TOT_002 end), 0) as C_05,
    coalesce(sum(case when substr(glwdat,5,2) = "06" then TOT_002 end), 0) as C_06,
    coalesce(sum(case when substr(glwdat,5,2) = "07" then TOT_002 end), 0) as C_07,
    coalesce(sum(case when substr(glwdat,5,2) = "08" then TOT_002 end), 0) as C_08,
    coalesce(sum(case when substr(glwdat,5,2) = "09" then TOT_002 end), 0) as C_09,
    coalesce(sum(case when substr(glwdat,5,2) = "10" then TOT_002 end), 0) as C_10,
    coalesce(sum(case when substr(glwdat,5,2) = "11" then TOT_002 end), 0) as C_11,
    coalesce(sum(case when substr(glwdat,5,2) = "12" then TOT_002 end), 0) as C_12',
                'where' => [
                    'tahun' => $m1->data_core,
                    'glwnco' => $m1->coa,
                    ],
                ];

                $core = get_data($tabel,$arr)->row_array();

                if($core){
                    if($m1->sumber_data == 1){
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
                }
            };
         

            

                    $data2 = array(
                        'kode_anggaran' => $ckode_anggaran,
                        'keterangan_anggaran' => $anggaran->keterangan, 
    	                'tahun'  => $anggaran->tahun_anggaran,
    	                'kode_cabang'   => $ckode_cabang,
                        'cabang'        => $nama_cabang,
                        'username'      => user('username'),
    	                'id_usulan_bf1'  => $m1->id,
    	                'keterangan' => $m1->keterangan,
    	                'grup'		=> $m1->grup,
                        'coa'       => $m1->coa,
                        'data_core ' => $m1->data_core,
                        'nomor'     => $m1->nomor,
                        'sumber_data' => $m1->sumber_data,
    	            );

                    if($m1->sumber_data == 1){
                        $data2['B_01'] = $C_01;
                        $data2['B_02'] = $C_02;
                        $data2['B_03'] = $C_03;
                        $data2['B_04'] = $C_04;
                        $data2['B_05'] = $C_05;
                        $data2['B_06'] = $C_06;
                        $data2['B_07'] = $C_07;
                        $data2['B_08'] = $C_08;
                        $data2['B_09'] = $C_09;
                        $data2['B_10'] = $C_10;
                        $data2['B_11'] = $C_11;
                        $data2['B_12'] = $C_12;

                        $kr = array("122502", "122506");
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_01'] = $C_01 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_02'] = $C_02 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_03'] = $C_03 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_04'] = $C_04 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_05'] = $C_05 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_06'] = $C_06 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_07'] = $C_07 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_08'] = $C_08 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_09'] = $C_09 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_10'] = $C_10 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_11'] = $C_11 * -1;
                        if (in_array($m1->coa,$kr, TRUE)) $data_update['B_12'] = $C_12 * -1;

                    }

    	            $cek		= get_data('tbl_bottom_up_form1',[
    	                'where'			=> [
                            'kode_anggaran' => $ckode_anggaran,
    	                    'kode_cabang'	=> $ckode_cabang,
    	                    'tahun'         => $anggaran->tahun_anggaran,
    	                    'id_usulan_bf1' => $m1->id,
    	                    ],
    	            ])->row();

                                  $arr_0            = [
                'select'    => '
    coalesce(sum(case when substr(glwdat,5,2) = "01" then TOT_002 end), 0) as C_01,
    coalesce(sum(case when substr(glwdat,5,2) = "02" then TOT_002 end), 0) as C_02,
    coalesce(sum(case when substr(glwdat,5,2) = "03" then TOT_002 end), 0) as C_03,
    coalesce(sum(case when substr(glwdat,5,2) = "04" then TOT_002 end), 0) as C_04,
    coalesce(sum(case when substr(glwdat,5,2) = "05" then TOT_002 end), 0) as C_05,
    coalesce(sum(case when substr(glwdat,5,2) = "06" then TOT_002 end), 0) as C_06,
    coalesce(sum(case when substr(glwdat,5,2) = "07" then TOT_002 end), 0) as C_07,
    coalesce(sum(case when substr(glwdat,5,2) = "08" then TOT_002 end), 0) as C_08,
    coalesce(sum(case when substr(glwdat,5,2) = "09" then TOT_002 end), 0) as C_09,
    coalesce(sum(case when substr(glwdat,5,2) = "10" then TOT_002 end), 0) as C_10,
    coalesce(sum(case when substr(glwdat,5,2) = "11" then TOT_002 end), 0) as C_11,
    coalesce(sum(case when substr(glwdat,5,2) = "12" then TOT_002 end), 0) as C_12',
                'where' => [
                    'tahun' => $m1->data_core - 1,
                    'glwnco' => $m1->coa,
                    ],
                ];

    	            
    	            if(!isset($cek->id)) {
    	                $response = insert_data('tbl_bottom_up_form1',$data2);
    	            }else{

                        $data_update = array(
                            'username'      => user('username'),
                            'id_usulan_bf1'  => $m1->id,
                            'keterangan' => $m1->keterangan,
                            'grup'      => $m1->grup,
                            'coa'       => $m1->coa,
                            'data_core ' => $m1->data_core,
                            'nomor'     => $m1->nomor,
                            'sumber_data' => $m1->sumber_data,
                        );

                    if($m1->sumber_data == 1){

                        if (in_array($m1->data_core,$thn, TRUE) && in_array(1,$bln, TRUE )) $data_update['B_01'] = $C_01;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(2,$bln, TRUE)) $data_update['B_02'] = $C_02;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(3,$bln, TRUE)) $data_update['B_03'] = $C_03;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(4,$bln, TRUE)) $data_update['B_04'] = $C_04;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(5,$bln, TRUE)) $data_update['B_05'] = $C_05;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(6,$bln, TRUE)) $data_update['B_06'] = $C_06;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(7,$bln, TRUE)) $data_update['B_07'] = $C_07;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(8,$bln, TRUE)) $data_update['B_08'] = $C_08;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(9,$bln, TRUE)) $data_update['B_09'] = $C_09;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(10,$bln, TRUE)) $data_update['B_10'] = $C_10;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(11,$bln, TRUE)) $data_update['B_11'] = $C_11;
                        if (in_array($m1->data_core,$thn, TRUE) && in_array(12,$bln, TRUE)) $data_update['B_12'] = $C_12;     
                        
                    } 

                    $kr = array("122502", "122506");
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_01'] = $C_01 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_02'] = $C_02 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_03'] = $C_03 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_04'] = $C_04 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_05'] = $C_05 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_06'] = $C_06 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_07'] = $C_07 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_08'] = $C_08 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_09'] = $C_09 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_10'] = $C_10 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_11'] = $C_11 * -1;
                    if (in_array($m1->coa,$kr, TRUE)) $data_update['B_12'] = $C_12 * -1;


                    
                        $v = '';
                        $vt = '';
                        for ($i = 1; $i <= 12; $i++) { 
                            $v = 'pert' .sprintf("%02d", $i);
                            $$v = 0;
                        }   

   
                        $tabel = 'tbl_history_' . $m1->data_core;    
                        $tabel_0 = 'tbl_history_' . ($m1->data_core - 1);
                     
                 //       $core_1 = '';
                 //       $core_0 = '';
                        if(table_exists($tabel)) {
                            $core_1 = get_data($tabel,$arr)->row_array();
                        }

                        if(table_exists($tabel_0)  ) {
                             $core_0 = get_data($tabel_0,$arr)->row_array();
                        }    

                        if($m1->sumber_data == 3){  
                            $core_1 = get_data('tbl_bottom_up_form1',[
                                'select' => 'B_01 as C_01,B_02 as C_02,B_03 as C_03,B_04 as C_04,B_05 as C_05,B_06 as C_06,B_07 as C_07,B_08 as C_08,B_09 as C_09,B_10 as C_10,B_11 as C_11,B_12 as C_12',
                                'where'  => [
                                    'id_usulan_bf1' => $m1->id,
                                    'kode_anggaran' => $ckode_anggaran,
                                    'kode_cabang'   => $ckode_cabang,
                                    'data_core'     => $m1->data_core,
                                ] 
                            ])->row_array();
                        }

 
                        if($m1->sumber_data == 5){    

                            if(isset($core_0['C_01']) && $core_0['C_01'] !=0) {

                                if (in_array($m1->data_core,$thn, TRUE) && in_array(1,$bln, TRUE)) {
                                    $core_1 = get_data('tbl_bottom_up_form1',[
                                    'select' => 'B_01 as C_01,B_02 as C_02,B_03 as C_03,B_04 as C_04,B_05 as C_05,B_06 as C_06,B_07 as C_07,B_08 as C_08,B_09 as C_09,B_10 as C_10,B_11 as C_11,B_12 as C_12',
                                    'where'  => [
                                        'id_usulan_bf1' => $m1->id,
                                        'kode_anggaran' => $ckode_anggaran,
                                        'kode_cabang'   => $ckode_cabang,
                                        'data_core'     => $m1->data_core,
                                    ] 
                                    ])->row_array();

                                }

                                $pert01 = (($core_1['C_01'] - $core_0['C_01']) / $core_0['C_01']) * 100;
                                
                                $data_update['B_01'] = $pert01; 
                            }

                            if(isset($core_0['C_02']) && $core_0['C_02'] !=0) {
                                $pert02 = (($core_1['C_02'] - $core_0['C_02']) / $core_0['C_02']) * 100;
                                 $data_update['B_02'] = $pert02; 
                            }

                            if(isset($core_0['C_03']) && $core_0['C_03'] !=0) {
                                $pert03 = round((($core_1['C_03'] - $core_0['C_03']) / $core_0['C_03']) * 100,2);
                                $data_update['B_03'] = $pert03; 
                            }

                            if(isset($core_0['C_04']) && $core_0['C_04'] !=0) {
                                $pert04 = round((($core_1['C_04'] - $core_0['C_04']) / $core_0['C_04']) * 100,2);
                                 $data_update['B_04'] = $pert04; 
                            }

                            if(isset($core_0['C_05']) && $core_0['C_05'] !=0) {     
                                $pert05 = round((($core_1['C_05'] - $core_0['C_05']) / $core_0['C_05']) * 100,2);
                                 $data_update['B_05'] = $pert05; 
                            }

                            if(isset($core_0['C_06']) && $core_0['C_06'] !=0) {     
                                $pert06 = round((($core_1['C_06'] - $core_0['C_06']) / $core_0['C_06']) * 100,2);
                                 $data_update['B_06'] = $pert06; 
                            }

                            if(isset($core_0['C_07']) && $core_0['C_07'] !=0) {     
                                $pert07 = round((($core_1['C_07'] - $core_0['C_07']) / $core_0['C_07']) * 100,2);
                                 $data_update['B_07'] = $pert07; 
                            }
                            if(isset($core_0['C_08']) && $core_0['C_08'] !=0) {     
                                $pert08 = round((($core_1['C_08'] - $core_0['C_08']) / $core_0['C_08']) * 100,2);
                                 $data_update['B_08'] = $pert08; 
                            }
                            if(isset($core_0['C_09']) && $core_0['C_09'] !=0) {     
                                $pert09 = round((($core_1['C_09'] - $core_0['C_09']) / $core_0['C_09']) * 100,2);
                                 $data_update['B_09'] = $pert09; 
                            }
                            if(isset($core_0['C_10']) && $core_0['C_10'] !=0) {   

                                if (in_array($m1->data_core,$thn, TRUE) && in_array(10,$bln, TRUE)) {
                                    
                                    debug($thn);die;
                                    $core_1 = get_data('tbl_bottom_up_form11',[
                                    'select' => 'B_01 as C_01,B_02 as C_02,B_03 as C_03,B_04 as C_04,B_05 as C_05,B_06 as C_06,B_07 as C_07,B_08 as C_08,B_09 as C_09,B_10 as C_10,B_11 as C_11,B_12 as C_12',
                                    'where'  => [
                                        'id_usulan_bf1' => $m1->id,
                                        'kode_anggaran' => $ckode_anggaran,
                                        'kode_cabang'   => $ckode_cabang,
                                        'data_core'     => $m1->data_core,
                                    ] 
                                    ])->row_array();


                                }

                                $pert10 = round((($core_1['C_10'] - $core_0['C_10']) / $core_0['C_10']) * 100,2);

                                 $data_update['B_10'] = $pert10; 

                            }
                            if(isset($core_0['C_11']) && $core_0['C_11'] !=0) {     
                                $pert11 = round((($core_1['C_11'] - $core_0['C_11']) / $core_0['C_11']) * 100,2);
                                 $data_update['B_11'] = $pert11; 
                            }
                            if(isset($core_0['C_12']) && $core_0['C_12'] !=0) {     
                                $pert12 = round((($core_1['C_12'] - $core_0['C_12']) / $core_0['C_12']) * 100,2);
                                 $data_update['B_12'] = $pert12; 
                            }              
                                          
                        }    




                        $response = update_data('tbl_bottom_up_form1',$data_update,['kode_cabang' => $ckode_cabang,'kode_anggaran'=>$ckode_anggaran,'tahun'=> $anggaran->tahun_anggaran,'id_usulan_bf1'=>$m1->id]);

               //         debug($response);die;

                    }
                }    


            }      


            $arr            = [
                'select'	=> 'a.*,b.data_core',
                'join'      => ['tbl_m_bottomup_besaran b on a.id_usulan_bf1 = b.id type LEFT'],
                'where'     => [
                    'a.grup' => $m0->grup,
                ],
                'sort_by'   => 'b.urutan'
            ];


            if($anggaran) {
                $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
            }
            
            if($cabang) {
                $arr['where']['a.kode_cabang']  = $ckode_cabang;
            }

            $data['produk'][$m0->grup] 	= get_data('tbl_bottom_up_form1 a',$arr)->result();

            $rc = array("2", "3");
            $data['rencana'] = get_data('tbl_detail_tahun_anggaran',[
                'where' => [
                    'kode_anggaran' => $ckode_anggaran,
                    'sumber_data' => $rc,

                ],
            ])->result();
        }	        

  
        $response	= array(
            'table'		=> $this->load->view('transaction/usulan_besaran/table',$data,true),
        );
	   
	    render($response,'json');
	}


	function get_data() {
		$data = get_data('tbl_m_bottomup_besaran','id',post('id'))->row_array();

		render($data,'json');
	}	

    function save_perubahan() {       
        $data   = json_decode(post('json'),true);

 //       debug($data);die;
        foreach($data as $id => $record) {
            $result = insert_view_report_arr($record);
            update_data('tbl_bottom_up_form1', $result,'id',$id);
         } 
    }

}