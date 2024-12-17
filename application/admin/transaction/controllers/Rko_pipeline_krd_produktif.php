<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class rko_pipeline_krd_produktif extends BE_Controller {
    var $controller = 'rko_pipeline_krd_produktif';
    var $path       = 'transaction/rko_pipeline/';
    var $sub_menu   = 'transaction/rko_pipeline/sub_menu';
    var $tipe       = 4;
    var $detail_tahun;
    var $kode_anggaran;
    var $tahun_anggaran;
    var $arr_sumber_data = array();
    var $arrWeekOfMonth = array();
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->tahun_anggaran = user('tahun_anggaran');
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
        $this->arrWeekOfMonth = arrWeekOfMonth($this->tahun_anggaran);
    }
    private  function check_sumber_data($sumber_data){
        $key = array_search($sumber_data, array_map(function($element){return $element->sumber_data;}, $this->detail_tahun));
        if(strlen($key)>0):
            array_push($this->arr_sumber_data,$sumber_data);
        endif;
    }

    function index() {
        $data = data_cabang();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['detail_tahun']    = $this->detail_tahun;
        $data['arrWeekOfMonth']  = $this->arrWeekOfMonth;
        $data['contact_type']   = $this->create_option('tbl_m_rko_contact_type');
        $data['tipe_nasabah']   = $this->create_option('tbl_m_rko_tipe_nasabah');
        $data['tipe_dana']      = $this->create_option('tbl_m_rko_tipe_dana');
        $data['controller']     = $this->controller;
        $a  = get_access($this->controller);
        $data['access_additional']  = $a['access_additional'];
        render($data,'view:'.$this->path.$this->controller.'/index');
    }

    private function create_option($tbl){
        $dt = get_data($tbl,'is_active','1')->result();
        $item = '';
        foreach ($dt as $k => $v) {
            $item .= '<option value="'.$v->id.'">'.$v->nama.'</option>';
        }
        return $item;
    }

    function save(){
        $kode_cabang = post('kode_cabang');
        $ckode_anggaran = user('kode_anggaran');

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
        $cabang   = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row();
        $tahun    = $anggaran->tahun_anggaran;

        $dt_id          = post('dt_id');
        $keterangan     = post('keterangan');
        $contact_type   = post('contact_type');
        $tipe_nasabah   = post('tipe_nasabah');
        $tipe_dana      = post('tipe_dana');
        $cabangTxt      = post('cabang');
        $pic            = post('pic');
        $pelaksanaan    = post('pelaksanaan');
        $biaya          = post('biaya');

        $arrID = array();
        if($dt_id):
            foreach ($dt_id as $k => $v) {
                $c = [
                    'tipe'  => $this->tipe,
                    'kode_anggaran' => $ckode_anggaran,
                    'keterangan_anggaran' => $anggaran->keterangan,
                    'tahun'         => $anggaran->tahun_anggaran,
                    'kode_cabang'   => $kode_cabang,
                    'cabang'        => $cabang->nama_cabang,
                    'username'      => user('username'),
                    'keterangan'  => $keterangan[$k],
                    'id_rko_contact_type'   => $contact_type[$k],
                    'id_rko_tipe_nasabah'   => $tipe_nasabah[$k],
                    'id_rko_tipe_dana'      => $tipe_dana[$k],
                    'nama_cabang'           => $cabangTxt[$k],
                    'pic'           => $pic[$k],
                    'pelaksanaan'   => $pelaksanaan[$k],
                    'biaya'         => insert_view_report(checkInputNumber($biaya[$k])),
                ];
                $cek = get_data('tbl_rko_pipeline',[
                    'where'         => [
                        'kode_anggaran'   => $ckode_anggaran,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $tahun,
                        'tipe'            => $this->tipe,
                        'id' => $dt_id[$k],
                    ],
                ])->row();
               if(!isset($cek->id)) {
                    $dt_insert = insert_data('tbl_rko_pipeline',$c);
                    array_push($arrID, $dt_insert);
                }else{
                    update_data('tbl_rko_pipeline',$c,['kode_anggaran'   => $ckode_anggaran,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $tahun,
                        'tipe'            => $this->tipe,
                        'id' => $dt_id[$k]]);
                    array_push($arrID, $dt_id[$k]);
                }
            }
        endif;

        if(count($arrID)>0 && post('id')):
            delete_data('tbl_rko_pipeline',['kode_anggaran'=>$ckode_anggaran,'id not'=>$arrID,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun,'tipe' => $this->tipe]);
        elseif(post('id')):
            delete_data('tbl_rko_pipeline',['kode_anggaran'=>$ckode_anggaran,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun,'tipe' => $this->tipe]);
        endif;

        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan'),
        ],'json');
    }

    function save_perubahan() {       
        $data   = json_decode(post('json'),true);
        foreach($data as $id => $record) {          
            update_data('tbl_rko_pipeline',$record,'id',$id); }
    }

    function data($anggaran="", $cabang="", $tipe = 'table'){
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $a = get_access($this->controller);
        $access_edit    = false;
        $access_delete  = false;
        if($a['access_edit'] && $cabang == user('kode_cabang')):
            $access_edit = true;
        elseif($a['access_edit'] && $a['access_additional']):
            $access_edit = true;
        endif;
        if($a['access_delete'] && $cabang == user('kode_cabang')):
            $access_delete = true;
        elseif($a['access_delete'] && $a['access_additional']):
            $access_delete = true;
        endif;
        $data['access_edit'] = $access_edit;
        $data['access_delete'] = $access_delete;

        $arr = ['select'    => '
                    a.*,
                    b.nama as contact_type_name,
                    c.nama as tipe_nasabah_name,
                    d.nama as tipe_dana_name,
                ',];
        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }

        $arr['join'][] = 'tbl_m_rko_contact_type b on b.id = a.id_rko_contact_type';
        $arr['join'][] = 'tbl_m_rko_tipe_nasabah c on c.id = a.id_rko_tipe_nasabah';
        $arr['join'][] = 'tbl_m_rko_tipe_dana d on d.id = a.id_rko_tipe_dana';
        $arr['where']['tipe'] = $this->tipe;
        $list = get_data('tbl_rko_pipeline a',$arr)->result();
        $data['list']     = $list;
        $data['current_cabang'] = $cabang;
        $data['detail_tahun']    = $this->detail_tahun;
        $data['arrWeekOfMonth']  = $this->arrWeekOfMonth;
 
        $response   = array(
            'table' => $this->load->view($this->path.$this->controller.'/table',$data,true),
            'edit'  => $access_edit,
            'delete'=> $access_delete,
        );
       
        render($response,'json');
    }

    function save_checkbox(){
        $ID     = post('ID');
        $val    = post('val');

        $a = get_access($this->controller);
        $access_edit    = false;

        $d = explode('-', $ID);
        try {
            $id     = $d[1];
            $key    = $d[2];
            $row = get_data('tbl_rko_pipeline',[
                'select'    => 'kode_cabang,checkbox',
                'where'     => "id = '".$d[1]."' and tipe = '".$this->tipe."'"
            ])->row();
            if($a['access_edit'] && $row->kode_cabang == user('kode_cabang')):
                $access_edit = true;
            elseif($a['access_edit'] && $a['access_additional']):
                $access_edit = true;
            endif;
            if(!$access_edit):
                render(['status' => false, 'message' => lang('cannot_edit')],'json');
                exit();
            endif;

            $x = json_decode($row->checkbox,true);
            $x[$key] = $val;
            update_data('tbl_rko_pipeline',['checkbox' => json_encode($x)],'id',$id);

            render(['status' => true, 'message' => lang('data_berhasil_disimpan')],'json');
        } catch (Exception $e) {
            render(['status' => false, 'message' => lang('data_not_found')],'json');
        }
    }

    function delete() {
        $response = destroy_data('tbl_rko_pipeline',['id' => post('id'), 'tipe' => $this->tipe]);
        render($response,'json');
    }

    function get_data(){
        $d = get_data('tbl_rko_pipeline',[
            'where'         => [
                'id'    => post('id'),
                'tipe'  => $this->tipe
            ],
        ])->row();

        $list = get_data('tbl_rko_pipeline',[
            'where'         => [
                'kode_anggaran'   => $d->kode_anggaran,
                'kode_cabang'     => $d->kode_cabang,
                'tahun'           => $d->tahun,
                'tipe'            => $this->tipe,
            ]
        ])->result();

        foreach ($list as $k => $v) {
            $v->biaya = view_report($v->biaya);
        }

        render([
            'status'    => 'success',
            'data'      => $list,
            'detail'    => $d,
            'post'    => post(),
        ],'json');
    }

}