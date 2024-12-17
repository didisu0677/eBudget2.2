<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_budget_control_group extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['tabel_option'] = $this->tabel_option();
		render($data);
	}

	private function tabel_option(){
		$data = array(
			array('value' => 'tbl_budget_plan_neraca', 'name' => 'Neraca Nett'),
			array('value' => 'tbl_labarugi', 'name' =>  'Laba Rugi Nett'),
		);
		return $data;
	}
	private function tabel_name(){
		return array(
			'tbl_budget_plan_neraca' => 'Neraca Nett',
			'tbl_labarugi' => 'Laba Rugi Nett',
		);
	}
	private function tabel_value(){
		return array(
			'neraca_nett' 		=> 'tbl_budget_plan_neraca',
			'laba_rugi_nett' 	=> 'tbl_labarugi',
		);
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_budget_control_group','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_m_budget_control_group',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_budget_control_group','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['coa' => 'COA Group','keterangan' => 'keterangan','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_m_budget_control_group',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['coa','keterangan','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$status = true;
					if($status):
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data('tbl_m_budget_control_group',$data);
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
		$arr = ['coa' => 'COA Group','keterangan' => 'Keterangan','is_active' => 'Aktif'];
		$data = get_data('tbl_m_budget_control_group')->result_array();
		foreach ($data as $k => $v) {
			$data[$k]['tabel'] = $this->tabel_name()[$v['tabel']];
		}
		$config = [
			'title' => 'data_m_budget_control_group',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}