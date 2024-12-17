<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Usulan_penyelesaian_krd extends BE_Controller {



    function __construct() {

        parent::__construct();

    }

    

    function index() {

        $cabang_user  = get_data('tbl_user',[

            'where' => [

                'is_active' => 1,

                'id_group'  => id_group_access('usulan_penyelesaian_krd')

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

        render($data);

    }

    

    function data($anggaran="", $cabang="", $tipe = 'table') {

        $menu = menu();

        $ckode_anggaran = $anggaran;

        $ckode_cabang = $cabang;



        $a = get_access('usulan_penyelesaian_krd');

        $data['akses_ubah'] = $a['access_edit'];



        $data['current_cabang'] = $cabang;



        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();



	   	    $arr            = [

                'select'	=> 'a.*',

            ];

            

            if($anggaran) {

                $arr['where']['a.kode_anggaran']  = $ckode_anggaran;

            }

            

            if($cabang) {

                $arr['where']['a.kode_cabang']  = $ckode_cabang;

            }



            $produk 	= get_data('tbl_penyelesaian_kredit a',$arr)->result();



            $nama_cabang ='';

            foreach ($produk as $m1) {

                $cabang = get_data('tbl_m_cabang','kode_cabang',$ckode_cabang)->row();

                if(isset($cabang->nama_cabang)) $nama_cabang = $cabang->nama_cabang;



            	$data2 = array(

                    'kode_anggaran' => $ckode_anggaran,

                    'keterangan_anggaran' => $anggaran->keterangan,

	                'tahun'  => $anggaran->tahun_anggaran,

	                'kode_cabang'   => $ckode_cabang,

                    'cabang'        => $nama_cabang,

                    'username'      => user('username'),

                    'nama_debitur' => $m1->nama_debitur,

	            );



	            $cek		= get_data('tbl_penyelesaian_kredit',[

	                'where'			=> [

                        'kode_anggaran'   => $ckode_anggaran,

	                    'kode_cabang'	  => $ckode_cabang,

	                    'tahun'           => $anggaran->tahun_anggaran,

	                    'nama_debitur'	  => $m1->nama_debitur,

	                    ],

	            ])->row();

	            

	            if(!isset($cek->id)) {

	                $response = 			insert_data('tbl_penyelesaian_kredit',$data2);

	            }

            }      



        	$arr            = [

                'select'	=> 'a.*,b.nama_produk_kredit',

                'join'      => 'tbl_produk_kredit b on a.id_produk_kredit = b.id type left'

            ];



            if($anggaran) {

                $arr['where']['a.kode_anggaran']  = $ckode_anggaran;

            }

            

            if($cabang) {

                $arr['where']['a.kode_cabang']  = $ckode_cabang;

            }



            

            $data['produk'] 	= get_data('tbl_penyelesaian_kredit a',$arr)->result();     

        	            

 

        $response	= array(

            'table'		=> $this->load->view('transaction/usulan_penyelesaian_krd/table',$data,true),

        );

	   

	    render($response,'json');

	}





	function get_data() {

        $dt = get_data('tbl_penyelesaian_kredit','id',post('id'))->row();

		$data = get_data('tbl_penyelesaian_kredit',[

            'where' => [

            'kode_anggaran' => $dt->kode_anggaran,

            'tahun' => $dt->tahun,

            'kode_cabang' => $dt->kode_cabang

        ],

        ])->row_array();



        $data['detail_ket'] = get_data('tbl_penyelesaian_kredit',[

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

            update_data('tbl_penyelesaian_kredit',$record,'id',$id); }

    }



    function save() {

        $data = post();

        $kode_cabang = post('kode_cabang');

        $ckode_anggaran = user('kode_anggaran');


        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();



        $tahun         = $anggaran->tahun_anggaran;



        $nama_debitur  = post('nama_debitur');

        $produk_kredit = post('produk_kredit');

        $posisi_kolek  = post('posisi_kolek');

        $tgl_jatuh_tempo = post('tgl_jatuh_tempo');

        $sisa_outstanding = post('sisa_outstanding');

        $deskripsi_penyelesaian = post('deskripsi_penyelesaian');

        $target_waktu_penyelesaian = post('target_waktu_penyelesaian');



        $cabang      = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row();





        $c = [];

        foreach($nama_debitur as $i => $v) {

     

            $c = [

                'kode_anggaran' => $ckode_anggaran,

                'keterangan_anggaran' => $anggaran->keterangan,

                'tahun'  => $anggaran->tahun_anggaran,

                'kode_cabang' => $kode_cabang,

                'cabang' => $cabang->nama_cabang,

                'username' => user('username'),

                'nama_debitur' => $nama_debitur[$i],

                'produk_kredit' => $produk_kredit[$i],

                'id_produk_kredit' => $produk_kredit[$i],

                'posisi_kolek' => $posisi_kolek[$i],

                'tgl_jatuh_tempo' => $tgl_jatuh_tempo[$i],

                'sisa_outstanding' => $sisa_outstanding[$i],

                'deskripsi_penyelesaian' => $deskripsi_penyelesaian[$i],

                'target_waktu_penyelesaian' => $target_waktu_penyelesaian[$i],

            ];



            $cek        = get_data('tbl_penyelesaian_kredit',[

                'where'         => [

                    'kode_anggaran'   => $ckode_anggaran,

                    'kode_cabang'     => $kode_cabang,

                    'tahun'           => $tahun,

                    'nama_debitur' => $nama_debitur[$i],

                    ],

            ])->row();

            

            if(!isset($cek->id)) {

                insert_data('tbl_penyelesaian_kredit',$c);

            }else{

                update_data('tbl_penyelesaian_kredit',$c,['kode_anggaran'   => $ckode_anggaran,

                    'kode_cabang'     => $kode_cabang,

                    'tahun'           => $tahun,

                    'nama_debitur' => $nama_debitur[$i]]);

            }

    

        }


        if(post('id')):

        delete_data('tbl_penyelesaian_kredit',['kode_anggaran'=>$ckode_anggaran,'nama_debitur not' =>$nama_debitur,'kode_cabang'=>$kode_cabang,'tahun'=>$anggaran->tahun_anggaran]);    
        endif;  

 

        render([

            'status'    => 'success',

            'message'   => lang('data_berhasil_disimpan')

        ],'json');

    }



    function get_produk_kredit($type ='echo') {

        $list = get_data('tbl_produk_kredit a',[

            'where'     => [

                'a.is_active' => 1,

            ]

        ])->result();

        $data           = '<option value=""></option>';

        foreach($list as $v) {

            $data       .= '<option value="'.$v->id.'">'.$v->nama_produk_kredit.'</option>';

        }

        if($type == 'echo') echo $data;

        else return $data;       

    }

}