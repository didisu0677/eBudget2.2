<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laba_rugi_new extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'laba_rugi_new';
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->row();
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => "a.kode_anggaran = '".$this->kode_anggaran."' and a.tahun = '".$anggaran->tahun_anggaran."' ",
            //     'a.kode_anggaran' => $this->kode_anggaran,
            //     'a.sumber_data'   => array(2,3)
            // ],
            'order_by' => 'tahun,bulan'
        ])->result_array();
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
               if(strlen($v[3])>0 && $v[3] != "Lakukan"):
                    if($k == 0):
                        $data[$k] = $v;
                    else:
                        $detail = [
                            $v[0],
                            $v[1],
                            $v[2],
                        ];
                        foreach ($v as $k2 => $v2) {
                            if($k2>2):
                                if(strlen($v2)>0):
                                    $v2 = (float) filter_money($v2);
                                endif;
                                $detail[] = $v2;
                            endif;
                        }
                        $data[$k] = $detail;
                    endif;
                    if(count($v)<count($neraca_header[1])):
                        for($i=count($v);$i<count($neraca_header[1]);$i++){
                            $data[$k][] = "";
                        }
                    endif;
                endif;

                if($v[1] == '59999'):
                    break;
                endif;
            else:
                $data[$k] = [];
                for($i=1;$i<=count($neraca_header[1]);$i++){
                    $data[$k][] = '';
                }
            endif;
        }

        unset($data[0]);
        $config[] = [
            'title' => 'Laba Rugi Nett',
            'header' => $neraca_header[1],
            'data'  => $data,
        ];
        // render($config,'json');exit();

        $this->load->library('simpleexcel',$config);
        $filename = 'Labarugi_Nett_'.str_replace(' ', '_', $kode_anggaran_txt).'_'.str_replace(' ', '_', $kode_cabang_txt).'_'.date('YmdHis');
        $this->simpleexcel->filename($filename);
        $this->simpleexcel->export();
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('laba_rugi_new')
            ]
        ])->result();

        $kode_cabang          = [];
        foreach($cabang_user as $c) $kode_cabang[] = $c->kode_cabang;

        $id = user('id_struktur');
        if($id){
            $cab = get_data('tbl_m_cabang','id',$id)->row();
        }else{
            $id = user('kode_cabang');
            $cab = get_data('tbl_m_cabang','kode_cabang',$id)->row();
        }

        $x ='';
        for ($i = 1; $i <= 4; $i++) { 
            $field = 'level' . $i ;

            if($cab->id == $cab->$field) {
                $x = $field ; 
            }    
        }    

        $data['cabang']            = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang,level_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cab->id,
                'a.kode_cabang' => $kode_cabang
            ]
        ])->result_array();

        $data['cabang_input'] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang,level_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.kode_cabang' => user('kode_cabang')
            ]
        ])->result_array();

        $data['detail_tahun'] = $this->detail_tahun;
        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result();
        $data['path'] = $this->path;
        return $data;
    }
    
    function index($p1="") { 
        $access         = get_access($this->controller);
        $data = $this->data_cabang();
        $data['access_additional']  = $access['access_additional'];
        $data['bulan_terakhir'] = month_lang($data['tahun'][0]->bulan_terakhir_realisasi);
        render($data,'view:'.$this->path.'laba_rugi_new/index');
    }

     function data ($anggaran1="", $cabang=""){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran1)->row();

        $bln_trakhir = $anggaran->bulan_terakhir_realisasi;
        $thn_trakhir = $anggaran->tahun_terakhir_realisasi;
        $tbl_history = 'tbl_history_'.$thn_trakhir;

         $data['adj'] = get_data('tbl_labarugi_adj',[
            'where' => "kode_cabang =  '".$cabang."' and kode_anggaran = '".$anggaran1."'"
        ])->result_array();

        $or_neraca  = "(a.glwnco like '4%' or a.glwnco like '5%')";
        $select     = 'distinct level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus';
        $coa = get_data('tbl_m_coa a',[
            'select' => $select.',b.TOT_'.$cabang,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.urutan',
            'join' => "$tbl_history b on b.bulan = '$bln_trakhir' and a.glwnco = b.glwnco type left"
        ])->result();
        $coa = $this->get_coa($coa);


        $query_result     = $this->db->query("CALL stored_laba_rugi_nett('$cabang','$anggaran->kode_anggaran','$anggaran->tahun_anggaran')");
        // $query_result = $this->get_data_net($anggaran->kode_anggaran,$cabang,$anggaran->tahun_anggaran);
        $data['stored']           = $query_result->result_array();
        // print_r($this->db->last_query());    
        // $data['adj'] = get_data('tbl_labarugi_adj',[
        //     'where' => [
        //         'kode_cabang'       => $cabang,
        //         'kode_anggaran'     => $anggaran
        //     ]
        // ])->result_array();
        //add this two line 
        $query_result->next_result(); 
        $query_result->free_result(); 
        //end of new code

        // $coa = $this->get_list_coa($coa,$detail);

      

        $data['coa']    = $coa['coa'];
        $data['detail'] = $coa['detail'];
        $data['cabang'] = $cabang;
        $data['bulan_terakhir'] = $bln_trakhir;

        // $data['biaya'] = get_data("tbl_biaya",[
        //     'kode_cabang'   => $cabang,
        // ])->result_array();


        // echo json_encode($data);

        $a = get_access($this->controller);
        $access_edit = false;
        if($a['access_edit'] && $cabang == user('kode_cabang')):
            $access_edit = true;
        elseif($a['access_edit'] && $a['access_additional']):
            $access_edit = true;
        endif;
        $data['akses_ubah'] = $access_edit;
        $data['access_additional'] = $a['access_additional'];

        $response   = array(
            'table'     => $this->load->view($this->path.'laba_rugi_new/table',$data,true),
            'data' => $data,
        );
        render($response,'json');
    }

     private function get_list_coa($coa,$detail){
        $data = [];
        foreach ($coa as $k => $v) {
            // level 0
            if(!$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'coa' => $v->glwnco,
                ));
                $h = $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['coa'][] = $h;
            endif;

            // level 1
            if($v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'level1' => $v->level1,
                    'coa'    => $v->glwnco,
                ));
                $h = (array) $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['1'][$v->level1][] = $h;
            endif;

            // level 2
            if(!$v->level1 && $v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'level2' => $v->level2,
                    'coa'    => $v->glwnco,
                ));
                $h = (array) $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['2'][$v->level2][] = $h;
            endif;

            // level 3
            if(!$v->level1 && !$v->level2 && $v->level3 && !$v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'level3' => $v->level3,
                    'coa'    => $v->glwnco,
                ));
                $h = (array) $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['3'][$v->level3][] = $h;
            endif;

            // level 4
            if(!$v->level1 && !$v->level2 && !$v->level3 && $v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'level4' => $v->level4,
                    'coa'    => $v->glwnco,
                ));
                $h = (array) $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['4'][$v->level4][] = $h;
            endif;

            // level 5
            if(!$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && $v->level5):
                $key = multidimensional_search($detail, array(
                    'level5' => $v->level5,
                    'coa'    => $v->glwnco,
                ));
                $h = (array) $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['5'][$v->level5][] = $h;
            endif;
        }
        return $data;
    }

    private function get_coa($coa){
        $data = [];
        foreach ($coa as $k => $v) {
            // level 0
            if(!$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $data['coa'][] = $v;
            endif;

            // level 1
            if($v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $data['detail']['1'][$v->level1][] = $v;
            endif;

            // level 2
            if(!$v->level1 && $v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $data['detail']['2'][$v->level2][] = $v;
            endif;

            // level 3
            if(!$v->level1 && !$v->level2 && $v->level3 && !$v->level4 && !$v->level5):
                $data['detail']['3'][$v->level3][] = $v;
            endif;

            // level 4
            if(!$v->level1 && !$v->level2 && !$v->level3 && $v->level4 && !$v->level5):
                $data['detail']['4'][$v->level4][] = $v;
            endif;

            // level 5
            if(!$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && $v->level5):
                $data['detail']['5'][$v->level5][] = $v;
            endif;
        }
        return $data;
    }

    function save_perubahan($anggaran="",$cabang="") {       

        $data   = json_decode(post('json'),true);

        // echo post('json');
        foreach($data as $getId => $record) {
            $cekId = $getId;

            foreach ($record as $k2 => $v2) {
                $value = filter_money($v2);
                $record[$k2] = $value;
            }

            $data = insert_view_report_arr($record);

            $cek  = get_data('tbl_labarugi a',[
                'select'    => 'a.id',
                'where'     => [
                    'a.glwnco'             => $cekId,
                    'a.kode_anggaran'   => $anggaran,
                    'a.kode_cabang'   => $cabang,
                ]
            ])->result_array();
     
            if(count($cek) > 0){
                update_data('tbl_labarugi', $data,'id',$cek[0]['id']);
            }else {
                    // echo $cekId."<br>";
                    // echo $anggaran."<br>";
                    // echo $cabang."<br>";
                    $data['glwnco'] = $cekId;
                    $data['kode_anggaran'] = $anggaran;
                    $data['kode_cabang'] = $cabang;
                    insert_data('tbl_labarugi',$data);
            }
         }

        $this->db->query("call stored_budget_nett('labarugi','".$cabang."','".$anggaran."')");
    }


     function save_adj($anggaran="",$cabang="",$glwnco="",$aksi="") {       

        $data   = json_decode(post('json'),true);

        $a = get_access($this->controller);
        $access_edit = false;
        if($a['access_edit'] && $cabang == user('kode_cabang')):
            $access_edit = true;
        elseif($a['access_edit'] && $a['access_additional']):
            $access_edit = true;
        endif;
        $access_additional = $a['access_additional'];

        // echo post('json');
        foreach($data as $key => $record) {

            foreach ($record as $k2 => $v2) {
                $value = filter_money($v2);
                $record[$k2] = $value;
            }

            $data = insert_view_report_arr($record);
            // echo json_encode($key);
            $record2= [];

            if($key == 'selisih'){
                  $cek  = get_data('tbl_labarugi_adj a',[
                    'select'    => 'a.id',
                    'where'     => [
                        'a.glwnco'          => $glwnco,
                        'a.kode_anggaran'   => $anggaran,
                        'a.kode_cabang'     => $cabang,
                        'a.type'            => $key,
                    ]
                ])->result_array();
            }else {
                $cek  = get_data('tbl_labarugi_adj a',[
                    'select'    => 'a.id',
                    'where'     => [
                        'a.kode_anggaran'   => $anggaran,
                        'a.kode_cabang'     => $cabang,
                        'a.type'            => $key,
                    ]
                ])->result_array();
            }
          
            $get = get_data('tbl_labarugi',[
                'where' => [
                    'glwnco' => $glwnco,
                    'kode_anggaran'   => $anggaran,
                    'kode_cabang'     => $cabang,
                ],
            ])->result_array();

            // echo json_encode($data);exit();

            if($key == 'selisih'){
                if(!empty($get)){
                    if($aksi == "t"){
                       for($i=1;$i<=12;$i++){
                            $bulan = "bulan_".$i;
                            $record2[$bulan] = $get[0][$bulan] + $data[$bulan];
                        }
                    }else {
                        for($i=1;$i<=12;$i++){
                            $bulan = "bulan_".$i;
                            $record2[$bulan] = $get[0][$bulan] - $data[$bulan];
                        }
                    }
                    
                }else {
                    for($i=1;$i<=12;$i++){
                        $bulan = "bulan_".$i;
                        $record2[$bulan] =  $data[$bulan];
                    }
                }
                $record2['glwnco'] = $glwnco;
            }else {
                for($i=1;$i<=12;$i++){
                    $bulan = "bulan_".$i;
                    $record2[$bulan] =  $data[$bulan];
                }
                $record2['glwnco'] = '';
            }

            if($access_additional):

            endif;
            
            
            $record2['type'] = $key;
            
            if(count($cek) > 0){
                update_data('tbl_labarugi_adj', $record2,'id',$cek[0]['id']);
            }else {
                    // $record2['glwnco'] = $glwnco;
                    $record2['kode_anggaran'] = $anggaran;
                    $record2['kode_cabang'] = $cabang;
                    insert_data('tbl_labarugi_adj',$record2);
            } 
            // echo json_encode($record2);
         } 
    }

    function delete_adj(){
        $kode_cabang = post('id');
        if($kode_cabang):
            delete_data('tbl_labarugi_adj',['type' => 'selisih','kode_cabang' => $kode_cabang, 'kode_anggaran' => user('kode_anggaran')]);
            render(['status' => 'success', 'message' => lang('berhasil')],'json');
        else:
            render(['status' => false,'message' => lang('data_not_found')],'json');
        endif;
    }

}