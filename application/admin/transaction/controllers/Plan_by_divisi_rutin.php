<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_by_divisi_rutin extends BE_Controller {
    var $path       = 'transaction/budget_planner/kantor_pusat/';
    var $sub_menu   = 'transaction/budget_planner/sub_menu';
    var $detail_tahun;
    var $kode_anggaran;
    var $anggaran;
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->anggaran       = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->row();
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran,
                'a.tahun'         => $this->anggaran->tahun_anggaran
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
    }
    
    function index($p1="") { 
        $a = get_access('plan_by_divisi_rutin');
        $data = cabang_divisi();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['access_additional'] = $a['access_additional'];
        $data['access_edit'] = $a['access_edit'];
        $data['detail_tahun']= $this->detail_tahun;
        render($data,'view:'.$this->path.'by_divisi_rutin/index');
    }

    function get_coa($type = 'echo'){
        $ls             = get_data('tbl_m_biaya_rkf a',[
            'select'    => 'a.coa as glwnco, b.glwdes',
            'where'     => "a.is_active = 1",
            'join'      => 'tbl_m_coa b on a.coa = b.glwnco'
        ])->result();
        $data           = '<option value=""></option>';
        foreach($ls as $e2) {
            $data       .= '<option value="'.$e2->glwnco.'">'.$e2->glwnco.' - '.remove_spaces($e2->glwdes).'</option>';
        }

        if($type == 'echo') echo $data;
        else return $data;
    }

    function data($anggaran="", $cabang="", $tipe = 'table') {
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('plan_by_divisi_rutin');
        $access_edit = false;
        if($a['access_edit']):
            $access_edit = true;
        elseif($a['access_edit'] && $a['access_additional']):
            $access_edit = true;
        endif;
        $data['akses_ubah'] = $access_edit;

        $data['current_cabang'] = $cabang;

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
              
        $arr            = [
            'select'    => '
                a.*,
                b.glwnco,
                b.glwdes,
            ',
        ];

        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }
        $arr['join']     = 'tbl_m_coa b on b.glwnco = a.coa';
        $arr['orderby']  = 'a.id';
        $list = get_data('tbl_divisi_rutin a',$arr)->result();
        $data['list'] = $list;

        // header
        $arrHeader = array();
        foreach ($list as $k => $v) {
            $name = str_replace(' ', '_', $v->kegiatan);
            if(isset($data['count_'.$name])):
                $data['count_'.$name] += 1;
            else:
                $data['count_'.$name] = 1;
            endif;

            if(!in_array($v->kegiatan,$arrHeader)):
                array_push($arrHeader,$v->kegiatan);
            endif;
        }
        $data['header']     = $arrHeader;
        $coa                = $this->get_coa('data');    
        $response   = array(
            'table'         => $this->load->view($this->path.'by_divisi_rutin/table',$data,true),
            'access_edit'   => $access_edit,
            'coa'           => $coa,
        );
       
        render($response,'json');
    }

    function save(){
        $data = post();
        $kode_cabang = post('kode_cabang');
        $ckode_anggaran = user('kode_anggaran');

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();

        $tahun  = $anggaran->tahun_anggaran;
        $kegiatan    = post('kegiatan');
        $dt_index    = post('dt_index');

        $cabang      = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();
        $status      = false;
        if($kegiatan):
            foreach ($kegiatan as $i => $h) {
                $status      = true;
                $arrID = array();
                $key = $dt_index[$i];
                $dt_id  = post('dt_id'.$key);
                $coa    = post('coa'.$key);
                $c = [];
                if(post('id')):
                    $dt = get_data('tbl_divisi_rutin','id',post('id'))->row();
                endif;
                foreach($dt_id as $k => $v) {
                    $c = [
                        'kode_anggaran' => $ckode_anggaran,
                        'keterangan_anggaran' => $anggaran->keterangan,
                        'tahun'  => $anggaran->tahun_anggaran,
                        'kode_cabang' => $kode_cabang,
                        'cabang' => $cabang->nama_cabang,
                        'username' => user('username'),
                        'coa' => $coa[$k],
                        'kegiatan' => $kegiatan[$i]

                    ];

                    $cek        = get_data('tbl_divisi_rutin',[
                        'where'         => [
                            'kode_anggaran'   => $ckode_anggaran,
                            'kode_cabang'     => $kode_cabang,
                            'tahun'           => $anggaran->tahun_anggaran,
                            'id'              => $dt_id[$k]
                            ],
                    ])->row();

                    
                    if(!isset($cek->id)) {
                        $id = insert_data('tbl_divisi_rutin',$c);
                    }else{
                        $id = $dt_id[$k];
                        update_data('tbl_divisi_rutin',$c,[
                            'kode_anggaran'   => $ckode_anggaran,
                            'keterangan_anggaran' => $anggaran->keterangan,
                            'kode_cabang'     => $kode_cabang,
                            'tahun'           => $anggaran->tahun_anggaran,
                            'id'              => $dt_id[$k]
                        ]);
                    }

                    array_push($arrID, $id);
                }

                if(count($arrID)>0 && post('id')):
                    delete_data('tbl_divisi_rutin',['kode_anggaran'=>$ckode_anggaran,'id not'=>$arrID,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun, 'kegiatan' => $dt->kegiatan]);
                endif;
            }
        endif;

        if(!$status && post('id')):
            $dt = get_data('tbl_divisi_rutin','id',post('id'))->row();
            delete_data('tbl_divisi_rutin',['kode_anggaran'=>$ckode_anggaran,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun, 'kegiatan' => $dt->kegiatan]);
        endif;

        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan')
        ],'json');
    }

    function save_perubahan() {       
        $data   = json_decode(post('json'),true);
        foreach($data as $id => $record) {
            $dt = insert_view_report_arr($record);
            update_data('tbl_divisi_rutin',$dt,'id',$id); 
        }
    }

    function get_data() {
        $dt = get_data('tbl_divisi_rutin','id',post('id'))->row();
        $list = get_data('tbl_divisi_rutin',[
            'where' => [
                'kode_anggaran' => $dt->kode_anggaran,    
                'tahun' => $dt->tahun,
                'kode_cabang' => $dt->kode_cabang,
                'kegiatan'  => $dt->kegiatan
            ],
        ])->result_array();
        $data['detail'] = $dt;
        $data['data'] = $list;
        render($data,'json');

    }
}