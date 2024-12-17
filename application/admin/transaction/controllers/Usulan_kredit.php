<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Usulan_kredit extends BE_Controller {



    function __construct() {

        parent::__construct();

    }

    

    function index() {

        $cabang_user  = get_data('tbl_user',[

            'where' => [

                'is_active' => 1,

                'id_group'  => id_group_access('usulan_kredit')

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



        $data['opt_grup']  = get_data('tbl_m_target_pipeline',[

            'where' => [

            'is_active' => 1,

            'grup' => 'KREDIT'

        ],

        ])->result_array();



        render($data);

    }

    

    function sortable() {

        render();

    }



    function data($anggaran="", $cabang="", $tipe = 'table') {

        $menu = menu();

        $ckode_anggaran = $anggaran;

        $ckode_cabang = $cabang;



        $a = get_access('usulan_kredit');

        $data['akses_ubah'] = $a['access_edit'];



        $data['current_cabang'] = $cabang;



        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();

        

                  

        $arr            = [

            'select'    => 'a.*',

            'where'     => [

                'a.is_active' => 1,

                'a.grup' => 'KREDIT'

            ],

        ];

        

    

        $data['grup'][0]= get_data('tbl_m_target_pipeline a',$arr)->result();

        



        foreach($data['grup'][0] as $m0) {         



            $arr            = [

                'select'    => 'a.*',

                'where'     => [

                    'a.grup' => $m0->keterangan,

                ],

            ];

            

            if($anggaran) {

                $arr['where']['a.kode_anggaran']  = $ckode_anggaran;

            }

            

            if($cabang) {

                $arr['where']['a.kode_cabang']  = $ckode_cabang;

            }



            

            $data['produk'][$m0->keterangan]  = get_data('tbl_target_kredit a',$arr)->result();     



    //        debug($data['produk']);die;

                                   

        }           

   



        $response   = array(

            'table'     => $this->load->view('transaction/usulan_kredit/table',$data,true),

        );

       

        render($response,'json');

	}





	function get_data() {

        $dt = get_data('tbl_target_kredit','id',post('id'))->row();



        $data = get_data('tbl_target_kredit',[

            'where' => [

            'kode_anggaran' => $dt->kode_anggaran,    

            'tahun' => $dt->tahun,

            'kode_cabang' => $dt->kode_cabang

        ],

        ])->row_array();



        $data['detail_ket'] = get_data('tbl_target_kredit',[

            'where' => [

            'kode_anggaran' => $dt->kode_anggaran,    

            'tahun' => $dt->tahun,

            'kode_cabang' => $dt->kode_cabang,

        ],

        ])->result_array();





		render($data,'json');

	}	



    function save_perubahan() {       

        $data   = json_decode(post('json'),true);

        foreach($data as $id => $record) {          

            update_data('tbl_target_kredit',$record,'id',$id); }

    }



    function save() {

        $data = post();

        $kode_cabang = post('kode_cabang');

        $ckode_anggaran = user('kode_anggaran');



        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();



        $tahun         = $anggaran->tahun_anggaran;



        $keterangan  = post('keterangan');

        $grup_aset   = post('grup_aset');

    





        $cabang      = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row();





        $c = [];

        foreach($keterangan as $i => $v) {



            $c = [

                'kode_anggaran' => $ckode_anggaran,

                'keterangan_anggaran' => $anggaran->keterangan,

                'tahun'  => $anggaran->tahun_anggaran,

                'kode_cabang' => $kode_cabang,

                'cabang' => $cabang->nama_cabang,

                'username' => user('username'),

                'keterangan' => $keterangan[$i],

                'grup'  => $grup_aset[$i],

            ];



            $cek        = get_data('tbl_target_kredit',[

                'where'         => [

                    'kode_anggaran'   => $ckode_anggaran,

                    'kode_cabang'     => $kode_cabang,

                    'tahun'           => $anggaran->tahun_anggaran,

                    'keterangan'      => $keterangan[$i],

                    'grup'            => $grup_aset[$i],

                    ],

            ])->row();

            

            if(!isset($cek->id)) {

                insert_data('tbl_target_kredit',$c);

            }else{

                update_data('tbl_target_kredit',$c,[

                    'kode_anggaran'   => $ckode_anggaran,

                    'kode_cabang'     => $kode_cabang,

                    'tahun'           => $anggaran->tahun_anggaran,

                    'keterangan'      => $keterangan[$i],

                    'grup'            => $grup_aset[$i],

                ]);

            }

    

        }


        if(post('id')):
            delete_data('tbl_target_kredit',['kode_anggaran'=>$ckode_anggaran,'keterangan not' =>$keterangan,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun,'grup'=>$grup_aset]);
        endif;   



  



  

        render([

            'status'    => 'success',

            'message'   => lang('data_berhasil_disimpan')

        ],'json');

    }



}