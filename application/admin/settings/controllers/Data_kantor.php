<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_kantor extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('usulan_aset')
            ]
        ])->result();

        $kode_cabang          = [];
        foreach($cabang_user as $c) $kode_cabang[] = $c->kode_cabang;

        $cab = get_data('tbl_m_cabang','id',user('id_struktur'))->row();

        $x ='';
        for ($i = 1; $i <= 4; $i++) { 
            $field = 'level' . $i ;

            if($cab->id == $cab->$field) {
                $x = $field ; 
            }    
        }    

        $data['opt_cabang']            = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cab->id,
                'a.kode_cabang' => $kode_cabang
            ],
            'sort_by' => 'kode_cabang'
        ])->result_array();
		render($data);
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_data_kantor','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_m_data_kantor',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_data_kantor','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode_cabang' => 'kode_cabang','nama_pimpinan' => 'nama_pimpinan','no_hp' => 'no_hp','tgl_mulai_menjabat' => 'tgl_mulai_menjabat','nama_cp' => 'nama_cp','no_hp_cp' => 'no_hp_cp','email_Cp' => 'email_Cp','pemeriksa_kp' => 'pemeriksa_kp','no_hp_pemeriksa' => 'no_hp_pemeriksa','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_data_kantor',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode_cabang','nama_pimpinan','no_hp','tgl_mulai_menjabat','nama_cp','no_hp_cp','email_Cp','pemeriksa_kp','no_hp_pemeriksa','is_active'];
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
					$save = insert_data('tbl_m_data_kantor',$data);
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
		$arr = ['kode_cabang' => 'Kode Cabang','nama_pimpinan' => 'Nama Pimpinan','no_hp' => 'No Hp','tgl_mulai_menjabat' => '-dTgl Mulai Menjabat','nama_cp' => 'Nama Cp','no_hp_cp' => 'No Hp Cp','email_Cp' => 'Email Cp','pemeriksa_kp' => 'Pemeriksa Kp','no_hp_pemeriksa' => 'No Hp Pemeriksa','is_active' => 'Aktif'];
		$data = get_data('tbl_m_data_kantor')->result_array();
		$config = [
			'title' => 'data_data_kantor',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}