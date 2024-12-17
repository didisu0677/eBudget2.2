<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Provinsi extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('provinsi','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('provinsi',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('provinsi','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['name' => 'name','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_provinsi',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['name','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		$u = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$check_name = get_data('provinsi','name',$data['name'])->row();
					if(isset($check_name->name)) {
						$id = $check_name->id;
						$data['update_at'] = date('Y-m-d H:i:s');
						$data['update_by'] = user('nama');
						$save = update_data('provinsi',$data,'id',$id);
						if($save) $u++;
					} else {
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data('provinsi',$data);
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
		$arr = ['name' => 'Nama Provinsi','is_active' => 'Aktif'];
		$data = get_data('provinsi')->result_array();
		$config = [
			'title' => 'data_provinsi',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}