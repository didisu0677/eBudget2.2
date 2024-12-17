<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rko_jaringan_kantor extends BE_Controller {

	var $controller = 'rko_jaringan_kantor';
    var $path       = 'transaction/';
    var $sub_menu   = 'transaction/rko_jaringan_kantor/sub_menu';
    var $detail_tahun;
    var $kode_anggaran;
    var $tahun_anggaran;
    var $arr_sumber_data = array();
    var $arrWeekOfMonth = array();
    var $dt_anggaran;
    var $dt_cabang;
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
		$a = get_access('rko_jaringan_kantor');
        $data = data_cabang();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['detail_tahun']    = $this->detail_tahun;
        $data['arrWeekOfMonth']  = $this->arrWeekOfMonth;
        $data['controller']     = $this->controller;
        $data['access_additional'] = $a['access_additional'];
        render($data,'view:'.$this->path.$this->controller.'/index');
    }

    function get_sub_menu($kode_anggaran,$kode_cabang){
    	$this->checkData($kode_anggaran,$kode_cabang);
    	$menu = get_data('tbl_rencana_pjaringan',[
    		'select' => 'id,rencana_jarkan',
    		'where'	 => [
    			'kode_cabang' 	=> $kode_cabang,
    			'kode_anggaran'	=> $kode_anggaran,
    			'tahun'			=> $this->dt_anggaran->tahun_anggaran,
    		]
    	])->result();
    	if(count($menu)<=0):
    		render(['status'=>false,'message' => lang('data_not_found')], 'json');
            exit();
    	endif;
    	$first = $menu[0];

    	$data['menu'] = $menu;

    	$view = $this->load->view($this->sub_menu,$data,true);

    	render([
    		'status' 	=> true,
    		'sub_menu'	=> $view,
    		'first'		=> $first->id,
    	],'json');
    }
    private function checkData($kode_anggaran,$kode_cabang){
    	$status = true;
    	$anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
    	if(!$anggaran):
    		render(['status'=>false,'message' => lang('data_not_found')], 'json');
            exit();
    	endif;

        $cabang   = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();
        if(!$cabang):
        	render(['status'=>false,'message' => lang('data_not_found')], 'json');
            exit();
        endif;
        $this->dt_anggaran 	= $anggaran;
        $this->dt_cabang 	= $cabang;
    }

    function data($anggaran="", $cabang="", $tipe = 'table'){
        $menu = menu();
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;

        $last_id = post('last_id');

        $a = get_access('rko_jaringan_kantor');
        $access_edit 	= false;
        $access_delete 	= false;
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
        $data['akses_ubah'] = $access_edit;
        $data['access_delete'] = $access_delete;

        $arr = ['select'    => '
                    a.*,
                ',];
        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
        }
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $ckode_cabang;
        }

        $arr['where']['id_rencana_pjaringan'] = $last_id;
        $list = get_data('tbl_rko_jaringan_kantor a',$arr)->result();
        $data['list']     = $list;
        $data['current_cabang'] = $cabang;
        $data['detail_tahun']    = $this->detail_tahun;
        $data['arrWeekOfMonth']  = $this->arrWeekOfMonth;
 
        $response   = array(
            'table' => $this->load->view($this->path.$this->controller.'/table',$data,true),
            'edit'	=> $access_edit,
            'delete'=> $access_delete,
        );
       
        render($response,'json');
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
        $id_rencana_pjaringan = post('id_rencana_pjaringan');
        $arrID = array();
        if($dt_id):
            foreach ($dt_id as $k => $v) {
                $c = [
                	'id_rencana_pjaringan' => $id_rencana_pjaringan,
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
                        'id_rencana_pjaringan' => $id_rencana_pjaringan,
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
                        'id_rencana_pjaringan' => $id_rencana_pjaringan,
                        'id' => $dt_id[$k]]);
                    array_push($arrID, $dt_id[$k]);
                }
            }
        endif;

        if(count($arrID)>0 && post('id')):
            delete_data('tbl_rko_jaringan_kantor',['kode_anggaran'=>$ckode_anggaran,'id not'=>$arrID,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun,'id_rencana_pjaringan' => $id_rencana_pjaringan]);
        elseif(post('id')):
            delete_data('tbl_rko_jaringan_kantor',['kode_anggaran'=>$ckode_anggaran,'kode_cabang'=>$kode_cabang,'tahun'=>$tahun,'id_rencana_pjaringan' => $id_rencana_pjaringan]);
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
    function save_checkbox(){
        $ID     = post('ID');
        $val    = post('val');
        $id_rencana_pjaringan = post('id_rencana_pjaringan');

        $a      = get_access('rko_jaringan_kantor');
        $access_edit = false;

        $d = explode('-', $ID);
        try {
            $id     = $d[1];
            $key    = $d[2];
            $row = get_data('tbl_rko_jaringan_kantor',[
                'select'    => 'kode_cabang,checkbox',
                'where'     => "id = '".$d[1]."' and id_rencana_pjaringan = '".$id_rencana_pjaringan."'"
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
            update_data('tbl_rko_jaringan_kantor',['checkbox' => json_encode($x)],'id',$id);

            render(['status' => true, 'message' => lang('data_berhasil_disimpan')],'json');
        } catch (Exception $e) {
            render(['status' => false, 'message' => lang('data_not_found')],'json');
        }
    }

    function delete() {
        $response = destroy_data('tbl_rko_jaringan_kantor',['id' => post('id')]);
        render($response,'json');
    }

    function get_data(){
        $d = get_data('tbl_rko_jaringan_kantor',[
            'where'         => [
                'id'    => post('id'),
            ],
        ])->row();

        $list = get_data('tbl_rko_jaringan_kantor',[
            'where'         => [
                'kode_anggaran'   => $d->kode_anggaran,
                'kode_cabang'     => $d->kode_cabang,
                'tahun'           => $d->tahun,
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