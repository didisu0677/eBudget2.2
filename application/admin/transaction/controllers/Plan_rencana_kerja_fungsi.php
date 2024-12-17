<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_rencana_kerja_fungsi extends BE_Controller {
    var $path       = 'transaction/budget_planner/kantor_pusat/';
    var $sub_menu   = 'transaction/budget_planner/sub_menu';
    function __construct() {
        parent::__construct();
    }
    
    function index($p1="") {
        $a = get_access('plan_rencana_kerja_fungsi');
        $data = cabang_divisi();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['access_additional']  = $a['access_additional'];
        $data['access_edit']        = $a['access_edit'];
        render($data,'view:'.$this->path.'rencana_kerja_fungsi/index');
    }

    function get_kebijakan_umum($type="echo"){
        $ls             = get_data('tbl_kebijakan_umum a',[
            'where'     => [
                'a.is_active' => 1,
            ]
        ])->result();
        $data           = '<option value=""></option>';
        foreach($ls as $e2) {
            $data       .= '<option value="'.$e2->id.'">'.$e2->nama.'</option>';
        }

        if($type == 'echo') echo $data;
        else return $data;  
    }
    function get_perspektif($type="echo"){
        $ls             = get_data('tbl_perspektif a',[
            'where'     => [
                'a.is_active' => 1,
            ]
        ])->result();
        $data           = '<option value=""></option>';
        foreach($ls as $e2) {
            $data       .= '<option value="'.$e2->id.'">'.$e2->nama.'</option>';
        }

        if($type == 'echo') echo $data;
        else return $data;  
    }
    function get_skala_program($type="echo"){
        $ls             = get_data('tbl_skala_program a',[
            'where'     => [
                'a.is_active' => 1,
            ]
        ])->result();
        $data           = '<option value=""></option>';
        foreach($ls as $e2) {
            $data       .= '<option value="'.$e2->id.'">'.$e2->nama.'</option>';
        }

        if($type == 'echo') echo $data;
        else return $data;
    }

    function save(){
        $kode_cabang = post('kode_cabang');
        $ckode_anggaran = user('kode_anggaran');

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
        $cabang   = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row();
        $tahun    = $anggaran->tahun_anggaran;


        $dt_id      = post('dt_id');
        $dt_key     = post('dt_key');
        $kebijakan_umum = post('kebijakan_umum');
        $program_kerja  = post('program_kerja');
        $perspektif     = post('perspektif');
        $status_program = post('status_program');
        $skala_program  = post('skala_program');
        $tujuan = post('tujuan');
        $output = post('output');

        $arrID = array();
        if($dt_key):
            foreach ($dt_key as $k => $v) {
                $key    = $v;
                $produk = 0;
                $anggaran_select = "0";
                $divisi_terkait  = [];
                $x = post('produk'.$key);
                if(isset($x[0])): $produk = $x[0]; endif;
                $x = post('anggaran'.$key);
                if(isset($x[0])): if($x[0]): $anggaran_select = $x[0]; endif; endif;

                $x = post('divisi_terkait_'.$key);
                if(isset($x[0])): if($x[0]): $divisi_terkait = $x; endif; endif;
                
                $c = [
                    'kode_anggaran' => $ckode_anggaran,
                    'keterangan_anggaran' => $anggaran->keterangan,
                    'tahun'         => $anggaran->tahun_anggaran,
                    'kode_cabang'   => $kode_cabang,
                    'cabang'        => $cabang->nama_cabang,
                    'username'      => user('username'),
                    'id_kebijakan_umum'  => $kebijakan_umum[$k],
                    'id_perspektif'      => $perspektif[$k],
                    'id_skala_program'   => $skala_program[$k],
                    'program_kerja'      => $program_kerja[$k],
                    'produk'             => $produk,
                    'status_program'     => $status_program[$k],
                    'anggaran'           => $anggaran_select,
                    'tujuan'   => $tujuan[$k],
                    'output'   => $output[$k],
                    'divisi_terkait'   => json_encode($divisi_terkait),
                ];
                $cek = get_data('tbl_input_rkf',[
                    'where'         => [
                        'kode_anggaran'   => $ckode_anggaran,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $tahun,
                        'id' => $dt_id[$k],
                    ],
                ])->row();

                if(!isset($cek->id)) {
                    $dt_insert = insert_data('tbl_input_rkf',$c);
                    $ID = $dt_insert;
                    array_push($arrID, $dt_insert);
                }else{
                    $ID = $cek->id;
                    update_data('tbl_input_rkf',$c,['kode_anggaran'   => $ckode_anggaran,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $tahun,
                        'id' => $dt_id[$k]]);
                    array_push($arrID, $dt_id[$k]);
                }

                // insert detail
                $arrID_detail = [];
                for ($i=1; $i <= 12 ; $i++) { 
                    $uraian = '';
                    $p_uraian = post('bulan_'.$key.'_'.$i);
                    if(isset($p_uraian) && $p_uraian[0]){
                        $uraian = $p_uraian[0];
                    }

                    $bobot = '';
                    $p_bobot = post('bobot_'.$key.'_'.$i);
                    if(isset($p_bobot) && $p_bobot[0]){
                        $bobot = $p_bobot[0];
                    }

                    if($uraian || $bobot):
                        $bobot = str_replace('.', '', $bobot);
                        $bobot = str_replace(',', '.', $bobot);
                        $dt_saved_detail = [
                            'id_input_rkf'  => $ID,
                            'bulan'         => $i,
                            'uraian'        => $uraian,
                            'bobot'         => $bobot,
                        ];
                        $ck_detail = get_data('tbl_input_rkf_detail',[
                            'select' => 'id',
                            'where'  => [
                                'id_input_rkf'  => $ID,
                                'bulan'         => $i
                            ]
                        ])->row();
                        if($ck_detail):
                            $dt_saved_detail['id'] = $ck_detail->id;
                        endif;
                        $res = save_data('tbl_input_rkf_detail',$dt_saved_detail);
                        array_push($arrID_detail, $res['id']);
                    endif;
                }
                if(count($arrID_detail)>0):
                    delete_data('tbl_input_rkf_detail',['id_input_rkf'=>$ID,'id not'=>$arrID_detail]);
                else:
                    delete_data('tbl_input_rkf_detail',['id_input_rkf'=>$ID]);
                endif;

            }
        endif;

        if(count($arrID)>0 && post('id')):
            delete_data('tbl_input_rkf',['kode_anggaran'=>$ckode_anggaran,'id not'=>$arrID,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun]);
        elseif(post('id')):
            delete_data('tbl_input_rkf',['kode_anggaran'=>$ckode_anggaran,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun]);
        endif;

        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan'),
        ],'json');
    }

    function save_perubahan() {       
        $data   = json_decode(post('json'),true);
        foreach($data as $id => $record) {          
            update_data('tbl_input_rkf',$record,'id',$id); }
    }

    function data($anggaran="", $cabang="", $tipe = 'table'){
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $dt_cabang = get_data('tbl_m_cabang','kode_cabang',$ckode_cabang)->row();
        $kode_cabang_divisi = $dt_cabang->kode_cabang;
        if($dt_cabang->level4):
            $dt_cabang = get_data('tbl_m_cabang','id',$dt_cabang->parent_id)->row();
            $kode_cabang_divisi = $dt_cabang->kode_cabang;
        endif;

        $a = get_access('plan_rencana_kerja_fungsi');
        $access_edit = false;
        if($a['access_edit'] && $cabang == user('kode_cabang')):
            $access_edit = true;
        elseif($a['access_edit'] && $a['access_additional']):
            $access_edit = true;
        endif;

        $data['akses_ubah'] = $access_edit;
        $data['cabang'] = $cabang;

        $arr = ['select'    => '
                    a.*,
                    b.nama as kebijakan_umum,
                    c.nama as perspektif,
                    d.nama as skala_program,
                    e.level4,
                    e.parent_id,
                ',];
        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }

        $arr['or_like']['a.divisi_terkait'] = $kode_cabang_divisi;
        if($anggaran) {
            $arr['where_array']['a.kode_anggaran']  = $ckode_anggaran;
        }

        $arr['join'][] = 'tbl_kebijakan_umum b on b.id = a.id_kebijakan_umum';
        $arr['join'][] = 'tbl_perspektif c on c.id = a.id_perspektif';
        $arr['join'][] = 'tbl_skala_program d on d.id = a.id_skala_program';
        $arr['join'][] = 'tbl_m_cabang e on e.kode_cabang = a.kode_cabang';
        $list = get_data('tbl_input_rkf a',$arr)->result();
        $data['list']     = $list;
        $data['current_cabang'] = $cabang;

        $arr_bulan = [];
        for ($i=1; $i <= 12 ; $i++) { 
            $arr_bulan[$i] = month_lang($i);
        }

        $response   = array(
            'table' => $this->load->view($this->path.'rencana_kerja_fungsi/table',$data,true),
            'access_edit'       => $access_edit,
            'arr_bulan'         => $arr_bulan,
        );
       
        render($response,'json');
    }

    function get_data(){
        $d = get_data('tbl_input_rkf',[
            'where'         => [
                'id' => post('id'),
            ],
        ])->row();

        $list = get_data('tbl_input_rkf',[
            'where'         => [
                'kode_anggaran'   => $d->kode_anggaran,
                'kode_cabang'     => $d->kode_cabang,
                'tahun'           => $d->tahun,
            ]
        ])->result();

        $detail_rkf = [];
        foreach ($list as $k => $v) {
            $divisi_terkait = $v->divisi_terkait;
            if($divisi_terkait):
                $list[$k]->divisi_terkait = json_decode($divisi_terkait);
            else:
                $list[$k]->divisi_terkait = [];
            endif;
            
            $dt_rkf = get_data('tbl_input_rkf_detail','id_input_rkf',$v->id)->result();
            if(count($dt_rkf)>0):
                $detail_rkf[$v->id] = $dt_rkf;
            endif;
        }

        render([
            'status'    => 'success',
            'data'      => $list,
            'detail'    => $d,
            'detail_rkf'=> $detail_rkf,
        ],'json');
    }

    function detail($id,$cabang){
        $arr = ['select'    => '
            a.*,
            b.nama as kebijakan_umum,
            c.nama as perspektif,
            d.nama as skala_program,
            e.level4,
            e.parent_id,
            e.nama_cabang,
        ',];
        $arr['join'][] = 'tbl_kebijakan_umum b on b.id = a.id_kebijakan_umum';
        $arr['join'][] = 'tbl_perspektif c on c.id = a.id_perspektif';
        $arr['join'][] = 'tbl_skala_program d on d.id = a.id_skala_program';
        $arr['join'][] = 'tbl_m_cabang e on e.kode_cabang = a.kode_cabang';
        $arr['where']['a.id'] = $id;

        $data = get_data('tbl_input_rkf a',$arr)->row_array();

        if(isset($data['id'])) {
            $detail = get_data('tbl_input_rkf_detail',[
                'where' => [
                    'id_input_rkf' => $data['id'],
                ],
                'order_by' => 'bulan',
            ])->result();

            $data['detail'] = $detail;
            render($data,'layout:false view:'.$this->path.'rencana_kerja_fungsi/detail');
        } else echo lang('tidak_ada_data');
    }
}