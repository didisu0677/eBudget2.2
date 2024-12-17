<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_bottomup extends BE_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index() {

    	if(user('id_group') == 5) {
    		
            $cabang = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row();

            $data['cabang'] = get_data('tbl_m_cabang',[
    			'select' => 'distinct kode_cabang,nama_cabang',
    			'where'	 => [
    				'is_active' => 1,
    				'kode_cabang' => user('kode_cabang'),
    			],	
    		])->result_array();

            $data['pos_cabang'] = get_data('tbl_m_struktur_cabang','id',$cabang->level_cabang)->result_array();

            $data['tahun'] = get_data('tbl_tahun_anggaran','tahun',user('tahun_anggaran'))->result();
    	}else{
    		$data['cabang'] = get_data('tbl_m_cabang',[
    			'select' => 'distinct kode_cabang,nama_cabang',
    			'where'	 => [
    				'is_active' => 1,
    			],	
    		])->result_array();


            $data['pos_cabang'] = get_data('tbl_m_struktur_cabang','is_active',1)->result_array();
            
            $data['tahun'] = get_data('tbl_tahun_anggaran','tahun',user('tahun_anggaran'))->result();    
    	} 

        render($data);
    }
    
    function sortable() {
        render();
    }

    function get_cabang($type ='echo',$level_cabang='') {
        if(post('level_cabang') !=""){   
            $level_cabang = post('level_cabang');
        }else{
            $level_cabang = $level_cabang;
        }
 
        $cabang = get_data('tbl_m_cabang','kode_cabang',$level_cabang)->row();

        $x ='';
        for ($i = 1; $i <= 4; $i++) { 
            $field = 'level' . $i ;

            if($cabang->id == $cabang->$field) {
                $x = $field ; 
            }    
        }    

        $rs             = get_data('tbl_m_cabang a',[
            'select'    => 'a.*',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cabang->id
            ]
        ])->result();
        $data           = '<option value="all">Semua cabang</option>';
        foreach($rs as $e) {
            $data       .= '<option value="'.$e->id.'" data-id ="'.$e->kode_cabang.'">'.$e->nama_cabang. '</opfmtion>';
        }
        
        if($type == 'echo') echo $data;
        else return $data;
        
    }

    function data($tahun=0, $cabang="all", $posisi ="", $tipe = 'table') {
        $menu = menu();
        $ctahun = $tahun;
        
        if($cabang == 'null') $cabang = 'all';
        $ckode_cabang = $cabang;

        $cabang = get_data('tbl_m_cabang','kode_cabang',$posisi)->row();
  

        $x ='';
        for ($i = 1; $i <= 4; $i++) { 
            $field = 'level' . $i ;

            if($cabang->id == $cabang->$field) {
                $x = $field ; 
            }    
        }    

        $rs = get_data('tbl_m_cabang a',[
            'select'    => 'a.*',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cabang->id
            ]
        ])->result();

        $allcabang = [''];
        foreach($rs as $c) $allcabang[] = $c->kode_cabang;
        

	    $arr            = [
	        'select'	=> 'distinct grup',
	        'where'     => [
	            'a.is_active' => 1,
	        ],
	        'sort_by'   => 'a.grup',
	    ];
	    
    
	    $data['grup'][0]= get_data('tbl_m_bottomup_besaran a',$arr)->result();
	   	

	   	foreach($data['grup'][0] as $m0) {	       


        	$arr            = [
                'select'    => 'a.id_usulan_bf1 as id, a.grup,a.keterangan,sum(a.B_01) as B_01,sum(a.B_02) as B_02,sum(a.B_03) as B_03,sum(a.B_04) as B_04,sum(a.B_05) as B_05,sum(a.B_06) as B_06,sum(a.B_07) as B_07,sum(a.B_08) as B_08,sum(a.B_09) as B_09,sum(a.B_10) as B_10,sum(a.B_11) as B_11,sum(a.B_12) as B_12',
                'where'     => [
                    'a.grup' => $m0->grup,
                ],
                'group_by' => 'a.id_usulan_bf1,a.grup,a.keterangan'
            ];

            if($tahun) {
                $arr['where']['a.tahun']  = $tahun;
            }
            
            if(($ckode_cabang) and $ckode_cabang != 'all')  {
                $arr['where']['a.kode_cabang']  =  $ckode_cabang;
            }else{
                if($ckode_cabang == 'all') {
                    $arr['where']['a.kode_cabang']  =  $allcabang;
                }
            }

            $data['produk'][$m0->grup] 	= get_data('tbl_bottom_up_form1 a',$arr)->result();     


            $arr            = [
                'select'    => 'a.grup,sum(a.B_01) as B_01,sum(a.B_02) as B_02,sum(a.B_03) as B_03,sum(a.B_04) as B_04,sum(a.B_05) as B_05,sum(a.B_06) as B_06,sum(a.B_07) as B_07,sum(a.B_08) as B_08,sum(a.B_09) as B_09,sum(a.B_10) as B_10,sum(a.B_11) as B_11,sum(a.B_12) as B_12',
                'group_by' => 'a.grup'
            ];

            if($tahun) {
                $arr['where']['a.tahun']  = $tahun;
            }
            
            if(($ckode_cabang) and $ckode_cabang != 'all')  {
                $arr['where']['a.kode_cabang']  =  $ckode_cabang;
            }else{
                if($ckode_cabang == 'all') {
                    $arr['where']['a.kode_cabang']  =  $allcabang;
                }
            }

           
            $data['total_grup'] = get_data('tbl_bottom_up_form1 a',$arr)->result();       
               	               

        }	        
   

            $response   = array(
                'table'     => $this->load->view('transaction/report_bottomup/table',$data,true),
            );

       
        render($response,'json');
	}

}