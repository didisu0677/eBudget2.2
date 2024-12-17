<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_rekap_rkf extends BE_Controller {
	var $path       = 'transaction/budget_planner/kantor_pusat/';
    var $sub_menu   = 'transaction/budget_planner/sub_menu';
    var $controller = 'plan_rekap_rkf';
    var $detail_tahun = [];
	function __construct() {
		parent::__construct();
		$this->kode_anggaran  = user('kode_anggaran');
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran,
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
	}

	function index() {
		$data = cabang_divisi();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['controller'] = $this->controller;
        render($data,'view:'.$this->path.$this->controller.'/index');	
    }

    function data($kode_anggaran,$kode_cabang){
    	$anggaran 	= get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
    	if(post('status_group') == 1):
    		$cabang 	= get_data('tbl_m_cabang','id',$kode_cabang)->row();
    	else:
    		$cabang 	= get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();
    	endif;

    	$level = '';
    	for($i=1;$i<=4;$i++){ if($cabang->{'level'.$i} == $cabang->id){ $level = 'level'.$i; } }
    	
    	$ls_cabang = get_data('tbl_m_cabang',[
    		'where'	 => [
    			'is_active' => '1',
    			''.$level 	=> $cabang->id
    		],
    	])->result_array();



    	$coa = get_data('tbl_m_biaya_rkf a',[
    		'select' => 'distinct a.coa,b.glwdes,b.glwsbi,b.glwnob',
    		'join'	 => 'tbl_m_coa b on b.glwnco = a.coa',
    		'where'	 => 'a.is_active = 1',
    	])->result();
    	$arr_coa = [];
    	foreach ($coa as $k => $v) {
    		if(!in_array($v->coa, $arr_coa)) array_push($arr_coa, $v->coa);
    	}
    	$ls_data = [];
    	$ls_cabang2 = [];
    	foreach ($ls_cabang as $k => $v) {
    		$level = '';
    		for($i=1;$i<=4;$i++){ if($v['level'.$i] == $v['id']){ $level = 'level'.$i; } }
    		if($v['id'] == $cabang->id or $v['parent_id'] == $cabang->id):
    			$ls_cabang2[] = $v;
    			$ls_data[$v['kode_cabang']] = $this->data_query($v['id'],$level,$anggaran,$arr_coa);
    		endif;
    	}
    	$ls_cabang = $ls_cabang2;

    	$data['coa'] 		= $this->list_coa();
    	$data['ls_cabang']	= $ls_cabang;
    	$data['ls_data']	= $ls_data;
    	$data['anggaran']	= $anggaran;
    	$data['detail_tahun'] = $this->detail_tahun;

    	$view = $this->load->view($this->path.$this->controller.'/table',$data,true);

    	render([
    		'view' => $view,
    	],'json');
    }

    private function data_query($id,$level,$anggaran,$arr_coa){
    	$coa = " a.coa != '0'";
    	if(count($arr_coa)>0):
    		$coa = " a.coa in (".implode(",", $arr_coa).")";
    	endif;
    	$query = "
    		select 
    			Z.coa,
    			SUM(Z.B_01) AS B_01,
				SUM(Z.B_02) AS B_02,
				SUM(Z.B_03) AS B_03,
				SUM(Z.B_04) AS B_04,
				SUM(Z.B_05) AS B_05,
				SUM(Z.B_06) AS B_06,
				SUM(Z.B_07) AS B_07,
				SUM(Z.B_08) AS B_08,
				SUM(Z.B_09) AS B_09,
				SUM(Z.B_10) AS B_10,
				SUM(Z.B_11) AS B_11,
				SUM(Z.B_12) AS B_12
    		from (
	    		SELECT
					'input_rkf' AS tipe, 
					a.coa,
					SUM(a.T_01) AS B_01,
					SUM(a.T_02) AS B_02,
					SUM(a.T_03) AS B_03,
					SUM(a.T_04) AS B_04,
					SUM(a.T_05) AS B_05,
					SUM(a.T_06) AS B_06,
					SUM(a.T_07) AS B_07,
					SUM(a.T_08) AS B_08,
					SUM(a.T_09) AS B_09,
					SUM(a.T_10) AS B_10,
					SUM(a.T_11) AS B_11,
					SUM(a.T_12) AS B_12
				FROM tbl_input_rkf a
				JOIN tbl_m_cabang b ON a.kode_cabang = b.kode_cabang
				WHERE b.".$level." = '".$id."' AND a.kode_anggaran = '".$anggaran->kode_anggaran."' AND a.anggaran = 1 AND ".$coa."
				GROUP BY a.coa

				union all

				SELECT
					'divisi_rutin' AS tipe, 
					a.coa,
					SUM(a.T_01) AS B_01,
					SUM(a.T_02) AS B_02,
					SUM(a.T_03) AS B_03,
					SUM(a.T_04) AS B_04,
					SUM(a.T_05) AS B_05,
					SUM(a.T_06) AS B_06,
					SUM(a.T_07) AS B_07,
					SUM(a.T_08) AS B_08,
					SUM(a.T_09) AS B_09,
					SUM(a.T_10) AS B_10,
					SUM(a.T_11) AS B_11,
					SUM(a.T_12) AS B_12
				FROM tbl_divisi_rutin a
				JOIN tbl_m_cabang b ON a.kode_cabang = b.kode_cabang
				WHERE b.".$level." = '".$id."' AND a.kode_anggaran = '".$anggaran->kode_anggaran."' AND ".$coa."
				GROUP BY a.coa

			) as Z group by Z.coa
		";
    	$result = $this->db->query($query)->result_array();
		return $result;
    }

    // mengambil coa kelapa 5
    private function list_coa(){
    	$arr_not_in = ['5100000','5810000','5811011','59999'];
    	$coa = get_data('tbl_m_coa',[
    		'select' => 'glwnco,glwsbi,glwdes,glwnob,level0,level1,level2,level3,level4,level5',
    		'where'	 => "is_active = 1 and glwnco like '5%' and glwnco not in (".implode(",", $arr_not_in).")",
    		'order_by' => 'urutan'
    	])->result();

    	$data = [];
        foreach ($coa as $k => $v) {
   
            // center
            if(!$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $h = $v;
                $data['coa'][] = $h;
            endif;

            // level 1
            if($v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $h = $v;
                $data['coa0'][$v->level1][] = $h;
            endif;

            // level 2
            if(!$v->level1 && $v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $h = $v;
                $data['coa1'][$v->level2][] = $h;
            endif;

            // level 3
            if(!$v->level1 && !$v->level2 && $v->level3 && !$v->level4 && !$v->level5):
                $h = $v;
                $data['coa2'][$v->level3][] = $h;
            endif;

            // level 4
            if(!$v->level1 && !$v->level2 && !$v->level3 && $v->level4 && !$v->level5):
                $h = $v;
                $data['coa3'][$v->level4][] = $h;
            endif;

            // level 5
            if(!$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && $v->level5):
                $h = $v;
                $data['coa4'][$v->level5][] = $h;
            endif;
        }
        return $data;
    }


    function export(){
        ini_set('memory_limit', '-1');
        
        $kode_anggaran_txt  = post('kode_anggaran_txt');
        $kode_cabang_txt    = post('kode_cabang_txt');

        $neraca_header = json_decode(post('neraca_header'));
        $neraca = json_decode(post('neraca'));

        $data = [];
        foreach ($neraca as $k => $v) {
            if(count($v)>2):
               if($k == 0):
                    $data[$k] = $v;
                else:
                    $detail = [
                        $v[0],
                        $v[1],
                        $v[2],
                        $v[3],
                    ];
                    foreach ($v as $k2 => $v2) {
                        if($k2>3):
                            if(strlen($v2)>0):
                                $v2 = (float) filter_money($v2);
                            endif;
                            $detail[] = $v2;
                        endif;
                    }
                    $data[$k] = $detail;
                endif;
            else:
                $data[$k] = [];
                for($i=1;$i<=count($neraca_header[0]);$i++){
                    $data[$k][] = '';
                }
            endif;
        }

        $config[] = [
            'title' => 'Budget Nett Neraca',
            'header' => $neraca_header[0],
            'data'  => $data,
        ];

        // render($config,'json');exit();
        
        $this->load->library('simpleexcel',$config);
        $filename = 'Rekap_Biaya_Divisi_'.str_replace(' ', '_', $kode_anggaran_txt).'_'.str_replace(' ', '_', $kode_cabang_txt).'_'.date('YmdHis');
        $this->simpleexcel->filename($filename);
        $this->simpleexcel->export();
        // render($neraca_header,'json');
    }

}