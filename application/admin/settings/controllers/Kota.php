<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kota extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['opt_id_provinsi'] = get_data('provinsi','is_active',1)->result_array();
		render($data);
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('kota','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('kota',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('kota','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['id_provinsi' => 'id_provinsi','name' => 'name','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_kota',
			'header' => $arr,
		];
		$id_provinsi = get_data('provinsi',[
			'select' => 'id,name',
			'where' => 'is_active = 1'
		])->result_array();
		$config[] = [
			'title' => 'data_provinsi',
			'data' => $id_provinsi,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['id_provinsi','name','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		$u = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$check_name = get_data('kota','name',$data['name'])->row();
					if(isset($check_name->name)) {
						$id = $check_name->id;
						$data['update_at'] = date('Y-m-d H:i:s');
						$data['update_by'] = user('nama');
						$save = update_data('kota',$data,'id',$id);
						if($save) $u++;
					} else {
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data('kota',$data);
						if($save) $c++;
					}
				}
			}
		}
		$response = [
			'status' => 'success',
			'message' => $c.' '.lang('data_berhasil_disimpan').'. '.$u.' '.lang('data_berhasil_diperbaharui').'.'
		];
		@unlink($file);
		render($response,'json');
	}

	function export() {
		ini_set('memory_limit', '-1');
		$arr = ['id_provinsi_name' => 'Provinsi','name' => 'Nama Kota','is_active' => 'Aktif'];
		$data = get_data('kota',[
			'select' => 'kota.*,provinsi.name AS id_provinsi_name',
			'join' => [
				'provinsi on kota.id_provinsi = provinsi.id type left',
			]
		])->result_array();
		$config = [
			'title' => 'data_kota',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}