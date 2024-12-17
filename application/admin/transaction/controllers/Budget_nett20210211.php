<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget_nett extends BE_Controller {
    var $path = 'transaction/budget_nett/';
    var $controller = 'budget_nett';
    var $cabang_gab = [];
    function __construct() {
        parent::__construct();
    }

    function export(){
        ini_set('memory_limit', '-1');
        
        $kode_anggaran_txt  = post('kode_anggaran_txt');
        $kode_cabang_txt    = post('kode_cabang_txt');

        $neraca_header = json_decode(post('neraca_header'));
        $neraca = json_decode(post('neraca'));
        $config[] = [
            'title' => 'Budget Nett',
            'header' => $neraca_header[0],
            'data'  => $neraca,
        ];

        $labarugi_header = json_decode(post('labarugi_header'));
        $labarugi = json_decode(post('labarugi'));
        $config[] = [
            'title' => 'Laba Rugi Nett',
            'header' => $labarugi_header[0],
            'data'  => $labarugi,
        ];

        $this->load->library('simpleexcel',$config);
        $filename = 'Budget_Nett_'.str_replace(' ', '_', $kode_anggaran_txt).'_'.str_replace(' ', '_', $kode_cabang_txt).'_'.date('YmdHis');
        $this->simpleexcel->filename($filename);
        $this->simpleexcel->export();
        // render($neraca_header,'json');
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('neraca_new')
            ]
        ])->result();

        $data['cabang']            = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang,level_cabang',
            'where'     => "a.is_active = 1 and a.kode_cabang like 'G%'"
        ])->result_array();

        $data['cabang_input'] = get_data('tbl_m_cabang a',[
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.kode_cabang' => user('kode_cabang')
            ]
        ])->result_array();

        $data['tahun'] = get_data('tbl_tahun_anggaran','kode_anggaran',user('kode_anggaran'))->result();
        $data['path'] = $this->path;
        return $data;
    }
    
    function index($p1="") {
        $kode_anggaran = user('kode_anggaran');
        $this->db->query("CALL stored_budget_nett('".$kode_anggaran."')");
        $coa_neraca     = $this->coa_neraca();
        $coa_labarugi   = $this->coa_labarugi();
        $this->session->set_userdata([
            'coa_neraca' => $this->get_list_coa($coa_neraca),
            'coa_labarugi' => $this->get_list_coa_labarugi($coa_labarugi),
        ]);
        $data = $this->data_cabang();
        render($data,'view:'.$this->path.'index');
        
    }

    private function coa_neraca(){
        $coa = get_data('tbl_budget_plan_neraca a',[
            'select' => 'DISTINCT a.coa,b.glwdes AS name, 
            b.glwsbi, b.glwcoa,
            b.level0,b.level1,b.level2,b.level3,b.level4,b.level5',
            'join' => 'tbl_m_coa b ON a.coa = b.glwnco'
        ])->result();

        return $coa;
    }
    private function coa_labarugi(){
        $coa = get_data('tbl_labarugi a',[
            'select' => 'DISTINCT a.glwnco as coa,b.glwdes AS name, 
            b.glwsbi, b.glwcoa,
            b.level0,b.level1,b.level2,b.level3,b.level4,b.level5',
            'join' => 'tbl_m_coa b ON a.glwnco = b.glwnco',
            'where' => "b.glwnco not in('')"
        ])->result();
        return $coa;
    }

    private function get_list_coa($coa){
        $data = [];
        foreach ($coa as $k => $v) {
            
            // center
            if(!$v->level0 && !$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $h = $v;
                $data['coa'][] = $h;
            endif;

            // level 0
            if($v->level0 && !$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $h = $v;
                $data['coa0'][$v->level0][] = $h;
            endif;

            // level 1
            if(!$v->level0 && $v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $h = $v;
                $data['coa1'][$v->level1][] = $h;
            endif;

            // level 2
            if(!$v->level0 && !$v->level1 && $v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $h = $v;
                $data['coa2'][$v->level2][] = $h;
            endif;

            // level 3
            if(!$v->level0 && !$v->level1 && !$v->level2 && $v->level3 && !$v->level4 && !$v->level5):
                $h = $v;
                $data['coa3'][$v->level3][] = $h;
            endif;

            // level 4
            if(!$v->level0 && !$v->level1 && !$v->level2 && !$v->level3 && $v->level4 && !$v->level5):
                $h = $v;
                $data['coa4'][$v->level4][] = $h;
            endif;

            // level 5
            if(!$v->level0 && !$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && $v->level5):
                $h = $v;
                $data['coa5'][$v->level5][] = $h;
            endif;
        }
        return $data;
    }


    private function get_list_coa_labarugi($coa){
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


      function rekaprasio_column($kode_anggaran,$kode_cabang){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
        $gab = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();
        if(!$gab || !$anggaran):
            render(['status' => false, 'message' => lang('data_not_found')],'json');
            exit();
        endif;

        $item = '';
        $dataRekap = get_data('tbl_keterangan_rekaprasio')->result();
        foreach ($dataRekap as $k => $v) {
            $item .= '<tr class="d-rekaprasio-'.str_replace('.', '-', $v->kode).'">';
            $item .= '<td class="wd-100">'.$v->kode.'</td>';
            $item .= '<td class="wd-100">'.$v->keterangan.'</td>';
            $item .= '</tr>';
            // $item .= $this->loopColumn($dataCoa,$v->coa,0,$page);
        }
        // exit();
        $this->session->set_userdata([
            'nett_anggaran' => $anggaran,
        ]);

        $cabang_list = $this->get_cabang($gab);
        $item_cab       = '';
        $item_month  = '';
        foreach ($cabang_list as $k => $v) {
            $item_cab .= '<th class="d-head" colspan="12"><span>'.$v->nama_cabang.'</span></th>';
            for ($i=1; $i <=12 ; $i++) { 
                $item_month .= '<th class="wd-150 d-head"><span>'.month_lang($i).'</span></th>';
            }
        }

        $response = [
            'status'    => true, 
            'view'      => $item,
            'cabang'    => $item_cab,
            'month'     => $item_month,
        ];
        render($response,'json');
    }

    function neraca_column($kode_anggaran,$kode_cabang,$page){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
        $gab = get_data('tbl_m_cabang','kode_cabang',$kode_cabang)->row();
        if(!$gab || !$anggaran):
            render(['status' => false, 'message' => lang('data_not_found')],'json');
            exit();
        endif;

        $item = '';
        $dataCoa        = $this->session->{'coa_'.$page};
        $dataCoaChanged = [];
        $this->cabang_gab = $gab;

        foreach ($dataCoa['coa'] as $k => $v) {
            // echo $v->coa.'<br>';
            ${'sort_coa_'.$page} = [];
            if(isset($this->session->{'sort_coa_'.$page})): 
                ${'sort_coa_'.$page} = $this->session->{'sort_coa_'.$page}; 
            endif;
            if(!in_array($v->coa, ${'sort_coa_'.$page})): ${'sort_coa_'.$page}[] = $v->coa; endif;
            $this->session->set_userdata(['sort_coa_'.$page => ${'sort_coa_'.$page}]);

            $item .= '<tr class="d-'.$page.'-'.$v->coa.'">';
            $item .= '<td class="wd-100">'.$v->glwsbi.'</td>';
            $item .= '<td class="wd-100">'.$v->glwcoa.'</td>';
            $item .= '<td class="wd-100">'.$v->coa.'</td>';
            $item .= '<td class="wd-230">'.remove_spaces($v->name).'</td>';
            for ($i=1; $i <=12 ; $i++) { 
                $item .= '<td class="text-right d-'.$page.'-'.$v->coa.'-'.$this->cabang_gab->id.'-'.$i.'">0</td>';
            }
            $item .= '</tr>';
            $item2 = $this->loopColumn($dataCoa,$v->coa,0,$page);
            $item .= $item2['item'];
            $dataCoaChanged = array_merge($dataCoaChanged,$item2['arr']);
            if(!$item2['item']):
                array_push($dataCoaChanged, $v->coa);
            endif;

        }
        // exit();
        $this->session->set_userdata([
            'nett_anggaran'     => $anggaran,
            'nett_cabang_gab_'.$page   => $this->cabang_gab,
            'total_gab_neraca'   => [],
            'total_gab_labarugi' => [],
            'coa_changed_'.$page => $dataCoaChanged,
        ]);

        $cabang_list = $this->get_cabang($gab);
        $item_cab       = '';
        $item_month  = '';

        $item_cab .= '<th class="d-head" colspan="12"><span>'.$gab->nama_cabang.'</span></th>';
        for ($i=1; $i <=12 ; $i++) { 
            $item_month .= '<th class="wd-150 d-head"><span>'.month_lang($i).'</span></th>';
        }
        
        foreach ($cabang_list as $k => $v) {
            $item_cab .= '<th class="d-head" colspan="12"><span>'.$v->nama_cabang.'</span></th>';
            for ($i=1; $i <=12 ; $i++) { 
                $item_month .= '<th class="wd-150 d-head"><span>'.month_lang($i).'</span></th>';
            }
        }

        $response = [
            'status'    => true, 
            'view'      => $item,
            'cabang'    => $item_cab,
            'month'     => $item_month,
        ];
        render($response,'json');
    }
    private function get_cabang($gab){
        $cab = get_data('tbl_m_cabang',[
            'select'    => 'kode_cabang,nama_cabang',
            'where'     => "parent_id = '$gab->id'",
            'order_by'  => "kode_cabang"
        ])->result();
        $this->session->set_userdata(['nett_cabang' => $cab]);
        return $cab;
    }
    private function loopColumn($full,$d,$k,$page){
        $item = '';
        // echo json_encode($full);
        // exit();
        $dataCoaChanged = [];
        if(isset($full['coa'.$k][$d])):
            // echo json_encode($full);
            // exit();
            $no = $k+1;
            foreach ($full['coa'.$k][$d] as $kx => $v) {
                ${'sort_coa_'.$page} = [];
                if(isset($this->session->{'sort_coa_'.$page})): 
                    ${'sort_coa_'.$page} = $this->session->{'sort_coa_'.$page}; 
                endif;
                if(!in_array($v->coa, ${'sort_coa_'.$page})): ${'sort_coa_'.$page}[] = $v->coa; endif;
                $this->session->set_userdata(['sort_coa_'.$page => ${'sort_coa_'.$page}]);

                $item .= '<tr class="d-'.$page.'-'.$v->coa.'">';
                $item .= '<td class="wd-100">'.$v->glwsbi.'</td>';
                $item .= '<td class="wd-100">'.$v->glwcoa.'</td>';
                $item .= '<td class="wd-100">'.$v->coa.'</td>';
                $item .= '<td class="wd-230 sb-'.$no.'">'.remove_spaces($v->name).'</td>';
                for ($i=1; $i <=12 ; $i++) {
                    $item .= '<td class="text-right d-'.$page.'-'.$v->coa.'-'.$this->cabang_gab->id.'-'.$i.'">0</td>';
                }
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
        if(in_array($page, ['neraca','labarugi'])):
            $this->load_more_general($page);
        else:
            render(['status' => false, 'message' => lang('data_not_found')],'json');
        endif;
    }

    private function load_more_general($page){
        $count  = post('count');
        $cab    = $this->session->nett_cabang;
        $total_gab = $this->session->{'total_gab_'.$page};
        $a = get_access($this->controller);
        $access_edit    = $a['access_edit'];

        $bgedit ="";
        $contentedit ="false" ;
        $id = 'keterangan';
        if($access_edit == 1) {
            $bgedit ="#ffbb33";
            $contentedit ="true" ;
            $id = 'id' ;
        }

        if(isset($cab[$count])):
            $anggaran = $this->session->nett_anggaran;
            $d      = $cab[$count];
            $coa    = $this->session->{'sort_coa_'.$page};
            $dataCoaChanged = $this->session->{'coa_changed_'.$page};
            $neraca_nett = get_data('tbl_budget_nett',[
                'select' => 'coa,B_01,B_02,B_03,B_04,B_05,B_06,B_07,B_08,B_09,B_10,B_11,B_12,',
                'where'  => [
                    'kode_cabang'       => $d->kode_cabang,
                    'kode_anggaran'     => $anggaran->kode_anggaran,
                    'coa'               => $coa,
                ]
            ])->result_array();
           

            $view = [];
            foreach ($coa as $k => $v) {
                $key = multidimensional_search($neraca_nett, array(
                    'coa' => $v,
                ));
                $item = '';
                if(strlen($key)>0):
                    $dt = $neraca_nett[$key];
                    for ($i=1; $i <=12 ; $i++) { 
                        $field = 'B_'.sprintf("%02d", $i);
                        if(in_array($v, $dataCoaChanged)):
                            $item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$field.'" data-id="'.$page.'-'.$d->kode_cabang.'-'.$v.'" data-value="'.$dt[$field].'">'.check_value($dt[$field]).'</div></td>';
                        else:
                            $item .= '<td class="text-right">'.check_value($dt[$field]).'</td>';
                        endif;

                        if(isset($total_gab[$v][$i])): $total_gab[$v][$i] += $dt[$field];
                        else: $total_gab[$v][$i] = $dt[$field]; endif;
                    }
                else:
                    for ($i=1; $i <=12 ; $i++) {
                        $field = 'B_'.sprintf("%02d", $i);
                        if(in_array($v, $dataCoaChanged)):
                            $item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$field.'" data-id="'.$page.'-'.$d->kode_cabang.'-'.$v.'" data-value="0"></div></td>';
                        else:
                            $item .= '<td class="text-right"></td>';
                        endif;
                    }
                endif;
                $view['.d-'.$page.'-'.$v] = $item;
            }
            $this->session->set_userdata([
                'total_gab_'.$page => $total_gab,
            ]);
            render([
                'status'    => true,
                'view'      => $view,
                'count'     => ($count+1),
                'classnya'  => '#'.$page.' tbody',
            ],'json');
        else:
            $cabang_gab = $this->session->{'nett_cabang_gab_'.$page};
            foreach ($total_gab as $k => $v) {
                for ($i=1; $i <= 12 ; $i++) { 
                    $total_gab[$k][$i] = check_value($total_gab[$k][$i]);
                }
            }
            render([
                'status'    => false,
                'total_gab' => $total_gab,
                'id'        => $cabang_gab->id,
                'classnya'  => '#'.$page.' tbody',
            ],'json');
            $this->session->unset_userdata([
                'sort_coa_'.$page,
                'total_gab_'.$page,
            ]);
        endif;
    }


    function load_more_rekap(){
        $count  = post('count');
        $cab    = $this->session->nett_cabang;
        if(isset($cab[$count])):
            $anggaran = $this->session->nett_anggaran;
            $d      = $cab[$count];
            $select = '';
            for($i=1;$i<=12;$i++){
                    $select .= "bulan_".$i.",";
            }
            $dataRekap = get_data('tbl_rekap_rasio',[
                'select'    => $select.',kode',
                'where'     => "kode_cabang = '$d->kode_cabang' and kode_anggaran = '$anggaran->kode_anggaran'"
            ])->result_array();
            $view = [];
            foreach ($dataRekap as $k => $v) {
                $item = '';
                if(count($dataRekap)>0):
                    for ($i=1; $i <=12 ; $i++) { 
                        $field = 'bulan_'.$i;
                        $item .= '<td>'.check_value($v[$field]).'</td>';
                    }
                else:
                    for ($i=1; $i <=12 ; $i++) { 
                        $item .= '<td></td>';
                    }
                endif;
                $view['.d-rekaprasio-'.str_replace('.', '-',$v['kode'])] = $item;
            }

            render([
                'status'    => true,
                'view'      => $view,
                'count'     => ($count+1),
                'classnya'  => '#rekaprasio tbody',
            ],'json');
        else:
            render(['status' => false],'json');
            
        endif;
    }

    private function load_more_labarugi(){
        
    }

    function save_perubahan(){
        $kode_anggaran = post('kode_anggaran');
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
        $data   = json_decode(post('json'),true);
        foreach($data as $k => $record) {
            $x = explode('-', $k);
            $page       = $x[0];
            $cabang     = $x[1];
            $coa        = $x[2];

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
    }
}