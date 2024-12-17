<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_jml_rekening extends BE_Controller {

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
		$data = get_data('tbl_import_jumlah_rekening','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_import_jumlah_rekening',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_import_jumlah_rekening','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['id_anggaran' => 'id_anggaran','kode_anggaran' => 'kode_anggaran','keterangan_anggaran' => 'keterangan_anggaran','no_coa' => 'no_coa','nama_coa' => 'nama_coa','TOT_002' => 'TOT_002','TOT_003' => 'TOT_003','TOT_004' => 'TOT_004','TOT_005' => 'TOT_005','TOT_006' => 'TOT_006','TOT_007' => 'TOT_007','TOT_008' => 'TOT_008','TOT_009' => 'TOT_009','TOT_010' => 'TOT_010','TOT_011' => 'TOT_011','TOT_012' => 'TOT_012','TOT_013' => 'TOT_013','TOT_014' => 'TOT_014','TOT_015' => 'TOT_015','TOT_016' => 'TOT_016','TOT_017' => 'TOT_017','TOT_018' => 'TOT_018','TOT_019' => 'TOT_019','TOT_020' => 'TOT_020','TOT_021' => 'TOT_021','TOT_022' => 'TOT_022','TOT_023' => 'TOT_023','TOT_024' => 'TOT_024','TOT_025' => 'TOT_025','TOT_026' => 'TOT_026','TOT_027' => 'TOT_027','TOT_028' => 'TOT_028','TOT_029' => 'TOT_029','TOT_030' => 'TOT_030','TOT_031' => 'TOT_031','TOT_032' => 'TOT_032','TOT_033' => 'TOT_033','TOT_034' => 'TOT_034','TOT_035' => 'TOT_035','TOT_036' => 'TOT_036','TOT_038' => 'TOT_038','TOT_051' => 'TOT_051','TOT_052' => 'TOT_052','TOT_053' => 'TOT_053','TOT_054' => 'TOT_054','TOT_055' => 'TOT_055','TOT_056' => 'TOT_056','TOT_057' => 'TOT_057','TOT_058' => 'TOT_058','TOT_059' => 'TOT_059','TOT_060' => 'TOT_060','TOT_062' => 'TOT_062','TOT_063' => 'TOT_063','TOT_064' => 'TOT_064','TOT_065' => 'TOT_065','TOT_066' => 'TOT_066','TOT_067' => 'TOT_067','TOT_068' => 'TOT_068','TOT_069' => 'TOT_069','TOT_070' => 'TOT_070','TOT_071' => 'TOT_071','TOT_072' => 'TOT_072','TOT_073' => 'TOT_073','TOT_074' => 'TOT_074','TOT_075' => 'TOT_075','TOT_076' => 'TOT_076','TOT_077' => 'TOT_077','TOT_078' => 'TOT_078','TOT_079' => 'TOT_079','TOT_080' => 'TOT_080','TOT_081' => 'TOT_081','TOT_082' => 'TOT_082','TOT_083' => 'TOT_083','TOT_084' => 'TOT_084','TOT_085' => 'TOT_085','TOT_086' => 'TOT_086','TOT_087' => 'TOT_087','TOT_088' => 'TOT_088','TOT_089' => 'TOT_089','TOT_090' => 'TOT_090','TOT_091' => 'TOT_091','TOT_092' => 'TOT_092','TOT_093' => 'TOT_093','TOT_094' => 'TOT_094','TOT_095' => 'TOT_095','TOT_096' => 'TOT_096','TOT_097' => 'TOT_097','TOT_098' => 'TOT_098','TOT_099' => 'TOT_099','TOT_100' => 'TOT_100','TOT_101' => 'TOT_101','TOT_102' => 'TOT_102','TOT_103' => 'TOT_103','TOT_104' => 'TOT_104','TOT_105' => 'TOT_105','TOT_106' => 'TOT_106','TOT_107' => 'TOT_107','TOT_108' => 'TOT_108','TOT_109' => 'TOT_109','TOT_110' => 'TOT_110','TOT_111' => 'TOT_111','TOT_112' => 'TOT_112','TOT_113' => 'TOT_113','TOT_114' => 'TOT_114','TOT_115' => 'TOT_115','TOT_116' => 'TOT_116','TOT_117' => 'TOT_117','TOT_118' => 'TOT_118','TOT_119' => 'TOT_119','TOT_120' => 'TOT_120','TOT_121' => 'TOT_121','TOT_122' => 'TOT_122','TOT_123' => 'TOT_123','TOT_124' => 'TOT_124','TOT_125' => 'TOT_125','TOT_126' => 'TOT_126','TOT_127' => 'TOT_127','TOT_128' => 'TOT_128','TOT_129' => 'TOT_129','TOT_130' => 'TOT_130','TOT_131' => 'TOT_131','TOT_132' => 'TOT_132','TOT_133' => 'TOT_133','TOT_134' => 'TOT_134','TOT_135' => 'TOT_135','TOT_136' => 'TOT_136','TOT_137' => 'TOT_137','TOT_138' => 'TOT_138','TOT_139' => 'TOT_139','TOT_140' => 'TOT_140','TOT_141' => 'TOT_141','TOT_142' => 'TOT_142','TOT_143' => 'TOT_143','TOT_144' => 'TOT_144','TOT_145' => 'TOT_145','TOT_146' => 'TOT_146','TOT_147' => 'TOT_147','TOT_148' => 'TOT_148','TOT_149' => 'TOT_149','TOT_150' => 'TOT_150','TOT_151' => 'TOT_151','TOT_152' => 'TOT_152','TOT_153' => 'TOT_153','TOT_154' => 'TOT_154','TOT_155' => 'TOT_155','TOT_156' => 'TOT_156','TOT_157' => 'TOT_157','TOT_158' => 'TOT_158','TOT_159' => 'TOT_159','TOT_160' => 'TOT_160','TOT_161' => 'TOT_161','TOT_162' => 'TOT_162','TOT_163' => 'TOT_163','TOT_164' => 'TOT_164','TOT_165' => 'TOT_165','TOT_166' => 'TOT_166','TOT_167' => 'TOT_167','TOT_168' => 'TOT_168','TOT_169' => 'TOT_169','TOT_170' => 'TOT_170','TOT_171' => 'TOT_171','TOT_172' => 'TOT_172','TOT_173' => 'TOT_173','TOT_174' => 'TOT_174','TOT_175' => 'TOT_175','TOT_176' => 'TOT_176','TOT_177' => 'TOT_177','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_import_jml_rekening',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['id_anggaran','kode_anggaran','keterangan_anggaran','no_coa','nama_coa','TOT_002','TOT_003','TOT_004','TOT_005','TOT_006','TOT_007','TOT_008','TOT_009','TOT_010','TOT_011','TOT_012','TOT_013','TOT_014','TOT_015','TOT_016','TOT_017','TOT_018','TOT_019','TOT_020','TOT_021','TOT_022','TOT_023','TOT_024','TOT_025','TOT_026','TOT_027','TOT_028','TOT_029','TOT_030','TOT_031','TOT_032','TOT_033','TOT_034','TOT_035','TOT_036','TOT_038','TOT_051','TOT_052','TOT_053','TOT_054','TOT_055','TOT_056','TOT_057','TOT_058','TOT_059','TOT_060','TOT_062','TOT_063','TOT_064','TOT_065','TOT_066','TOT_067','TOT_068','TOT_069','TOT_070','TOT_071','TOT_072','TOT_073','TOT_074','TOT_075','TOT_076','TOT_077','TOT_078','TOT_079','TOT_080','TOT_081','TOT_082','TOT_083','TOT_084','TOT_085','TOT_086','TOT_087','TOT_088','TOT_089','TOT_090','TOT_091','TOT_092','TOT_093','TOT_094','TOT_095','TOT_096','TOT_097','TOT_098','TOT_099','TOT_100','TOT_101','TOT_102','TOT_103','TOT_104','TOT_105','TOT_106','TOT_107','TOT_108','TOT_109','TOT_110','TOT_111','TOT_112','TOT_113','TOT_114','TOT_115','TOT_116','TOT_117','TOT_118','TOT_119','TOT_120','TOT_121','TOT_122','TOT_123','TOT_124','TOT_125','TOT_126','TOT_127','TOT_128','TOT_129','TOT_130','TOT_131','TOT_132','TOT_133','TOT_134','TOT_135','TOT_136','TOT_137','TOT_138','TOT_139','TOT_140','TOT_141','TOT_142','TOT_143','TOT_144','TOT_145','TOT_146','TOT_147','TOT_148','TOT_149','TOT_150','TOT_151','TOT_152','TOT_153','TOT_154','TOT_155','TOT_156','TOT_157','TOT_158','TOT_159','TOT_160','TOT_161','TOT_162','TOT_163','TOT_164','TOT_165','TOT_166','TOT_167','TOT_168','TOT_169','TOT_170','TOT_171','TOT_172','TOT_173','TOT_174','TOT_175','TOT_176','TOT_177','is_active'];
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
					$save = insert_data('tbl_import_jumlah_rekening',$data);
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
		$arr = ['id_anggaran' => 'Id Anggaran','kode_anggaran' => 'Kode Anggaran','keterangan_anggaran' => 'Keterangan Anggaran','no_coa' => 'No Coa','nama_coa' => 'Nama Coa','TOT_002' => 'TOT 002','TOT_003' => 'TOT 003','TOT_004' => 'TOT 004','TOT_005' => 'TOT 005','TOT_006' => 'TOT 006','TOT_007' => 'TOT 007','TOT_008' => 'TOT 008','TOT_009' => 'TOT 009','TOT_010' => 'TOT 010','TOT_011' => 'TOT 011','TOT_012' => 'TOT 012','TOT_013' => 'TOT 013','TOT_014' => 'TOT 014','TOT_015' => 'TOT 015','TOT_016' => 'TOT 016','TOT_017' => 'TOT 017','TOT_018' => 'TOT 018','TOT_019' => 'TOT 019','TOT_020' => 'TOT 020','TOT_021' => 'TOT 021','TOT_022' => 'TOT 022','TOT_023' => 'TOT 023','TOT_024' => 'TOT 024','TOT_025' => 'TOT 025','TOT_026' => 'TOT 026','TOT_027' => 'TOT 027','TOT_028' => 'TOT 028','TOT_029' => 'TOT 029','TOT_030' => 'TOT 030','TOT_031' => 'TOT 031','TOT_032' => 'TOT 032','TOT_033' => 'TOT 033','TOT_034' => 'TOT 034','TOT_035' => 'TOT 035','TOT_036' => 'TOT 036','TOT_038' => 'TOT 038','TOT_051' => 'TOT 051','TOT_052' => 'TOT 052','TOT_053' => 'TOT 053','TOT_054' => 'TOT 054','TOT_055' => 'TOT 055','TOT_056' => 'TOT 056','TOT_057' => 'TOT 057','TOT_058' => 'TOT 058','TOT_059' => 'TOT 059','TOT_060' => 'TOT 060','TOT_062' => 'TOT 062','TOT_063' => 'TOT 063','TOT_064' => 'TOT 064','TOT_065' => 'TOT 065','TOT_066' => 'TOT 066','TOT_067' => 'TOT 067','TOT_068' => 'TOT 068','TOT_069' => 'TOT 069','TOT_070' => 'TOT 070','TOT_071' => 'TOT 071','TOT_072' => 'TOT 072','TOT_073' => 'TOT 073','TOT_074' => 'TOT 074','TOT_075' => 'TOT 075','TOT_076' => 'TOT 076','TOT_077' => 'TOT 077','TOT_078' => 'TOT 078','TOT_079' => 'TOT 079','TOT_080' => 'TOT 080','TOT_081' => 'TOT 081','TOT_082' => 'TOT 082','TOT_083' => 'TOT 083','TOT_084' => 'TOT 084','TOT_085' => 'TOT 085','TOT_086' => 'TOT 086','TOT_087' => 'TOT 087','TOT_088' => 'TOT 088','TOT_089' => 'TOT 089','TOT_090' => 'TOT 090','TOT_091' => 'TOT 091','TOT_092' => 'TOT 092','TOT_093' => 'TOT 093','TOT_094' => 'TOT 094','TOT_095' => 'TOT 095','TOT_096' => 'TOT 096','TOT_097' => 'TOT 097','TOT_098' => 'TOT 098','TOT_099' => 'TOT 099','TOT_100' => 'TOT 100','TOT_101' => 'TOT 101','TOT_102' => 'TOT 102','TOT_103' => 'TOT 103','TOT_104' => 'TOT 104','TOT_105' => 'TOT 105','TOT_106' => 'TOT 106','TOT_107' => 'TOT 107','TOT_108' => 'TOT 108','TOT_109' => 'TOT 109','TOT_110' => 'TOT 110','TOT_111' => 'TOT 111','TOT_112' => 'TOT 112','TOT_113' => 'TOT 113','TOT_114' => 'TOT 114','TOT_115' => 'TOT 115','TOT_116' => 'TOT 116','TOT_117' => 'TOT 117','TOT_118' => 'TOT 118','TOT_119' => 'TOT 119','TOT_120' => 'TOT 120','TOT_121' => 'TOT 121','TOT_122' => 'TOT 122','TOT_123' => 'TOT 123','TOT_124' => 'TOT 124','TOT_125' => 'TOT 125','TOT_126' => 'TOT 126','TOT_127' => 'TOT 127','TOT_128' => 'TOT 128','TOT_129' => 'TOT 129','TOT_130' => 'TOT 130','TOT_131' => 'TOT 131','TOT_132' => 'TOT 132','TOT_133' => 'TOT 133','TOT_134' => 'TOT 134','TOT_135' => 'TOT 135','TOT_136' => 'TOT 136','TOT_137' => 'TOT 137','TOT_138' => 'TOT 138','TOT_139' => 'TOT 139','TOT_140' => 'TOT 140','TOT_141' => 'TOT 141','TOT_142' => 'TOT 142','TOT_143' => 'TOT 143','TOT_144' => 'TOT 144','TOT_145' => 'TOT 145','TOT_146' => 'TOT 146','TOT_147' => 'TOT 147','TOT_148' => 'TOT 148','TOT_149' => 'TOT 149','TOT_150' => 'TOT 150','TOT_151' => 'TOT 151','TOT_152' => 'TOT 152','TOT_153' => 'TOT 153','TOT_154' => 'TOT 154','TOT_155' => 'TOT 155','TOT_156' => 'TOT 156','TOT_157' => 'TOT 157','TOT_158' => 'TOT 158','TOT_159' => 'TOT 159','TOT_160' => 'TOT 160','TOT_161' => 'TOT 161','TOT_162' => 'TOT 162','TOT_163' => 'TOT 163','TOT_164' => 'TOT 164','TOT_165' => 'TOT 165','TOT_166' => 'TOT 166','TOT_167' => 'TOT 167','TOT_168' => 'TOT 168','TOT_169' => 'TOT 169','TOT_170' => 'TOT 170','TOT_171' => 'TOT 171','TOT_172' => 'TOT 172','TOT_173' => 'TOT 173','TOT_174' => 'TOT 174','TOT_175' => 'TOT 175','TOT_176' => 'TOT 176','TOT_177' => 'TOT 177','is_active' => 'Aktif'];
		$data = get_data('tbl_import_jumlah_rekening')->result_array();
		$config = [
			'title' => 'data_import_jml_rekening',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}