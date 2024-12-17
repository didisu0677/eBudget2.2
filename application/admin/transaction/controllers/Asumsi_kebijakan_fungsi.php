<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asumsi_kebijakan_fungsi extends BE_Controller {
    var $path       = 'transaction/budget_planner/kantor_pusat/';
    var $sub_menu   = 'transaction/budget_planner/sub_menu';
    function __construct() {
        parent::__construct();
    }
    
    function index($p1="") {
        $a = get_access('asumsi_kebijakan_fungsi');
        $data = cabang_divisi();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['access_additional'] = $a['access_additional'];
        render($data,'view:'.$this->path.'asumsi_kebijakan_fungsi/index');
    }

    function data($anggaran="", $cabang="", $tipe = 'table'){
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('asumsi_kebijakan_fungsi');
        $access_edit = false;
        if($a['access_edit'] && $cabang == user('kode_cabang')):
            $access_edit = true;
        elseif($a['access_edit'] && $a['access_additional']):
            $access_edit = true;
        endif;
        $data['akses_ubah'] = $access_edit;

        $arr = ['select'    => '
                    a.*,
                    b.nama as kebijakan_fungsi,
                ',];
        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }

        $arr['join'][] = 'tbl_kebijakan_fungsi b on b.id = a.id_kebijakan_fungsi';
        $list = get_data('tbl_kebijakan_asumsi a',$arr)->result();
        $data['list']     = $list;
        $data['kebijakan_fungsi'] = get_data('tbl_kebijakan_fungsi')->result();
        $data['current_cabang'] = $cabang;
 
        $response   = array(
            'table' => $this->load->view($this->path.'asumsi_kebijakan_fungsi/table',$data,true),
            'access_edit' => $access_edit,
        );
       
        render($response,'json');
    }

    function get_kebijakan_fungsi($type = 'echo'){
        $ls             = get_data('tbl_kebijakan_fungsi a',[
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
        $kebijakan_fungsi = post('kebijakan_fungsi');
        $uraian         = post('uraian');
        $anggaranx      = post('anggaran');
        $kantor_cabang  = post('kantor_cabang');
        $pelaksanaan    = post('pelaksanaan');

        $arrID = array();
        if($kebijakan_fungsi):
            foreach ($kebijakan_fungsi as $k => $v) {
                $value = str_replace('.', '', $anggaranx[$k]);
                $value = str_replace(',', '.', $value);
                $c = [
                    'kode_anggaran' => $ckode_anggaran,
                    'keterangan_anggaran' => $anggaran->keterangan,
                    'tahun'         => $anggaran->tahun_anggaran,
                    'kode_cabang'   => $kode_cabang,
                    'cabang'        => $cabang->nama_cabang,
                    'username'      => user('username'),
                    'id_kebijakan_fungsi'  => $kebijakan_fungsi[$k],
                    'uraian'        => $uraian[$k],
                    'anggaran'      => insert_view_report($value),
                    'kantor_cabang' => $kantor_cabang[$k],
                    'pelaksanaan'   => $pelaksanaan[$k],
                ];
                $cek = get_data('tbl_kebijakan_asumsi',[
                    'where'         => [
                        'kode_anggaran'   => $ckode_anggaran,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $tahun,
                        'id' => $dt_id[$k],
                    ],
                ])->row();

                if(!isset($cek->id)) {
                    $dt_insert = insert_data('tbl_kebijakan_asumsi',$c);
                    array_push($arrID, $dt_insert);
                }else{
                    update_data('tbl_kebijakan_asumsi',$c,['kode_anggaran'   => $ckode_anggaran,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $tahun,
                        'id' => $dt_id[$k]]);
                    array_push($arrID, $dt_id[$k]);
                }
            }
        endif;

        if(count($arrID)>0 && post('id') || post('id')):
            $d = get_data('tbl_kebijakan_asumsi',[
                'where'         => [
                    'id' => post('id'),
                ],
            ])->row();

            if(count($arrID)>0):
                delete_data('tbl_kebijakan_asumsi',['kode_anggaran'=>$ckode_anggaran,'id not'=>$arrID,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun, 'id_kebijakan_fungsi' => $d->id_kebijakan_fungsi ]);
            else:
                delete_data('tbl_kebijakan_asumsi',['kode_anggaran'=>$ckode_anggaran,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun, 'id_kebijakan_fungsi' => $d->id_kebijakan_fungsi ]);
            endif;
        endif;

        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan'),
        ],'json');
    }

    function get_data(){
        $d = get_data('tbl_kebijakan_asumsi',[
            'where'         => [
                'id' => post('id'),
            ],
        ])->row();

        $list = get_data('tbl_kebijakan_asumsi',[
            'where'         => [
                'kode_anggaran'   => $d->kode_anggaran,
                'kode_cabang'     => $d->kode_cabang,
                'tahun'           => $d->tahun,
                'id_kebijakan_fungsi' => $d->id_kebijakan_fungsi,
            ]
        ])->result();

        render([
            'status'    => 'success',
            'data'      => $list,
            'detail'    => $d,
        ],'json');
    }
}