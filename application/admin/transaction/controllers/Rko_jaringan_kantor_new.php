<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rko_jaringan_kantor_new extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data  		   = data_cabang('usulan_kantor');
		$data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result(); 

        $a  = get_access('rko_jaringan_kantor_new');
        $data['access_additional']  = $a['access_additional'];
        $data['access_edit']  = $a['access_edit'];
        render($data);
	}

	function data($anggaran="", $cabang="") {
		$anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();
		$arr            = [
            'select'	=> 'a.*,
            b.name as provinsi,c.name as kota,d.name as kecamatan,e.nama as nama_keterangan,e.warna as warna_keterangan,
            g.nama_cabang,
            ',
        ];

        if($anggaran) {
            $arr['where']['a.kode_anggaran']  = $anggaran->kode_anggaran;
        }
        
        if($cabang) {
            $arr['where']['a.kode_cabang']  = $cabang;
        }

        $arr['join'][] = 'provinsi b on b.id = a.id_provinsi type left';
        $arr['join'][] = 'kota c on c.id = a.id_kota type left';
        $arr['join'][] = 'kecamatan d on d.id = a.id_kecamatan type left';
        $arr['join'][] = 'tbl_kategori_kantor_keterangan e on e.id = a.id_keterangan type left';
        $arr['join'][] = 'tbl_m_cabang g on g.kode_cabang = a.kode_cabang type left';
        $data['data'] 	= get_data('tbl_rencana_pjaringan a',$arr)->result();

        if(post('export')):
        	$list = $data['data'];
        	ini_set('memory_limit', '-1');
			$header = [lang('no'),lang('cabang_induk'),lang('cabang'),lang('rencana'),lang('tahapan'),lang('jenis_kantor'),lang('biaya_perkiraan').' ('.get_view_report().')',lang('nama_kantor'),lang('jadwal'),lang('status'),lang('kecamatan'),lang('penjelasan'),lang('keterangan')];
			$data = [];
			foreach ($list as $k => $v) {
				$h = [
					($k+1),
					$v->cabang_induk,
					$v->nama_cabang,
					$v->rencana_jarkan,
					$v->tahapan_pengembangan,
					$v->kategori_kantor,
					view_report($v->harga),
					$v->nama_kantor,
					month_lang($v->jadwal),
					$v->status_ket_kantor,
					$v->kecamatan.', '.$v->kota,
					$v->penjelasan,
					$v->nama_keterangan
				];
				$data[] = $h;
			}
			$config[] = [
	            'title' => 'Jaringan Kantor',
	            'header' => $header,
	            'data'  => $data,
	        ];
	        $this->load->library('simpleexcel',$config);
	        $filename = 'jaringan_kantor_'.str_replace(' ', '_', $anggaran->keterangan).date('YmdHis');
	        $this->simpleexcel->filename($filename);
	        $this->simpleexcel->export();
        else:
        	$response	= array(
	            'table'		=> $this->load->view('transaction/rko_jaringan_kantor_new/table',$data,true),
	        );
		   
		    render($response,'json');
        endif;
	}

}