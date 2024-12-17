<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_mac extends BE_Controller {
	var $table = 'tbl_m_mac';
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
		$data = get_data($this->table,'id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$dt_id  = post('dt_id');
		$coa 	= post('coa');
		$nama 	= post('nama');
		$id 	= post('id');
		if(is_array($id)):
			$response = save_data($this->table,post());
			render($response,'json');exit();
		endif;
		if($coa):
			foreach ($coa as $k => $v) {
                $ck_data['coa'] = $coa[$k];
                if($dt_id[$k]):
                    $ck_data['id != '] = $dt_id[$k];
                endif;
                $cek        = get_data($this->table,[
                    'where'         => $ck_data,
                ])->row_array();
                if(isset($cek['id'])):
                    $get_coa = get_data('tbl_m_coa','glwnco',$coa[$k])->row();
                    $message = 'COA "'.$coa[$k].'" ';
                    if($get_coa):
                        $message = 'COA "'.$get_coa->glwnco.'-'.remove_spaces($get_coa->glwdes).'" ';
                    endif;
                    render([
                        'status'    => 'info',
                        'message'   => $message.lang('sudah_ada'),
                    ],'json');
                    exit();
                endif;
            }
		else:
			render([
                'status'    => 'info',
                'message'   => 'tidak ada data yang dipilih '.json_encode(post()),
            ],'json');exit();
		endif;

		foreach ($coa as $k => $v) {
			$glwnco = $coa[$k];
			$id 	= $dt_id[$k];
			$ck_data = [];
			$ck_data['coa'] = $coa[$k];
		 	$cek        = get_data($this->table,[
                'where'         => $ck_data,
            ])->row_array();
            if(isset($cek['id'])):
            	$id = $cek['id'];
            endif;
			$data   = [
				'coa' 	=> $glwnco,
				'id'	=> $id,
				'nama'	=> $nama[$k],
			];
			save_data($this->table,$data);
		}
		
		render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan')
        ],'json');
	}

	function delete() {
		$response = destroy_data($this->table,'id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['coa' => 'coa','nama' => 'nama','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_m_mac',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['coa','nama','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		$u = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$check_coa = get_data($this->table,'coa',$data['coa'])->row();
					if(isset($check_coa->coa)) {
						$id = $check_coa->id;
						$data['update_at'] = date('Y-m-d H:i:s');
						$data['update_by'] = user('nama');
						$save = update_data($this->table,$data,'id',$id);
						if($save) $u++;
					} else {
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data($this->table,$data);
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
		$arr = ['coa' => 'COA','coa' => 'Nama','is_active' => 'Aktif'];
		$data = get_data($this->table)->result_array();
		$config = [
			'title' => 'data_m_mac',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}
}