<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Usulan_pengembangan extends BE_Controller {



    function __construct() {

        parent::__construct();

    }

    

    function index() {



    	if(user('id_group') == 5) {

    		$data['cabang'] = get_data('tbl_m_cabang',[

    			'select' => 'distinct kode_cabang,nama_cabang',

    			'where'	 => [

    				'is_active' => 1,

    				'kode_cabang' => user('kode_cabang'),

    			],	

    		])->result_array();



            $data['tahun'] = get_data('tbl_tahun_anggaran','tahun_anggaran',user('tahun_anggaran'))->result();

    	}else{

    		    $data['cabang'] = get_data('tbl_m_cabang',[

    			'select' => 'distinct kode_cabang,nama_cabang',

    			'where'	 => [

    				'is_active' => 1,

    			],	

    		])->result_array();

            $data['tahun'] = get_data('tbl_tahun_anggaran','tahun_anggaran',user('tahun_anggaran'))->result();    

    	} 



        $data['opt_jaringan']  = get_data('tbl_status_jaringan_kantor',[

            'where' => [

            'is_active' => 1,

        ],

        ])->result_array();



        $data['opt_kategori']  = get_data('tbl_kategori_kantor',[

            'where' => [

            'is_active' => 1,

        ],

        ])->result_array();



        $data['opt_status']  = get_data('tbl_status_ket_kantor',[

            'where' => [

            'is_active' => 1,

        ],

        ])->result_array();

        render($data);

    }

    

    function data($tahun=0, $cabang="", $tipe = 'table') {

        $menu = menu();

        $ctahun = $tahun;

        $ckode_cabang = $cabang;

        

	   	    $arr            = [

                'select'	=> 'a.*',

            ];

            

            if($tahun) {

                $arr['where']['a.tahun']  = $tahun;

            }

            

            if($cabang) {

                $arr['where']['a.kode_cabang']  = $ckode_cabang;

            }



            $produk 	= get_data('tbl_rencana_survey_jaringan a',$arr)->result();



            $nama_cabang ='';

            foreach ($produk as $m1) {



                $cabang = get_data('tbl_m_cabang','kode_cabang',$ckode_cabang)->row();

                

                if(isset($cabang->nama_cabang)) $nama_cabang = $cabang->nama_cabang;



            	$data2 = array(

	                'tahun'  => $ctahun,

	                'kode_cabang'   => $ckode_cabang,

                    'cabang'        => $nama_cabang,

                    'username'      => user('username'),

                    'id_status_jaringan' => '',

                    'status_jaringan_kantor' => '',

                    'id_kategori_kantor' => '',

                    'kategori_kantor' => '',

                    'nama_lokasi' => '',

                    'bulan' => $m1->bulan,

                    'id_status_kantor' => '',

                    'status_ket_kantor' => ''

	            );



	            $cek		= get_data('tbl_rencana_survey_jaringan',[

	                'where'			=> [

	                    'kode_cabang'	  => $ckode_cabang,

	                    'tahun'           => $ctahun,

                        'id_status_jaringan'  => $m1->id_status_jaringan,  

	                    'id_kategori_kantor'	  => $m1->id_kategori_kantor,

                        'bulan' => $m1->bulan

	                    ],

	            ])->row();

	            

	            if(!isset($cek->id)) {

	                $response = 			insert_data('tbl_rencana_survey_jaringan',$data2);

	            }

            }      



        	$arr            = [

                'select'	=> 'a.*',

            ];



            if($tahun) {

                $arr['where']['a.tahun']  = $tahun;

            }

            

            if($cabang) {

                $arr['where']['a.kode_cabang']  = $ckode_cabang;

            }



            

            $data['produk'] 	= get_data('tbl_rencana_survey_jaringan a',$arr)->result();     

        	            

 

        $response	= array(

            'table'		=> $this->load->view('transaction/usulan_pengembangan/table',$data,true),

        );

	   

	    render($response,'json');

	}





	function get_data() {

        $dt = get_data('tbl_rencana_survey_jaringan','id',post('id'))->row();

		$data = get_data('tbl_rencana_survey_jaringan',[

            'where' => [

            'tahun' => $dt->tahun,

            'kode_cabang' => $dt->kode_cabang

        ],

        ])->row_array();



        $data['detail_ket'] = get_data('tbl_rencana_survey_jaringan',[

            'where' => [

            'tahun' => $dt->tahun,

            'kode_cabang' => $dt->kode_cabang,

        ],

        ])->result_array();



		render($data,'json');

	}	



    function save_perubahan() {       

        $data   = json_decode(post('json'),true);

        foreach($data as $id => $record) {          

            update_data('tbl_rencana_survey_jaringan',$record,'id',$id); }

    }



    function save() {

        $data = post();

        $kode_cabang = post('kode_cabang');

        $tahun  = user('tahun_anggaran');

        $status_jaringan_kantor = post('status_jaringan_kantor');

        $kategori = post('kategori');

        $status_ket =post('status_ket');

        $nama_lokasi  = post('nama_lokasi');

        $bulan = post('bulan');



        $cabang      = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row();





        $c = [];

        foreach($nama_lokasi as $i => $v) {

            $jaringan_kantor = '';

            $kategori_kantor = '';

            $status_kantor = '';



            $jaringan = get_data('tbl_status_jaringan_kantor','id',$status_jaringan_kantor[$i])->row();

            if(isset($jaringan->id)) $jaringan_kantor = $jaringan->status_jaringan;



            $kat = get_data('tbl_kategori_kantor','id',$kategori[$i])->row();

            if(isset($kat->id)) $kategori_kantor = $kat->kategori;



            $st = get_data('tbl_status_ket_kantor','id',$status_ket[$i])->row();

            if(isset($st->id)) $status_kantor = $st->status_ket;



            $c = [

                'tahun'  => $tahun,

                'kode_cabang' => $kode_cabang,

                'cabang' => $cabang->nama_cabang,

                'username' => user('username'),

                'id_status_jaringan' => $status_jaringan_kantor[$i],

                'status_jaringan_kantor' => $jaringan_kantor,

                'id_kategori_kantor' => $kategori[$i],

                'kategori_kantor' => $kategori_kantor,

                'nama_lokasi' => $nama_lokasi[$i],

                'id_status_kantor' => $status_ket[$i],

                'bulan'           => $bulan[$i],  

                'status_ket_kantor' => $status_kantor,

            ];



            $cek        = get_data('tbl_rencana_survey_jaringan',[

                'where'         => [

                    'kode_cabang'     => $kode_cabang,

                    'tahun'           => $tahun,

                    'id_status_jaringan' => $status_jaringan_kantor[$i],

                    'id_kategori_kantor' => $kategori[$i],

                    'id_status_kantor' => $status_ket[$i]

                    ],

            ])->row();

            

            if(!isset($cek->id)) {

                insert_data('tbl_rencana_survey_jaringan',$c);

            }else{

                update_data('tbl_rencana_survey_jaringan',$c,

                    ['kode_cabang'     => $kode_cabang,

                    'tahun'           => $tahun,

                    'id_status_jaringan' => $status_jaringan_kantor[$i],

                    'id_kategori_kantor' => $kategori[$i],

                    'id_status_kantor' => $status_ket[$i]]);

            }



    

        }



        if(post('id')):
            delete_data('tbl_rencana_survey_jaringan',['nama_lokasi not'=>$nama_lokasi,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun]);    
        endif;


    //    if(count($c) > 0) insert_batch('tbl_rencana_aset',$c);



    

 

        render([

            'status'    => 'success',

            'message'   => lang('data_berhasil_disimpan')

        ],'json');

    }

}