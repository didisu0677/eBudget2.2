<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kolektibilitas extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'kolektabilitas/';

    var $detail_tahun;
    var $kode_anggaran;
    var $arr_sumber_data = array();
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
    }

    private  function check_sumber_data($sumber_data){
        $key = array_search($sumber_data, array_map(function($element){return $element->sumber_data;}, $this->detail_tahun));
        if(strlen($key)>0):
            array_push($this->arr_sumber_data,$sumber_data);
        endif;
    }

    function index($p1="") {
        $a = get_access('kolektibilitas');
        $data = data_cabang();
        $data['path']               = $this->path;
        $data['detail_tahun']       = $this->detail_tahun;
        $data['access_additional']  = $a['access_additional'];
        render($data,'view:'.$this->path.$this->controller.'index');
    }

    private function get_coa($group){
        $ls = get_data('tbl_produk_kredit',"is_active = 1 and grup =  '$group'")->result();
        $data           = '<option value=""></option>';
        foreach($ls as $e2) {
            $data       .= '<option value="'.$e2->coa.'">'.remove_spaces($e2->nama_produk_kredit).'</option>';
        }
        return $data;
    }

    function save_perubahan(){
        $data   = json_decode(post('json'),true);
        foreach($data as $id => $record) {
            $arr = explode("-", $id);
            $dt_id = $arr[0]; 
            $table = $arr[1];
            $arrSaved = [];
            if($table == 'tbl_kolektibilitas_detail'):
                $get_data = get_data($table,[
                    'select' => 'changed',
                    'where'  => ['id' => $dt_id]
                ])->row_array();
                $changed = json_decode($get_data['changed']);
            endif;
            foreach ($record as $k => $v) {
                $value = str_replace('.', '', $v);
                $value = str_replace(',', '.', $value);
                $arrSaved[$k] = $value;
                if($table == 'tbl_kolektibilitas_detail'):
                    if(!in_array($k,$changed)):
                        array_push($changed,$k);
                        $arrSaved['changed'] = json_encode($changed);
                    endif;
                endif;

            }
            update_data($table,$arrSaved,'id',$dt_id); }
    }

    function save(){
        $kode_cabang    = post('kode_cabang');
        $ckode_anggaran = user('kode_anggaran');
        $anggaran       = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
        $tahun          = $anggaran->tahun_anggaran;
        $cabang         = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();

        $where = [
            'kode_anggaran'         => $ckode_anggaran,
            'tahun'                 => $anggaran->tahun_anggaran,
            'kode_cabang'           => $kode_cabang,
        ];

        $c = $where;
        $c['keterangan_anggaran'] = $anggaran->keterangan;
        $c['cabang'] = $cabang->nama_cabang;

        $this->validate($where);

        $this->save_kolektibilitas(1,$c,$where);
        $this->save_kolektibilitas(2,$c,$where);

        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan')
        ],'json');
    }

    private function save_kolektibilitas($tipe,$data,$where){
        $dt_id  = post('dt_id');
        $coa    = post('coa');
        if($tipe == 2){
            $dt_id    = post('dt_id_konsumtif');
            $coa      = post('coa_konsumtif');
        }

        $arrID = array();
        if($coa){
            foreach ($coa as $k => $v) {
                $c = $data;
                $c['username'] = user('username');
                $c['coa_produk_kredit'] = $coa[$k];
                $c['tipe'] = $tipe;

                $ck_data    = $where;
                $ck_data['id'] = $dt_id[$k];
                $ck_data['tipe'] = $tipe;
                $cek        = get_data('tbl_kolektibilitas',[
                    'where'         => $ck_data,
                ])->row();
                if(!isset($cek->id)) {
                    $c['create_by'] = user('username');
                    $c['create_at'] = date("Y-m-d H:i:s");
                    $id = insert_data('tbl_kolektibilitas',$c);
                }else{
                    $id = $dt_id[$k];
                    $c['update_by'] = user('username');
                    $c['update_at'] = date("Y-m-d H:i:s");
                    update_data('tbl_kolektibilitas',$c,$ck_data);
                }
                foreach ($this->arr_sumber_data as $sumber_data) {
                    $thn = $where['tahun'];
                    if($sumber_data == 2) { $thn -= 1; }
                    $c_data = [
                        'id_kolektibilitas' => $id,
                        'tipe'  => $tipe,
                        'sumber_data' => $sumber_data,
                    ];
                    $cek        = get_data('tbl_kolektibilitas_detail',[
                        'where'         => $c_data,
                    ])->row();
                    if(!isset($cek->id)) {
                        $c_data['tahun_core'] = $thn;
                        $c_data['create_by'] = user('username');
                        $c_data['create_at'] = date("Y-m-d H:i:s");
                        insert_data('tbl_kolektibilitas_detail',$c_data);
                    }
                }
                array_push($arrID, $id);
            }
        }

        if(post('id') && count($arrID)>0){
            $ck_data = $where;
            $ck_data['id not'] = $arrID;
            $ck_data['tipe']   = $tipe;
            $ck_data['default']= 0;
            delete_data('tbl_kolektibilitas',$ck_data);

            // $ck_data_detail = [
            //     'tipe' => $tipe,
            //     'id_kolektibilitas not' => $arrID,
            // ];
            // delete_data('tbl_kolektibilitas_detail',$ck_data_detail);
        }elseif(post('id')){
        	$check = get_data('tbl_kolektibilitas','id',post('id'))->row();
        	if(isset($check->tipe) && $check->tipe == $tipe):
        		$ck_data = $where;
	            $ck_data['tipe']   = $tipe;
	            $ck_data['default']= 0;
	            delete_data('tbl_kolektibilitas',$ck_data);
        	endif;
        }
    }

    function data($anggaran="", $cabang="", $tipe = 'table') {
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('kolektibilitas');
        $data['akses_ubah'] = $a['access_edit'];
        $data['current_cabang'] = $cabang;
        $data['title']  = ['NPL Total Kredit','NPL Kredit Produktif','NPL Kredit Konsumtif'];
        $data['title_'] = ['A. ','B. ','C. '];

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();    
        $select = '
                a.*,
                b.coa,
                b.nama_produk_kredit,
            ';
        $arr['select'] = $select;
        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }

        //table npl
        $arrWhereNpl = $arr;
        $arrWhereNpl['select'] = 'a.*';
        $listNpl = get_data('tbl_kolektibilitas_npl a',$arrWhereNpl)->result_array();
        // end table npl

        // table total kredit
        $arr2 = $arr;
        $arrWhere = $arr;
        $arrWhere['where']['default'] = 2;
        $arrWhere['select'] = 'a.*';
        $arrWhere['order_by']   = 'a.id,a.tipe';
        $listTotal = get_data('tbl_kolektibilitas a',$arrWhere)->result();
        $arrWhere['select'] = 'c.*,d.parent_id as parent_index,hasil1,hasil2,hasil3,hasil4,hasil5,hasil6,hasil7,hasil8,hasil9,hasil10,hasil11,hasil12';
        $arrWhere['join'][] = 'tbl_kolektibilitas_detail c on c.id_kolektibilitas = a.id';
        $arrWhere['join'][] = 'tbl_indek_besaran d on a.coa_produk_kredit = d.coa and a.kode_anggaran = d.kode_anggaran and a.kode_cabang = d.kode_cabang type LEFT';
        $listTotalDetail    = get_data('tbl_kolektibilitas a',$arrWhere)->result_array();
        // end total kredit

        $arr['join'][]     = 'tbl_produk_kredit b on b.coa = a.coa_produk_kredit';
        $arr['order_by']   = 'a.id,a.tipe';

        $listAll = get_data('tbl_kolektibilitas a',$arr)->result();

        $arrWhere = $arr;
        $arrWhere['where']['default'] = 0;
        $list        = get_data('tbl_kolektibilitas a',$arrWhere)->result();
        $arrWhere['where']['default'] = 1;
        $listDefault = get_data('tbl_kolektibilitas a',$arrWhere)->result();

        $s_join = 'd.tahun_core = c.tahun_core and d.kode_cabang = a.kode_cabang and d.kode_anggaran = a.kode_anggaran type left';
        $s_select = ',d.P_01,d.P_02,d.P_03,d.P_04,d.P_05,d.P_06,d.P_07,d.P_08,d.P_09,d.P_10,d.P_11,d.P_12';
        $select .= 'c.*';
        $arr['select'] = $select.$s_select;
        $arr['join'][]     = 'tbl_kolektibilitas_detail c on c.id_kolektibilitas = a.id';
        $arr['join'][]     = 'tbl_budget_plan_kredit d on d.coa = a.coa_produk_kredit and '.$s_join;
        $listDetail        = get_data('tbl_kolektibilitas a',$arr)->result_array();

        // table
        $data['listTotal']          = $listTotal;
        $data['listTotalDetail']    = $listTotalDetail;
        $data['listNpl']            = $listNpl;
        $data['listDefault']        = $listDefault;
        $data['listAll']            = $listAll;
        $data['list']   = $list;
        $data['detail'] = $listDetail;
        $data['detail_tahun'] = $this->detail_tahun;
        $data['anggaran'] = $anggaran;

        $data['tipe'] = 1;
        $view_detail  = $this->load->view($this->path.$this->controller.'detail',$data,true);
        $data['tipe'] = 2;
        $view_detail  .= $this->load->view($this->path.$this->controller.'detail',$data,true);

        $data['tipe'] = 1;
        $view_produktif = $this->load->view($this->path.$this->controller.'table',$data,true);
        $view_produktif_sum = $this->load->view($this->path.$this->controller.'detail_sum',$data,true);
        $data['tipe'] = 2;
        $view_konsumtif = $this->load->view($this->path.$this->controller.'table',$data,true);

        //view total kredit
        $arrWhere = $arr2;
        $arrWhere['where']['default'] = 2;
        $arrWhere['order_by']   = 'a.id,a.tipe';
        $arrWhere['select'] = 'c.*';
        $arrWhere['join'][] = 'tbl_kolektibilitas_detail c on c.id_kolektibilitas = a.id';
        $listTotalKredit    = get_data('tbl_kolektibilitas a',$arrWhere)->result();
        $data['listTotalKredit'] = $listTotalKredit;
        $view_total_kredit = $this->load->view($this->path.$this->controller.'total',$data,true);

        // chart
        $chart = $this->get_chart($data);

        $response   = array(
            'produktif'   => $view_produktif,
            'produktif_sum'   => $view_produktif_sum,
            'konsumtif'   => $view_konsumtif,
            'detail'      => $view_detail,
            'total_kredit'=> $view_total_kredit,
            'chart'       => $chart,
            'table_npl'   => $this->session->npl2,
            'edit'   => $a['access_edit'],
        );
        $response['opt_produktif'] = $this->get_coa('122502');
        $response['opt_konsumtif'] = $this->get_coa('122506');
       
        render($response,'json');
    }

    function get_data() {
        $dt = get_data('tbl_kolektibilitas','id',post('id'))->row();
        $list = get_data('tbl_kolektibilitas',[
            'where' => [
                'kode_anggaran' => $dt->kode_anggaran,    
                'tahun' => $dt->tahun,
                'kode_cabang' => $dt->kode_cabang,
                'tipe'  => $dt->tipe,
                'default'   => 0,
            ],
        ])->result_array();
        $data['detail'] = $dt;
        $data['data'] = $list;
        render($data,'json');

    }

    function get_chart($dt){
        $npl = $this->session->npl;
        foreach ($npl as $k => $v) {
            $npl[$k] = number_format($v,2,'.','');
        }
        $data = [
            'tipe_1' => [],
            'tipe_2' => [],
            'npl'    => $npl,
        ];
        foreach ($dt['listNpl'] as $k => $v) {
            $tipe = 'tipe_'.$v['tipe'];
            if($v['sumber_data'] == 3):
                for ($i=1; $i <= 12 ; $i++) { 
                    $v_field  = 'B_' . sprintf("%02d", $i);
                    $column = month_lang($i);
                    $data[$tipe][$column] = number_format($v[$v_field],2,'.','');
                }
            endif;
        }
        return $data;
    }

    function input_npl($anggaran="", $cabang=""){
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access('kolektibilitas');
        $data['akses_ubah'] = $a['access_edit'];
        $data['current_cabang'] = $cabang;

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
        $cabang         = get_data('tbl_m_cabang','kode_cabang',$ckode_cabang)->row();

        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }

        // check table tbl_kolektibilitas_npl
        $arrTipe = [1,2];
        foreach ($arrTipe as $k => $v) {
            $arrCk = $arr;
            $arrCk['where']['tipe'] = $v;
            $arrCk['where']['sumber_data'] = 3;
            $ck = get_data('tbl_kolektibilitas_npl a',$arrCk)->row();
            if(!$ck):
                $dtSaved = [
                    'kode_anggaran' => $ckode_anggaran,
                    'tahun'         => $anggaran->tahun_anggaran,
                    'tahun_core'    => $anggaran->tahun_anggaran,
                    'kode_cabang'   => $ckode_cabang,
                    'keterangan_anggaran'   => $anggaran->keterangan,
                    'cabang' => $cabang->nama_cabang,
                    'tipe' => $v,
                    'sumber_data' => 3,
                    'username'  => user('username'),
                    'create_at' => date("Y-m-d H:i:s"),
                    'create_by' => user('username'),
                ];
                $id_npl = insert_data('tbl_kolektibilitas_npl',$dtSaved);
                update_data('tbl_kolektibilitas_npl',['parent_id' => $id_npl],'id',$id_npl);
                $dtSaved['parent_id']   = $id_npl;
                $dtSaved['sumber_data'] = 2;
                $dtSaved['tahun_core']  = ($anggaran->tahun_anggaran-1);
                $id_npl = insert_data('tbl_kolektibilitas_npl',$dtSaved);
            endif;
        }
        //

        // check coa default
        $arrDefaultCoa = [
            '1454321-1-1',
            '1454327-1-2',
            '122502-2-1',
            '122506-2-2',
        ];
        foreach ($arrDefaultCoa as $k => $v) {
            $d_         = explode('-', $v);
            $coa        = $d_[0];
            $default    = $d_[1];
            $tipe       = $d_[2];

            $arrCk = $arr;
            $arrCk['where']['coa_produk_kredit'] = $coa;
            $arrCk['where']['default']           = $default;
            $arrCk['where']['tipe']              = $tipe;
            $ck = get_data('tbl_kolektibilitas a',$arrCk)->row();
            if(!$ck):
                $dtSaved = [
                    'kode_anggaran' => $ckode_anggaran,
                    'tahun'         => $anggaran->tahun_anggaran,
                    'kode_cabang'   => $ckode_cabang,
                    'keterangan_anggaran'   => $anggaran->keterangan,
                    'cabang' => $cabang->nama_cabang,
                    'tipe' => $tipe,
                    'default' => $default,
                    'coa_produk_kredit' => $coa,
                    'username'  => user('username'),
                    'create_at' => date("Y-m-d H:i:s"),
                    'create_by' => user('username'),
                ];
                $id_kolektibilitas = insert_data('tbl_kolektibilitas',$dtSaved);
                // insert detail
                foreach ($this->arr_sumber_data as $v2) {
                    $thn = $anggaran->tahun_anggaran;
                    if($v2 == 2){ $thn -= 1; }
                    $dtSavedDetail = [
                        'id_kolektibilitas' => $id_kolektibilitas,
                        'tahun_core'    => $thn,
                        'tipe'  => $tipe,
                        'sumber_data' => $v2,
                    ];
                    insert_data('tbl_kolektibilitas_detail',$dtSavedDetail);
                }
            endif;
        }
        // end check coa default

        $arr['order_by']   = 'a.tipe';
        $arr['where']['a.tipe']     = [1,2];
        $all = get_data('tbl_kolektibilitas_npl a',$arr)->result_array();
        $arr['where']['sumber_data'] = 3;
        $list = get_data('tbl_kolektibilitas_npl a',$arr)->result();
        $data['list'] = $list;
        $data['all']  = $all;
        $data['detail_tahun'] = $this->detail_tahun;

        $view = $this->load->view($this->path.$this->controller.'table_npl',$data,true);
        $response   = array(
            'table' => $view,
        );
       
        render($response,'json');
        
    }

    function validate($where){
        $dt_id  = post('dt_id');
        $coa    = post('coa');
        $status = true;
        $data   = [];
        if($coa):
            foreach ($coa as $k => $v) {
                $ck_data         = $where;
                $ck_data['coa_produk_kredit'] = $coa[$k];
                if($dt_id[$k]):
                    $ck_data['id != '] = $dt_id[$k];
                endif;
                $cek        = get_data('tbl_kolektibilitas',[
                    'where'         => $ck_data,
                ])->row();
                if(isset($cek->id)):
                    $get_coa = get_data('tbl_produk_kredit','coa',$coa[$k])->row();
                    $message = 'COA "'.$coa[$k].'" ';
                    if($get_coa):
                        $message = 'COA "'.$get_coa->coa.'-'.remove_spaces($get_coa->nama_produk_kredit).'" ';
                    endif;
                    render([
                        'status'    => 'info',
                        'message'   => $message.lang('sudah_ada'),
                    ],'json');
                    exit();
                endif;
            }
        endif;

        $dt_id    = post('dt_id_konsumtif');
        $coa      = post('coa_konsumtif');
        if($coa):
            foreach ($coa as $k => $v) {
                $ck_data         = $where;
                $ck_data['coa_produk_kredit'] = $coa[$k];
                if($dt_id[$k]):
                    $ck_data['id != '] = $dt_id[$k];
                endif;
                $cek        = get_data('tbl_kolektibilitas',[
                    'where'         => $ck_data,
                ])->row();
                if(isset($cek->id)):
                    $get_coa = get_data('tbl_produk_kredit','coa',$coa[$k])->row();
                    $message = 'COA "'.$coa[$k].'" ';
                    if($get_coa):
                        $message = 'COA "'.$get_coa->coa.'-'.remove_spaces($get_coa->nama_produk_kredit).'" ';
                    endif;
                    render([
                        'status'    => 'info',
                        'message'   => $message.lang('sudah_ada'),
                    ],'json');
                    exit();
                endif;
            }
        endif;
    }
}