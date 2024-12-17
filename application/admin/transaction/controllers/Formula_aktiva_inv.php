<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formula_aktiva_inv extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'formula_aktiva_inv';
    var $detail_tahun;
    var $kode_anggaran;
    var $arr_tahun_core = [];
    var $arr_coa_core = ['1621013','1622013','5621011','1621014','1622014','5621014','1621015','1622015','5621015','1621016','1622016','5621012'];
    var $arr_coa_core_sewa = ['1621017','1622017','2991245','5621013','5791010','1621018','1622018','2991246','5621016','1621019','1622019','2991245','5621017'];
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
        $this->checkSumberData();
    }

    private function checkSumberData(){
        foreach ($this->detail_tahun as $k => $v) {
            if(!in_array($v->tahun, $this->arr_tahun_core)):
                array_push($this->arr_tahun_core, $v->tahun);
            endif;
        }
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('biaya')
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
        $data['path'] = $this->path;
        $data['detail_tahun'] = $this->detail_tahun;
        return $data;
    }
    
    function index($p1="") { 
        $access         = get_access($this->controller);
        $data = $this->data_cabang();
        $data['access_additional']  = $access['access_additional'];
        render($data,'view:'.$this->path.'formula_aktiva_inv/index');
    }

    function data($anggaran="", $cabang=""){

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();

        $bln_trakhir = $anggaran->bulan_terakhir_realisasi;
        $getMinBulan = $anggaran->bulan_terakhir_realisasi - 1;
        $thn_trakhir = $anggaran->tahun_terakhir_realisasi;
        $tbl_history = 'tbl_history_'.$thn_trakhir;

        $dataDefault = [];
        $dataDefault['anggaran']     = $anggaran;
        $dataDefault['thn_trakhir']  = $thn_trakhir;
        $dataDefault['detail_tahun'] = $this->detail_tahun;
        $dataDefault['bln_trakhir']  = $bln_trakhir;
        $dataDefault['cabang']       = $cabang;
        $dataDefault['data_core']    = get_data_core($this->arr_coa_core,$this->arr_tahun_core,'TOT_'.$cabang);

        $dataA = $dataDefault;
        $dataB = $dataDefault;
        $dataC = $dataDefault;
        $dataD = $dataDefault;
        
        $select     = 'a.glwsbi,a.glwnco,a.glwdes';

        $select2    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";
        
        // data ke 1
        $dataA['arr_tahun_core'] = $this->arr_tahun_core;
        $dataA['A'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621013%' or a.glwnco like '1622013%' or a.glwnco like '5621011%' group by a.glwdes",

        ])->result();
        $dataA['A_saved'] = get_data('tbl_formula_akt a',[
            'where' => "a.kode_cabang = '$cabang' and a.kode_anggaran = '$anggaran->kode_anggaran' and (a.glwnco like '1621013%' or a.glwnco like '1622013%' or a.glwnco like '5621011%')"
        ])->result_array();

        $valA = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5621011%' group by a.glwdes",

        ])->result_array();
        $valA[0]['hasil'] *= -1;
        $valA[0]['hasil2'] *= -1;
        $dataA['valA'] = $valA;

        $dataA['E2'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan,tahun',
            'where'     => " grup = 'E.2' and kode_cabang = '".$cabang."' and kode_anggaran = '$anggaran->kode_anggaran'",
            'group_by'  => 'bulan'
        ])->result();

        // data ke 2
        $dataB['arr_tahun_core'] = $this->arr_tahun_core;
        $dataB['B'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621014%' or a.glwnco like '1622014%' or a.glwnco like '5621014%' group by a.glwdes",

        ])->result();

        $dataB['A_saved'] = get_data('tbl_formula_akt a',[
            'where' => "a.kode_cabang = '$cabang' and a.kode_anggaran = '$anggaran->kode_anggaran' and (a.glwnco like '1621014%' or a.glwnco like '1622014%' or a.glwnco like '5621014%')"
        ])->result_array();

        $valB = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5621014%' group by a.glwdes",

        ])->result_array();
        $valB[0]['hasil'] *= -1;
        $valB[0]['hasil2'] *= -1;
        $dataB['valB'] = $valB;

        $dataB['E4'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan,tahun',
            'where'     => " grup = 'E.4' and kode_cabang = '".$cabang."' and kode_anggaran = '$anggaran->kode_anggaran'",
            'group_by'  => 'bulan'
        ])->result();


        // data ke 3
        $dataC['arr_tahun_core'] = $this->arr_tahun_core;
        $dataC['C'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621015%' or a.glwnco like '1622015%' or a.glwnco like '5621015%' group by a.glwdes",

        ])->result();
        $dataC['A_saved'] = get_data('tbl_formula_akt a',[
            'where' => "a.kode_cabang = '$cabang' and a.kode_anggaran = '$anggaran->kode_anggaran' and (a.glwnco like '1621015%' or a.glwnco like '1622015%' or a.glwnco like '5621015%')"
        ])->result_array();

        $valC = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5621015%' group by a.glwdes",

        ])->result_array();
        $valC[0]['hasil'] *= -1;
        $valC[0]['hasil2'] *= -1;
        $dataC['valC'] = $valC;

        $dataC['E5'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan,tahun',
            'where'     => " grup = 'E.5' and kode_cabang = '".$cabang."' and kode_anggaran = '$anggaran->kode_anggaran'",
            'group_by'  => 'bulan'
        ])->result();

        // data ke 4
        $dataD['arr_tahun_core'] = $this->arr_tahun_core;
        $dataD['D'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621016%' or a.glwnco like '1622016%' or a.glwnco like '5621012%' group by a.glwdes",

        ])->result();
        $dataD['A_saved'] = get_data('tbl_formula_akt a',[
            'where' => "a.kode_cabang = '$cabang' and a.kode_anggaran = '$anggaran->kode_anggaran' and (a.glwnco like '1621016%' or a.glwnco like '1622016%' or a.glwnco like '5621012%')"
        ])->result_array();

        $valD = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5621012%' group by a.glwdes",

        ])->result_array();
        $valD[0]['hasil'] *= -1;
        $valD[0]['hasil2'] *= -1;
        $dataD['valD'] = $valD;

        $dataD['E3'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan,tahun',
            'where'     => " grup = 'E.3' and kode_cabang = '".$cabang."' and kode_anggaran = '$anggaran->kode_anggaran'",
            'group_by'  => 'bulan'
        ])->result();

        $view = '';

        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/table',$dataA,true);
        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableB',$dataB,true);
        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableC',$dataC,true);
        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableD',$dataD,true);


        // echo json_encode($dataB);    

        $response   = array(
            'table'     => $view,
        );
        render($response,'json');
    }



      function dataSewa($anggaran="", $cabang=""){

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();

        $bln_trakhir = $anggaran->bulan_terakhir_realisasi;
        $getMinBulan = $anggaran->bulan_terakhir_realisasi - 1;
        $thn_trakhir = $anggaran->tahun_terakhir_realisasi;
        $tbl_history = 'tbl_history_'.$thn_trakhir;

        $peresntase = get_data('tbl_m_aset_tak_guna',[
            'select' => 'kode,kode_inventaris,persen',
            'where'  => "kode_anggaran = '$anggaran->kode_anggaran'"
        ])->result_array();

        $view = '';
        $dataDefault = [];
        $dataDefault['anggaran']     = $anggaran;
        $dataDefault['thn_trakhir']  = $thn_trakhir;
        $dataDefault['detail_tahun'] = $this->detail_tahun;
        $dataDefault['bln_trakhir']  = $bln_trakhir;
        $dataDefault['cabang']       = $cabang;
        $dataDefault['arr_tahun_core'] = $this->arr_tahun_core;
        $dataDefault['data_core']    = get_data_core($this->arr_coa_core_sewa,$this->arr_tahun_core,'TOT_'.$cabang);
        
        $select     = 'a.glwsbi,a.glwnco,a.glwdes';
        $select2    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";

        // Gedung
        $dataA = $dataDefault;
        $dataA['data'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',
            'join' => "$tbl_history b on a.glwnco = b.glwnco type left",
            'where'     => " a.is_active = '1' and (a.glwnco like '1621017%' or a.glwnco like '1622017%' or a.glwnco like '2991245%' or a.glwnco like '5791010%' or a.glwnco like '5621013%') group by a.glwdes",
            'order_by' => 'a.glwnco'
        ])->result();
        $dataA['A_saved'] = get_data('tbl_formula_akt a',[
            'where' => "a.kode_cabang = '$cabang' and a.kode_anggaran = '$anggaran->kode_anggaran' and (a.glwnco like '1621017%' or a.glwnco like '1622017%' or a.glwnco like '2991245%' or a.glwnco like '5791010%' or a.glwnco like '5621013%')"
        ])->result_array();
        $valA = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',
            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",
            'where'     => " a.is_active = '1' and a.glwnco like '5621013%' group by a.glwdes",
        ])->result_array();
        $valA[0]['hasil'] *= -1;
        $valA[0]['hasil2'] *= -1;
        $dataA['dtA'] = $valA;
        $dataA['E'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan,tahun',
            'where'     => " grup = 'E.7' and kode_inventaris like 'M%' and kode_cabang = '".$cabang."' and kode_anggaran = '$anggaran->kode_anggaran'",
            'group_by'  => 'bulan'
        ])->result();
        $dataA['E_detail'] = get_data('tbl_rencana_aset a',[
            'select' => 'harga,jumlah, bulan,tahun',
            'where'     => " grup = 'E.7' and kode_inventaris like 'M%' and kode_cabang = '".$cabang."' and kode_anggaran = '$anggaran->kode_anggaran'"
        ])->result_array();
        $dataA['persen1'] = searchPersentase(['kode_inventaris' => 'M','kode' => 'M01'],$peresntase);
        $dataA['persen2'] = searchPersentase(['kode_inventaris' => 'M','kode' => 'M02'],$peresntase);
        $dataA['kode_inventaris'] = 'M';
        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableSewa',$dataA,true);

        // Kel 1
        $dataB = $dataDefault;
        $dataB['data'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',
            'join' => "$tbl_history b on a.glwnco = b.glwnco type left",
            'where'     => " a.is_active = '1' and (a.glwnco like '1621018%' or a.glwnco like '1622018%' or a.glwnco like '2991246%' or a.glwnco like '5791010%' or a.glwnco like '5621016%') group by a.glwdes",
            'order_by' => 'a.glwnco'
        ])->result();
        $dataB['A_saved'] = get_data('tbl_formula_akt a',[
            'where' => "a.kode_cabang = '$cabang' and a.kode_anggaran = '$anggaran->kode_anggaran' and (a.glwnco like '1621018%' or a.glwnco like '1622018%' or a.glwnco like '2991246%' or a.glwnco like '5791010%' or a.glwnco like '5621016%')"
        ])->result_array();
        $valb = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',
            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",
            'where'     => " a.is_active = '1' and a.glwnco like '5621016%' group by a.glwdes",
        ])->result_array();
        $valb[0]['hasil'] *= -1;
        $valb[0]['hasil2'] *= -1;
        $dataB['dtA'] = $valb;
        $dataB['E'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan,tahun',
            'where'     => " grup = 'E.7' and kode_inventaris like 'N%' and kode_cabang = '".$cabang."' and kode_anggaran = '$anggaran->kode_anggaran'",
            'group_by'  => 'bulan'
        ])->result();
        $dataB['E_detail'] = get_data('tbl_rencana_aset a',[
            'select' => 'harga,jumlah, bulan,tahun',
            'where'     => " grup = 'E.7' and kode_inventaris like 'N%' and kode_cabang = '".$cabang."' and kode_anggaran = '$anggaran->kode_anggaran'"
        ])->result_array();
        $dataB['persen1'] = searchPersentase(['kode_inventaris' => 'N','kode' => 'N01'],$peresntase);
        $dataB['persen2'] = searchPersentase(['kode_inventaris' => 'N','kode' => 'N02'],$peresntase);
        $dataB['kode_inventaris'] = 'N';
        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableSewaB',$dataB,true);

        // Kel 2
        $dataC = $dataDefault;
        $dataC['data'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',
            'join' => "$tbl_history b on a.glwnco = b.glwnco type left",
            'where'     => " a.is_active = '1' and (a.glwnco like '1621019%' or a.glwnco like '1622019%' or a.glwnco like '2991245%' or a.glwnco like '5791010%' or a.glwnco like '5621017%') group by a.glwdes",
            'order_by' => 'a.glwnco'
        ])->result();
        $dataC['A_saved'] = get_data('tbl_formula_akt a',[
            'where' => "a.kode_cabang = '$cabang' and a.kode_anggaran = '$anggaran->kode_anggaran' and (a.glwnco like '1621019%' or a.glwnco like '1622019%' or a.glwnco like '2991245%' or a.glwnco like '5791010%' or a.glwnco like '5621017%')"
        ])->result_array();
        $valC = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',
            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",
            'where'     => " a.is_active = '1' and a.glwnco like '5621017%' group by a.glwdes",
        ])->result_array();
        $valC[0]['hasil'] *= -1;
        $valC[0]['hasil2'] *= -1;
        $dataC['dtA'] = $valC;
        $dataC['E'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan,tahun',
            'where'     => " grup = 'E.7' and kode_inventaris like 'P%' and kode_cabang = '".$cabang."' and kode_anggaran = '$anggaran->kode_anggaran'",
            'group_by'  => 'bulan'
        ])->result();
        $dataC['E_detail'] = get_data('tbl_rencana_aset a',[
            'select' => 'harga,jumlah, bulan,tahun',
            'where'     => " grup = 'E.7' and kode_inventaris like 'P%' and kode_cabang = '".$cabang."' and kode_anggaran = '$anggaran->kode_anggaran'"
        ])->result_array();
        $dataC['persen1'] = searchPersentase(['kode_inventaris' => 'P','kode' => 'P01'],$peresntase);
        $dataC['persen2'] = searchPersentase(['kode_inventaris' => 'P','kode' => 'P02'],$peresntase);
        $dataC['kode_inventaris'] = 'P';
        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableSewaC',$dataC,true);

        // sum liabilitaas
        // $this->db->query("call sp_operasional('formula_akt','liabilitas','".$anggaran->kode_anggaran."','".$cabang."')");
        $dataLia = $dataDefault;
        $dataLia['data'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',
            'join' => "$tbl_history b on a.glwnco = b.glwnco type left",
            'where'     => " a.is_active = '1' and (a.glwnco like '5791010%') group by a.glwdes",
            'order_by' => 'a.glwnco'
        ])->result();
        $dataLia['A_saved'] = get_data('tbl_formula_akt a',[
            'where' => "a.kode_cabang = '$cabang' and a.kode_anggaran = '$anggaran->kode_anggaran' and (
            a.glwnco like '5791010%')"
        ])->result_array();
        $where = [
            '5791010_M_baru',
            '5791010_N_baru',
            '5791010_P_baru'
        ];
        $dataLia['baru'] = get_data('tbl_formula_akt a',[
            'select' => '
                a.tahun_core,
                SUM(ifnull(a.bulan_1,0)) as bulan_1,
                SUM(ifnull(a.bulan_2,0)) as bulan_2,
                SUM(ifnull(a.bulan_3,0)) as bulan_3,
                SUM(ifnull(a.bulan_4,0)) as bulan_4,
                SUM(ifnull(a.bulan_5,0)) as bulan_5,
                SUM(ifnull(a.bulan_6,0)) as bulan_6,
                SUM(ifnull(a.bulan_7,0)) as bulan_7,
                SUM(ifnull(a.bulan_8,0)) as bulan_8,
                SUM(ifnull(a.bulan_9,0)) as bulan_9,
                SUM(ifnull(a.bulan_10,0)) as bulan_10,
                SUM(ifnull(a.bulan_11,0)) as bulan_11,
                SUM(ifnull(a.bulan_12,0)) as bulan_12,
            ',
            'where' => [
                'a.kode_cabang'     => $cabang,
                'a.kode_anggaran'   => $anggaran->kode_anggaran,
                'a.glwnco'          => $where,
            ],
            'group_by' => 'a.tahun_core'
        ])->result_array();
        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableLiabilitas',$dataLia,true);

        $response   = array(
            'table'     => $view,
        );
        render($response,'json');
    }


     function save_perubahan($anggaran="",$cabang="") {       
        $dt_anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();
        $data   = json_decode(post('json'),true);
        foreach($data as $getId => $record) {
        	$cekId = explode("-",$getId);
			$sumber_data = $cekId[0];
			$glwnco = $cekId[1];
            $tahun  = '0'; if(isset($cekId[2])) $tahun  = $cekId[2];

            $changed = [];
            foreach ($record as $k2 => $v2) {
                $value = filter_money($v2);
                $record[$k2] = insert_view_report($value);
                if(!in_array($k2, $changed)) array_push($changed, $k2);
            }

            $parent_id = "0";
            if($tahun != $dt_anggaran->tahun_anggaran):
                $parent_id = $cabang;
            endif;

			$cek  = get_data('tbl_formula_akt a',[
                'select'    => 'a.id,a.changed',
                'where'     => [
                    'a.glwnco'             => $glwnco,
                    'a.kode_anggaran'   => $anggaran,
                    'a.kode_cabang'   => $cabang,
                    'a.parent_id'   => $parent_id,
                ]
            ])->result_array();
     
            if(count($cek) > 0){
                $changed = json_decode($cek[0]['changed']);
                foreach ($record as $k => $v) {
                    if(!in_array($k, $changed)) array_push($changed, $k);
                }
                $record['changed'] = json_encode($changed);
                update_data('tbl_formula_akt', $record,'id',$cek[0]['id']);
            }else {
                    $record['changed'] = json_encode($changed);
                    $record['glwnco'] = $glwnco;
                    $record['tahun_core'] = $tahun;
                    $record['kode_anggaran'] = $anggaran;
                    $record['kode_cabang'] = $cabang;
                    $record['parent_id'] = $parent_id;
                    insert_data('tbl_formula_akt',$record);
            }         
         } 
    }

    function delete(){
        $dt = post('id');
        $dt = explode('-', $dt);
        if(count($dt)>0):
            $coa    = $dt[0];
            $cabang = $dt[1];
            delete_data('tbl_formula_akt',['glwnco' => $coa,'kode_cabang' => $cabang, 'kode_anggaran' => user('kode_anggaran')]);
            render(['status' => 'success', 'message' => lang('berhasil')],'json');
        else:
            render(['status' => false,'message' => lang('data_not_found')],'json');
        endif;
    }

}