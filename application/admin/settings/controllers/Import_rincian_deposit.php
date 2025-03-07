<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_rincian_deposit extends BE_Controller {

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
		$data = get_data('tbl_m_rincian_deposit','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_m_rincian_deposit',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_rincian_deposit','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['nama' => 'nama'];
		$config[] = [
			'title' => 'template_import_import_rincian_deposit',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['nama'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					if($data['nama']):
						$data['nama'] = 'SIMP. BERJANGKA RATE '.$data['nama'];
						$data['is_active'] = 1;
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data('tbl_m_rincian_deposit',$data);
						if($save) $c++;
					endif;
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
		$arr = ['nama' => 'Nama','is_active' => 'Aktif'];
		$data = get_data('tbl_m_rincian_deposit')->result_array();
		$config = [
			'title' => 'data_import_rincian_deposit',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}