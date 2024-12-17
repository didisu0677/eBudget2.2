<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kategori_kantor extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$data = data_serverside();
		foreach ($data['data'] as $k => $v) {
			$val = $data['data'][$k]['tbl_kategori_kantor_harga'];
			$data['data'][$k]['tbl_kategori_kantor_harga'] = view_report($val);
		}
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_kategori_kantor','id',post('id'))->row_array();
		$data['harga'] = (string) view_report($data['harga']);
		render($data,'json');
	}

	function save() {
		$data = post();

		$harga = post('harga');
		$harga = str_replace('.', '', $harga);
		$harga = (string) insert_view_report($harga);
		$data['harga'] = $harga;

		$response = save_data('tbl_kategori_kantor',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_kategori_kantor','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kategori' => 'kategori','harga' => 'harga','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_kategori_kantor',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kategori','harga','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);

					$harga = insert_view_report($data['harga']);
					$data['harga'] = $harga;

					$data['create_at'] = date('Y-m-d H:i:s');
					$data['create_by'] = user('nama');
					$save = insert_data('tbl_kategori_kantor',$data);
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
		$arr = ['kategori' => 'Kategori','harga' => '-cPerkiraan Biaya','is_active' => 'Aktif'];
		$data = get_data('tbl_kategori_kantor')->result_array();
		foreach ($data as $k => $v) {
			$data[$k]['harga'] = view_report($v['harga']);
		}
		$config = [
			'title' => 'data_kategori_kantor',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}