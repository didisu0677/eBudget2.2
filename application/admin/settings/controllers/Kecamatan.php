<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kecamatan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['opt_id_provinsi'] = get_data('provinsi','is_active',1)->result_array();
		$data['opt_id_kota'] = [];
		render($data);
	}

	function data() {
		$config['join'][]  = 'provinsi provinsi on provinsi.id = kota.id_provinsi';
        $data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('kecamatan','id',post('id'))->row_array();
		$data['id_provinsi'] = '';
		$provinsi = get_data('kota',['select' => 'id_provinsi','where' => 'id = '.$data['id_kota']])->row();
		if($provinsi) $data['id_provinsi'] = $provinsi->id_provinsi;
		
		render($data,'json');
	}

	function save() {
		$response = save_data('kecamatan',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('kecamatan','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['id_kota' => 'id_kota','name' => 'name','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_kecamatan',
			'header' => $arr,
		];
		$id_kota = get_data('kota',[
			'select' => 'id,name',
			'where' => 'is_active = 1'
		])->result_array();
		$config[] = [
			'title' => 'data_kota',
			'data' => $id_kota,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['id_kota','name','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$data['create_at'] = date('Y-m-d H:i:s');
					$data['create_by'] = user('nama');
					$save = insert_data('kecamatan',$data);
					if($save) $c++;
				}
			}
		}
		$response = [
			'status' => 'success',
			'message' => $c.' '.lang('data_berhasil_disimpan').'.'
		];
		@unlink($file);
		render($response,'json');
	}

	function export() {
		ini_set('memory_limit', '-1');
		$arr = ['id_kota_name' => 'Kota','name' => 'Nama Kecamatan','is_active' => 'Aktif'];
		$data = get_data('kecamatan',[
			'select' => 'kecamatan.*,kota.name AS id_kota_name',
			'join' => [
				'kota on kecamatan.id_kota = kota.id type left',
			]
		])->result_array();
		$config = [
			'title' => 'data_kecamatan',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}