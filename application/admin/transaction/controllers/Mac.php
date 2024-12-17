<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mac extends BE_Controller {
	var $controller = 'mac';
	var $path       = 'transaction/';
    var $arr_coa_other = [];
	
    function __construct() {
        parent::__construct();
        $m_mac = get_data('tbl_m_mac',[
            'select' => 'coa',
            'where'  => "is_active = '1'"
        ])->result_array();
        foreach ($m_mac as $v) {
            array_push($this->arr_coa_other, $v['coa']);
        }
    }
    
    function index() {
	 	$tahun = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result_array();
        $a     = get_access($this->controller);
        $data['controller']     = $this->controller;
        $data['coa'] 			= $this->coa_option();
        $data['tahun']     		= $tahun;
        $data['bulan']     		= $this->month_option();
   		$data['cabang']     	= $this->data_cabang();
        $data['arr_coa_other']  = $this->arr_coa_other;
        $data['access_additional']  = $a['access_additional'];
        $data['coa_laba']       = get_data('tbl_m_coa',['where' => ['glwnco' => ['4100000','4500000','4800000','5100000','5500000','5800000','59999']]])->result_array();

        render($data);
    }

    private function coa_option(){
    	$data = get_data('tbl_m_budget_control a',[
    		'select' 	=> 'a.coa,b.glwdes as name',
    		'where'		=> 'a.is_active = 1',
    		'join'		=> 'tbl_m_coa b on a.coa = b.glwnco',
    		'order_by'	=> 'a.id',
    	])->result_array();
    	return $data;
    }
    private function month_option(){
    	$data = array();
    	for ($i=1; $i <=12 ; $i++) { 
    		$month = month_lang($i);
    		array_push($data, array('value' => $i,'name' => $month));
    	}
    	return $data;
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('usulan_besaran')
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

        $data           = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cab->id,
                'a.kode_cabang' => $kode_cabang
            ]
        ])->result_array();

      
        return $data;
    }

    function get_content(){
    	$bulan 	= post('bulan');
    	$tahun 	= post('tahun');
    	$cabang	= post('cabang');

       	$tahun    = get_data('tbl_tahun_anggaran','kode_anggaran',$tahun)->row();
    	$cabang   = get_data('tbl_m_cabang','kode_cabang',$cabang)->row();


    	$data['bulan'] = $bulan;
    	$data['tahun'] = $tahun;
    	$data['cabang'] = $cabang;


    	$view 	= $this->load->view($this->path.$this->controller.'/content',$data,true);

    	render([
    		'view' => $view,
    	],'json');
    }

    function data2($tahun="", $cabang="", $bulan="") {

        $kode_anggaran = user('kode_anggaran');
      	$tahun_ = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->row();

    	$status_history = true;
        $status = true;
       	$tbl_history = 'tbl_history_'.($tahun_->tahun_anggaran-1);
        $tbl_history_current = 'tbl_history_'.($tahun_->tahun_anggaran);
       	if(!$this->db->table_exists($tbl_history)):
       		$status_history = false;
       	endif;
        $TOT_cab = 'TOT_' . $cabang ;
        if($status_history):
            $field_tabel    = get_field($tbl_history,'name');
            if(!in_array($TOT_cab, $field_tabel)):
                $status_history = false;
            endif;
        endif;

        if(!$this->db->table_exists($tbl_history_current)):
            $status = false;
        endif;
        if($status):
            $field_tabel    = get_field($tbl_history_current,'name');
            if(!in_array($TOT_cab, $field_tabel)):
                $status = false;
            endif;
        endif;
        
        #dpk
        // giro + tabungan + simpanan berjakngka = dpk
        $coa_dpk = array('2100000','2120011','2130000');
        if($status):
            $dt_history_current = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,
                    (case when a.kali_minus = 1 then (ifnull($TOT_cab,0)*-1) else ifnull($TOT_cab,0) end) as total",
                'join'   => [
                    $tbl_history_current." as b on b.glwnco = a.glwnco and b.bulan = '$bulan' type left"
                ],
                'where'  => ['a.glwnco' => $coa_dpk],
                'order_by' => 'a.id'
            ])->result_array();
        else:
            $dt_history_current = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,0 as total",
                'where'  => ['a.glwnco' => $coa_dpk],
                'order_by' => 'a.id'
            ])->result_array();
        endif;

        if($status_history):
            $dt_history = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,
                    (case when a.kali_minus = 1 then (ifnull($TOT_cab,0)*-1) else ifnull($TOT_cab,0) end) as total",
                'join'   => [
                    $tbl_history." as b on b.glwnco = a.glwnco and b.bulan = '$bulan' type left"
                ],
                'where'  => ['a.glwnco' => $coa_dpk],
                'order_by' => 'a.id'
            ])->result_array();
        else:
            $dt_history = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,0 as total",
                'where'  => ['a.glwnco' => $coa_dpk],
                'order_by' => 'a.id'
            ])->result_array();
        endif;

        $total_dpk = 0;
        foreach ($dt_history_current as $k => $v) {
            $total_dpk += $v['total'];
            ${'dpk_'.$v['glwnco']} = $v['total'];
        }

        $total_dpk_history = 0;
        foreach ($dt_history as $k => $v) {
            $total_dpk_history += $v['total'];
        }

        $chart_dpk = [];
        foreach ($dt_history_current as $k => $v) {
            $pembagi = $total_dpk; if(!$total_dpk) $pembagi = 1;
            $chart_dpk['title'][] = $v['glwdes'];
            $chart_dpk['data'][]  = round(($v['total']/$pembagi)*100,2);
            $chart_dpk['data2'][]  = round(view_report($v['total']));
        }

        // dana pihak ke-3
        $field = 'B_' . sprintf("%02d", $bulan);
        $dt_dana_3 = get_data('tbl_m_coa a',[
            'select' => "a.glwnco,a.glwdes,ifnull(b.$field,0) as total",
            'join'   => [
                "tbl_budget_nett as b on 
                    b.coa = a.glwnco and b.kode_cabang = '$cabang' and b.kode_anggaran = '$kode_anggaran' type left"
            ],
            'where'  => ['a.glwnco' => $coa_dpk],
            'order_by' => 'a.id'
        ])->result_array();
        $total_dana_3 = 0;
        foreach ($dt_dana_3 as $k => $v) {
            $total_dana_3 += $v['total'];
            ${'data_3_'.$v['glwnco']} = $v['total'];
        }

        // penghimpunan
        $pembagi        = $total_dana_3; if(!$total_dana_3) $pembagi = 1;
        $penghimpunan   = ($total_dpk / $pembagi)*100;
        // pertumbuhan/PERT (YOY)
        $pembagi        = $total_dpk_history; if(!$total_dpk_history) $pembagi = 1;
        $pertumbuhan    = (($total_dpk - $total_dpk_history)/$pembagi)*100;
        // Deviasi
        $deviasi = ($total_dpk - $total_dpk_history);
        // giro tabungan simpanan berjangja
        foreach ($coa_dpk as $v) {
            $pembagi        = ${'data_3_'.$v}; if(!${'data_3_'.$v}) $pembagi = 1;
            $first          = 0; if(isset(${'dpk_'.$v})) $first = ${'dpk_'.$v};
            $val            = ($first / $pembagi) * 100;
            $dana_3[$v] = round($val,2).' %';
        }

        $dana_3['penghimpunan'] = round($penghimpunan,2).' %';
        $dana_3['pertumbuhan']  = round($pertumbuhan,2).' %';
        $dana_3['deviasi']      = custom_format(view_report($deviasi));
        $dana_3['real_giro']    = custom_format(view_report($dpk_2100000));
        $dana_3['renc_giro']    = custom_format(view_report($data_3_2100000));
        $dana_3['real_tab']     = custom_format(view_report($dpk_2120011));
        $dana_3['renc_tab']     = custom_format(view_report($data_3_2120011));
        $dana_3['real_simp_bjk']     = custom_format(view_report($dpk_2130000));
        $dana_3['renc_simp_bjk']     = custom_format(view_report($data_3_2130000));

        #kredit
        // coa kredit produktif + kredit konsumtif
        $coa_kredit = ['122502','122506'];
        if($status):
            $dt_kredit_current = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,
                (case when a.kali_minus = 1 then (ifnull($TOT_cab,0)*-1) else ifnull($TOT_cab,0) end) as total",
                'join'   => [
                    $tbl_history_current." as b on b.glwnco = a.glwnco and b.bulan = '$bulan' type left"
                ],
                'where'  => ['a.glwnco' => $coa_kredit],
                'order_by' => 'a.id'
            ])->result_array();
        else:
            $dt_kredit_current = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,0 as total",
                'where'  => ['a.glwnco' => $coa_kredit],
                'order_by' => 'a.id'
            ])->result_array();
        endif;

        if($status_history):
            $dt_kredit_history = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,
                    (case when a.kali_minus = 1 then (ifnull($TOT_cab,0)*-1) else ifnull($TOT_cab,0) end) as total",
                'join'   => [
                    $tbl_history." as b on b.glwnco = a.glwnco and b.bulan = '$bulan' type left"
                ],
                'where'  => ['a.glwnco' => $coa_kredit],
                'order_by' => 'a.id'
            ])->result_array();
        else:
            $dt_kredit_history = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,0 as total",
                'where'  => ['a.glwnco' => $coa_kredit],
                'order_by' => 'a.id'
            ])->result_array();
        endif;
        
        $total_kredit = 0;
        foreach ($dt_kredit_current as $k => $v) {
            $total_kredit += $v['total'];
            ${'kredit_'.$v['glwnco']} = $v['total'];
        }
        $total_kredit_history = 0;
        foreach ($dt_kredit_history as $k => $v) {
            $total_kredit_history += $v['total'];
        }

        $chart_kredit = [];
        foreach ($dt_kredit_current as $k => $v) {
            $pembagi = $total_kredit; if(!$total_kredit) $pembagi = 1;
            $chart_kredit['title'][] = $v['glwdes'];
            $chart_kredit['data'][]  = round(($v['total']/$pembagi)*100,2);
            $chart_kredit['data2'][]  = round(view_report($v['total']));
        }

        // data budget nett/rencana
        $field = 'B_' . sprintf("%02d", $bulan);
        $dt_kredit_nett = get_data('tbl_m_coa a',[
            'select' => "a.glwnco,a.glwdes,ifnull(b.$field,0) as total",
            'join'   => [
                "tbl_budget_nett as b on 
                    b.coa = a.glwnco and b.kode_cabang = '$cabang' and b.kode_anggaran = '$kode_anggaran' type left"
            ],
            'where'  => ['a.glwnco' => $coa_kredit],
            'order_by' => 'a.id'
        ])->result_array();
        $total_kredit_nett = 0;
        foreach ($dt_kredit_nett as $k => $v) {
            $total_kredit_nett += $v['total'];
            ${'kredit_nett_'.$v['glwnco']} = $v['total'];
        }

        // Ekspansi
        $pembagi = $total_kredit_nett; if(!$total_kredit_nett) $pembagi = 1;
        $ekspansi = ($total_kredit/$pembagi)*100;
        // pertumbuhan/PERT (YOY)
        $pembagi        = $total_kredit_history; if(!$total_kredit_history) $pembagi = 1;
        $pertumbuhan    = (($total_kredit - $total_kredit_history)/$pembagi)*100;
        // Deviasi
        $deviasi = ($total_kredit - $total_kredit_history);
        foreach ($coa_kredit as $v) {
            $pembagi        = ${'kredit_nett_'.$v}; if(!${'kredit_nett_'.$v}) $pembagi = 1;
            $first = 0; if(isset(${'kredit_'.$v})) $first = ${'kredit_'.$v};
            $hasil = $first / $pembagi;
            $kredit[$v] = round($hasil,2).' %';
        }

        $kredit['ekspansi']     = round($ekspansi,2).' %';
        $kredit['pertumbuhan']  = round($pertumbuhan,2).' %';
        $kredit['deviasi']      = custom_format(view_report($deviasi));
        $kredit['real_krd_prod'] = custom_format(view_report($kredit_122502));
        $kredit['renc_krd_prod'] = custom_format(view_report($kredit_nett_122502));
        $kredit['real_krd_kons'] = custom_format(view_report($kredit_122506));
        $kredit['renc_krd_kons'] = custom_format(view_report($kredit_nett_122506));

        #pendapatan & beban
        $coa_laba       = ['4100000','4500000','4800000','5100000','5500000','5800000','59999'];
        $coa_pendapatan = ['4100000','4500000','4800000'];
        $coa_beban      = ['5100000','5500000','5800000'];
        $coa_laba_fix   = ['59999'];

        if($status):
            $dt_laba_current = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,
                    (case when a.kali_minus = 1 then (ifnull($TOT_cab,0)*-1) else ifnull($TOT_cab,0) end) as total",
                'join'   => [
                    $tbl_history_current." as b on b.glwnco = a.glwnco and b.bulan = '$bulan' type left"
                ],
                'where'  => ['a.glwnco' => $coa_laba],
                'order_by' => 'a.id'
            ])->result_array();
        else:
            $dt_laba_current = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,0 as total",
                'where'  => ['a.glwnco' => $coa_laba],
                'order_by' => 'a.id'
            ])->result_array();
        endif;
        if($status_history):
            $dt_laba_history = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,
                    (case when a.kali_minus = 1 then (ifnull($TOT_cab,0)*-1) else ifnull($TOT_cab,0) end) as total",
                'join'   => [
                    $tbl_history." as b on b.glwnco = a.glwnco and b.bulan = '$bulan' type left"
                ],
                'where'  => ['a.glwnco' => $coa_laba],
                'order_by' => 'a.id'
            ])->result_array();
        else:
            $dt_laba_history = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,a.glwdes,0 as total",
                'where'  => ['a.glwnco' => $coa_laba],
                'order_by' => 'a.id'
            ])->result_array();
        endif;
        $dt_laba_nett = get_data('tbl_m_coa a',[
            'select' => "a.glwnco,a.glwdes,ifnull(b.$field,0) as total",
            'join'   => [
                "tbl_budget_nett as b on 
                    b.coa = a.glwnco and b.kode_cabang = '$cabang' and b.kode_anggaran = '$kode_anggaran' type left"
            ],
            'where'  => ['a.glwnco' => $coa_laba],
            'order_by' => 'a.id'
        ])->result_array();

        //deklarasi untuk real pendapatan dan beban
        $pendapatan_total   = 0;
        $beban_total        = 0;
        $laba_total         = 0;
        $dt_pendapatan      = [];
        $dt_beban           = [];
        $dt_laba_fix        = [];    
        foreach ($dt_laba_current as $k => $v) {
            if(in_array($v['glwnco'], $coa_pendapatan)):
                $pendapatan_total += $v['total'];
                $dt_pendapatan[] = $v;
            elseif(in_array($v['glwnco'], $coa_beban)):
                $beban_total += $v['total'];
                $dt_beban[] = $v;
            elseif(in_array($v['glwnco'], $coa_laba_fix)):
                $laba_total += $v['total'];
                $dt_laba_fix[] = $v;
            endif;
        }

        //deklarasi untuk real pendapatan dan beban history
        $pendapatan_total_history   = 0;
        $beban_total_history        = 0;
        $laba_total_history         = 0;
        $dt_pendapatan_history      = [];
        $dt_beban_history           = [];
        $dt_laba_fix_hitory         = [];    
        foreach ($dt_laba_history as $k => $v) {
            if(in_array($v['glwnco'], $coa_pendapatan)):
                $pendapatan_total_history += $v['total'];
                $dt_pendapatan_history[] = $v;
            elseif(in_array($v['glwnco'], $coa_beban)):
                $beban_total_history += $v['total'];
                $dt_beban_history[] = $v;
            elseif(in_array($v['glwnco'], $coa_laba_fix)):
                $laba_total_history += $v['total'];
                $dt_laba_fix_hitory[] = $v;
            endif;
        }

        //deklarasi untuk rencana pendapatan dan beban
        $pendapatan_total_nett  = 0;
        $beban_total_nett       = 0;
        $laba_total_nett        = 0;
        $dt_pendapatan_nett     = [];
        $dt_beban_nett          = [];
        $dt_laba_fix_nett       = [];
        foreach ($dt_laba_nett as $k => $v) {
            if(in_array($v['glwnco'], $coa_pendapatan)):
                $pendapatan_total_nett += $v['total'];
                $dt_pendapatan_nett[] = $v;
            elseif(in_array($v['glwnco'], $coa_beban)):
                $beban_total_nett += $v['total'];
                $dt_beban_nett[] = $v;
            elseif(in_array($v['glwnco'], $coa_laba_fix)):
                $laba_total_nett += $v['total'];
                $dt_laba_fix_nett[] = $v;
            endif;
        }

        // chart pendapatan dan beban
        $chart_laba_pendapatan = [
            'title' => ['RENC','REAL'],
            'data'  => [view_report($pendapatan_total_nett),view_report($pendapatan_total)],
        ];
        $chart_laba_beban = [
            'title' => ['RENC','REAL'],
            'data'  => [view_report($beban_total_nett),view_report($beban_total)],
        ];

        // Pencapaian
        $pembagi        = $laba_total_nett; if(!$laba_total_nett) $pembagi = 1;
        $pencapaian     = ($laba_total/$pembagi)*100;
        // Pertumbuhan 
        $pembagi        = $laba_total_history; if(!$laba_total_history) $pembagi = 1;
        $pertumbuhan    = (($laba_total-$laba_total_history)/$pembagi)*100;
        // Deviasi
        $deviasi        = $laba_total - $laba_total_history;
        // Pendapatan
        $pembagi        = $pendapatan_total_nett; if(!$pendapatan_total_nett) $pembagi = 1;
        $pendapatan     = $pendapatan_total/$pembagi * 100;
        // Beban
        $pembagi        = $beban_total_nett; if(!$beban_total_nett) $pembagi = 1;
        $beban          = $beban_total/$pembagi * 100;

        $laba['a'] = $laba_total;
        $laba['b'] = $laba_total_nett;
        $laba['pencapaian']     = round($pencapaian,2).' %';
        $laba['pertumbuhan']    = round($pertumbuhan,2).' %';
        $laba['deviasi']        = custom_format(view_report($deviasi));
        $laba['pendapatan']     = round($pendapatan,2).' %';
        $laba['beban']          = round($beban,2).' %';
        foreach ($dt_laba_current as $k => $v) {
            $laba['real_'.$v['glwnco']]        = custom_format(view_report($v['total']));
        }
        foreach ($dt_laba_nett as $k => $v) {
            $laba['renc_'.$v['glwnco']]        = custom_format(view_report($v['total']));
        }

        // chart pendapatan dari core dan budget nett
        // Pend Bunga : 4100000 , Pend Rak : 4190000, Pend Oprs : 4500000, Pend Non Oprs :4800000
        $arr_coa_pendapatan     = ['4100000','4190000','4500000','4800000'];
        $arr_label_pendapatan   = ['4100000' => 'Pend Bunga','4190000' => 'Pend RAK','4500000' => 'Pend Oprs','4800000' => 'Pend Non Oprs'];
        $field  = 'B_'.sprintf("%02d", $bulan);
        $dt_pendapatan_core       = [];
        $dt_pendapatan_rencana    = get_data('tbl_m_coa a',[
            'select'    => "a.glwnco,sum(ifnull($field,0)) as total",
            'join'      => "tbl_budget_nett b on a.glwnco = b.coa and b.kode_anggaran = '".$tahun_->kode_anggaran."' and b.kode_cabang = '".$cabang."' type left",
            'where'     => ['a.glwnco' => $arr_coa_pendapatan],
            'group_by'  => 'a.glwnco',
        ])->result_array();
        if($status):
            $dt_pendapatan_core = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,
                    (case when a.kali_minus = 1 then (ifnull($TOT_cab,0)*-1) else ifnull($TOT_cab,0) end) as total",
                'join'   => [
                    $tbl_history_current." as b on b.glwnco = a.glwnco and b.bulan = '$bulan' type left"
                ],
                'where'  => ['a.glwnco' => $arr_coa_pendapatan],
                'order_by' => 'a.id'
            ])->result_array();
        endif;
        $chart_pendapatan_core = [];
        foreach ($arr_label_pendapatan as $v) {
            $chart_pendapatan_core['labels'][] = $v;
        }
        foreach ($arr_coa_pendapatan as $v) {
            $key1 = multidimensional_search($dt_pendapatan_rencana, array(
                'glwnco' => $v,
            ));
            if(strlen($key1)>0):
                $dt = $dt_pendapatan_rencana[$key1];
                $chart_pendapatan_core['data']['RENC'][] = view_report($dt['total']);
            else:
                $chart_pendapatan_core['data']['RENC'][] = 0;
            endif;

            $key2 = multidimensional_search($dt_pendapatan_core, array(
                'glwnco' => $v,
            ));
            if(strlen($key2)>0):
                $dt = $dt_pendapatan_core[$key2];
                $chart_pendapatan_core['data']['REAL'][] = view_report($dt['total']);
            else:
                $chart_pendapatan_core['data']['REAL'][] = 0;
            endif;
        }

        // chart beban bunga dari core dan budget nett
        // Beban Bunga : 5100000, Beban RAK : 5190000, Beban Oprs : 5500000, Beban Non Oprs : 5800000
        $arr_coa_beban    = ['5100000','5190000','5500000','5800000'];
        $arr_label_beban  = ['5100000' => 'Beban Bunga','5190000' => 'Beban RAK','5500000' => 'Beban Oprs','5800000' => 'Beban Non Oprs'];
        $field  = 'B_'.sprintf("%02d", $bulan);
        $dt_beban_core       = [];
        $dt_beban_rencana    = get_data('tbl_m_coa a',[
            'select'    => "a.glwnco,sum(ifnull($field,0)) as total",
            'join'      => "tbl_budget_nett b on a.glwnco = b.coa and b.kode_anggaran = '".$tahun_->kode_anggaran."' and b.kode_cabang = '".$cabang."' type left",
            'where'     => ['a.glwnco' => $arr_coa_beban],
            'group_by'  => 'a.glwnco',
        ])->result_array();
        if($status):
            $dt_beban_core = get_data('tbl_m_coa a',[
                'select' => "a.glwnco,
                    (case when a.kali_minus = 1 then (ifnull($TOT_cab,0)*-1) else ifnull($TOT_cab,0) end) as total",
                'join'   => [
                    $tbl_history_current." as b on b.glwnco = a.glwnco and b.bulan = '$bulan' type left"
                ],
                'where'  => ['a.glwnco' => $arr_coa_beban],
                'order_by' => 'a.id'
            ])->result_array();
        endif;
        $chart_beban_core = [];
        foreach ($arr_label_beban as $v) {
            $chart_beban_core['labels'][] = $v;
        }
        foreach ($arr_coa_beban as $v) {
            $key1 = multidimensional_search($dt_beban_rencana, array(
                'glwnco' => $v,
            ));
            if(strlen($key1)>0):
                $dt = $dt_beban_rencana[$key1];
                $chart_beban_core['data']['RENC'][] = view_report($dt['total']);
            else:
                $chart_beban_core['data']['RENC'][] = 0;
            endif;

            $key2 = multidimensional_search($dt_beban_core, array(
                'glwnco' => $v,
            ));
            if(strlen($key2)>0):
                $dt = $dt_beban_core[$key2];
                $chart_beban_core['data']['REAL'][] = view_report($dt['total']);
            else:
                $chart_beban_core['data']['REAL'][] = 0;
            endif;
        }

        // coa 
        $arr_coa_other = $this->arr_coa_other;
        $field  = 'B_'.sprintf("%02d", $bulan);
        $dt_other_core       = [];
        $dt_other_rencana    = get_data('tbl_m_mac a',[
            'select'    => "a.coa as glwnco,a.nama as glwdes,sum(ifnull($field,0)) as total",
            'join'      => "tbl_budget_nett b on a.coa = b.coa and b.kode_anggaran = '".$tahun_->kode_anggaran."' and b.kode_cabang = '".$cabang."' type left",
            'where'     => ['a.is_active' => 1],
            'group_by'  => 'a.coa',
        ])->result_array();
        if($status):
            $dt_other_core = get_data('tbl_m_mac a',[
                'select' => "a.coa as glwnco,a.nama as glwdes,
                (case when c.kali_minus = 1 then (ifnull($TOT_cab,0)*-1) else ifnull($TOT_cab,0) end) as total",
                'join'   => [
                    $tbl_history_current." as b on b.glwnco = a.coa and b.bulan = '$bulan' type left",
                    'tbl_m_coa as c on c.glwnco = a.coa'
                ],
                'where'  => ['a.is_active' => 1],
                'order_by' => 'a.id'
            ])->result_array();
        endif;

        $chart_other = [];
        foreach ($arr_coa_other as $v) {
            $renc = 0;
            $real = 0;

            $key1 = multidimensional_search($dt_other_rencana, array(
                'glwnco' => $v,
            ));
            if(strlen($key1)>0):
                $dt     = $dt_other_rencana[$key1];
                $renc   = $dt['total'];
                $chart_other[$v]['label'] = remove_spaces($dt['glwdes']);
                $chart_other[$v]['data']['RENC'][]  = view_report($dt['total']);
            else:
                $chart_other[$v]['label'] = '';
                $chart_other[$v]['data']['RENC'][]  = 0;
            endif;

            $key2 = multidimensional_search($dt_other_core, array(
                'glwnco' => $v,
            ));
            if(strlen($key2)>0):
                $dt     = $dt_other_core[$key2];
                $real   = $dt['total'];
                $chart_other[$v]['label']           = remove_spaces($dt['glwdes']);
                $chart_other[$v]['data']['REAL'][]  = view_report($dt['total']);
            else:
                $chart_other[$v]['label'] = '';
                $chart_other[$v]['data']['REAL'][]  = 0;
            endif;

            $pembagi        = $real; if(!$real) $pembagi = 1;
            $pencapaian     = ($renc / $pembagi)*100;
            $hemat          =  $renc - $real;
            $chart_other[$v]['pencapaian']  = round($pencapaian,2).' %';
            $chart_other[$v]['hemat']       = custom_format(view_report($hemat));
        }

        $data['chart_dpk']      = $chart_dpk;
        $data['dpk']            = $dana_3;
        $data['chart_kredit']   = $chart_kredit;
        $data['kredit']         = $kredit;
        $data['chart_pendapatan'] = $chart_laba_pendapatan;
        $data['chart_beban']    = $chart_laba_beban;
        $data['laba']           = $laba;
        $data['chart_pendapatan_core']  = $chart_pendapatan_core;
        $data['chart_beban_core']       = $chart_beban_core;
        $data['chart_other']            = $chart_other;

        render($data,'json');
    }


 	private function check_column(){

        $coa    = post('coa');
        $bulan  = post('bulan');
        
        $dt  = get_data('tbl_m_budget_control',[
            'select' => 'tabel',
            'where'  => "coa = '$coa' and is_active = '1'" 
        ])->row();
        $column = '';
        $tabel  = '';
        $where  = '';
        if($dt):
            $tabel = $dt->tabel;
            if($dt->tabel == 'tbl_budget_plan_neraca'):
                $c  = 'c.B_'.sprintf("%02d", $bulan);
                $as = 'B_'.sprintf("%02d", $bulan);
                $column .= $c.' as '.$as.', ';
                $where = 'c.coa';
            elseif($dt->tabel == 'tbl_labarugi'):
                $c  = 'c.bulan_'.$bulan;
                $as = 'B_'.sprintf("%02d", $bulan);
                $column .= $c.' as '.$as.', ';
                $where = 'c.glwnco';
            endif;
        endif;

        $data = [
            'column'    => $column,
            'tabel'     => $tabel,
            'where'     => $where,
        ];

        return $data;
    }

    public function get_arr_coa_other(){
        render($this->arr_coa_other,'json');
    }
}