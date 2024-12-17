<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usulan_kantor_rekap extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$cabang = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row_array();
		
		$relokasi = '';
		if(isset($cabang['id'])):
			if('cabang pembantu' == strtolower($cabang['struktur_cabang'])):
                $relokasi = 'relokasi';
            endif;
		endif;

		$a  						= get_access('usulan_kantor_rekap');
		$data 						= data_cabang('usulan_kantor');
		$data['tahun'] 				= get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result();
        $data['access_additional']  = $a['access_additional'];
        $data['tahapan']			= get_data('tbl_tahapan_pengembangan','is_active',1)->result_array();
        $data['jenis_kantor']		= get_data('tbl_kategori_kantor','is_active',1)->result_array();
        $data['jenis_kantor_ket']	= get_data('tbl_kategori_kantor_keterangan','is_active',1)->result_array();
        $data['status_kantor']		= get_data('tbl_status_ket_kantor','is_active',1)->result_array();
        
        // tbl_status_jaringan_kantor
		$where_renc = ['is_active' => 1];
		if($relokasi):
			$where_renc['status_jaringan like'] = $relokasi;
		endif;
		$data['rencana']		= get_data('tbl_status_jaringan_kantor',['where' => $where_renc])->result_array();

		render($data);
	}

	function data(){
		$a  			= get_access('usulan_kantor_rekap');
		$kode_anggaran 	= post('kode_anggaran');
		$length_cabang 	= post('length_cabang');
		$cabang 		= post('cabang');
		$rencana 		= post('rencana');
		$tahapan 		= post('tahapan');
		$jenis_kantor 	= post('jenis_kantor');
		$keterangan 	= post('keterangan');
		$status_kantor 	= post('status_kantor');
		$export 		= post('export');

		$arr_kode_cabang = [];
		$dt_cabang = [];

		$anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();

		if($cabang):
			$arr_kode_cabang[] = $cabang;
		elseif($length_cabang<=0):
			$dt_cabang = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row_array();
		elseif(!$a['access_additional']):
			$dt_cabang = get_data('tbl_m_cabang','kode_cabang',user('kode_cabang'))->row_array();
		endif;
		if(isset($dt_cabang['id'])):
			$arr_kode_cabang[] = $dt_cabang['kode_cabang'];
			if('cabang induk' == strtolower($dt_cabang['struktur_cabang'])):
				$dt_capem = get_data('tbl_m_cabang','parent_id',$dt_cabang['parent_id'])->result();
				foreach ($dt_capem as $k => $v) {
					if(!in_array($v->kode_cabang, $arr_kode_cabang)) array_push($arr_kode_cabang, $v->kode_cabang);
				}
			endif;

		endif;

		$where['a.kode_anggaran'] = $kode_anggaran;
		if(count($arr_kode_cabang)>0):
			$where['a.kode_cabang'] = $arr_kode_cabang;
		endif;
		if($rencana) $where['a.id_rencana'] = $rencana;
		if($tahapan) $where['a.id_tahapan'] = $tahapan;
		if($jenis_kantor) $where['a.id_kategori_kantor'] = $jenis_kantor;
		if($keterangan) $where['a.id_keterangan'] = $keterangan;
		if($status_kantor) $where['a.id_status_kantor'] = $status_kantor;

		$list = get_data('tbl_rencana_pjaringan a',[
			'select' 	=> '
				a.*,
				b.name as provinsi,c.name as kota,d.name as kecamatan,e.nama as nama_keterangan,e.warna as warna_keterangan,
				f.nama_cabang,
			',
			'where' 	=> $where,
			'join'  	=> [
				'provinsi b on b.id = a.id_provinsi type left',
				'kota c on c.id = a.id_kota type left',
				'kecamatan d on d.id = a.id_kecamatan type left',
				'tbl_kategori_kantor_keterangan e on e.id = a.id_keterangan type left',
				'tbl_m_cabang f on f.kode_cabang = a.kode_cabang type left',
			],
			'order_by' => 'f.urutan,a.id'
		])->result_array();

		if($export):
			ini_set('memory_limit', '-1');
			$header = [lang('no'),lang('cabang_induk'),lang('cabang'),lang('rencana'),lang('tahapan'),lang('jenis_kantor'),lang('biaya_perkiraan').' ('.get_view_report().')',lang('nama_kantor'),lang('jadwal'),lang('status'),lang('kecamatan'),lang('penjelasan'),lang('keterangan')];
			$data = [];
			foreach ($list as $k => $v) {
				$h = [
					($k+1),
					$v['cabang_induk'],
					$v['nama_cabang'],
					$v['rencana_jarkan'],
					$v['tahapan_pengembangan'],
					$v['kategori_kantor'],
					view_report($v['harga']),
					$v['nama_kantor'],
					month_lang($v['jadwal']),
					$v['status_ket_kantor'],
					$v['kecamatan'].', '.$v['kota'],
					$v['penjelasan'],
					$v['nama_keterangan']
				];
				$data[] = $h;
			}

			$config[] = [
	            'title' => 'Rekap Jaringan Kantor',
	            'header' => $header,
	            'data'  => $data,
	        ];
	         $this->load->library('simpleexcel',$config);
	         $filename = 'rekap_jaringan_kantor_'.str_replace(' ', '_', $anggaran->keterangan).date('YmdHis');
	        $this->simpleexcel->filename($filename);
	        $this->simpleexcel->export();
		else:
			$data['data'] = $list;
			$response	= array(
	            'table'		=> $this->load->view('transaction/usulan_kantor_rekap/table',$data,true),
	        );
			render($response,'json');
		endif;
	}

}