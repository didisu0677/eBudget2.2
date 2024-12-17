<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formula_aktiva_inv extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'formula_aktiva_inv';
    var $detail_tahun;
    var $kode_anggaran;
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

        $dataA['detail_tahun'] = $this->detail_tahun;
        $dataB['detail_tahun'] = $this->detail_tahun;
        $dataC['detail_tahun'] = $this->detail_tahun;
        $dataD['detail_tahun'] = $this->detail_tahun;
        
        $select     = 'a.glwsbi,a.glwnco,a.glwdes';

        $select2    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";
        
        $dataA['A'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621013%' or a.glwnco like '1622013%' or a.glwnco like '5621011%' group by a.glwdes",

        ])->result();

        $dataA['valA'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5621011%' group by a.glwdes",

        ])->result_array();


        $dataA['E2'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan',
            'where'     => " grup = 'E.2' and kode_cabang = '".$cabang."'",
        ])->result();

         $dataB['B'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621014%' or a.glwnco like '1622014%' or a.glwnco like '5621014%' group by a.glwdes",

        ])->result();


        $dataB['valB'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5621014%' group by a.glwdes",

        ])->result_array();


        $dataB['E4'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan',
            'where'     => "grup = 'E.4' and kode_cabang = '".$cabang."'",
        ])->result();


        $dataC['C'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621015%' or a.glwnco like '1622015%' or a.glwnco like '5621015%' group by a.glwdes",

        ])->result();

        $dataC['valC'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5621015%' group by a.glwdes",

        ])->result_array();


        $dataC['E5'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan',
            'where'     => "grup = 'E.5' and kode_cabang = '".$cabang."'",
        ])->result();

        $dataC['D'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621016%' or a.glwnco like '1622016%' or a.glwnco like '5621012%' group by a.glwdes",

        ])->result();

        $dataD['valD'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5621012%' group by a.glwdes",

        ])->result_array();


        $dataD['E3'] = get_data('tbl_rencana_aset a',[
            'select' => 'sum(harga * jumlah) as total, bulan',
            'where'     => " grup = 'E.3' and kode_cabang = '".$cabang."'",
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

        $dataA['detail_tahun'] = $this->detail_tahun;
        $dataB['detail_tahun'] = $this->detail_tahun;
        $dataC['detail_tahun'] = $this->detail_tahun;
        $dataD['detail_tahun'] = $this->detail_tahun;
        
        $select     = 'a.glwsbi,a.glwnco,a.glwdes';

        $select2    = "coalesce(sum(case when b.bulan = '".$bln_trakhir."' then b.TOT_".$cabang." end), 0) as hasil, coalesce(sum(case when b.bulan = '".$getMinBulan."'  then b.TOT_".$cabang." end), 0) as hasil2";
        
        $dataA['A'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621017%' or a.glwnco like '1622017%' or a.glwnco like '2991245%' or a.glwnco like '5791010%' or a.glwnco like '5791013%'  group by a.glwdes",

        ])->result();

        // print_r($this->db->last_query()); 

        $dataA['valA'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5791010%' group by a.glwdes",

        ])->result_array();


        $dataA['E2'] = get_data('tbl_aset_sewa a',[
            'select' => 'jumlah as total, bulan',
            'where'     => " grup = 'E.2' and kode_cabang = '".$cabang."'",
        ])->result();

         $dataB['B'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621014%' or a.glwnco like '1622014%' or a.glwnco like '2991246%' or a.glwnco like '5791010%' or a.glwnco like '5621014%' group by a.glwdes",

        ])->result();


        $dataB['valB'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5621014%' group by a.glwdes",

        ])->result_array();


        $dataB['E4'] =  get_data('tbl_aset_sewa a',[
            'select' => 'jumlah as total, bulan',
            'where'     => " grup = 'E.4' and kode_cabang = '".$cabang."'",
        ])->result();


        $dataC['C'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '1621019%' or a.glwnco like '1622019%' or a.glwnco like '2991245%' or a.glwnco like '2991247%' or a.glwnco like '5621013%' group by a.glwdes",

        ])->result();

        $dataC['valC'] = get_data('tbl_m_coa a',[
            'select' => $select.','.$select2,
            'order_by' => 'a.id',

            'join' => "$tbl_history b on a.glwnco = b.glwnco type left",

            'where'     => " a.is_active = '1' and a.glwnco like '5621013%' group by a.glwdes",

        ])->result_array();


        $dataC['E5'] =  get_data('tbl_aset_sewa a',[
            'select' => 'jumlah as total, bulan',
            'where'     => " grup = 'E.5' and kode_cabang = '".$cabang."'",
        ])->result();

        // $dataC['D'] = get_data('tbl_m_coa a',[
        //     'select' => $select.','.$select2,
        //     'order_by' => 'a.id',

        //     'join' => "$tbl_history b on  a.glwnco = b.glwnco type left",

        //     'where'     => " a.is_active = '1' and a.glwnco like '1621016%' or a.glwnco like '1622016%' or a.glwnco like '5621012%' group by a.glwdes",

        // ])->result();

        // $dataD['valD'] = get_data('tbl_m_coa a',[
        //     'select' => $select.','.$select2,
        //     'order_by' => 'a.id',

        //     'join' => "$tbl_history b on a.glwnco = b.glwnco type left",

        //     'where'     => " a.is_active = '1' and a.glwnco like '5621012%' group by a.glwdes",

        // ])->result_array();


        // $dataD['E3'] =  get_data('tbl_aset_sewa a',[
        //     'select' => 'sum (jumlah), bulan',
        //     'where'     => " grup = 'E.3' and kode_cabang = '".$cabang."'",
        // ])->result();

        $view = '';

        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableSewa',$dataA,true);
        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableSewaB',$dataB,true);
        $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableSewaC',$dataC,true);
        // $view .= $this->load->view('transaction/budget_planner/formula_aktiva_inv/tableD',$dataD,true);


        // echo json_encode($dataA);    

        $response   = array(
            'table'     => $view,
        );
        render($response,'json');
    }


     function save_perubahan($anggaran="",$cabang="") {       

        $data   = json_decode(post('json'),true);
        foreach($data as $getId => $record) {
        	$cekId = explode("-",$getId);
			$sumber_data = $cekId[0];
			$glwnco = $cekId[1];

            foreach ($record as $k2 => $v2) {
                $value = filter_money($v2);
                $record[$k2] = $value;
            }

			if($sumber_data == '2'){
				 $cek  = get_data('tbl_formula_akt a',[
	                'select'    => 'a.id',
	                'where'     => [
	                    'a.glwnco'             => $glwnco,
	                    'a.kode_anggaran'   => $anggaran,
	                    'a.kode_cabang'   => $cabang,
	                    'a.parent_id'   => $cabang,
	                ]
	            ])->result_array();
	     
	            if(count($cek) > 0){
	                update_data('tbl_formula_akt', $record,'id',$cek[0]['id']);
	            }else {
	                    $record['glwnco'] = $glwnco;
	                    $record['kode_anggaran'] = $anggaran;
	                    $record['kode_cabang'] = $cabang;
	                    $record['parent_id'] = $cabang;
	                    insert_data('tbl_formula_akt',$record);
	            } 
			}else {

				 $cek  = get_data('tbl_formula_akt a',[
	                'select'    => 'a.id',
	                'where'     => [
	                    'a.glwnco'             => $glwnco,
	                    'a.kode_anggaran'   => $anggaran,
	                    'a.kode_cabang'   => $cabang,
	                    'a.parent_id'   => '0',
	                ]
	            ])->result_array();
	     
	            if(count($cek) > 0){
	                update_data('tbl_formula_akt', $record,'id',$cek[0]['id']);
	            }else {
	                    $record['glwnco'] = $glwnco;
	                    $record['kode_anggaran'] = $anggaran;
	                    $record['kode_cabang'] = $cabang;
	                    $record['parent_id'] = '0';
	                    insert_data('tbl_formula_akt',$record);
	            }

			}           
         } 
    }

    

}