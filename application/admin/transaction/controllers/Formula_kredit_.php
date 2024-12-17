<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formula_kredit extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'formula_kredit';
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

        $data['realisasi'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->row_array();

        $data['path'] = $this->path;
        $data['detail_tahun'] = $this->detail_tahun;
        return $data;
    }
    
    function index($p1="") { 
        $access         = get_access($this->controller);
        $data = $this->data_cabang();
        $data['access_additional']  = $access['access_additional'];
        render($data,'view:'.$this->path.'formula_kredit/index');
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

    	$data['coa'] = get_data('tbl_produk_kredit a',[
    		'select' => 'a.*,b.glwdes as nama',
            'join'   => 'tbl_m_coa b on a.bunga_kredit = b.glwnco type LEFT',
    		'where'  => "a.is_active = '1' and a.bunga_kredit != ''",
            'sort_by' => 'a.coa'
    	])->result();

        $l_coa = [];
        foreach ($data['coa'] as $c) {
            $l_coa[] = $c->bunga_kredit; 
        }

    //    debug($data['coa']);die;

    	$data['detail_tahun'] = $this->detail_tahun;
        $data['anggaran']     = $anggaran;
        $data['kode_cabang']  = $kode_cabang;

        $TOT_cab = 'TOT_' . $kode_cabang ;   
        $field_tabel    = get_field('tbl_rate','name');

        if (in_array($TOT_cab, $field_tabel)) {
            $data['rinc_kr'] = get_data('tbl_budget_plan_kredit a',[
                'select' => 'a.*,'.$TOT_cab.' as rate',
                'join'   => "tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",
                'where'  => [
                    'a.kode_cabang' => $kode_cabang,
                    'a.kode_anggaran' => $kode_anggaran,
                    'a.parent_id !=' => 0, 
                ]
            ])->result();
        }else{
            $data['rinc_kr'] = get_data('tbl_budget_plan_kredit a',[
                'select' => 'a.*,0 as rate',
                'join'   => "tbl_rate b on a.coa = b.no_coa and b.kode_anggaran = '$kode_anggaran' type LEFT",
                'where'  => [
                    'a.kode_cabang' => $kode_cabang,
                    'a.kode_anggaran' => $kode_anggaran,
                    'a.parent_id !=' => 0, 
                ]
            ])->result();

        }

        $select = 'TOT_'.$kode_cabang;
        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result_array();

        $tahun = 'tbl_history_'.$data['tahun'][0]['tahun_terakhir_realisasi'];
        $getMinBulan = $data['tahun'][0]['bulan_terakhir_realisasi'] - 1;

        $h1 = 'hasil' . $getMinBulan;
        $h0 = 'hasil' . $data['tahun'][0]['bulan_terakhir_realisasi'];

        $data['awal_anggaran'] = $data['tahun'][0]['tahun_terakhir_realisasi'] . 
        sprintf("%02d", $data['tahun'][0]['bulan_terakhir_realisasi'] + 1);

        $data['B'] = get_data($tahun,[

            'select'    => 
                    "glwnco,coalesce(sum(case when bulan = '".$data['tahun'][0]['bulan_terakhir_realisasi']."'  then ".$select." end), 0) as hasil10,
                    coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as hasil9,
                    account_name,
                    coa,
                    gwlsbi,
                    glwnco",

            'where'     => [
            'glwnco' => $l_coa,       
            ],
            'group_by' => 'glwnco',
        ])->result();  


        $data['real_akhir'] = get_data($tahun,[

            'select'    => 
                    "glwnco,coalesce(sum(case when bulan = '".$data['tahun'][0]['bulan_terakhir_realisasi']."'  then ".$select." end), 0) as ".$h1.",
                    coalesce(sum(case when bulan = '".$getMinBulan."'  then ".$select." end), 0) as ".$h0.",
                    account_name,
                    coa,
                    gwlsbi,
                    glwnco",

            'where'     => [
            'glwnco' => '1454399',       
            ],
            'group_by' => 'glwnco',
        ])->row_array();  



       	$response   = array(
       		'table'     => $this->load->view($this->path.'formula_kredit/table',$data,true),
            'table2'     => $this->load->view($this->path.'formula_kredit/table2',$data,true),
        );

        render($response,'json');
    }

    function save_perubahan($anggaran="",$cabang="") {
        $anggaran = get_data('tbl_tahun_anggaran',[
            'where'  => [
                'kode_anggaran' => $anggaran,
            ],
        ])->row();

        $data   = json_decode(post('json'),true);
        foreach($data as $getId => $record) {
            $cekId = explode("-",$getId);
            $tahun = $cekId[0];
            $glwnco = $cekId[1];


            if($tahun == $anggaran->tahun_anggaran){
                 $cek  = get_data('tbl_formula_kredit a',[
                    'select'    => 'a.id',
                    'where'     => [
                        'a.glwnco'             => $glwnco,
                        'a.kode_anggaran'   => $anggaran,
                        'a.kode_cabang'   => $cabang,
                        'a.parent_id'   => $cabang,
                    ]
                ])->result_array();
         
                if(count($cek) > 0){
                    update_data('tbl_formula_kredit', $record,'id',$cek[0]['id']);
                }else {
                        $record['glwnco'] = $glwnco;
                        $record['kode_anggaran'] = $anggaran;
                        $record['kode_cabang'] = $cabang;
                        $record['parent_id'] = $cabang;
                        insert_data('tbl_formula_kredit',$record);
                } 
            }else {

                 $cek  = get_data('tbl_formula_kredit a',[
                    'select'    => 'a.id',
                    'where'     => [
                        'a.glwnco'             => $glwnco,
                        'a.kode_anggaran'   => $anggaran,
                        'a.kode_cabang'   => $cabang,
                        'a.parent_id'   => '0',
                    ]
                ])->result_array();
         
                if(count($cek) > 0){
                    update_data('tbl_formula_kredit', $record,'id',$cek[0]['id']);
                }else {
                        $record['glwnco'] = $glwnco;
                        $record['kode_anggaran'] = $anggaran;
                        $record['kode_cabang'] = $cabang;
                        $record['parent_id'] = '0';
                        insert_data('tbl_formula_kredit',$record);
                } 

            }           
         } 
    }
}