<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Online_dokumen extends BE_Controller {
    var $controller = 'Online_dokumen';
	function __construct() {
		parent::__construct();
	}

	function index() {
		$cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('file_sharing')
            ]
        ])->result();

        $kode_cabang          = [];
        foreach($cabang_user as $c) $kode_cabang[] = $c->kode_cabang;

        $cab = get_data('tbl_m_cabang','id',user('id_struktur'))->row();

        $id = user('id_struktur');
        if($id){
            $cab = get_data('tbl_m_cabang','id',$id)->row();
        }else{
            $id = user('kode_cabang');
            $cab = get_data('tbl_m_cabang','kode_cabang',$id)->row();
        }

        if(isset($cab->id)){ 
            $x ='';
            for ($i = 1; $i <= 4; $i++) { 
                $field = 'level' . $i ;

                if($cab->id == $cab->$field) {
                    $x = $field ; 
                }    
            }    
        }

		$data['cabang']            = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cab->id,
                'a.kode_cabang' => $kode_cabang
            ]
        ])->result_array();

        $data['cabang_input'] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
            ]
        ])->result_array();

        $access         = get_access($this->controller);
        $data['access_additional']  = $access['access_additional'];

		render($data);
	}

	function data($kode_cabang='') {
		$config				= [
			'access_view' 	=> true,
		];

	    if(menu()['access_edit'] == 0) {
		    if($kode_cabang && isset($kode_cabang) && !empty($kode_cabang) && ! is_null($kode_cabang)) {
		    	$config['where']['kode_cabang']	= $kode_cabang;	
		    }
		}

		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_dokumen_online','id',post('id'))->row_array();
		render($data,'json');
	}

	function detail($id='') {
		$data				= get_data('tbl_dokumen_online a',[
			'select' => 'a.*',
			'where'  => [			
				'a.id' => $id,
			],
		])->row_array();

		render($data,'layout:false');
	}

	function save() {
		$data = post();
		$cabang = get_data('tbl_m_cabang','kode_cabang',$data['kode_cabang'])->row();
		if(isset($cabang->nama_cabang)) $data['nama_cabang'] = $cabang->nama_cabang;

		$response = save_data('tbl_dokumen_online',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_dokumen_online','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode_cabang' => 'kode_cabang','nama_cabang' => 'nama_cabang','link' => 'link','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_online_dokumen',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode_cabang','nama_cabang','link','is_active'];
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
					$save = insert_data('tbl_dokumen_online',$data);
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
		$arr = ['kode_cabang' => 'Kode Cabang','nama_cabang' => 'Nama Cabang','link' => 'Link','is_active' => 'Aktif'];
		$data = get_data('tbl_dokumen_online')->result_array();
		$config = [
			'title' => 'data_online_dokumen',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}