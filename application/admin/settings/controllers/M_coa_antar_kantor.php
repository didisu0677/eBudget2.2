<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_coa_antar_kantor extends BE_Controller {
	var $path       = 'settings/m_coa_antar_kantor/';
	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$a = get_access('m_coa_antar_kantor');

		$header = get_data('tbl_m_antar_kantor',['select' => 'distinct coa'])->result_array();
		$data 	= [];
		foreach ($header as $k => $v) {
			$coa = $v['coa'];
			$data['data'][$coa] = get_data('tbl_m_antar_kantor a',[
				'select'	=> 'a.id,a.coa,b.glwdes as coa_name,a.coa_lawan,c.glwdes as coa_lawan_name,a.is_active',
				'join' => [
					'tbl_m_coa b on b.glwnco = a.coa',
					'tbl_m_coa c on c.glwnco = a.coa_lawan',
				],
				'where' => "a.coa = '$coa'",
			])->result();
		}
		$data['header'] = $header;
		$data['access'] = $a;
		$response   = array(
            'table'         => $this->load->view($this->path.'table',$data,true),
            'access_edit'   => $a['access_edit'],
        );
        render($response,'json');
	}

	function get_data() {
		$dt = get_data('tbl_m_antar_kantor','id',post('id'))->row();
		$list = get_data('tbl_m_antar_kantor',[
            'where' => [
                'coa'  => $dt->coa
            ],
        ])->result_array();

		$data['detail'] = $dt;
        $data['data'] = $list;
        render($data,'json');
	}

	function save() {
		$coa    	= post('coa');
        $dt_index   = post('dt_index');
        $status     = false;
        $id 		= post('id');
		if(is_array($id)):
			$response = save_data('tbl_m_antar_kantor',post());
			render($response,'json');exit();
		endif;
        
        if($coa):
        	foreach ($coa as $i => $h) {
        		$status      = true;
                $arrID = array();
                $key = $dt_index[$i];
                $dt_id  = post('dt_id'.$key);
                $coa_lawan    = post('coa_lawan'.$key);
                $c = [];
                if(post('id')):
                    $dt = get_data('tbl_m_antar_kantor','id',post('id'))->row();
                endif;
                foreach($dt_id as $k => $v) {
                    $c = [
                        'coa' => $coa[$i],
                        'coa_lawan' => $coa_lawan[$k]
                    ];

                    $cek        = get_data('tbl_m_antar_kantor',[
                        'where'         => [
                            'id'              => $dt_id[$k]
                        ],
                    ])->row();

                    
                    if(!isset($cek->id)) {
                    	$c['is_active'] = 1;
                    	$c['create_at'] = date("Y-m-d H:i:s");
                    	$c['create_by'] = user('username');
                        $id = insert_data('tbl_m_antar_kantor',$c);
                    }else{
                        $id = $dt_id[$k];
                        $c['update_at'] = date("Y-m-d H:i:s");
                    	$c['update_by'] = user('username');
                        update_data('tbl_m_antar_kantor',$c,[
                            'id'              => $dt_id[$k]
                        ]);
                    }

                    array_push($arrID, $id);
                }

                if(count($arrID)>0 && post('id')):
                    delete_data('tbl_m_antar_kantor',['id not'=>$arrID,'coa' => $dt->coa]);
                endif;
        	}
        endif;

        if(!$status && post('id')):
            $dt = get_data('tbl_m_antar_kantor','id',post('id'))->row();
            delete_data('tbl_m_antar_kantor',['coa' => $dt->coa]);
        endif;

        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan')
        ],'json');
        
	}

	function delete() {
		$response = destroy_data('tbl_m_antar_kantor','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['coa' => 'coa','coa_lawan' => 'coa_lawan','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_m_coa_antar_kantor',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['coa','coa_lawan','is_active'];
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
					$save = insert_data('tbl_m_antar_kantor',$data);
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
		$arr = ['coa' => 'Coa','coa_lawan' => 'Coa Lawan','is_active' => 'Aktif'];
		$data = get_data('tbl_m_antar_kantor')->result_array();
		$config = [
			'title' => 'data_m_coa_antar_kantor',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}