<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Usulan_kegiatan extends BE_Controller {
    function __construct() {
        parent::__construct();
    }

    function index() {
       $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('usulan_kegiatan')
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

        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result();   
        $data['bulan'] = get_data('tbl_detail_tahun_anggaran a',[
            'select' => 'a.*,b.singkatan',
            'join'  => ['tbl_m_data_budget b on a.sumber_data = b.id type LEFT',
            ],
            'where' => [
                'a.kode_anggaran' => user('kode_anggaran'),
                'a.sumber_data !=' => 1
                ],
            'sort_by'   => 'a.tahun,a.bulan',
            'sort'      => 'ASC'
        ])->result();

        $data['cabang_input'] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.kode_cabang' => user('kode_cabang')
            ]
        ])->result_array();

        $a  = get_access('usulan_kegiatan');
        $data['access_additional']  = $a['access_additional'];
        render($data);
    }

    function data($anggaran="", $cabang="", $tipe = 'table') {
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('usulan_kegiatan');
        $access_edit = false;
        if($a['access_edit'] && $cabang == user('kode_cabang')):
            $access_edit = true;
        elseif($a['access_edit'] && $a['access_additional']):
            $access_edit = true;
        endif;
        $data['akses_ubah'] = $access_edit;

        $data['current_cabang'] = $cabang;
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
        $data['bulan'] = get_data('tbl_detail_tahun_anggaran a',[
            'select' => 'a.*,b.singkatan',
            'join'  => ['tbl_m_data_budget b on a.sumber_data = b.id type LEFT',
            ],
            'where' => [
                'a.kode_anggaran' => user('kode_anggaran'),
                'a.sumber_data !=' => 1
                ],
            'sort_by'   => 'a.tahun,a.bulan',
            'sort'      => 'ASC'
        ])->result();

	   	    $arr            = [
                'select'	=> 'a.*',
            ];

            if($anggaran) {
                $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
            }

            if($cabang) {
                $arr['where']['a.kode_cabang']  = $ckode_cabang;
            }

            $produk 	= get_data('tbl_rencana_kpromosi a',$arr)->result();
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
                    'nomor_kegiatan' => $m1->nomor_kegiatan,
                    'nama_kegiatan' => $m1->nama_kegiatan,
	            );

	            $cek		= get_data('tbl_rencana_kpromosi',[
	                'where'			=> [
                        'kode_anggaran'   => $ckode_anggaran,
	                    'kode_cabang'	  => $ckode_cabang,
	                    'tahun'           => $anggaran->tahun_anggaran,
                        'nomor_kegiatan'  => $m1->nomor_kegiatan,  
	                    'nama_kegiatan'	  => $m1->nama_kegiatan,
	                    ],
	            ])->row();

	            
	            if(!isset($cek->id)) {
	                $response = 			insert_data('tbl_rencana_kpromosi',$data2);
	            }
            }      

        	$arr            = [
                'select'	=> 'a.*',
            ];

            if($anggaran) {
                $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
            }

            if($cabang) {
                $arr['where']['a.kode_cabang']  = $ckode_cabang;
            }

            $data['produk'] 	= get_data('tbl_rencana_kpromosi a',$arr)->result();     

        $response	= array(
            'table'		=> $this->load->view('transaction/usulan_kegiatan/table',$data,true),
            'edit'      => $access_edit,
        );

	    render($response,'json');
	}

	function get_data() {
        $dt = get_data('tbl_rencana_kpromosi','id',post('id'))->row();
		$data = get_data('tbl_rencana_kpromosi',[
            'where' => [
            'kode_anggaran' => $dt->kode_anggaran,    
            'tahun' => $dt->tahun,
            'kode_cabang' => $dt->kode_cabang
        ],
        ])->row_array();

        $data['detail_ket'] = get_data('tbl_rencana_kpromosi',[
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
            $result = insert_view_report_arr($record);
            update_data('tbl_rencana_kpromosi', $result,'id',$id);
         } 
    }

    function save() {
        $data = post();
        $kode_cabang = post('kode_cabang');
        $ckode_anggaran = user('kode_anggaran');

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
        $tahun         = $anggaran->tahun_anggaran;
        $keterangan  = post('keterangan');
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
                'nomor_kegiatan' => '',
                'nama_kegiatan' => $keterangan[$i],
            ];

            $cek        = get_data('tbl_rencana_kpromosi',[
                'where'         => [
                    'kode_anggaran'   => $ckode_anggaran,
                    'kode_cabang'     => $kode_cabang,
                    'tahun'           => $tahun,
                    'nomor_kegiatan' => '',  
                    'nama_kegiatan' => $keterangan[$i],
                    ],
            ])->row();

            if(!isset($cek->id)) {
                insert_data('tbl_rencana_kpromosi',$c);
            }
        }

        if(post('id')):
            delete_data('tbl_rencana_kpromosi',['kode_anggaran' => $ckode_anggaran,'nomor_kegiatan'=>'','nama_kegiatan not' =>$keterangan,'kode_cabang'=>$kode_cabang,'tahun'=>$anggaran->tahun_anggaran]);   
        endif; 
    //    if(count($c) > 0) insert_batch('tbl_rencana_aset',$c);
        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan')
        ],'json');
    }

}