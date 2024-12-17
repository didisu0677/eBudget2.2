<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabang extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['opt_cabang'] = get_data('tbl_m_struktur_cabang','is_active',1)->result_array();
		render($data);
	}

	function sortable() {
		render();
	}

	function data($tipe = 'table') {

			$data['cabang'][0] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>0),'order_by' => 'urutan'))->result();
			foreach($data['cabang'][0] as $m0) {
				$data['cabang'][$m0->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m0->id),'order_by' => 'urutan'))->result();
				foreach($data['cabang'][$m0->id] as $m1) {
					$data['cabang'][$m1->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m1->id),'order_by' => 'urutan'))->result();
					foreach($data['cabang'][$m1->id] as $m2) {
						$data['cabang'][$m2->id] = get_data('tbl_m_cabang',array('where_array'=>array('parent_id'=>$m2->id),'order_by' => 'urutan'))->result();
					}
				}
			}
			if($tipe == 'sortable') {
				$response	= array(
					'content' => $this->load->view('settings/cabang/sortable',$data,true)
				);
			} else {
				$response	= array(
					'table'		=> $this->load->view('settings/cabang/table',$data,true),
					'option'	=> $this->load->view('settings/cabang/option',$data,true)
				);
			}

		render($response,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_cabang','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data = post();
		$struktur_cabang = get_data('tbl_m_struktur_cabang','id',$data['level_cabang'])->row();
		if(isset($struktur_cabang->struktur_cabang)){
			$data['struktur_cabang'] =$struktur_cabang->struktur_cabang;
		}

		$response = save_data('tbl_m_cabang',$data,post(':validation'));
		if($response['status'] == 'success') {

			$mn = get_data('tbl_m_cabang','id',$response['id'])->row_array();
			if($mn['parent_id'] == 0) {
				update_data('tbl_m_cabang',array('level1'=>$mn['id']),'id',$mn['id']);
			} else {
				$parent = get_data('tbl_m_cabang','id',$mn['parent_id'])->row_array();
				$data_update = array(
					'level1' => $parent['level1'],
					'level2' => $parent['level2'],
					'level3' => $parent['level3'],
					'level4' => $parent['level4']
				);
				if(!$parent['level2']) $data_update['level2'] = $mn['id'];
				else if(!$parent['level3']) $data_update['level3'] = $mn['id'];
				else if(!$parent['level4']) $data_update['level4'] = $mn['id'];
				$data_update['struktur_cabang'] = $struktur_cabang->struktur_cabang;
				update_data('tbl_m_cabang',$data_update,'id',$mn['id']);
			}
		}
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_cabang','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['parent_id' => 'parent_id','kode_cabang' => 'kode_cabang','nama_cabang' => 'nama_cabang','level' => 'level','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_cabang',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['parent_id','kode_cabang','nama_cabang','level','is_active'];
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
					$save = insert_data('tbl_m_cabang',$data);
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
		$arr = ['parent_id' => 'Parent Id','kode_cabang' => 'Kode Cabang','nama_cabang' => 'Nama Cabang','level' => 'Level','is_active' => 'Aktif'];
		$data = get_data('tbl_m_cabang')->result_array();
		$config = [
			'title' => 'data_cabang',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function save_sortable() {
		$data = post('menuItem');
		update_data('tbl_m_cabang',['urutan'=>0]);
		$urutan = 0;
		foreach($data as $id => $parent_id) {
			if(!$parent_id || $parent_id == null || $parent_id == 'null') $parent_id = 0;
			$urutan++;
			$save 		= update_data('tbl_m_cabang',['parent_id'=>$parent_id,'urutan'=>$urutan],'id',$id);
			if($save) {
				$mn = get_data('tbl_m_cabang','id',$id)->row_array();
				if($mn['parent_id'] == 0) {
					update_data('tbl_m_cabang',array('level1'=>$mn['id']),'id',$mn['id']);
				} else {
					$parent = get_data('tbl_m_cabang','id',$mn['parent_id'])->row_array();
					$data_update = array(
						'level1' => $parent['level1'],
						'level2' => $parent['level2'],
						'level3' => $parent['level3'],
						'level4' => $parent['level4']
					);
					if(!$parent['level2']) $data_update['level2'] = $mn['id'];
					else if(!$parent['level3']) $data_update['level3'] = $mn['id'];
					else if(!$parent['level4']) $data_update['level4'] = $mn['id'];
					update_data('tbl_m_cabang',$data_update,'id',$mn['id']);
					
				}
			}
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_diperbaharui')
		],'json');
	}

}