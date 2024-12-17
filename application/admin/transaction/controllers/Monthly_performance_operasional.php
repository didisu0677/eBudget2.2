<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monthly_performance_operasional extends BE_Controller {
    var $path = 'transaction/monthly_performance_operasional/';
    var $controller = 'monthly_performance_operasional';
    var $cabang_gab = [];
    var $detail_tahun;
    var $anggaran;
    var $kode_anggaran;
    var $data_cab = [];
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->anggaran       = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->result();
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran,
                'a.tahun'         => $this->anggaran[0]->tahun_anggaran
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
    }

    function index() {
        $kode_anggaran          = $this->kode_anggaran;
        $data['tahun']          = $this->anggaran;
        $data['controller']     = $this->controller;
        $data['cabang']         = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang,level_cabang',
            'where'     => "a.is_active = 1 and status_group = 1 and (a.nama_cabang not like '%divisi%' or a.kode_cabang = '00100')"
        ])->result_array();
        $data['nilai']      = get_data('tbl_m_monly_performance_nilai','is_active',1)->result_array();
        render($data);
    }

    function data($kode_anggaran,$kode_cabang,$bulan){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();

         $coa = get_data('tbl_item_plan_ba a',[
            'select' => 'a.coa,a.grup,b.glwdes,b.kali_minus',
            'join'   => 'tbl_m_coa b on a.coa = b.glwnco',
            'order_by' => 'a.id',
        ])->result();

        $arr_group  = [];
        $arr_coa    = [];
        foreach ($coa as $k => $v) {
            $arr_group[$v->grup][] = $v;
            if(!in_array($v->coa,$arr_coa)) array_push($arr_coa,$v->coa);
        }

        if($kode_cabang == 'konsolidasi'):
            $cabang   = [
                'kode_cabang'   => 'konsolidasi',
                'nama_cabang'   => 'KONSOLIDASI',
                'struktur_cabang' => 'Kantor Pusat',
            ];
            $cabang = json_encode($cabang); 
            $cabang = json_decode($cabang);
            $x = get_data('tbl_m_cabang',[
                'select'    => 'kode_cabang,nama_cabang,struktur_cabang',
                'where'     => "parent_id = 0 and is_active = 1",
                'order_by'  => "urutan"
            ])->result_array();
            $cab = [
                array('kode_cabang' => 'KONS', 'nama_cabang' => 'KONSOLIDASI', 'struktur_cabang' => 'Kantor Pusat'),
                array('kode_cabang' => 'KONV', 'nama_cabang' => 'KONVENSIONAL', 'struktur_cabang' => 'Kantor Pusat'),
            ];
            foreach ($x as $k => $v) {
                $cab[] = $v;
            }
            foreach ($cab as $k => $v) {
                $field  = 'B_' . sprintf("%02d", $bulan);
                $x2 = get_data('tbl_budget_nett',[
                    'select' => "kode_cabang,'".$v['nama_cabang']."' as nama_cabang,coa,".$field,
                    'where'  => [
                        'coa'           => $arr_coa,
                        'kode_cabang'   => $v['kode_cabang'],
                    ]
                ])->result_array();
                $this->data_cab['cabang'][$v['kode_cabang']]['nama_cabang'] = $v['nama_cabang'];
                $this->data_cab['cabang'][$v['kode_cabang']]['kode_cabang'] = $v['kode_cabang'];
                $this->data_cab['cabang'][$v['kode_cabang']]['struktur_cabang'] = $v['struktur_cabang'];
                $this->data_cab['cabang'][$v['kode_cabang']]['data'][] = $x2;
            }
        else:
            $this->more_cabang(0,$kode_cabang,$arr_coa,$anggaran,$bulan,0);
            $cabang = $this->data_cab['cabang'][$kode_cabang];
            $cabang = json_encode($cabang); 
            $cabang = json_decode($cabang);
        endif;

        $tbl_history = 'tbl_history_'.($anggaran->tahun_anggaran-1);
        $history     = [];
        if($this->db->table_exists($tbl_history)):
            $history = get_data($tbl_history.' a',[
                'join'  => 'tbl_m_coa b on b.glwnco = a.glwnco',
                'where' => [
                    'a.bulan'     => $bulan,
                    'a.glwnco'    => $arr_coa,
                ]
            ])->result_array();
        endif;
        $tbl_history_current = 'tbl_history_'.($anggaran->tahun_anggaran);
        $history_current     = [];
        if($this->db->table_exists($tbl_history_current)):
            $history_current = get_data($tbl_history_current.' a',[
                'join'  => 'tbl_m_coa b on b.glwnco = a.glwnco',
                'where' => [
                    'a.bulan'     => $bulan,
                    'a.glwnco'    => $arr_coa,
                ]
            ])->result_array();
        endif;

        $data['cab']         = $this->data_cab;
        $data['group']       = $arr_group;
        $data['bulan']       = $bulan;
        $data['anggaran']    = $anggaran;
        $data['cabang']      = $cabang;
        $data['history_current'] = $history_current;
        $data['history']    = $history;
        $data['nilai']      = get_data('tbl_m_monly_performance_nilai','is_active',1)->result_array();
        $view     = $this->load->view($this->path.'/content',$data,true);
        render([
            'view'      => $view,
            // 'x'      => $data['cab'],
        ],'json');
    }

    private function more_cabang($parent_id,$kode_cabang,$arr_coa,$anggaran,$bulan,$key){
        $field  = 'b.B_' . sprintf("%02d", $bulan);
        if($key == 0):
            $x = get_data('tbl_m_cabang a',[
                'select'    => 'a.parent_id,a.id,a.kode_cabang,a.nama_cabang,a.struktur_cabang,b.coa,'.$field,
                'join'      => [
                    "tbl_budget_nett b on b.kode_cabang = a.kode_cabang and b.kode_anggaran = '$anggaran->kode_anggaran' and b.coa in (".implode(",", $arr_coa).") type left"
                ],
                'where'     => [
                    'a.kode_cabang'   => $kode_cabang,
                    'a.is_active'   => 1,
                ],
                'order_by'  => "a.urutan"
            ])->result_array();
        else:
            $cabang = get_data('tbl_m_cabang',[
                'select' => 'id,kode_cabang,nama_cabang,struktur_cabang',
                'where'  => [
                    'is_active' => 1,
                    'parent_id' => $parent_id,
                ],
                'order_by'  => "urutan"
            ])->result_array();
        endif;

        if($key == 0):
            foreach ($x as $k => $v) {
                $kode_cabang2 = $v['kode_cabang'];
                $this->data_cab['cabang'][$kode_cabang]['nama_cabang'] = $v['nama_cabang'];
                $this->data_cab['cabang'][$kode_cabang]['kode_cabang'] = $v['kode_cabang'];
                $this->data_cab['cabang'][$kode_cabang]['struktur_cabang'] = $v['struktur_cabang'];
                $this->data_cab['cabang'][$kode_cabang]['data'] = $x;
                $x2 = $this->more_cabang($v['id'],$v['kode_cabang'],$arr_coa,$anggaran,$bulan,1);
                break;
            }
        else:
            foreach ($cabang as $k => $v) {
                $kode_cabang2 = $v['kode_cabang'];
                $x = get_data('tbl_m_cabang a',[
                    'select'    => 'a.parent_id,a.id,a.kode_cabang,a.nama_cabang,b.coa,'.$field,
                    'join'      => [
                        "tbl_budget_nett b on b.kode_cabang = a.kode_cabang and b.kode_anggaran = '$anggaran->kode_anggaran' and b.coa in (".implode(",", $arr_coa).") type left"
                    ],
                    'where'     => [
                        'a.kode_cabang'   => $kode_cabang2,
                        'a.is_active'   => 1,
                    ],
                    'order_by'  => "a.urutan"
                ])->result_array();
                
                $this->data_cab[$kode_cabang][$kode_cabang2]['nama_cabang'] = $v['nama_cabang'];
                $this->data_cab[$kode_cabang][$kode_cabang2]['kode_cabang'] = $v['kode_cabang'];
                $this->data_cab[$kode_cabang][$kode_cabang2]['struktur_cabang'] = $v['struktur_cabang'];
                $this->data_cab[$kode_cabang][$kode_cabang2]['data'] = $x;
                $x2 = $this->more_cabang($v['id'],$v['kode_cabang'],$arr_coa,$anggaran,$bulan,1);
            }
        endif;
        

    }

}