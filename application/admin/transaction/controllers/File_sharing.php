<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_sharing extends BE_Controller {
    var $controller = 'File_sharing';
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

	function data() {
		$config				= [
			'access_view' 	=> true,
		];


		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_dokumen_file','id',post('id'))->row_array();
		$data['file'] 			= json_decode($data['file'],true);
		render($data,'json');
	}

	function detail($id='') {
		$data				= get_data('tbl_dokumen_file a',[
			'select' => 'a.*',
			'where'  => [			
				'a.id' => $id,
			],
		])->row_array();

		render($data,'layout:false');
	}

	
	function detail_file() {		
	//	$id = decode_id($id);
		$nomor_dokumen 	= get('nomor_dokumen');
		if($nomor_dokumen) {
			$data 	= get_data('tbl_dokumen_file','nomor_dokumen',$nomor_dokumen)->row_array();
			render($data,'layout:false access:true');
		} else echo lang('data_tidak_ada');		
	}
	

	function save() {

		$data = post();
		$last_file = [];
		if($data['id']) {
			$dt = get_data('tbl_dokumen_file','id',$data['id'])->row();
			if(isset($dt->id)) {
				if($dt->file != '') {
					$lf 	= json_decode($dt->file,true);
					foreach($lf as $l) {
						$last_file[$l] = $l;
					}
				}
			}
		}

		$file 						= post('file');
		$keterangan_file 			= post('keterangan_file');
		$filename 					= [];
		$dir 						= '';
		if(isset($file) && is_array($file)) {
			foreach($file as $k => $f) {
				if(strpos($f,'exist:') !== false) {
					$orig_file = str_replace('exist:','',$f);
					if(isset($last_file[$orig_file])) {
						unset($last_file[$orig_file]);
						$filename[$keterangan_file[$k]]	= $orig_file;
					}
				} else {
					if(file_exists($f)) {
						if(@copy($f, FCPATH . 'assets/uploads/dokumen_file/'.basename($f))) {
							$filename[$keterangan_file[$k]]	= basename($f);
							if(!$dir) $dir = str_replace(basename($f),'',$f);
						}
					}
				}
			}
		}
		if($dir) {
			delete_dir(FCPATH . $dir);
		}
		foreach($last_file as $lf) {
			@unlink(FCPATH . 'assets/uploads/dokumen_file/' . $lf);
		}
		$data['file']					= json_encode($filename);

		$response = save_data('tbl_dokumen_file',$data,post(':validation'));

		$data['is_file'] = '';
		if($response['status'] == 'success'){
			$nomor = get_data('tbl_dokumen_file','id',$response['id'])->row();
			if($nomor->file != '[]'){
				$data['is_file'] = $nomor->nomor_dokumen;
			}			
			update_data('tbl_dokumen_file',['is_file'=>$data['is_file']],'id',$nomor->id);
		}

		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_dokumen_file','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['nomor_dokumen' => 'nomor_dokumen','nama_dokumen' => 'nama_dokumen','file' => 'file','jumlah_file' => 'jumlah_file','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_dokumen_file',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['nomor_dokumen','nama_dokumen','file','jumlah_file','is_active'];
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
					$save = insert_data('tbl_dokumen_file',$data);
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
		$arr = ['nomor_dokumen' => 'Nomor Dokumen','nama_dokumen' => 'Nama Dokumen','file' => 'File','jumlah_file' => 'Jumlah File','is_active' => 'Aktif'];
		$data = get_data('tbl_dokumen_file')->result_array();
		$config = [
			'title' => 'data_dokumen_file',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}