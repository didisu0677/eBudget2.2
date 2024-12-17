<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_usulan_aset extends BE_Controller {
    var $kode_anggaran;
    var $kode_inventaris;
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->kode_inventaris = get_data('tbl_rencana_aset',[
            'select' => 'distinct kode_inventaris',
            'where'  => "kode_anggaran = '$this->kode_anggaran' and kode_inventaris != ''",
            'order_by' => 'kode_inventaris'
        ])->result();
    }
    
    function index() {
        $tahun_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->result(); 
        $data['tahun']  = $tahun_anggaran;
        $data['kode_inventaris']  = $this->kode_inventaris;
        render($data);
    }

    function data($anggaran="", $kode_inventaris=""){
        $kode_inventaris = str_replace('-', ' ', $kode_inventaris);
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

        $dSum = get_data('tbl_rencana_aset',[
            'select' => 'kode_cabang,nama_inventaris,harga,jumlah,bulan',
            'where' => [
                'kode_anggaran' => $anggaran,
                'kode_inventaris'   => $kode_inventaris,
                'kode_cabang' => $arrCodeCabang
            ],
        ])->result_array();
        $dKey = get_data('tbl_rencana_aset',[
            'select' => 'DISTINCT kode_cabang,nama_inventaris',
            'where' => [
                'kode_anggaran' => $anggaran,
                'kode_inventaris'   => $kode_inventaris,
                'kode_cabang' => $arrCodeCabang
            ]
        ])->result_array();
        $data['dSum'] = $dSum;
        $data['dKey'] = $dKey;

        $response   = array(
            'table'     => $this->load->view('transaction/rekap_usulan_aset/table',$data,true),
            'kode_inventaris' => $kode_inventaris,
            'dSum' => $dSum,
            'dKey' => $dKey,

        );
        render($response,'json');
    }
}