<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kategori_kantor_keterangan extends BE_Controller {

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
		$data = get_data('tbl_kategori_kantor_keterangan','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_kategori_kantor_keterangan',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_kategori_kantor_keterangan','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['nama' => 'nama','warna' => 'warna','default' => 'default','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_kategori_kantor_keterangan',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['nama','warna','default','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		$u = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$check_nama = get_data('tbl_kategori_kantor_keterangan','nama',$data['nama'])->row();
					if(isset($check_nama->nama)) {
						$id = $check_nama->id;
						$data['update_at'] = date('Y-m-d H:i:s');
						$data['update_by'] = user('nama');
						$save = update_data('tbl_kategori_kantor_keterangan',$data,'id',$id);
						if($save) $u++;
					} else {
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data('tbl_kategori_kantor_keterangan',$data);
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
		$arr = ['nama' => 'Nama Keterangan','warna' => 'Warna','default' => 'Default','is_active' => 'Aktif'];
		$data = get_data('tbl_kategori_kantor_keterangan')->result_array();
		$config = [
			'title' => 'data_kategori_kantor_keterangan',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}