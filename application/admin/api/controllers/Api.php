<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends BE_Controller {
	function coa_option(){
		$db = get_data('tbl_m_coa',[
			'select' 	=> 'glwnco,glwdes',
			'where'		=> 'is_active = 1',
		])->result();
		$data = '<option></option>';
		foreach ($db as $v) {
			$data .= '<option value="'.$v->glwnco.'">'.$v->glwnco.' - '.remove_spaces($v->glwdes).'</option>';
		}
		render(['data'=>$data],'json');
	}

	function cabang_option(){
		$parent = post('parent');
		$type	= post('type');
		if($parent && $type == 'divisi'):
			$ck_parent = get_data('tbl_m_cabang','id',$parent)->row_array();
			if(isset($ck_parent) && $ck_parent['status_group'] == 1):
				$parent = " and (parent_id = '$parent')";
			else:
				$parent = " and (parent_id = '$parent' or id = '$parent')";
			endif;
		elseif($parent):
			$parent = " and parent_id = '$parent'";
		endif;
		$db = get_data('tbl_m_cabang',[
			'select' 	=> 'kode_cabang,nama_cabang',
			'where'		=> "is_active = '1'".$parent,
			'order_by'	=> 'kode_cabang',
		])->result();
		$data = '';
		foreach ($db as $v) {
			$data .= '<option value="'.$v->kode_cabang.'">'.$v->nama_cabang.'</option>';
		}
		render(['data'=>$data],'json');
	}

	function currency_option(){
		$db = get_data('tbl_m_currency',[
			'select' 	=> 'id,nama',
			'where'		=> 'is_active = 1',
		])->result();
		// $data = '<option></option>';
		$data = '';
		foreach ($db as $v) {
			$data .= '<option value="'.$v->id.'">'.$v->nama.'</option>';
		}
		render(['data'=>$data],'json');
	}

	function provinsi_option(){
		$db = get_data('provinsi',[
			'select' 	=> 'id,name',
			'where'		=> "is_active = '1'",
			'order_by'	=> 'name',
		])->result();
		$data = '<option value="">'.lang('pilih_provinsi').'</option>';
		foreach ($db as $v) {
			$data .= '<option value="'.$v->id.'">'.$v->name.'</option>';
		}
		render(['data'=>$data],'json');
	}

	function kota_option(){
		$parent = post('parent');
		if($parent):
			$parent = " and id_provinsi = '$parent'";
		endif;
		$db = get_data('kota',[
			'select' 	=> 'id,name',
			'where'		=> "is_active = '1'".$parent,
			'order_by'	=> 'name',
		])->result();
		$data = '<option value="">'.lang('pilih_kota').'</option>';
		foreach ($db as $v) {
			$data .= '<option value="'.$v->id.'">'.$v->name.'</option>';
		}
		render(['data'=>$data],'json');
	}

	function kecamatan_option(){
		$parent = post('parent');
		if($parent):
			$parent = " and id_kota = '$parent'";
		endif;
		$db = get_data('kecamatan',[
			'select' 	=> 'id,name',
			'where'		=> "is_active = '1'".$parent,
			'order_by'	=> 'name',
		])->result();
		$data = '<option value="">'.lang('pilih_kecamatan').'</option>';
		foreach ($db as $v) {
			$data .= '<option value="'.$v->id.'">'.$v->name.'</option>';
		}
		render(['data'=>$data],'json');
	}

	function divisi_option(){
		$gab = get_data('tbl_m_cabang','kode_cabang','00100')->row();
		$data = '';
		if($gab):
			$ls = get_data('tbl_m_cabang',[
				'where'	=> [
					'parent_id' => $gab->id,
					'is_active' => 1,
				]
			])->result();
			foreach ($ls as $k => $v) {
				$data .= '<option value="'.$v->kode_cabang.'">'.$v->nama_cabang.'</option>';
			}
		endif;
		render(['data'=>$data],'json');
	}

	function kategori_kantor_keterangan_option(){
		$db = get_data('tbl_kategori_kantor_keterangan',[
			'select' 	=> 'id,nama',
			'where'		=> "is_active = '1'",
			'order_by'	=> 'id',
		])->result();
		$data = '';
		foreach ($db as $v) {
			$data .= '<option value="'.$v->id.'">'.$v->nama.'</option>';
		}
		render(['data'=>$data],'json');
	}
}