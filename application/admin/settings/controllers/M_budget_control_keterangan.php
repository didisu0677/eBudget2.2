<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_budget_control_keterangan extends BE_Controller {

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
		$data = get_data('tbl_m_budget_control_keterangan','id',post('id'))->row_array();
		$data['dir_upload']	= base_url().dir_upload('m_budget_control_keterangan');
		render($data,'json');
	}

	function save() {
		$data 			= post();
		foreach ($data as $k => $v) {
			$data[$k] = html_entity_decode($v);
		}
		$response = save_data('tbl_m_budget_control_keterangan',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_budget_control_keterangan','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['nama' => 'nama','formula' => 'formula','keterangan' => 'keterangan','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_m_budget_control_keterangan',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['nama','formula','keterangan','is_active'];
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
					$save = insert_data('tbl_m_budget_control_keterangan',$data);
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
		$arr = ['nama' => 'Nama','formula' => 'Formula','keterangan' => 'Keterangan','is_active' => 'Aktif'];
		$data = get_data('tbl_m_budget_control_keterangan')->result_array();
		$config = [
			'title' => 'data_m_budget_control_keterangan',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}