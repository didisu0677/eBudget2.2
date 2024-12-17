<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_usulan_besaran extends BE_Controller {
    var $detail_tahun;
    var $kode_anggaran;
    var $arr_tahun_core = [];
    var $arr_sumber_data = [];
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran,
            ],
            'order_by' => 'tahun,bulan'
        ])->result();

        foreach ($this->detail_tahun as $k => $v) {
            if(!in_array($v->tahun,$this->arr_tahun_core)):
                array_push($this->arr_tahun_core, $v->tahun);
            endif;

            if(!in_array($v->sumber_data,$this->arr_sumber_data)):
                array_push($this->arr_sumber_data, $v->sumber_data);
            endif;
        }
    }
    
    function index() {
        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->result(); 
        $id_coa         = json_decode($tahun_anggaran[0]->id_coa_besaran);
        $coa            = get_data('tbl_m_coa','id', $id_coa)->result();
        $data['tahun']  = $tahun_anggaran;
        $data['coa']    = $coa;
        $data['detail_tahun']    = $this->detail_tahun;
        render($data);
    }

    function data($anggaran="", $coa=""){
        $data['cabang'][0] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>0, 'is_active' => 1),'order_by' => 'nama_cabang'))->result();
        $arrCodeCabang = array();
        foreach($data['cabang'][0] as $m0) {
            $data['cabang'][$m0->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m0->id, 'is_active' => 1),'order_by' => 'kode_cabang'))->result();
            foreach($data['cabang'][$m0->id] as $m1) {
                $data['cabang'][$m1->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m1->id, 'is_active' => 1),'order_by' => 'kode_cabang'))->result();
                foreach($data['cabang'][$m1->id] as $m2) {
                    $dataLevel4 = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m2->id, 'is_active' => 1),'order_by' => 'kode_cabang'))->result();
                    $data['cabang'][$m2->id] = $dataLevel4;

                    foreach ($dataLevel4 as $v) {
                        if(!in_array($v->kode_cabang,$arrCodeCabang)):
                            array_push($arrCodeCabang, $v->kode_cabang);
                        endif;
                    }
                }
            }
        }

        $dSum = get_data('tbl_bottom_up_form1',[
            'select' => 
                'sum(B_01) as B_01,sum(B_02) as B_02,sum(B_03) as B_03,sum(B_04) as B_04,sum(B_05) as B_05,sum(B_06) as B_06,sum(B_07) as B_07,sum(B_08) as B_08,sum(B_09) as B_09,sum(B_10) as B_10,sum(B_11) as B_11,sum(B_12) as B_12, kode_cabang,sumber_data,data_core',
            'where' => [
                'sumber_data'   => $this->arr_sumber_data,
                'kode_anggaran' => $anggaran,
                'coa'   => $coa,
                'kode_cabang' => $arrCodeCabang
            ],
            'group_by' => 'kode_cabang,sumber_data,data_core'
        ])->result_array();
        $data['dSum'] = $dSum;
        $data['detail_tahun'] = $this->detail_tahun;

        $response   = array(
            'table'     => $this->load->view('transaction/rekap_usulan_besaran/table',$data,true),
        );
        render($response,'json');
    }
}