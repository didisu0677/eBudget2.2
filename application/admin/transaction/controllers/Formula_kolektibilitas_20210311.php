<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formula_kolektibilitas extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $detail_tahun;
    var $kode_anggaran;
    var $arr_sumber_data = array();
    var $add_on1 = ["1552011","1552015","1552016",'sum_kol',"1552012"];
    var $add_on2 = ["5586012","5586013","5586011","4571001"];
    var $arr_real= [];
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

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->row();
        $bulan  = sprintf('%02d', $anggaran->bulan_terakhir_realisasi);
        $this->arr_real[] = checkMonthAnggaran($anggaran);
        $this->arr_real[] = $bulan."-".$anggaran->tahun_terakhir_realisasi;
    }

    private  function check_sumber_data($sumber_data){
        $key = array_search($sumber_data, array_map(function($element){return $element->sumber_data;}, $this->detail_tahun));
        if(strlen($key)>0):
            array_push($this->arr_sumber_data,$sumber_data);
        endif;
    }
    
    function index($p1="") { 
        $a = get_access('kolektibilitas');
        $data = data_cabang('kolektibilitas');
        $data['path'] = $this->path;
        $data['detail_tahun'] = $this->detail_tahun;
        $data['arr_real'] = $this->arr_real;
        $data['akses_ubah'] = $a['access_edit'];
        render($data,'view:'.$this->path.'formula_kolektibilitas/index');
    }

    function data($anggaran="", $cabang="") {
    	$menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('kolektibilitas');
        $data['akses_ubah'] = $a['access_edit'];
        $data['current_cabang'] = $cabang;
        $data['title']  = ['NPL Total Kredit','NPL Kredit Produktif','NPL Kredit Konsumtif'];
        $data['title_'] = ['A. ','B. ','C. '];

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();

        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }
        $select = 'a.id,a.kode_anggaran,a.kode_cabang,a.tahun,
            a.cabang,a.coa_produk_kredit,a.tipe,a.default,
            b.coa,b.nama_produk_kredit,';
        $arrWhere = $arr;
        $arrWhere['select'] = $select.'kol_1,kol_2,kol_3,kol_4,kol_5';
        $arrWhere['where']['default'] = [1,0];
        $arrWhere['join'][]  = 'tbl_produk_kredit b on b.coa = a.coa_produk_kredit';
        $arrWhere['join'][]  = 'tbl_m_tarif_kolektibilitas c on c.coa = a.coa_produk_kredit and c.kode_anggaran = a.kode_anggaran type left';
        $arrWhere['order_by']= 'a.id';
        $arrWhere['where']['a.tipe'] = 1;
        $list = get_data('tbl_kolektibilitas a',$arrWhere)->result();
        $arrWhere['where']['a.tipe'] = 2;
        $listKonsumtif = get_data('tbl_kolektibilitas a',$arrWhere)->result();

        $arrWhere = $arr;
        $select = 'b.id_kolektibilitas,b.sumber_data,b.tahun_core,';
        for ($i=1; $i <=12 ; $i++) { 
            $blnTxt = 'B_' . sprintf("%02d", $i);
            $select .= $blnTxt.','; 
            $select .= $blnTxt.'_1,'; 
            $select .= $blnTxt.'_2,'; 
            $select .= $blnTxt.'_3,'; 
            $select .= $blnTxt.'_4,'; 
            $select .= $blnTxt.'_5,'; 
        }
        $arrWhere['select'] = $select;
        $arrWhere['where']['default'] = [1,0];
        $arrWhere['join'][] = 'tbl_kolektibilitas_detail b on b.id_kolektibilitas = a.id';
        $arrWhere['where']['a.tipe'] = 1;
        $listDetail = get_data('tbl_kolektibilitas a',$arrWhere)->result_array();
        $arrWhere['where']['a.tipe'] = 2;
        $listDetailKonsumtif = get_data('tbl_kolektibilitas a',$arrWhere)->result_array();


        $h['for_kolek'] = [];
        $this->session->set_userdata($h);

        $data['list']       = $list;
        $data['for_kolek']  = $this->session->for_kolek;
        $data['detail']     = $listDetail;
        $data['detail_tahun'] = $this->detail_tahun;
        $view  = $this->load->view($this->path.'formula_kolektibilitas/table',$data,true);

        $data['list'] = $listKonsumtif;
        $data['for_kolek']  = $this->session->for_kolek;
        $data['detail'] = $listDetailKonsumtif;
        $view2  = $this->load->view($this->path.'formula_kolektibilitas/table',$data,true);

        $view3 = $this->load->view($this->path.'formula_kolektibilitas/total',$data,true);

       	$response   = array(
       		'table'     => $view,
            'table2'     => $view2,
            'table3'     => $view3,
        );

        render($response,'json');
    }

    private function arrAdditional($coa,$arrWhere,$anggaran){
        $x = explode('-', $this->arr_real[0]);
        $bln1 = $x[0];
        $bln1x = (int) $bln1;
        $x2 = explode('-', $this->arr_real[1]);
        $bln2 = $x2[0];
        $bln2x = (int) $bln2;

        $cabang         = $arrWhere['where']['a.kode_cabang'];
        $tahun_core     = $anggaran->tahun_terakhir_realisasi;
        $tbl_history    = 'tbl_history_'.$anggaran->tahun_terakhir_realisasi;
        $column         = 'TOT_'.$cabang;

        if(isset($coa['sum_kol'])){ unset($coa['sum_kol']); }
        
        $check_tbl = $this->db->table_exists($tbl_history);
        if(!$check_tbl):
            render(['status'=>false,'message' => lang('data_not_found')], 'json');
            exit();
        endif;
        if (!$this->db->field_exists($column, $tbl_history)):
            render(['status'=>false,'message' => lang('data_not_found')], 'json');
            exit();
        endif;

        $sub_1  = "(select sum(".$column.") from ".$tbl_history." where glwnco = a.glwnco and bulan = '".$bln1x."') as core_".$bln1.",";
        $sub_2  = "(select sum(".$column.") from ".$tbl_history." where glwnco = a.glwnco and bulan = '".$bln2x."') as core_".$bln2.",";
        $select_formula = "b.id as fID,b.tahun_core as ftahun_core,b.sumber_data as fsumber_data,b.B_01 as fB_01,b.B_02 as fB_02,b.B_03 as fB_03,b.B_04 as fB_04,b.B_05 as fB_05,b.B_06 as fB_06,b.B_07 as fB_07,b.B_08 as fB_08,b.B_09 as fB_09,b.B_10 as fB_10,b.B_11 as fB_11,b.B_12 as fB_12,b.changed";
        $coa = get_data('tbl_m_coa a',[
            'select'    => 'a.glwnco as coa,a.glwdes as name,'.$sub_1.$sub_2.$select_formula,
            'where'     => ['a.glwnco' => $coa],
            'join'      => [
                "tbl_formula_kolektibilitas b on b.coa = a.glwnco and b.kode_cabang = '".$cabang."' and b.kode_anggaran = '".$anggaran->kode_anggaran."' type left"
            ]
        ])->result_array();
        return $coa;
    }

    function data2($anggaran="", $cabang=""){
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;
        $a = get_access('kolektibilitas');
        $data['akses_ubah'] = $a['access_edit'];
        $data['current_cabang'] = $cabang;

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();

        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }

        $h['arr_sum_kol'] = [];
        $h['arr_individu'] = [];
        $this->session->set_userdata($h);

        $coa1 = $this->arrAdditional($this->add_on1,$arr,$anggaran);
        $data['add_on']         = $coa1;
        $data['arr_coa']        = $this->add_on1;
        $data['arr_real']       = $this->arr_real;
        $data['detail_tahun']   = $this->detail_tahun;
        $data['all_core']       = ['1552012'];
        $data['kol_1']          = ['1552011'];
        $data['kol_2']          = ['1552015'];
        $data['kol_3']          = ['1552016'];
        $data['arr_sum_kol']    = $this->session->arr_sum_kol;
        $data['arr_individu']   = $this->session->arr_individu;
        $data['to_month']       = [];
        $data['to_individu']    = [];
        $data['arr_sum_sd_bln'] = [];
        $data['cabang']         = $cabang;
        $data['anggaran']       = $anggaran;
        $data['for_kolek']      = $this->session->for_kolek;
        $data['dt_total']       = $this->get_data_formula_total($arr,$anggaran);
        $view = $this->load->view($this->path.'formula_kolektibilitas/coa',$data,true);
        // $this->load->view($this->path.'formula_kolektibilitas/coa',$data);

        $coa1 = $this->arrAdditional($this->add_on2,$arr,$anggaran);
        $data['add_on'] = $coa1;
        $data['arr_coa'] = $this->add_on2;
        $data['all_core']       = [];
        $data['to_month']       = ['5586012'];
        $data['to_individu']    = ['5586013'];
        $data['arr_sum_sd_bln'] = ['5586011'];
        $data['arr_sum_kol']    = $this->session->arr_sum_kol;
        $data['arr_individu']   = $this->session->arr_individu;
        $view .= $this->load->view($this->path.'formula_kolektibilitas/coa',$data,true);
        // $this->load->view($this->path.'formula_kolektibilitas/coa',$data);

        $response   = array(
            'status'    => true,
            'table'     => $view,
        );

        render($response,'json');
    }

    private function get_data_formula_total($arrWhere,$anggaran){
        $cabang         = $arrWhere['where']['a.kode_cabang'];
        $select_formula = "a.id as fID,a.tahun_core as ftahun_core,a.sumber_data as fsumber_data,a.B_01 as fB_01,a.B_02 as fB_02,a.B_03 as fB_03,a.B_04 as fB_04,a.B_05 as fB_05,a.B_06 as fB_06,a.B_07 as fB_07,a.B_08 as fB_08,a.B_09 as fB_09,a.B_10 as fB_10,a.B_11 as fB_11,a.B_12 as fB_12,a.changed";
        $arrWhere['where']['coa like '] = '%_total%';
        $coa = get_data('tbl_formula_kolektibilitas a',[
            'select'    => 'a.coa,'.$select_formula,
            'where'     => $arrWhere['where'],
        ])->result_array();
        return $coa;
    }

    function save_perubahan(){
        $kode_anggaran = post('kode_anggaran');
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
        $data   = json_decode(post('json'),true);
        $table = 'tbl_formula_kolektibilitas';
        foreach($data as $k => $record) {
            $x = explode('-', $k);
            $id= $x[0];
            if(strlen(strpos($id,'ID'))>0):
                $id     = $x[0];
                $coa    = $x[1];
                $thn    = $x[2];
                $sumber_data = $x[3];
                $cabang = $x[4];
            else:
                $coa    = $x[0];
                $thn    = $x[1];
                $sumber_data = $x[2];
                $cabang = $x[3];
            endif;

            $ck = get_data($table,[
                'select'    => 'id,changed',
                'where'     => "coa = '$coa' and kode_cabang = '$cabang' and sumber_data = '$sumber_data' and kode_anggaran = '$kode_anggaran' and tahun_core = '$thn'",
            ])->row();
            if($ck):
                $changed = json_decode($ck->changed,true);
                foreach ($record as $k2 => $v2) {
                    $value = filter_money($v2);
                    $changed[$k2] = 1;
                    $record[$k2] = insert_view_report($value);
                }
                $record['changed'] = json_encode($changed);
                $where = [
                    'coa' => $coa,
                    'tahun_core' => $thn,
                    'sumber_data' => $sumber_data,
                    'kode_cabang' => $cabang,
                    'kode_anggaran' => $kode_anggaran,
                ];
                update_data($table,$record,$where);
            else:
                $changed = [];
                foreach ($record as $k2 => $v2) {
                    $value = filter_money($v2);
                    $changed[$k2] = 1;
                    $record[$k2] = insert_view_report($value);
                }
                $record['changed'] = json_encode($changed);

                $h = $record;
                $h['coa']                   = $coa;
                $h['kode_anggaran']         = $anggaran->kode_anggaran;
                $h['tahun_anggaran']        = $anggaran->tahun_anggaran;
                $h['keterangan_anggaran']   = $anggaran->keterangan;
                $h['kode_cabang']           = $cabang;
                $h['tahun_core']            = $thn;
                $h['sumber_data']           = $sumber_data;
                insert_data($table,$h);
            endif;
        }
    }
}