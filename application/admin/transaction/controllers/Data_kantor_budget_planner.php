<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_kantor_budget_planner extends BE_Controller {
    var $path       = 'transaction/budget_planner/';
    var $controller = 'data_kantor_budget_planner';
    function __construct() {
        parent::__construct();
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('Data_kantor_budget_planner')
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

        $data['detail_tahun'] = get_data('tbl_detail_tahun_anggaran a',[
            'select' => 'a.tahun,a.bulan,b.singkatan',
            'where'  => [
                'a.kode_anggaran' => user('kode_anggaran'),
            ],
            'join' => 'tbl_m_data_budget b on b.id = a.sumber_data'
        ])->result_array();

        $data['path'] = $this->path;

        return $data;
    }
    
    function index($p1="") { 
        $a      = get_access($this->controller);
        $data   = $this->data_cabang();
        $data['access_additional']  = $a['access_additional'];
        render($data,'view:'.$this->path.'data_kantor/index');
    }


    function get_data($kode_anggaran="",$kode_cabang=""){
        $data = array();

        $cabang = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();

        if(isset($cabang->kode_cabang)) {
            $data2 = array(
                'kode_anggaran' => $anggaran->kode_anggaran,
                'keterangan_anggaran' => $anggaran->keterangan,
                'kode_cabang' => $kode_cabang,
                'nama_kantor' => $cabang->nama_cabang,
            ); 
        }

        $cek = get_data('tbl_plan_berita_acara','kode_cabang',$kode_cabang)->row();
        if(!isset($cek->kode_cabang)) {
            insert_data('tbl_plan_berita_acara',$data2);
        }else{
            update_data('tbl_plan_berita_acara',$data2,['kode_cabang'=>$kode_cabang]);

        }

        $data = get_data('tbl_plan_berita_acara',[
            'where' =>[
                'kode_anggaran' => $kode_anggaran,
                'kode_cabang'   => $kode_cabang,
            ],
        ])->row_array();

        if($data){
            $data['tgl_mulai_menjabat'] = date("d-m-Y", strtotime($data['tgl_mulai_menjabat']));
        } else{
            $data = get_data('tbl_m_data_kantor',"kode_cabang",$kode_cabang)->row_array();

            if($data) $data['tgl_mulai_menjabat'] = date("d-m-Y", strtotime($data['tgl_mulai_menjabat']));
            else $data = array();
            
        }
        render($data,'json');
    }

     function data2($kode_anggaran="", $kode_cabang="") {
        $anggaran     = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->row();
        $detail_tahun = get_data('tbl_detail_tahun_anggaran a',[
            'select' => 'a.tahun,a.bulan,b.singkatan',
            'where'  => [
                'a.kode_anggaran' => $kode_anggaran,
            ],
            'join' => 'tbl_m_data_budget b on b.id = a.sumber_data'
        ])->result_array();

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

        $dt = [];
        for ($i=3; $i >= 0 ; $i--) { 
            $t      = ($anggaran->tahun_anggaran - $i);
            $table  = 'tbl_history_'.$t;
            $bulan  = [12];
            
            if(12 != $anggaran->bulan_terakhir_realisasi && $t == $anggaran->tahun_terakhir_realisasi):
                $bulan[] = $anggaran->bulan_terakhir_realisasi;
            endif;

            if($this->db->table_exists($table)):
                $column = 'TOT_'.$kode_cabang;
                if($this->db->field_exists($column, $table)):
                    $dt[$t] = get_data($table,[
                        'select' => $column.' as total,bulan,glwnco as coa',
                        'where'  => [
                            'bulan'     => $bulan,
                            'glwnco'    => $arr_coa,
                        ]
                    ])->result_array();
                endif;
            endif;
            if($t == $anggaran->tahun_anggaran):
                $dt['renc'] = get_data('tbl_indek_besaran',[
                    'select' => 'hasil12 as total,coa,parent_id',
                    'where'  => [
                        'coa' => $arr_coa,
                        'kode_anggaran' => $anggaran->kode_anggaran,
                        'kode_cabang'   => $kode_cabang,
                    ]
                ])->result_array();
            endif;
        }

        $data['data']       = $dt;
        $data['group']      = $arr_group;
        $data['anggaran']   = $anggaran;
        $data['kode_cabang']= $kode_cabang;
        $data['detail_tahun']      = $detail_tahun;
        $view = $this->load->view($this->path.'data_kantor/table',$data,true);
     
        $data = [
            'data' => $view,
        ];

        render($data,'json');
    }

    function save(){
        $data = post();
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->row();

        $data['kode_anggaran'] = user('kode_anggaran');
        $data['keterangan_anggaran'] = $anggaran->keterangan;   
        $cek = get_data('tbl_plan_berita_acara',[
            'kode_anggaran' => user('kode_anggaran'),
            'kode_cabang'   => $data['kode_cabang']
        ])->row();

        if(!isset($cek->id)) {
            $response = insert_data('tbl_plan_berita_acara',$data,post(':validation'));
        }else{
            $data_update = $data;
            $data_update['keterangan_anggaran'] = $anggaran->keterangan;
            $data_update['kode_anggaran'] = user('kode_anggaran');
                
            $response = update_data('tbl_plan_berita_acara',$data_update,[
                'kode_anggaran'=>user('kode_anggaran'),'kode_cabang'=>$data['kode_cabang']]);


        }

        if($response) {
            $ID = get_data('tbl_m_data_kantor','kode_cabang',$data['kode_cabang'])->row_array();
            if($ID):
                $data['id'] = $ID['id'];
            else:
                unset($data['id']);
            endif;

            $response = save_data('tbl_m_data_kantor',$data,post(':validation'));
        }
            
        render($response,'json');
    }
}       