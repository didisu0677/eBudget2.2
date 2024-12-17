<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_monly_performance_nilai extends BE_Controller {

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
		$data = get_data('tbl_m_monly_performance_nilai','id',post('id'))->row_array();
		$data['dir_upload']	= base_url().dir_upload('m_monly_performance_nilai');
		render($data,'json');
	}

	function save() {
		$data 			= post();
		foreach ($data as $k => $v) {
			$data[$k] = html_entity_decode($v);
		}
		$response = save_data('tbl_m_monly_performance_nilai',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_monly_performance_nilai','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['nama' => 'nama','formula' => 'formula','warna' => 'Warna','keterangan' => 'keterangan','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_m_monly_performance_nilai',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['nama','formula','warna','keterangan','is_active'];
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
					$save = insert_data('tbl_m_monly_performance_nilai',$data);
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
		$arr = ['nama' => 'Nama','formula' => 'Formula','warna' => 'Warna','keterangan' => 'Keterangan','is_active' => 'Aktif'];
		$data = get_data('tbl_m_monly_performance_nilai')->result_array();
		$config = [
			'title' => 'data_m_monly_performance_nilai',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}