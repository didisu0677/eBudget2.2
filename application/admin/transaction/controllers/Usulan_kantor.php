<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usulan_kantor extends BE_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function opt_cabang($type="echo"){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('usulan_kantor')
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

        $cabang            = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cab->id,
                'a.kode_cabang' => $kode_cabang
            ],
            'order_by' => 'a.kode_cabang'
        ])->result_array();

        if($type == 'echo'):
            $data           = '<option value=""></option>';
            foreach($cabang as $e1) {
                $data       .= '<option value="'.$e1['kode_cabang'].'">'.$e1['nama_cabang'].'</option>';
            }
            echo $data;
        else:
            return $cabang;
        endif;
    }
    function index() {
        $data['cabang'] = $this->opt_cabang('');
        $data['cabang_input'] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.kode_cabang' => user('kode_cabang')
            ]
        ])->result_array();

        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result(); 

        $a  = get_access('usulan_kantor');
        $data['access_additional']  = $a['access_additional'];
        $data['access_edit']  = $a['access_edit'];
        render($data);
    }


    function get_status($type ='echo') {
        $barang             = get_data('tbl_status_ket_kantor a',[
            'where'     => [
                'a.is_active' => 1,
            ]
        ])->result();
        $data           = '<option value=""></option>';
        foreach($barang as $e1) {
            $data       .= '<option value="'.$e1->id.'">'.$e1->status_ket.'</option>';
        }

        if($type == 'echo') echo $data;
        else return $data;       
    }


    function get_rencana($type ='echo',$cabang="") {
        $relokasi = '';
        if($cabang):
            $cabang = get_data('tbl_m_cabang','kode_cabang',$cabang)->row_array();
            if(isset($cabang['id'])):
                if('cabang pembantu' == strtolower($cabang['struktur_cabang'])):
                    $relokasi = 'relokasi';
                endif;
            endif;
        endif;
        $where = [
            'a.is_active' => 1,
        ];
        if($relokasi):
            $where['a.status_jaringan like'] = $relokasi;
        endif;
        $barang             = get_data('tbl_status_jaringan_kantor a',[
            'where'     => $where,
        ])->result();
        $data           = '<option value=""></option>';
        foreach($barang as $e1) {
            $data       .= '<option value="'.$e1->id.'">'.$e1->status_jaringan.'</option>';
        }

        if($type == 'echo') render(['data' => $data,'relokasi' => $relokasi],'json');
        else return $data;       
    }

    function get_tahapan($type ='echo') {
        $barang             = get_data('tbl_tahapan_pengembangan a',[
            'where'     => [
                'a.is_active' => 1,
            ]
        ])->result();
        $data           = '<option value=""></option>';
        foreach($barang as $e2) {
            $data       .= '<option value="'.$e2->id.'">'.$e2->tahapan.'</option>';
        }

        if($type == 'echo') echo $data;
        else return $data;       
    }

    function get_jenis_kantor($type ='echo') {
        $barang             = get_data('tbl_kategori_kantor a',[
            'where'     => [
                'a.is_active' => 1,
            ]
        ])->result();
        $data           = '<option value=""></option>';
        foreach($barang as $e3) {
            $harga = view_report($e3->harga);
            $data       .= '<option data-harga="'.$harga.'" value="'.$e3->id.'">'.$e3->kategori.'</option>';
        }

        if($type == 'echo') echo $data;
        else return $data;       
    }

    function get_jadwal($type ='echo') {
        $data           = '<option value=""></option>';
        for($i = 1; $i <= 12; $i++){
            $data       .= '<option value="'.$i.'">'.month_lang($i).'</option>';
        }

        if($type == 'echo') echo $data;
        else return $data;       

    }

    
    
    function data($anggaran="", $cabang="", $tipe = 'table') {
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('usulan_kantor');
        $access_edit = false;
        if($a['access_edit'] && $cabang == user('kode_cabang')):
            $access_edit = true;
        elseif($a['access_edit'] && $a['access_additional']):
            $access_edit = true;
        endif;
        $data['akses_ubah'] = $access_edit;

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

            $produk 	= get_data('tbl_rencana_pjaringan a',$arr)->result();

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
                    'id_rencana' => '',
                    'rencana_jarkan' => '',
                    'id_kategori_kantor' => '',
                    'kategori_kantor' => '',
                    'nama_lokasi' => '',
                    'jadwal' => $m1->jadwal,
                    'id_status_kantor' => '',
                    'status_ket_kantor' => ''
	            );

	            $cek		= get_data('tbl_rencana_pjaringan',[
	                'where'			=> [
                        'kode_anggaran'   => $ckode_anggaran,
	                    'kode_cabang'	  => $ckode_cabang,
	                    'tahun'           => $anggaran->tahun_anggaran,
                        'id_rencana'  => $m1->id_rencana,  
	                    'id_kategori_kantor'	  => $m1->id_kategori_kantor
	                    ],
	            ])->row();
	            
	            if(!isset($cek->id)) {
	                $response = 			insert_data('tbl_rencana_pjaringan',$data2);
	            }
            }      

        	$arr            = [
                'select'	=> 'a.*,b.name as provinsi,c.name as kota,d.name as kecamatan,e.nama as nama_keterangan,e.warna as warna_keterangan',
            ];

            if($anggaran) {
                $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
            }
            
            if($cabang) {
                $arr['where']['a.kode_cabang']  = $ckode_cabang;
            }

            $arr['join'][] = 'provinsi b on b.id = a.id_provinsi type left';
            $arr['join'][] = 'kota c on c.id = a.id_kota type left';
            $arr['join'][] = 'kecamatan d on d.id = a.id_kecamatan type left';
            $arr['join'][] = 'tbl_kategori_kantor_keterangan e on e.id = a.id_keterangan type left';
            $data['produk'] 	= get_data('tbl_rencana_pjaringan a',$arr)->result();     
        	            
 
        $response	= array(
            'table'		=> $this->load->view('transaction/usulan_kantor/table',$data,true),
            'edit'      => $access_edit,
        );
	   
	    render($response,'json');
	}


	function get_data() {
        $dt = get_data('tbl_rencana_pjaringan','id',post('id'))->row();

		$data = get_data('tbl_rencana_pjaringan',[
            'where' => [
            'kode_anggaran' => $dt->kode_anggaran,    
            'kode_cabang' => $dt->kode_cabang
        ],
        ])->row_array();

        $data['detail'] = get_data('tbl_rencana_pjaringan',[
            'where' => [
            'kode_anggaran' => $dt->kode_anggaran,    
            'tahun' => $dt->tahun,
            'kode_cabang' => $dt->kode_cabang,
        ],
        ])->result_array();

        foreach ($data['detail'] as $k => $v) {
            $data['detail'][$k]['harga'] = (string) view_report($v['harga']);
        }

		render($data,'json');
	}	

    function save_perubahan() {       
        $data   = json_decode(post('json'),true);
        foreach($data as $id => $record) {
        //    $result = insert_view_report_arr($record);
            update_data('tbl_rencana_pjaringan', $record,'id',$id);
        } 
    }

    function save() {
        $data = post();
        $kode_cabang = post('kode_cabang');

        $ckode_anggaran = user('kode_anggaran');

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();

        $tahun         = $anggaran->tahun_anggaran;

        $rencana    = post('rencana');
        $tahapan    = post('tahapan');
        $kategori   = post('jenis_kantor');
        $jadwal     = post('jadwal');
        $status_ket = post('status_ket');
        $provinsi   = post('provinsi');
        $kota       = post('kota');
        $kecamatan  = post('kecamatan');
        $keterangan = post('keterangan');
        $harga      = post('harga');
        $p_cabang   = post('cabang');
        $nama_kantor   = post('nama_kantor');
   //     $nama_lokasi  = post('nama_lokasi');
   //     $bulan = post('bulan');


        $cabang      = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row();


        $c = [];
        if($tahapan):
            foreach($tahapan as $i => $v) {
                $jaringan_kantor = '';
                $kategori_kantor = '';
                $status_kantor = '';

                $jaringan = get_data('tbl_status_jaringan_kantor','id',$rencana[$i])->row();
                if(isset($jaringan->id)) $jaringan_kantor = $jaringan->status_jaringan;

                $kat = get_data('tbl_kategori_kantor','id',$kategori[$i])->row();
                if(isset($kat->id)) $kategori_kantor = $kat->kategori;

                $st = get_data('tbl_status_ket_kantor','id',$status_ket[$i])->row();
                if(isset($st->id)) $status_kantor = $st->status_ket;

                $tah = get_data('tbl_tahapan_pengembangan','id',$tahapan[$i])->row();
                if(isset($tah->id)) $tahapan_pengembangan = $tah->tahapan;

                $d_harga = str_replace('.', '', $harga[$i]);
                $d_harga = insert_view_report($d_harga);

                $c = [
                    'kode_anggaran'         => $ckode_anggaran,
                    'keterangan_anggaran'   => $anggaran->keterangan,
                    'tahun'                 => $anggaran->tahun_anggaran,
                    'kode_cabang'           => $kode_cabang,
                    'cabang'                => $cabang->nama_cabang,
                    'cabang_induk'          => $p_cabang[$i],
                    'username'              => user('username'),
                    'id_rencana'            => $rencana[$i],
                    'rencana_jarkan'        => $jaringan_kantor,
                    'id_tahapan'            => $tahapan[$i],
                    'tahapan_pengembangan'  => $tahapan_pengembangan,
                    'id_kategori_kantor'    => $kategori[$i],
                    'kategori_kantor'       => $kategori_kantor,
                    'harga'                 => $d_harga,
                    'jadwal'                => $jadwal[$i],
                    'id_provinsi'           => $provinsi[$i],
                    'id_kota'               => $kota[$i],
                    'id_kecamatan'          => $kecamatan[$i],
                    'id_keterangan'         => $keterangan[$i],
                    'nama_kantor'           => $nama_kantor[$i],
                    'id_status_kantor' => $status_ket[$i],
                 //   'bulan'           => $bulan[$i],  
                    'status_ket_kantor' => $status_kantor,
                ];

                $cek        = get_data('tbl_rencana_pjaringan',[
                    'where'         => [
                        'kode_anggaran'   => $ckode_anggaran,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $anggaran->tahun_anggaran,
                        'id_rencana' => $rencana[$i],
                        'id_kategori_kantor' => $kategori[$i],
                        ],
                ])->row();
                
                if(!isset($cek->id)) {
                    insert_data('tbl_rencana_pjaringan',$c);
                }else{
                    update_data('tbl_rencana_pjaringan',$c,
                        ['id' => $cek->id]);
                }

            }
        endif;

        if(post('id')):
            if($tahapan):
                delete_data('tbl_rencana_pjaringan',['kode_anggaran'=>$ckode_anggaran,'id_tahapan not'=>$tahapan,'kode_cabang'=>$kode_cabang,'tahun'=>$anggaran->tahun_anggaran]);
            else:
                delete_data('tbl_rencana_pjaringan',['kode_anggaran'=>$ckode_anggaran,'kode_cabang'=>$kode_cabang,'tahun'=>$anggaran->tahun_anggaran]);
            endif;    
        endif;

    
 
        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan')
        ],'json');
    }

    function detail($id,$cabang){
       $arr            = [
            'select'    => '
                a.*,
                b.name as provinsi,c.name as kota,d.name as kecamatan,e.nama as nama_keterangan,e.warna as warna_keterangan,
                f.keterangan as anggaran_keterangan,
                g.nama_cabang,
                ',
        ];

        $arr['join'][] = 'provinsi b on b.id = a.id_provinsi type left';
        $arr['join'][] = 'kota c on c.id = a.id_kota type left';
        $arr['join'][] = 'kecamatan d on d.id = a.id_kecamatan type left';
        $arr['join'][] = 'tbl_kategori_kantor_keterangan e on e.id = a.id_keterangan type left';
        $arr['join'][] = 'tbl_tahun_anggaran f on f.kode_anggaran = a.kode_anggaran type left';
        $arr['join'][] = 'tbl_m_cabang g on g.kode_cabang = a.kode_cabang type left';
        $arr['where']['a.id'] = $id;
        $data = get_data('tbl_rencana_pjaringan a',$arr)->row_array();

        if(isset($data['id'])) {
            render($data,'layout:false view:transaction/usulan_kantor/detail');
        } else echo lang('tidak_ada_data');
    }
}