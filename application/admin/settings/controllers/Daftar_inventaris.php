<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_inventaris extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['opt_grup'] = get_data('tbl_grup_asetinventaris','is_active',1)->result_array();
		render($data);
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_kode_inventaris','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data = post();

		$grup = get_data('tbl_grup_asetinventaris','kode',post('grup'))->row();
		if(isset($grup->keterangan)) $data['nama_grup_aset'] = $grup->keterangan;

		$response = save_data('tbl_kode_inventaris',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_kode_inventaris','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode_inventaris' => 'kode_inventaris','nama_inventaris' => 'nama_inventaris','grup' => 'grup','nama_grup_aset' => 'nama_grup_aset','harga' => 'harga','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_daftar_inventaris',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode_inventaris','nama_inventaris','grup','nama_grup_aset','harga','is_active'];
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
					$save = insert_data('tbl_kode_inventaris',$data);
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
		$arr = ['kode_inventaris' => 'Kode Inventaris','nama_inventaris' => 'Nama Inventaris','grup' => 'Grup','nama_grup_aset' => 'Nama Grup Aset','harga' => 'harga','is_active' => 'Aktif'];
		$data = get_data('tbl_kode_inventaris')->result_array();
		$config = [
			'title' => 'data_daftar_inventaris',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}