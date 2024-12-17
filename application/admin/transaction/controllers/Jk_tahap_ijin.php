<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jk_tahap_ijin extends BE_Controller {
	var $controller = 'jk_tahap_ijin';
	var $path       = 'transaction/rko_jaringan_kantor/';
    var $sub_menu   = 'transaction/rko_jaringan_kantor/sub_menu';
    var $tipe       = 2;
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
        $data['skala_program']   = $this->create_option('tbl_skala_program');
        $data['controller']     = $this->controller;
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
        $jenis_jaringan = post('jenis_jaringan');
        $nama_cabang    = post('nama_cabang');
        $pic            = post('pic');
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
                    'keterangan'    => $keterangan[$k],
                    'jenis_jaringan'   => $jenis_jaringan[$k],
                    'nama_cabang'   => $nama_cabang[$k],
                    'pic'           => $pic[$k],
                ];
                $cek = get_data('tbl_rko_jaringan_kantor',[
                    'where'         => [
                        'kode_anggaran'   => $ckode_anggaran,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $tahun,
                        'tipe'            => $this->tipe,
                        'id' => $dt_id[$k],
                    ],
                ])->row();
               if(!isset($cek->id)) {
                    $c['checkbox'] = '[]';
                    $dt_insert = insert_data('tbl_rko_jaringan_kantor',$c);
                    array_push($arrID, $dt_insert);
                }else{
                    update_data('tbl_rko_jaringan_kantor',$c,['kode_anggaran'   => $ckode_anggaran,
                        'kode_cabang'     => $kode_cabang,
                        'tahun'           => $tahun,
                        'tipe'            => $this->tipe,
                        'id' => $dt_id[$k]]);
                    array_push($arrID, $dt_id[$k]);
                }
            }
        endif;

        if(count($arrID)>0 && post('id')):
            delete_data('tbl_rko_jaringan_kantor',['kode_anggaran'=>$ckode_anggaran,'id not'=>$arrID,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun,'tipe' => $this->tipe]);
        elseif(post('id')):
            delete_data('tbl_rko_jaringan_kantor',['kode_anggaran'=>$ckode_anggaran,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun,'tipe' => $this->tipe]);
        endif;

        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan'),
        ],'json');
    }

    function save_perubahan() {       
        $data   = json_decode(post('json'),true);
        foreach($data as $id => $record) {          
            update_data('tbl_rko_jaringan_kantor',$record,'id',$id); }
    }

    function data($anggaran="", $cabang="", $tipe = 'table'){
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $akses = get_access('rko_pipeline_giro');
        $data['akses'] = $akses;

        $arr = ['select'    => '
                    a.*,
                ',];
        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }

        $arr['where']['tipe'] = $this->tipe;
        $list = get_data('tbl_rko_jaringan_kantor a',$arr)->result();
        $data['list']     = $list;
        $data['current_cabang'] = $cabang;
        $data['detail_tahun']    = $this->detail_tahun;
        $data['arrWeekOfMonth']  = $this->arrWeekOfMonth;
 
        $response   = array(
            'table' => $this->load->view($this->path.$this->controller.'/table',$data,true),
        );
       
        render($response,'json');
    }

    function save_checkbox(){
        $ID     = post('ID');
        $val    = post('val');

        $a      = get_access('rko_pipeline_giro');
        $edit   = $a['access_edit'];

        $d = explode('-', $ID);
        try {
            $id     = $d[1];
            $key    = $d[2];
            $row = get_data('tbl_rko_jaringan_kantor',[
                'select'    => 'kode_cabang,checkbox',
                'where'     => "id = '".$d[1]."' and tipe = '".$this->tipe."'"
            ])->row();
            if($row->kode_cabang != user('kode_cabang')):
                render(['status' => false, 'message' => lang('cannot_edit')],'json');
            endif;
            if($edit != 1):
                render(['status' => false, 'message' => lang('cannot_edit')],'json');
            endif;

            $x = json_decode($row->checkbox,true);
            $x[$key] = $val;
            update_data('tbl_rko_jaringan_kantor',['checkbox' => json_encode($x)],'id',$id);

            render(['status' => true, 'message' => lang('data_berhasil_disimpan')],'json');
        } catch (Exception $e) {
            render(['status' => false, 'message' => lang('data_not_found')],'json');
        }
    }

    function delete() {
        $response = destroy_data('tbl_rko_jaringan_kantor',['id' => post('id'), 'tipe' => $this->tipe]);
        render($response,'json');
    }

    function get_data(){
        $d = get_data('tbl_rko_jaringan_kantor',[
            'where'         => [
                'id'    => post('id'),
                'tipe'  => $this->tipe
            ],
        ])->row();

        $list = get_data('tbl_rko_jaringan_kantor',[
            'where'         => [
                'kode_anggaran'   => $d->kode_anggaran,
                'kode_cabang'     => $d->kode_cabang,
                'tahun'           => $d->tahun,
                'tipe'            => $this->tipe,
            ]
        ])->result();

        render([
            'status'    => 'success',
            'data'      => $list,
            'detail'    => $d,
            'post'    => post(),
        ],'json');
    }

}