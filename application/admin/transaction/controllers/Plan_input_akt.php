<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_input_akt extends BE_Controller {
    var $path       = 'transaction/budget_planner/kantor_pusat/';
    var $sub_menu   = 'transaction/budget_planner/sub_menu';
    var $detail_tahun;
    var $kode_anggaran;
    var $arrID = [];
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran,
                'a.sumber_data'   => array(2,3)
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
    }
    
    function index($p1="") { 
        $a = get_access('plan_input_akt');
        $data = $data = cabang_divisi();
        $data['access_additional'] = $a['access_additional'];
        $data['opt_grup']  = get_data('tbl_grup_asetinventaris',[
            'where' => [
                'is_active' => 1,
                'kode' => ['E.1','E.2','E.3','E.6'],
                ],
            'order_by' => 'kode',
        ])->result_array();

        $data['opt_inv1']  = get_data('tbl_kode_inventaris',[
            'where' => [
                'is_active' => 1,
                'grup'      => 'E.4'
            ],
            'order_by'  => 'kode_inventaris',
        ])->result_array();
        $data['opt_inv2']  = get_data('tbl_kode_inventaris',[
            'where' => [
                'is_active' => 1,
                'grup'      => 'E.5'
            ],
            'order_by'  => 'kode_inventaris',
        ])->result_array();
        $data['opt_inv3']  = get_data('tbl_kode_inventaris',[
            'where' => [
                'is_active' => 1,
                'grup'      => 'E.7'
            ],
            'order_by'  => 'kode_inventaris',
        ])->result_array();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['detail_tahun']    = $this->detail_tahun;
        render($data,'view:'.$this->path.'plan_input_akt/index');
    }

    function data($anggaran="", $cabang="", $tipe = 'table') {
        $ckode_anggaran = $anggaran;
        $ckode_cabang = $cabang;
        
        $a = get_access('plan_input_akt');
        $access_edit = false;
        if($a['access_edit']):
            $access_edit = true;
        elseif($a['access_edit'] && $a['access_additional']):
            $access_edit = true;
        endif;
        $data['akses_ubah'] = $access_edit;

        $data['current_cabang'] = $cabang;

        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
                  
        $arr            = [
            'select'    => 'a.*',
            'where'     => [
                'a.is_active' => 1,
            ],
            'sort_by'   => 'a.kode',
        ];
        
    
        $data['grup'][0]= get_data('tbl_grup_asetinventaris a',$arr)->result();
        

        foreach($data['grup'][0] as $m0) {         

            $arr            = [
                'select'    => 'a.*',
                'where'     => [
                    'a.grup' => $m0->kode,
                ],
            ];
            
            if($anggaran) {
                $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
            }
            
            if($cabang) {
                $arr['where']['a.kode_cabang']  = $ckode_cabang;
            }

            $produk     = get_data('tbl_rencana_aset a',$arr)->result();

            $nama_cabang ='';
            foreach ($produk as $m1) {

                $cabang = get_data('tbl_m_cabang','kode_cabang',$ckode_cabang)->row();
                
                if(isset($cabang->nama_cabang)) $nama_cabang = $cabang->nama_cabang;

                $data2 = array(
                    'kode_anggaran' => $ckode_anggaran,
                    'keterangan_anggaran' => $anggaran->keterangan,
                    'tahun'  => $anggaran->tahun_anggaran,
                    'kode_cabang'   => $ckode_cabang,
                    'cabang'        => $nama_cabang,
                    'username'      => user('username'),
                    'kode_inventaris' => $m1->kode_inventaris,
                    'nama_inventaris' => $m1->nama_inventaris,
                    'grup'      => $m1->grup,
                    'nama_grup' => $m1->nama_grup,
                );

                $cek        = get_data('tbl_rencana_aset',[
                    'where'         => [
                        'kode_anggaran'   => $ckode_anggaran,  
                        'kode_cabang'     => $ckode_cabang,
                        'tahun'           => $anggaran->tahun_anggaran,
                        'kode_inventaris' => $m1->kode_inventaris,  
                        'grup'            => $m1->grup,
                        ],
                ])->row();
                
                if(!isset($cek->id)) {
                    $response =             insert_data('tbl_rencana_aset',$data2);
                }
            }      

            $arr            = [
                'select'    => 'a.*',
                'where'     => [
                    'a.grup' => $m0->kode,
                ],
            ];

            if($anggaran) {
                $arr['where']['a.kode_anggaran']  = $ckode_anggaran;
            }
            
            if($cabang) {
                $arr['where']['a.kode_cabang']  = $ckode_cabang;
            }

            
            $data['produk'][$m0->kode]  = get_data('tbl_rencana_aset a',$arr)->result();     
                        
        }           
   
        $data['cabang_user'] = user('kode_cabang');
        $data['page']    = "inv";

        $data2 = $data;
        $data2['page'] = 'aset_sewa';

        $response   = array(
            'table'      => $this->load->view($this->path.'plan_input_akt/table',$data,true),
            'table_sewa' => $this->load->view($this->path.'plan_input_akt/table_sewa',$data2,true),
            'edit'       => $access_edit,
        );
       
        render($response,'json');
    }

    function getKodeInventaris(){
        $get = get_data('tbl_rencana_aset a',[
            'select' => 'a.kode_inventaris',
            'where' => [
                'kode_cabang'   => user("kode_cabang"),
                'kode_inventaris like' => 'H%'
            ],
            'order_by' => 'id',
            'sort' => 'DESC',
            'limit' => '1'
        ])->result();

        if(!empty($get)){
            $data = $get;
        }else {
            $test['kode_inventaris'] = "H-00";
            $data[] = $test;
        }

        render($data,'json');
    }


    function getKodeInventaris2(){
        $get = get_data('tbl_rencana_aset a',[
            'select' => 'a.kode_inventaris',
            'where' => [
                'kode_cabang'   => user("kode_cabang"),
                'kode_inventaris like' => 'M%'
            ],
            'order_by' => 'id',
            'sort' => 'DESC',
            'limit' => '1'
        ])->result();

        if(!empty($get)){
            $data = $get;
        }else {
            $test['kode_inventaris'] = "M 0";
            $data[] = $test;
        }

        render($data,'json');
    }


    function get_data() {
        $dt = get_data('tbl_rencana_aset','id',post('id'))->row();
        $data = get_data('tbl_rencana_aset',[
            'where' => [
            'kode_anggaran' => $dt->kode_anggaran,    
            'tahun' => $dt->tahun,
            'kode_cabang' => $dt->kode_cabang
        ],
        ])->row_array();

        $data_inv = get_data('tbl_rencana_aset',[
            'where' => [
            'kode_anggaran' => $dt->kode_anggaran,    
            'tahun' => $dt->tahun,
            'kode_cabang' => $dt->kode_cabang,
            'grup' => ['E.1','E.2','E.3','E.6']
        ],
        ])->result_array();
        $data_inv = $this->convert_data($data_inv);
        $data['detail_ket'] = $data_inv;

        $data_inv1 = get_data('tbl_rencana_aset a',[
            'select' => 'a.*',
            'join' => 'tbl_kode_inventaris b on b.kode_inventaris = a.kode_inventaris',
            'where' => [
            'a.kode_anggaran' => $dt->kode_anggaran, 
            'a.tahun' => $dt->tahun,
            'a.kode_cabang' => $dt->kode_cabang,
            'a.grup' => 'E.4'
        ],
        ])->result_array();
        $data_inv1 = $this->convert_data($data_inv1);
        $data['detail_invk1'] =  $data_inv1;

        $data_inv2 = get_data('tbl_rencana_aset a',[
            'select' => 'a.*',
            'join' => 'tbl_kode_inventaris b on b.kode_inventaris = a.kode_inventaris',
            'where' => [
            'a.kode_anggaran' => $dt->kode_anggaran, 
            'a.tahun' => $dt->tahun,
            'a.kode_cabang' => $dt->kode_cabang,
            'a.grup' => 'E.5'
        ],
        ])->result_array();
        $data_inv2 = $this->convert_data($data_inv2);

        $data['detail_invk2'] = $data_inv2;

        $data_inv3 = get_data('tbl_rencana_aset a',[
            'select' => 'a.*',
            'join' => 'tbl_kode_inventaris b on b.kode_inventaris = a.kode_inventaris',
            'where' => [
            'a.kode_anggaran' => $dt->kode_anggaran, 
            'a.tahun' => $dt->tahun,
            'a.kode_cabang' => $dt->kode_cabang,
            'a.grup' => 'E.7'
        ],
        ])->result_array();
        $data_inv3 = $this->convert_data($data_inv3);

        $data['detail_invk3'] = $data_inv3;


        $data['detail_tambahan1'] = get_data('tbl_rencana_aset a',[
            'select' => 'a.*',
            'join' => 'tbl_kode_inventaris b on b.kode_inventaris = a.kode_inventaris TYPE left',
            'where' => [
            'a.kode_anggaran' => $dt->kode_anggaran, 
            'a.tahun' => $dt->tahun,
            'a.kode_cabang' => $dt->kode_cabang,
            'a.grup' => 'E.4',
            'b.kode_inventaris' => null
        ],
        ])->result_array();

        $data['detail_tambahan2'] = get_data('tbl_rencana_aset',[
            'where' => [
            'kode_anggaran' => $dt->kode_anggaran, 
            'tahun' => $dt->tahun,
            'kode_cabang' => $dt->kode_cabang,
            'grup' => 'E.5',
            'kode_inventaris' => '' 
        ],
        ])->result_array();

        render($data,'json');
    }

    private function convert_data($p1){
        $data = [];
        foreach ($p1 as $k => $v) {
            $v['harga'] = view_report($v['harga']);
            $data[] = $v;
        }
        return $data;
    }

    function save_perubahan() {       
        $data   = json_decode(post('json'),true);
        foreach($data as $id => $record) {
            if(isset($record['harga'])):
                $record['harga'] = insert_view_report(filter_money($record['harga']));
            endif;
            update_data('tbl_rencana_aset',$record,'id',$id); }
    }

    function save() {
        $kode_cabang = post('kode_cabang');
        $ckode_anggaran = user('kode_anggaran');

        $anggaran       = get_data('tbl_tahun_anggaran','kode_anggaran',$ckode_anggaran)->row();
        $cabang         = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();
        $tahun          = $anggaran->tahun_anggaran;

        $dataDefault = [
            'cabang'            => $cabang,
            'kode_cabang'       => $kode_cabang,
            'anggaran'          => $anggaran,
            'tahun'             => $tahun,
        ];

        // ASET DAN INSTALASI BANGUNAN
        $data = $dataDefault;
        $data['kodeinventaris'] = post('kodeinventaris');
        $data['keterangan']     = post('keterangan');
        $data['grup_aset']      = post('grup_aset');
        $data['catatan']        = post('catatan');
        $data['bulan_aset']     = post('bulan_aset');
        $this->pengecekan($data);

        // Inventaris Kel 1
        $data = $dataDefault;
        $data['kodeinventaris'] = post('kel1');
        $data['keterangan']     = post('inv_kel1');
        $data['grup_aset']      = post('grup_aset');
        $data['catatan']        = post('catatanInvKel1');
        $data['bulan_aset']     = post('bulan_kel1');
        $this->pengecekan($data,'grouping');

        // Inventaris Kel 2
        $data = $dataDefault;
        $data['kodeinventaris'] = post('kel2');
        $data['keterangan']     = post('inv_kel2');
        $data['grup_aset']      = post('grup_aset');
        $data['catatan']        = post('catatanInvKel2');
        $data['bulan_aset']     = post('bulan_kel2');
        $this->pengecekan($data,'grouping');

        // Aset Sewa
        $data = $dataDefault;
        $data['kodeinventaris'] = post('kel3');
        $data['keterangan']     = post('inv_kel3');
        $data['grup_aset']      = post('grup_aset');
        $data['catatan']        = post('catatanInvKel3');
        $data['bulan_aset']     = post('bulan_kel3');
        $data['jumlah']         = post('jumlah3');
        $data['harga']          = post('harga3');
        $this->pengecekan($data,'grouping');

        if(post('id') && count($this->arrID)):
            delete_data('tbl_rencana_aset',['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$kode_cabang,'id not' => $this->arrID]);  
        elseif(post('id')):
            delete_data('tbl_rencana_aset',['kode_anggaran'=>$anggaran->kode_anggaran,'kode_cabang'=>$kode_cabang]); 
        endif;

        render([
            'status'    => 'success',
            'message'   => lang('data_berhasil_disimpan')
        ],'json');
    }

    private function pengecekan($data,$page=""){

        $kode_cabang    = $data['kode_cabang'];
        $cabang         = $data['cabang'];
        $anggaran       = $data['anggaran'];
        $tahun          = $data['tahun'];

        $kodeinventaris = $data['kodeinventaris'];
        $keterangan     = $data['keterangan'];
        $grup_aset      = $data['grup_aset'];
        $catatan        = $data['catatan'];
        $bulan_aset     = $data['bulan_aset'];

        foreach ($keterangan as $k => $v) {

            if($v):
                $dataWhere['kode_cabang']    = $kode_cabang;
                $dataWhere['kode_anggaran']  = $anggaran->kode_anggaran;

                $dataSave = $dataWhere;
                $dataSave['cabang']              = $cabang->nama_cabang;
                $dataSave['keterangan_anggaran'] = $anggaran->keterangan;
                $dataSave['tahun']               = $tahun;
                $dataSave['nama_inventaris']     = $v;
                $dataSave['catatan']             = $catatan[$k];
                $dataSave['bulan']               = $bulan_aset[$k];
                $dataSave['is_active']           = 1;
                $prefiks = '';

                // jika grouping hanya bisa input satu keterangan dalam satu tahun berdasarkan tbl_kode_inventaris yang terdaftar
                if($page == 'grouping'):
                    $dt_kode_inv = get_data('tbl_kode_inventaris','kode_inventaris',$v)->row_array();
                    $dataSave['nama_inventaris'] = $dt_kode_inv['nama_inventaris'];
                    $dataSave['kode_inventaris'] = $dt_kode_inv['kode_inventaris'];
                    $dataSave['grup']            = $dt_kode_inv['grup'];
                    $dataSave['nama_grup']       = $dt_kode_inv['nama_grup_aset'];
                    $dataSave['harga']           = $dt_kode_inv['harga'];

                    if(isset($data['harga'][$k])):
                        $harga  = insert_view_report(filter_money($data['harga'][$k])); if(!$harga) $harga = 0;
                        $jumlah = filter_money($data['jumlah'][$k]); if(!$jumlah) $jumlah = 0;
                        $dataSave['harga']  = $harga;
                        $dataSave['jumlah'] = $jumlah;
                        $dataSave['total']  = $harga * $jumlah;
                    endif;

                    $where = $dataWhere;
                    $where['kode_inventaris'] = $dt_kode_inv['kode_inventaris'];
                    $ck_id = get_data('tbl_rencana_aset',['select' => 'id,kode_inventaris','where' => $where])->row_array();
                    if($ck_id):
                        $dataSave['id'] = $ck_id['id'];
                    endif;

                else:

                    $dt_grup = get_data('tbl_grup_asetinventaris','kode',$grup_aset[$k])->row_array();
                    if($dt_grup):
                        $prefiks                = $dt_grup['prefiks'];
                        $dataSave['grup']       = $grup_aset[$k];
                        $dataSave['nama_grup']  = $dt_grup['keterangan'];
                    endif;

                    $where = $dataWhere;
                    $where['id'] = $kodeinventaris[$k];
                    $ck_id = get_data('tbl_rencana_aset',['select' => 'id,kode_inventaris','where' => $where])->row_array();
                    $old_kode_inventaris = '';
                    if($ck_id):
                        $old_kode_inventaris = explode(" ", $ck_id['kode_inventaris'])[0];
                    endif;


                    $where = $dataWhere;
                    $where['kode_inventaris like '] = $prefiks.' %';
                    $ck_prefiks = get_data('tbl_rencana_aset',
                        ['select' => 'kode_inventaris','where' => $where,'order_by' => 'kode_inventaris', 'sort' => 'DESC']
                    )->row_array();
                    if($prefiks != $old_kode_inventaris && $ck_prefiks):
                        $count   = explode(" ", $ck_prefiks['kode_inventaris']);
                        $dataSave['kode_inventaris'] = $prefiks.' '.($count[1]+1);
                    elseif($prefiks != $old_kode_inventaris):
                        $dataSave['kode_inventaris'] = $prefiks.' 1';
                    endif;

                    if($ck_id):
                        $dataSave['id'] = $ck_id['id'];
                    endif;
                endif;

                $ID = save_data('tbl_rencana_aset',$dataSave);
                if(!in_array($ID['id'],$this->arrID)):
                    array_push($this->arrID, $ID['id']);
                endif;
            endif;
        }
    }
}