<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rko_menu extends BE_Controller {
	var $controller = 'rko_menu';
	var $path       = 'transaction/';
    var $tipe       = 1;
    var $kode_anggaran;
    var $tahun_anggaran;
    var $dt_anggaran;
	function __construct() {
		parent::__construct();
		$this->kode_anggaran  = user('kode_anggaran');
        $this->tahun_anggaran = user('tahun_anggaran');
	}

	function index() {
		$data = data_cabang('usulan_besaran');
        $data['path']     = $this->path;
        $data['controller']     	= $this->controller;
        $data['tahun_anggaran']     = $this->tahun_anggaran;
        $a  = get_access($this->controller);
        render($data,'view:'.$this->path.$this->controller.'/index');
	}

	function data($kode_anggaran,$cabang){
		$anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
		$this->dt_anggaran = $anggaran;
		// pipeline
		$data = [];
		$pipeline_total = 0;
		for ($i=1; $i <=6 ; $i++) { 
			${'pipeline'.$i} = $this->count('tbl_rko_pipeline',$kode_anggaran,$cabang,$i);
			$data['.pipeline-'.$i] = custom_format(${'pipeline'.$i});
			$pipeline_total += ${'pipeline'.$i};
		}
		$data['#pipeline .total'] = $pipeline_total;

		//pko
		$pko_total = 0;
		for ($i=1; $i <=7 ; $i++) { 
			${'pko'.$i} = $this->count('tbl_rko_pko',$kode_anggaran,$cabang,$i);
			$data['.pko-'.$i] = custom_format(${'pko'.$i});
			$pko_total += ${'pko'.$i};
		}
		$data['#pko .total'] = $pko_total;

		//pjk
		$pjk_menu  	= $this->pjk($cabang);
		$pjk_total 	= 0;
		$item 		= '';
		if(count($pjk_menu)>0):
			foreach ($pjk_menu as $v) {
				$item .= '<tr>';
				$item .= '<td>'.$v->rencana_jarkan.'</td>';
				$item .= '<td class="text-center">'.custom_format($v->total).'</td>';
				$item .= '</tr>';
				$pjk_total += $v->total;
			}
		endif;
		$data['#pjk tbody'] 	= $item;
		$data['#pjk .total'] 	= custom_format($pjk_total);

		//asset
		$asset_menu  	= $this->asset($cabang);
		$asset_total 	= 0;
		$item 			= '';
		if(count($asset_menu)>0):
			foreach ($asset_menu as $v) {
				$item .= '<tr>';
				$item .= '<td>'.$v->keterangan.'</td>';
				$item .= '<td class="text-center">'.custom_format($v->total).'</td>';
				$item .= '</tr>';
				$asset_total 	+= $v->total;
			}
		endif;
		$data['#asset tbody'] 	= $item;
		$data['#asset .total'] 	= custom_format($asset_total);

		$total = $pipeline_total + $pko_total + $pjk_total + $asset_total;
		$data['.cabang-info .total-data'] = custom_format($total);
		render($data,'json');
	}
	private function count($table,$kode_anggaran,$cabang,$tipe){
		return $this->db->count_all("$table where kode_anggaran = '$kode_anggaran' and kode_cabang = '$cabang' and tipe = '$tipe'");
	}

	private function pjk($kode_cabang){
		$menu = get_data('tbl_rencana_pjaringan a',[
    		'select' => 'a.id,a.rencana_jarkan,count(b.id) as total',
    		'join'	 => [
    			'tbl_rko_jaringan_kantor b on a.id = b.id_rencana_pjaringan type left'
    		],
    		'where'	 => [
    			'a.kode_cabang' 	=> $kode_cabang,
    			'a.kode_anggaran'	=> $this->dt_anggaran->kode_anggaran,
    			'a.tahun'			=> $this->dt_anggaran->tahun_anggaran,
    		],
    		'group_by' => 'a.id,a.rencana_jarkan'
    	])->result();
    	return $menu;
	}

	private function asset($kode_cabang){
		$menu = get_data('tbl_grup_asetinventaris a',[
    		'select' => 'a.id,a.keterangan,count(b.id) as total',
    		'join'	 => [
    			"tbl_rko_inventaris b on a.id = b.id_group_inventaris and b.kode_cabang = '$kode_cabang' and b.kode_anggaran = '".$this->dt_anggaran->kode_anggaran."' and b.tahun = '".$this->dt_anggaran->tahun_anggaran."' type left"
    		],
    		'where'	 => [
    			'a.is_active' 	=> 1,
    		],
    		'group_by' => 'a.id,a.keterangan'
    	])->result();

    	return $menu;
	}

}