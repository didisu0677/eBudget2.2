<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_aset_tak_guna extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$config['where']['kode_anggaran']   = user('kode_anggaran');
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_aset_tak_guna','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_m_aset_tak_guna',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_aset_tak_guna','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode_anggaran' => 'kode_anggaran','kode' => 'kode','kode_inventaris' => 'kode_inventaris','nama' => 'nama','persen' => 'persen','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_m_aset_tak_guna',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode_anggaran','kode','kode_inventaris','nama','persen','is_active'];
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
					$save = insert_data('tbl_m_aset_tak_guna',$data);
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
		$arr = ['kode_anggaran' => 'Kode Anggaran','kode' => 'Kode','kode_inventaris' => 'Kode Inventaris','nama' => 'Nama','persen' => 'Persen','is_active' => 'Aktif'];
		$data = get_data('tbl_m_aset_tak_guna')->result_array();
		$config = [
			'title' => 'data_m_aset_tak_guna',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}