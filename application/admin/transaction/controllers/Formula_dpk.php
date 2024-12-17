<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formula_dpk extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'formula_dpk';
    var $detail_tahun;
    var $kode_anggaran;
    var $detail_tahun2;
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

        $this->detail_tahun2   = get_data('tbl_detail_tahun_anggaran a',[
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
        $data['detail_tahun2'] = $this->detail_tahun2;
        return $data;
    }
    
    function index($p1="") { 
        $access         = get_access($this->controller);
        $data = $this->data_cabang();
        $data['access_additional']  = $access['access_additional'];
        render($data,'view:'.$this->path.'formula_dpk/index');
    }

    function data($kode_anggaran="", $kode_cabang="") {
        $nama_cabang ='';
        $cab = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();               
        if(isset($cab->nama_cabang)) $nama_cabang = $cab->nama_cabang;

        $anggaran = get_data('tbl_tahun_anggaran',[
            'select' => '*',
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
            ],
        ])->row();

        if(isset($anggaran)) $tahun_anggaran = $anggaran->tahun_anggaran;

        $data['detail_tahun'] = $this->detail_tahun;
        $data['detail_tahun2'] = $this->detail_tahun2;

        $TOT_cab = 'TOT_' . $kode_cabang ;   
        $field_tabel    = get_field('tbl_rate','name');

 
        if (in_array($TOT_cab, $field_tabel)) {
            $TOT_cab = 'TOT_' . $kode_cabang ;   
        }else{
            $TOT_cab = 0 ;  
        }   

        $data['rate_kasda'] = get_data('tbl_rate',[
            'select' => 'no_coa,'.$TOT_cab.' as rate',
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
                'no_coa' => '2101012'
            ],
        ])->row_array();


        $data['rate_nonkasda'] = get_data('tbl_rate',[
            'select' => 'no_coa,'.$TOT_cab.' as rate',
            'where'  => [
                'kode_anggaran' => $kode_anggaran,
                'no_coa' => '2101011'
            ],
        ])->row_array();

            
       $data['A1'] = get_data('tbl_m_coa a',[

            'select'    => 
                    "a.glwdes,
                    a.glwsbi,
                    a.glwnco",

            'where'     => " a.glwnco like '2101011%' or a.glwnco like '5131011%'  group by a.glwdes order by a.glwnco  "
        ])->result();



        $data['A1_detail'] = get_data('tbl_budget_plan_giro a',[
            'select' => 'a.*',
            'where'  => [
                    'a.kode_cabang' => $kode_cabang,
                    'a.kode_anggaran' => $kode_anggaran,
                    'a.coa' => '2101011' 
                ] 
        ])->result();

         $data['A2'] = get_data('tbl_m_coa',[

            'select'    => 
                    "glwdes,
                    glwsbi,
                    glwnco",

            'where'     => " glwnco like '2101012%' or glwnco like '5131012%' group by glwdes order by glwnco  "
        ])->result();  

        $data['A2_detail'] = get_data('tbl_budget_plan_giro a',[
            'select' => 'a.*',
            'where'  => [
                    'a.kode_cabang' => $kode_cabang,
                    'a.kode_anggaran' => $kode_anggaran,
                    'a.coa' => '2101012' 
                ] 
        ])->result();

        $data['B'] = get_data('tbl_m_rincian_tabungan a',[
            'select'    => 'a.nama as glwdes,a.coa as glwnco,a.biaya_bunga,b.glwdes as acct_bunga', 
            'join'      => 'tbl_m_coa b on a.biaya_bunga = b.glwnco type LEFT',
            'where'     => [
                'a.is_active' => 1,
            ],
            'sort_by' => 'a.coa',
        ])->result();  

        $TOT_cab = 'TOT_' . $kode_cabang ;   
        $field_tabel    = get_field('tbl_rate','name');
        if (in_array($TOT_cab, $field_tabel)) {
            $data['C'] = get_data('tbl_m_rincian_deposit a',[
                'select' => 'a.*,b.'.$TOT_cab.' as rate',
                'join'   => "tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",
                'where' => [
                    'a.is_active' => 1,
                ],
                'sort_by' => 'a.coa'
            ])->result();  
        }else{
            $data['C'] = get_data('tbl_m_rincian_deposit a',[
                'select' => 'a.*,0 as rate,0 as prsn',
                'join'   => "tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",
                'where' => [
                    'a.is_active' => 1,
                ],
                'sort_by' => 'a.coa'
            ])->result();  
        }

        $TOT_cab = 'TOT_' . $kode_cabang ;   
        $field_tabel    = get_field('tbl_rate','name');

        if (in_array($TOT_cab, $field_tabel)) {
            $data['rinc_tab'] = get_data('tbl_budget_plan_tabungan a',[
                'select' => 'a.*,'.$TOT_cab.' as rate',
                'join'   => "tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",
                'where'  => [
                    'a.kode_cabang' => $kode_cabang,
                    'a.kode_anggaran' => $kode_anggaran,
                    'a.parent_id !=' => 0, 
                ]
            ])->result();
        }else{
            $data['rinc_tab'] = get_data('tbl_budget_plan_tabungan a',[
                'select' => 'a.*,0 as rate',
                'join'   => "tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",
                'where'  => [
                    'a.kode_cabang' => $kode_cabang,
                    'a.kode_anggaran' => $kode_anggaran,
                    'a.parent_id !=' => 0, 
                ]
            ])->result();
        }

        $TOT_cab = 'TOT_' . $kode_cabang ;   
        $field_tabel    = get_field('tbl_rate','name');

        if (in_array($TOT_cab, $field_tabel)) {
            $data['rinc_dep'] = get_data('tbl_budget_plan_deposito a',[
                'select' => 'a.*,'.$TOT_cab.' as rate',
                'join'   => "tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",
                'where'  => [
                    'a.kode_cabang' => $kode_cabang,
                    'a.kode_anggaran' => $kode_anggaran,
                    'a.parent_id !=' => 0, 
                ]
            ])->result();
        }else{
            $data['rinc_dep'] = get_data('tbl_budget_plan_deposito a',[
                'select' => 'a.*,0 as rate',
                'join'   => "tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",
                'where'  => [
                    'a.kode_cabang' => $kode_cabang,
                    'a.kode_anggaran' => $kode_anggaran,
                    'a.parent_id !=' => 0, 
                ]
            ])->result();
        }


        $data['realisasi'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->row_array();

        $data['bulan_real'] = get_data('tbl_detail_tahun_anggaran',[
            'select' => 'count(bulan) as jmlbulan',
            'where'  => [
                'kode_anggaran' => user('kode_anggaran'),
                'sumber_data' => 1,
            ],
        ])->row_array();


        $select = 'TOT_'.$kode_cabang;
        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result_array();

        $tahun = 'tbl_history_'.$data['tahun'][0]['tahun_terakhir_realisasi'];
        $getMinBulan = $data['tahun'][0]['bulan_terakhir_realisasi'] - 1;
        $getMinBulan1 = $data['tahun'][0]['bulan_terakhir_realisasi'] - 2;
        $getMinBulan2 = $data['tahun'][0]['bulan_terakhir_realisasi'] - 3;
        $getMinBulan3 = $data['tahun'][0]['bulan_terakhir_realisasi'] - 4;
        $getMinBulan4 = $data['tahun'][0]['bulan_terakhir_realisasi'] - 5;

        $data['Bunga'] = get_data($tahun,[

            'select'    => 
                    "glwnco,account_name,
                    coalesce(sum(case when bulan = '".$data['tahun'][0]['bulan_terakhir_realisasi']."'  then ".$select." end), 0) as hasil10,
                    coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9,
                    coalesce(sum(case when bulan = '".$getMinBulan1."'  then ".$select." end), 0) as hasil8,
                    coalesce(sum(case when bulan = '".$getMinBulan2."'  then ".$select." end), 0) as hasil7,
                    coalesce(sum(case when bulan = '".$getMinBulan3."'  then ".$select." end), 0) as hasil6,
                    coalesce(sum(case when bulan = '".$getMinBulan4."'  then ".$select." end), 0) as hasil5,                    
                    account_name,
                    coa,
                    gwlsbi,
                    glwnco",

            'where'     => [
            'glwnco' => '5132012',       
            ],
            'group_by' => 'glwnco',
        ])->row_array();

        $data['A1_Real'] = get_data($tahun,[

            'select'    => 
                    "glwnco,account_name,
                    coalesce(sum(case when bulan = '".$data['tahun'][0]['bulan_terakhir_realisasi']."'  then ".$select." end), 0) as hasil10,
                    coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9,
                    coalesce(sum(case when bulan = '".$getMinBulan1."'  then ".$select." end), 0) as hasil8,
                    coalesce(sum(case when bulan = '".$getMinBulan2."'  then ".$select." end), 0) as hasil7,
                    coalesce(sum(case when bulan = '".$getMinBulan3."'  then ".$select." end), 0) as hasil6,
                    coalesce(sum(case when bulan = '".$getMinBulan4."'  then ".$select." end), 0) as hasil5,   
                    account_name,
                    coa,
                    gwlsbi,
                    glwnco",

            'where'     => [
            'glwnco' => ['2101011','5131011'],       
            ],
            'group_by' => 'glwnco',
        ])->result();


        $data['A2_Real'] = get_data($tahun,[
            'select'    => 
                    "glwnco,account_name,
                    coalesce(sum(case when bulan = '".$data['tahun'][0]['bulan_terakhir_realisasi']."'  then ".$select." end), 0) as hasil10,
                    coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9,
                    coalesce(sum(case when bulan = '".$getMinBulan1."'  then ".$select." end), 0) as hasil8,
                    coalesce(sum(case when bulan = '".$getMinBulan2."'  then ".$select." end), 0) as hasil7,
                    coalesce(sum(case when bulan = '".$getMinBulan3."'  then ".$select." end), 0) as hasil6,
                    coalesce(sum(case when bulan = '".$getMinBulan4."'  then ".$select." end), 0) as hasil5,   
                    account_name,
                    coa,
                    gwlsbi,
                    glwnco",

            'where'     => [
            'glwnco' => ['2101012','5131012'],       
            ],
            'group_by' => 'glwnco',
        ])->result();

        $where['__m'] = 'a.glwnco in (select coa from tbl_m_rincian_tabungan) or a.glwnco in (select biaya_bunga from tbl_m_rincian_tabungan)';

        $data['Real_tab'] = get_data($tahun. " a",[
                    'select' => "glwnco,account_name,
                    coalesce(sum(case when bulan = '".$data['tahun'][0]['bulan_terakhir_realisasi']."'  then ".$select." end), 0) as hasil10,
                    coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9,
                    coalesce(sum(case when bulan = '".$getMinBulan1."'  then ".$select." end), 0) as hasil8,
                    coalesce(sum(case when bulan = '".$getMinBulan2."'  then ".$select." end), 0) as hasil7,
                    coalesce(sum(case when bulan = '".$getMinBulan3."'  then ".$select." end), 0) as hasil6,
                    coalesce(sum(case when bulan = '".$getMinBulan4."'  then ".$select." end), 0) as hasil5,   
                    account_name,
                    coa,
                    gwlsbi,
                    glwnco",
                    'where' => $where,
                ])->result();   

        $where['__m'] = 'a.glwnco in (select coa from tbl_m_rincian_tabungan)';

        $data['Real_dep'] = get_data($tahun. " a",[
                    'select' => "glwnco,account_name,
                    coalesce(sum(case when bulan = '".$data['tahun'][0]['bulan_terakhir_realisasi']."'  then ".$select." end), 0) as hasil10,
                    coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9,
                    coalesce(sum(case when bulan = '".$getMinBulan1."'  then ".$select." end), 0) as hasil8,
                    coalesce(sum(case when bulan = '".$getMinBulan2."'  then ".$select." end), 0) as hasil7,
                    coalesce(sum(case when bulan = '".$getMinBulan3."'  then ".$select." end), 0) as hasil6,
                    coalesce(sum(case when bulan = '".$getMinBulan4."'  then ".$select." end), 0) as hasil5,   
                    account_name,
                    coa,
                    gwlsbi,
                    glwnco",
                    'where' => $where,
                ])->result();                     

        $data['anggaran']       = $anggaran;
        $data['kode_cabang']    = $kode_cabang;


        $response   = array(
            'table'     => $this->load->view('transaction/budget_planner/formula_dpk/table',$data,true),
        );
        render($response,'json');
    }

   function save_perubahan($anggaran="",$cabang="") {
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();
        $data   = json_decode(post('json'));
        foreach($data as $getId => $record) {
            $cekId = explode("-",$getId);
            $tahun = $cekId[0];
            $glwnco= $cekId[1];

            $where = [
                'kode_anggaran' => $anggaran->kode_anggaran,
                'kode_cabang'   => $cabang,
                'glwnco'        => $glwnco,
                'parent_id'     => '0',
            ];
            if($tahun != $anggaran->tahun_anggaran):
                $where['parent_id'] = $cabang;
            endif;

            $ck = get_data('tbl_formula_dpk',[
                'select'    => 'id',
                'where'     => $where
            ])->row();
            $record = insert_view_report_arr($record);
            $dt = array_merge($record,$where);
            if($ck):
                $dt['id'] = $ck->id;
            endif;
            save_data('tbl_formula_dpk',$dt);
        } 
    }    

}