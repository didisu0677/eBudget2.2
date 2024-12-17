<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kebijakan_strategis extends BE_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('kebijakan_strategis')
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

    function data($anggaran="", $cabang="", $tipe = 'table'){
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('kebijakan_strategis');
        $data['akses_ubah'] = $a['access_edit'];

        $arr = ['select'    => 'a.*',];
        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }
        $arr['order_by'] = 'name,aktivitas';

        $list = get_data('tbl_kebijakan_strategis a',$arr)->result();
        $data['list']     = $list;

        // header
        $arrHeader = array();
        foreach ($list as $k => $v) {
            $name = str_replace(' ', '_', $v->name);
            if(isset($data['count_'.$name])):
                $data['count_'.$name] += 1;
            else:
                $data['count_'.$name] = 1;
            endif;

            if(!in_array($v->name,$arrHeader)):
                array_push($arrHeader,$v->name);
            endif;
        }
        $data['header']     = $arrHeader;
 
        $response   = array(
            'table' => $this->load->view('transaction/kebijakan_strategis/table',$data,true),
            'list'  => $list,
        );
       
        render($response,'json');
    }

    function save(){
        $data = post();
        $kode_cabang = post('kode_cabang');
        $ckode_anggaran = user('kode_anggaran');

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
        $cabang   = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row();
        $tahun    = $anggaran->tahun_anggaran;

        $dt_index      = post('dt_index');
        $kebijakan  = post('kebijakan');

        $c = [];
        if($kebijakan):
            foreach ($kebijakan as $i => $v) {
                $key = $dt_index[$i];
                $aktivitas  = post('aktivitas'.$key);
                $target     = post('target'.$key);
                $deskripsi  = post('deskripsi'.$key);
                $goal       = post('goal'.$key);
                $tanggal_target  = post('tanggal_target'.$key);
                $id_kebijakan    = post('id_kebijakan'.$key);
                $dt_aktivitas = array();
                $arrID = array();
                if($aktivitas):
                    foreach ($aktivitas as $k => $v2) {
                        $c = [
                            'kode_anggaran' => $ckode_anggaran,
                            'keterangan_anggaran' => $anggaran->keterangan,
                            'tahun'         => $anggaran->tahun_anggaran,
                            'kode_cabang'   => $kode_cabang,
                            'aktivitas'     => $aktivitas[$k],
                            'cabang'        => $cabang->nama_cabang,
                            'username'      => user('username'),
                            'name'          => $kebijakan[$i],
                            'target'        => $target[$k],
                            'keterangan'    => $deskripsi[$k],
                            'tanggal_target'  => $tanggal_target[$k],
                            'goal'          => $goal[$k],
                        ];

                        $cek = get_data('tbl_kebijakan_strategis',[
                            'where'         => [
                                'kode_anggaran'   => $ckode_anggaran,
                                'kode_cabang'     => $kode_cabang,
                                'tahun'           => $tahun,
                                'id' => $id_kebijakan[$k],
                            ],
                        ])->row();

                        if(!isset($cek->id)) {
                            $dt_insert = insert_data('tbl_kebijakan_strategis',$c);
                            array_push($arrID, $dt_insert);
                        }else{
                            update_data('tbl_kebijakan_strategis',$c,['kode_anggaran'   => $ckode_anggaran,
                                'kode_cabang'     => $kode_cabang,
                                'tahun'           => $tahun,
                                'id' => $id_kebijakan[$k]]);
                            array_push($arrID, $id_kebijakan[$k]);
                        }
                    }
                endif;

                if(count($arrID)>0 && post('id')):
                    delete_data('tbl_kebijakan_strategis',['kode_anggaran'=>$ckode_anggaran,'id not'=>$arrID,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun,'name' => $v]);
                endif;
            }
        else:
            if(post('id')):
                $d = get_data('tbl_kebijakan_strategis',[
                    'where'         => [
                        'id' => post('id'),
                    ],
                ])->row();
                delete_data('tbl_kebijakan_strategis',['kode_anggaran'=>$ckode_anggaran,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun,'name' => $d->name]);
            endif;
        endif;

        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan'),
        ],'json');
    }

    function get_data(){
        $d = get_data('tbl_kebijakan_strategis',[
            'where'         => [
                'id' => post('id'),
            ],
        ])->row();

        $list = get_data('tbl_kebijakan_strategis',[
            'where'         => [
                'name' => $d->name,
                'kode_anggaran'   => $d->kode_anggaran,
                'kode_cabang'     => $d->kode_cabang,
                'tahun'           => $d->tahun,
            ],
            'order_by'  => 'name,aktivitas'
        ])->result();

        render([
            'status'    => 'success',
            'data'      => $list,
            'detail'    => $d,
        ],'json');
    }
}