<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_coa extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['opt_cabang'] = get_data('tbl_m_coa','is_active',1)->result_array();
		render($data);
	}

	function data($tipe="") {
		if($tipe == 'sortable') {
			$data = [];
			if(post('tipe') == 1):
				$neraca = get_data('tbl_m_coa',[
					'where' 		=> 'tipe = 1',
					'order_by'		=> 'urutan'
				])->result();
				$neraca = coa_neraca($neraca);
				$data['neraca'] 	= $neraca;
			elseif(post('tipe') == 2):
				$labarugi = get_data('tbl_m_coa',[
					'where' 		=> 'tipe = 2',
					'order_by'		=> 'urutan'
				])->result();
				$labarugi = coa_labarugi($labarugi);
				$data['labarugi'] 	= $labarugi;
			endif;
			$data['access'] 	= get_access('master_coa');
			$data['tipe']		= post('tipe');
			$response	= array(
				'content' => $this->load->view('settings/master_coa/sortable',$data,true)
			);
		} else {
			$neraca = get_data('tbl_m_coa',[
				'where' 		=> 'tipe = 1',
				'order_by'		=> 'urutan'
			])->result();
			$neraca = coa_neraca($neraca);

			$labarugi = get_data('tbl_m_coa',[
				'where' 		=> 'tipe = 2',
				'order_by'		=> 'urutan'
			])->result();
			$labarugi = coa_labarugi($labarugi);

			$data['access'] 	= get_access('master_coa');
			$data['neraca'] 	= $neraca;
			$data['labarugi'] 	= $labarugi;

			$option_tipe = '<option value="1">Neraca</option>';
			$option_tipe .= '<option value="2">Laba Rugi</option>';
			$response	= array(
				'table'			=> $this->load->view('settings/master_coa/table',$data,true),
				'option'		=> $this->load->view('settings/master_coa/option',$data,true),
				'option_tipe'	=> $option_tipe,
			);
		}

		render($response,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_coa','id',post('id'))->row_array();
		for ($i=0; $i <=5 ; $i++) { 
			if($data['level'.$i]):
				$data['level'] = get_data('tbl_m_coa',[
					'select' => 'id',
					'where'	 => [
						'glwnco' => $data['level'.$i]
					]
				])->row()->id;
			endif;
		}
		render($data,'json');
	}

	function save() {

		$data = post();
		if(!post('level') && !post('id')):
			$urutan = get_data('tbl_m_coa',['select' => 'urutan','where' => "level0 = '' and level1 = '' and level2 = '' and level3 = '' and level4 = '' and level5 = ''",'order_by' => 'id', 'sort' => 'DESC'])->row();
			if($urutan) $urutan = $urutan->urutan + 1; else $urutan = 1;
			$data['urutan'] = $urutan;
		elseif(post('level')):
			$parent = get_data('tbl_m_coa','id',post('level'))->row_array();
			if($parent):
				$level = '';
				for ($i=0; $i <= 5 ; $i++) {
					$data['level'.$i] = '';
					if($parent['level'.$i]):
						$level = $i+1;
					endif;
				}
				if(!$level && post('tipe') == 1) $level = 0; elseif(!$level) $level = 1;
				$data['level'.$level] = $parent['glwnco'];

				if(!post('id')):
					$urutan = get_data('tbl_m_coa',['select' => 'urutan','where' => "level".$level." = '".$parent['glwnco']."'",'order_by' => 'id', 'sort' => 'DESC'])->row();
					if($urutan) $urutan = $urutan->urutan + 1; else $urutan = 1;
					$data['urutan'] = $urutan;
				endif;
			endif;
		else:
			for ($i=0; $i <= 5 ; $i++) {
				$data['level'.$i] = '';
			}
		endif;
		$response = save_data('tbl_m_coa',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_coa','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['glwsbi' => 'glwsbi','glwnob' => 'glwnob','glwnco' => 'glwnco','level1' => 'level1','level2' => 'level2','level3' => 'level3','level4' => 'level4','level5' => 'level5','glwdes' => 'glwdes','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_master_coa',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['glwsbi','glwnob','glwnco','level1','level2','level3','level4','level5','glwdes','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		$urutan = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					if($data['glwnco']):
						$urutan++;
						$g = substr($data['glwnco'], 0,1);
						if(in_array($g, [1,2,3])):
							$data['tipe'] = 1;
						else:
							$data['tipe'] = 4;
						endif;
						$data['urutan']    = $urutan;
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data('tbl_m_coa',$data);
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
		$arr = ['glwsbi' => 'Glwsbi','glwnob' => 'Glwnob','glwcoa' => 'Glwcoa','glwnco' => 'Glwnco','glwdes' => 'Glwdes','is_active' => 'Aktif'];
		$data = get_data('tbl_m_coa')->result_array();
		$config = [
			'title' => 'data_master_coa',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function save_sortable($tipe) {
		$data = post('menuItem');
		update_data('tbl_m_coa',['urutan'=>0],'tipe',$tipe);
		foreach($data as $id => $parent_id) {
			$dataSaved = [];
			if(!$parent_id || $parent_id == null || $parent_id == 'null'):
				$urutan = get_data('tbl_m_coa',['select' => 'MAX(urutan) urutan','where' => "level0 = '' and level1 = '' and level2 = '' and level3 = '' and level4 = '' and level5 = ''"])->row();
				if($urutan) $urutan = $urutan->urutan + 1; else $urutan = 1;
				$dataSaved['urutan'] = $urutan;
			else:
				$parent = get_data('tbl_m_coa','glwnco',$parent_id)->row_array();
				if($parent):
					$level = '';
					for ($i=0; $i <= 5 ; $i++) {
						$dataSaved['level'.$i] = '';
						if($parent['level'.$i]):
							$level = $i+1;
						endif;
					}
					if(!$level && $parent['tipe'] == 1) $level = 0; elseif(!$level) $level = 1;
					$dataSaved['level'.$level] = $parent['glwnco'];

					$urutan = get_data('tbl_m_coa',['select' => 'MAX(urutan) urutan','where' => "level".$level." = '".$parent['glwnco']."'"])->row();
					if($urutan) $urutan = $urutan->urutan + 1; else $urutan = 1;
					$dataSaved['urutan'] = $urutan;
				endif;
			endif;
			if(count($dataSaved)>0):
				$save 		= update_data('tbl_m_coa',$dataSaved,'glwnco',$id);
			endif;
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_diperbaharui')
		],'json');
	}

}