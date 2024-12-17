<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tarif_kolektibilitas extends BE_Controller {

	var $path = 'settings/tarif_kolektibilitas/';
	function __construct() {
		parent::__construct();
	}

	function index() {
		$anggaran = get_data('tbl_tahun_anggaran',[
			'select'	=> 'kode_anggaran,keterangan',
			'where'		=> 'is_active = 1'
		])->result_array();
		$data['anggaran'] = $anggaran;
		render($data);
	}

	function data($kode_anggaran) {
		$list = get_data('tbl_m_tarif_kolektibilitas a',[
			'select' => 'a.*, b.grup,b.nama_produk_kredit as nama',
			'join' => 'tbl_produk_kredit b on a.coa = b.coa',
			'where' => "a.kode_anggaran = '$kode_anggaran'"
		])->result();

		$grup = get_data('tbl_produk_kredit a',[
			'select' => 'b.glwdes as group_name, a.grup,a.is_active',
			'join' => 'tbl_m_coa b on a.grup = b.glwnco',
			'group_by' => 'a.grup'
		])->result();
		
		$view = '';
		$a = get_access('tarif_kolektibilitas');
		$data['data'] = $list;
		$data['akses']= $a;
		foreach ($grup as $k => $v) {
			$data['grup'] = $v->grup;
			$data['grup_name'] = $v->group_name;
			$view  .= $this->load->view($this->path.'table',$data,true);
		}

		render(['table' => $view],'json');
		
	}

	function get_data() {
		$data = get_data('tbl_m_tarif_kolektibilitas','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data = post();
		$id 	= post('id');
		if(is_array($id)):
			$response = save_data('tbl_m_tarif_kolektibilitas',post());
			render($response,'json');exit();
		endif;
		$anggaran = get_data('tbl_tahun_anggaran', 'kode_anggaran',$data['kode_anggaran'])->row();
		$data['keterangan_anggaran'] = $anggaran->keterangan;
		$data['tahun']	= $anggaran->tahun_anggaran;
		$response = save_data('tbl_m_tarif_kolektibilitas',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_tarif_kolektibilitas','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = [
				'coa' 		=> lang('kode_akun'),
				'kol_1' => lang('kol_1'),'kol_2' => lang('kol_2'),lang('kol_3') => lang('kol_3'),
				'kol_4' => lang('kol_4'),'kol_5' => lang('kol_5')];
		$config[] = [
			'title' => 'template_import_tarif_kolektibilitas',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['coa','kol_1','kol_2','kol_3','kol_4','kol_5'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		$u = 0;
		$anggaran = get_data('tbl_tahun_anggaran', 'kode_anggaran' ,post('kode_anggaran'))->row();
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$data['kode_anggaran'] = $anggaran->kode_anggaran;
					$data['tahun'] = $anggaran->tahun_anggaran;
					$check_coa = get_data('tbl_m_tarif_kolektibilitas','coa',$data['coa'])->row();
					if(isset($check_coa->coa)) {
						$id = $check_coa->id;
						$data['update_at'] = date('Y-m-d H:i:s');
						$data['update_by'] = user('nama');
						$save = update_data('tbl_m_tarif_kolektibilitas',$data,'id',$id);
						if($save) $u++;
					} else {
						$data['keterangan_anggaran'] = $anggaran->keterangan;
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$data['is_active'] = 1;
						$save = insert_data('tbl_m_tarif_kolektibilitas',$data);
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
		$arr = ['coa' => lang('coa'),'kol_1' => lang('kol_1'),'kol_2' => lang('kol_2'),'kol_3' => lang('kol_3'),'kol_4' => lang('kol_4'),'kol_5' => lang('kol_5')];
		$data = get_data('tbl_m_tarif_kolektibilitas')->result_array();
		$config = [
			'title' => 'data_tarif_kolektibilitas',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function opt_coa(){
		$item = '';
		$data = get_data('tbl_produk_kredit a',[
			'select' => 'b.glwdes as group_name,a.grup, a.coa,a.nama_produk_kredit as nama',
			'where'	=> 'a.is_active = 1',
			'join' => 'tbl_m_coa b on a.grup = b.glwnco',
		])->result();
		$grup = get_data('tbl_produk_kredit a',[
			'select' => 'b.glwdes as group_name, a.grup',
			'where'	=> 'a.is_active = 1',
			'join' => 'tbl_m_coa b on a.grup = b.glwnco',
			'group_by' => 'a.grup'
		])->result();
		foreach ($grup as $k => $v) {
			$item .= '<optgroup label="'.remove_spaces($v->group_name).'">';
			foreach ($data as $k2 => $v2) {
				if($v2->grup == $v->grup):
					$item .= '<option value="'.$v2->coa.'">'.remove_spaces($v2->nama).'</option>';
				endif;
			}
			$item .= '</optgroup>';
		}
		render(['coa'=> $item],'json');
	}

}