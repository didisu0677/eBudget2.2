<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_rincian_tabungan extends BE_Controller {
	var $arrNotInsert = array('Total');
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
		$data = get_data('tbl_m_rincian_tabungan','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_m_rincian_tabungan',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_rincian_tabungan','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['nama' => 'nama'];
		$config[] = [
			'title' => 'template_import_import_rincian_tabungan',
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
					if($data['nama'] && !in_array($data['nama'],$this->arrNotInsert)):
						$data['is_active'] = 1;
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data('tbl_m_rincian_tabungan',$data);
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
		$arr = ['nama' => 'Nama'];
		$data = get_data('tbl_m_rincian_tabungan')->result_array();
		$config = [
			'title' => 'data_import_rincian_tabungan',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}