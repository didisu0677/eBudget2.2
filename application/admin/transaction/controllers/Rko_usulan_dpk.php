<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rko_usulan_dpk extends BE_Controller {

    var $detail_tahun;
    var $kode_anggaran;
    var $arr_sumber_data = array();
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran,
                'a.sumber_data'   => array(2,3)
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
        $this->check_sumber_data(2);
        $this->check_sumber_data(3);
    }
    
    function index() {
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('rko_usulan_dpk')
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
            'grup' => 'DPK'
        ],
        ])->result_array();

        $data['detail_tahun']    = $this->detail_tahun;
        render($data);
    }
    
    function sortable() {
        render();
    }

    function data($anggaran="", $cabang="", $tipe = 'table') {
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('rko_usulan_dpk');
        $data['akses_ubah'] = $a['access_edit'];

        $data['current_cabang'] = $cabang;

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
              
        $arr            = [
            'select'    => 'a.*',
            'where'     => [
                'a.is_active' => 1,
                'a.grup' => 'DPK'
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
            $arr['order_by'] = 'a.grup, a.keterangan';
            
            $data['produk'][$m0->keterangan.'all']  = get_data('tbl_target_dpk a',$arr)->result_array();

            $arr['where']['a.sumber_data']    = 3;
            $data['produk'][$m0->keterangan]  = get_data('tbl_target_dpk a',$arr)->result();     
        }           
   
        $data['detail_tahun']    = $this->detail_tahun;
        $response   = array(
            'table'     => $this->load->view('transaction/rko_usulan_dpk/table',$data,true),
        );
       
        render($response,'json');
	}


	function get_data() {
        $dt = get_data('tbl_target_dpk','id',post('id'))->row();

        $data = get_data('tbl_target_dpk',[
            'where' => [
            'kode_anggaran' => $dt->kode_anggaran,    
            'tahun' => $dt->tahun,
            'kode_cabang' => $dt->kode_cabang,
            'grup'  => $dt->grup,
            'sumber_data' => 3,
        ],
        ])->row_array();

        $data['detail_ket'] = get_data('tbl_target_dpk',[
            'where' => [
            'kode_anggaran' => $dt->kode_anggaran,    
            'tahun' => $dt->tahun,
            'kode_cabang' => $dt->kode_cabang,
            'grup'  => $dt->grup,
            'sumber_data' => 3,
        ],
        ])->result_array();


		render($data,'json');
	}	

    function save_perubahan() {       
        $data   = json_decode(post('json'),true);
        foreach($data as $id => $record) {          
            update_data('tbl_target_dpk',$record,'id',$id); }
    }

    function save() {
        $data = post();
        $kode_cabang = post('kode_cabang');
        $ckode_anggaran = user('kode_anggaran');

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();

        $tahun         = $anggaran->tahun_anggaran;
        $keterangan  = post('keterangan');
        $grup_aset   = post('grup_aset');
        $id_data     = post('id_data');
    


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
                'sumber_data' => 3,
            ];

            $cek        = get_data('tbl_target_dpk',[
                'where'         => [
                    'kode_anggaran'   => $ckode_anggaran,
                    'kode_cabang'     => $kode_cabang,
                    'tahun'           => $anggaran->tahun_anggaran,
                    'grup'            => $grup_aset[$i],
                    'sumber_data'     => 3,
                    'id'              => $id_data[$i]
                    ],
            ])->row();
            
            if(!isset($cek->id)) {
                $id = insert_data('tbl_target_dpk',$c);
            }else{
                $id = $id_data[$i];
                update_data('tbl_target_dpk',$c,[
                    'kode_anggaran'   => $ckode_anggaran,
                    'keterangan_anggaran' => $anggaran->keterangan,
                    'kode_cabang'     => $kode_cabang,
                    'tahun'           => $anggaran->tahun_anggaran,
                    'grup'            => $grup_aset[$i],
                    'sumber_data'     => 3,
                    'id'              => $id_data[$i]
                ]);
            }

            //check Estimasi
            if(in_array(2,$this->arr_sumber_data)):
                $c['sumber_data'] = 2;
                $c['parent_id'] = $id;
                $cek        = get_data('tbl_target_dpk',[
                    'where'         => [
                        'kode_anggaran'   => $ckode_anggaran,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $anggaran->tahun_anggaran,
                        'grup'            => $grup_aset[$i],
                        'sumber_data'     => 2,
                        'parent_id'       => $id_data[$i]
                        ],
                ])->row();
                if(!isset($cek->id)) {
                    insert_data('tbl_target_dpk',$c);
                }else{
                    update_data('tbl_target_dpk',$c,[
                        'kode_anggaran'   => $ckode_anggaran,
                        'keterangan_anggaran' => $anggaran->keterangan,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $anggaran->tahun_anggaran,
                        'grup'            => $grup_aset[$i],
                        'sumber_data'     => 2,
                        'parent_id'       => $id_data[$i]
                    ]);
                }
            endif;
        }

        if(post('id')):
            delete_data('tbl_target_dpk',['kode_anggaran'=>$ckode_anggaran,'keterangan not' =>$keterangan,'kode_cabang'=>$kode_cabang,'tahun'=>$anggaran->tahun_anggaran,'grup'=>$grup_aset]);
        endif;    

        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan')
        ],'json');
    }

    private  function check_sumber_data($sumber_data){
        $key = array_search($sumber_data, array_map(function($element){return $element->sumber_data;}, $this->detail_tahun));
        if(strlen($key)>0):
            array_push($this->arr_sumber_data,$sumber_data);
        endif;
    }

}