<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konsolidasi_labarugi extends BE_Controller {
    var $path = 'transaction/budget_nett_konsolidasi/';
    var $controller = 'konsolidasi_labarugi';
    var $sub_menu   = 'transaction/budget_nett_konsolidasi/sub_menu';
    var $cabang_gab = [];
    var $detail_tahun;
    var $anggaran;
    var $kode_anggaran;
    function __construct() {
        parent::__construct();
        $this->kode_anggaran  = user('kode_anggaran');
        $this->anggaran       = get_data('tbl_tahun_anggaran','kode_anggaran',$this->kode_anggaran)->row();
        $this->detail_tahun   = get_data('tbl_detail_tahun_anggaran a',[
            'select'    => 'a.bulan,a.tahun,a.sumber_data,b.singkatan',
            'join'      => 'tbl_m_data_budget b on b.id = a.sumber_data',
            'where'     => [
                'a.kode_anggaran' => $this->kode_anggaran,
                'a.tahun'         => $this->anggaran->tahun_anggaran
            ],
            'order_by' => 'tahun,bulan'
        ])->result();
    }

    function export(){
        ini_set('memory_limit', '-1');
        
        $kode_anggaran_txt  = post('kode_anggaran_txt');

        $labarugi_header = json_decode(post('labarugi_header'));
        $labarugi = json_decode(post('labarugi'));

        $data = [];
        foreach ($labarugi as $k => $v) {
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
                for($i=1;$i<=count($labarugi_header[0]);$i++){
                    $data[$k][] = '';
                }
            endif;
        }

        // render($labarugi_header,'json');exit();

        $config[] = [
            'title' => 'Konsolidasi Laba Rugi',
            'header' => $labarugi_header[0],
            'data'  => $data,
        ];
        
        $this->load->library('simpleexcel',$config);
        $filename = 'Budget_Nett_Konsolidasi_labarugi_'.str_replace(' ', '_', $kode_anggaran_txt).'_'.'_'.date('YmdHis');
        $this->simpleexcel->filename($filename);
        $this->simpleexcel->export();
        // render($labarugi_header,'json');
    }
    
    function index() {
        $kode_anggaran    = user('kode_anggaran');
        $this->db->query("CALL stored_budget_nett_konsolidasi('".$kode_anggaran."')");
        $coa_labarugi     = $this->coa_labarugi();
        $this->session->set_userdata([
            'coa_nett_labarugi' => $this->get_list_coa($coa_labarugi),
        ]);
        $data['tahun']    = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result();
        $data['path']     = $this->path;
        $data['sub_menu'] = $this->sub_menu;
        $data['controller']     = $this->controller;
        render($data,'view:'.$this->path.$this->controller.'/index');
        
    }

    private function coa_labarugi(){
        // $coa = get_data('tbl_labarugi a',[
        //     'select' => 'DISTINCT a.glwnco as coa,b.glwdes AS name, 
        //     b.glwsbi, b.glwnob,
        //     b.level0,b.level1,b.level2,b.level3,b.level4,b.level5',
        //     'join' => 'tbl_m_coa b ON a.glwnco = b.glwnco',
        //     'where' => "b.glwnco not in('')",
        //     'order_by' => 'urutan',
        // ])->result();

        $or_labarugi  = "(b.glwnco like '4%' or b.glwnco like '5%')";
        $coa = get_data('tbl_m_coa b',[
            'select' => 'DISTINCT b.glwnco as coa,b.glwdes AS name, b.glwsbi, b.glwnob, b.level0,b.level1,b.level2,b.level3,b.level4,b.level5',
            'where' => "is_active = 1 and ".$or_labarugi
        ])->result();
        return $coa;
    }

    private function get_list_coa($coa){
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

    function neraca_column($kode_anggaran,$page){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();

        $item = '';
        $dataCoa        = $this->session->{'coa_nett_'.$page};
        $dataCoaChanged = [];

        foreach ($dataCoa['coa'] as $k => $v) {
            ${'sort_coa_nett_'.$page} = [];
            if(isset($this->session->{'sort_coa_nett_'.$page})): 
                ${'sort_coa_nett_'.$page} = $this->session->{'sort_coa_nett_'.$page}; 
            endif;
            if(!in_array($v->coa, ${'sort_coa_nett_'.$page})): ${'sort_coa_nett_'.$page}[] = $v->coa; endif;
            $this->session->set_userdata(['sort_coa_nett_'.$page => ${'sort_coa_nett_'.$page}]);

            $item .= '<tr class="d-'.$page.'-'.$v->coa.'">';
            $item .= '<td class="wd-100">'.$v->glwsbi.'</td>';
            $item .= '<td class="wd-100">'.$v->glwnob.'</td>';
            $item .= '<td class="wd-100">'.$v->coa.'</td>';
            $item .= '<td class="wd-230 d-name-'.$v->coa.'">'.remove_spaces($v->name).'</td>';
            $item .= '</tr>';
            $item2 = $this->loopColumn($dataCoa,$v->coa,0,$page);
            $item .= $item2['item'];
            $dataCoaChanged = array_merge($dataCoaChanged,$item2['arr']);
            if(!$item2['item']):
                array_push($dataCoaChanged, $v->coa);
            endif;

        }

        $this->session->set_userdata([
            'nett_konsolidasi_anggaran'      => $anggaran,
            'coa_konsolidasi_changed_'.$page => $dataCoaChanged,
        ]);

        $cabang_list = $this->get_cabang();
        $item_cab    = '';
        $item_month  = '';
        $arr_kode_cabang = ['KONS','KONV'];

        $item_cab .= '<th class="d-head" colspan="12"><span>Konsolidasi</span></th>';
        foreach ($this->detail_tahun as $k => $v) {
            $column = month_lang($v->bulan).' '.$v->tahun;
            $column .= '<br> ('.$v->singkatan.')';
            $item_month .= '<th class="wd-150 text-center d-head"><span>'.$column.'</span></th>';
        }

        $item_cab .= '<th class="border-none bg-white d-head" style="min-width:80px;"></th>';
        $item_cab .= '<th class="d-head" colspan="12"><span>Konvensional</span></th>';
        $item_month .= '<th class="border-none bg-white d-head"></th>';
        foreach ($this->detail_tahun as $k => $v) {
            $column = month_lang($v->bulan).' '.$v->tahun;
            $column .= '<br> ('.$v->singkatan.')';
            $item_month .= '<th class="wd-150 text-center d-head"><span>'.$column.'</span></th>';
        }
        
        foreach ($cabang_list as $k => $v) {
            $arr_kode_cabang[] = $v->kode_cabang;
            $item_cab .= '<th class="border-none bg-white d-head" style="min-width:80px;"></th>';
            $item_cab .= '<th class="d-head" colspan="12"><span>'.$v->nama_cabang.'</span></th>';
            $item_month .= '<th class="border-none bg-white d-head"></th>';
            foreach ($this->detail_tahun as $k => $v) {
                $column = month_lang($v->bulan).' '.$v->tahun;
                $column .= '<br> ('.$v->singkatan.')';
                $item_month .= '<th class="wd-150 text-center d-head"><span>'.$column.'</span></th>';
            }
        }

        $this->session->set_userdata(['nett_konolidasi_cabang' => $arr_kode_cabang]);

        $response = [
            'status'    => true, 
            'view'      => $item,
            'cabang'    => $item_cab,
            'month'     => $item_month,
        ];
        render($response,'json');
    }

    private function get_cabang(){
        $cab = get_data('tbl_m_cabang',[
            'select'    => 'kode_cabang,nama_cabang',
            'where'     => "parent_id = 0 and is_active = 1",
            'order_by'  => "urutan"
        ])->result();
        return $cab;
    }

    private function loopColumn($full,$d,$k,$page){
        $item = '';
        $dataCoaChanged = [];
        if(isset($full['coa'.$k][$d])):
            $no = $k+1;
            foreach ($full['coa'.$k][$d] as $kx => $v) {
                ${'sort_coa_nett_'.$page} = [];
                if(isset($this->session->{'sort_coa_nett_'.$page})): 
                    ${'sort_coa_nett_'.$page} = $this->session->{'sort_coa_nett_'.$page}; 
                endif;
                if(!in_array($v->coa, ${'sort_coa_nett_'.$page})): ${'sort_coa_nett_'.$page}[] = $v->coa; endif;
                $this->session->set_userdata(['sort_coa_nett_'.$page => ${'sort_coa_nett_'.$page}]);

                $item .= '<tr class="d-'.$page.'-'.$v->coa.'">';
                $item .= '<td class="wd-100">'.$v->glwsbi.'</td>';
                $item .= '<td class="wd-100">'.$v->glwnob.'</td>';
                $item .= '<td class="wd-100">'.$v->coa.'</td>';
                $item .= '<td class="wd-230 sb-'.$no.' d-name-'.$v->coa.'">'.remove_spaces($v->name).'</td>';
                $item .= '</tr>';
                $item2 = $this->loopColumn($full,$v->coa,$no,$page);
                $item .= $item2['item'];
                $dataCoaChanged = array_merge($dataCoaChanged,$item2['arr']);
                if(!$item2['item']):
                    array_push($dataCoaChanged, $v->coa);
                endif;
            }
        endif;
        return [
            'item'  => $item,
            'arr'   => $dataCoaChanged,
        ];
    }

    function load_more(){
        $page = post('page');
        if(in_array($page, ['labarugi'])):
            $this->load_more_general($page);
        else:
            render(['status' => false, 'message' => lang('data_not_found')],'json');
        endif;
    }

    private function load_more_general($page){
        $count  = post('count');
        $cab    = $this->session->nett_konolidasi_cabang;
        $a = get_access($this->controller);
        $access_edit    = $a['access_edit'];

        $bgedit ="";
        $contentedit ="false" ;
        $id = 'keterangan';

        if(isset($cab[$count])):
            $anggaran = $this->session->nett_konsolidasi_anggaran;
            $kode_cabang      = $cab[$count];
            $coa    = $this->session->{'sort_coa_nett_'.$page};
            $dataCoaChanged = $this->session->{'coa_konsolidasi_changed_'.$page};
            $labarugi_nett = get_data('tbl_budget_nett',[
                'select' => 'coa,B_01,B_02,B_03,B_04,B_05,B_06,B_07,B_08,B_09,B_10,B_11,B_12,',
                'where'  => [
                    'kode_cabang'       => $kode_cabang,
                    'kode_anggaran'     => $anggaran->kode_anggaran,
                    'coa'               => $coa,
                ]
            ])->result_array();
           
            if($access_edit == 1 && !in_array($kode_cabang, ['KONV','KONS','G001'])) {
                // $bgedit ="#ffbb33";
                $contentedit ="true" ;
                $id = 'id' ;
            }

            $view = [];
            foreach ($coa as $k => $v) {
                $key = multidimensional_search($labarugi_nett, array(
                    'coa' => $v,
                ));
                $item = '';
                if($count != 0):
                    $item ='<td class="border-none bg-white"></td>';
                endif;
                if(strlen($key)>0):
                    $dt = $labarugi_nett[$key];
                    for ($i=1; $i <=12 ; $i++) { 
                        $field = 'B_'.sprintf("%02d", $i);
                        if(in_array($v, $dataCoaChanged)):
                            $item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$field.'" data-id="'.$page.'-'.$kode_cabang.'-'.$v.'" data-value="'.$dt[$field].'">'.check_value($dt[$field]).'</div></td>';
                        else:
                            $item .= '<td class="text-right">'.check_value($dt[$field]).'</td>';
                        endif;
                    }
                else:
                    for ($i=1; $i <=12 ; $i++) {
                        $field = 'B_'.sprintf("%02d", $i);
                        if(in_array($v, $dataCoaChanged)):
                            $item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$field.'" data-id="'.$page.'-'.$kode_cabang.'-'.$v.'" data-value="0"></div></td>';
                        else:
                            $item .= '<td class="text-right"></td>';
                        endif;
                    }
                endif;
                $view['.d-'.$page.'-'.$v] = $item;
            }
            render([
                'status'    => true,
                'view'      => $view,
                'count'     => ($count+1),
                'classnya'  => '#'.$page.' tbody',
            ],'json');
        else:
            render([
                'status'    => false,
            ],'json');
            $this->session->unset_userdata([
                'sort_coa_nett_'.$page,
                'nett_konsolidasi_anggaran',
                'coa_konsolidasi_changed_'.$page
            ]);
        endif;
    }

    function save_perubahan(){
        $kode_anggaran = post('kode_anggaran');
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
        $data   = json_decode(post('json'),true);
        $arr_kode_cabang = [];
        foreach($data as $k => $record) {
            $x = explode('-', $k);
            $page       = $x[0];
            $cabang     = $x[1];
            $coa        = $x[2];

            if(!in_array($cabang, $arr_kode_cabang)) array_push($arr_kode_cabang,$cabang);

            $table = 'tbl_budget_nett';
            $ck = get_data($table,[
                'select'    => 'id,changed',
                'where'     => "coa = '$coa' and kode_cabang = '$cabang' and kode_anggaran = '$kode_anggaran'",
            ])->row();
            if($ck):
                $changed = json_decode($ck->changed,true);
                foreach ($record as $k2 => $v2) {
                    $value = filter_money($v2);
                    if(!in_array($k2, $changed)):
                        array_push($changed, $k2);
                    endif;
                    $record[$k2] = insert_view_report($value);
                }
                $record['changed'] = json_encode($changed);
                $record['status_update_group'] = 0;
                $where = [
                    'coa' => $coa,
                    'kode_cabang' => $cabang,
                    'kode_anggaran' => $kode_anggaran,
                ];
                update_data($table,$record,$where);
            else:
                $changed = [];
                foreach ($record as $k2 => $v2) {
                    $value = filter_money($v2);
                    if(!in_array($k2, $changed)):
                        array_push($changed, $k2);
                    endif;
                    $record[$k2] = insert_view_report($value);
                }
                $record['changed'] = json_encode($changed);

                $h = $record;
                $h['coa']                   = $coa;
                $h['kode_anggaran']         = $anggaran->kode_anggaran;
                $h['kode_cabang']           = $cabang;
                insert_data($table,$h);
            endif;
        }
        if(count($arr_kode_cabang)>0):
            // $this->db->query('CALL stored_budget_nett_sum_coa("'.$kode_anggaran.'","'.implode(",", $arr_kode_cabang).'")');
            foreach ($arr_kode_cabang as $v) {
                $this->db->query('CALL stored_budget_nett_sum_coa("'.$kode_anggaran.'","'.$v.'")');
            }
        endif;
        $this->db->query("CALL stored_budget_nett_konsolidasi('".$kode_anggaran."')");
    }
}