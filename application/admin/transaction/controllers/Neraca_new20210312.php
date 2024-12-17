<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Neraca_new extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    var $controller = 'neraca_new';
    var $table = "tbl_budget_plan_neraca";
    var $arrAdjusment = ['1801000','2801000'];
    var $arrHeadAdjusment = ['1000000','2000000'];
    var $arrDpk = ['2130000','2120011','2100000'];
    var $arrHimpunanDana = [];
    var $arrAdditional = [];

    function __construct() {
        parent::__construct();
        $this->arrAdditional = get_data('tbl_m_neraca_nett_additional',[
            'select' => 'id,nama,coa',
            'where'  => 'is_active = 1',
        ])->result_array();
    }

    private function data_cabang(){
        $cabang_user  = get_data('tbl_user',[
            'where' => [
                'is_active' => 1,
                'id_group'  => id_group_access('neraca_new')
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
            'select'    => 'distinct a.kode_cabang,a.nama_cabang',
            'where'     => [
                'a.is_active' => 1,
                'a.'.$x => $cab->id,
                'a.kode_cabang' => $kode_cabang
            ]
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
        $access         = get_access('neraca_new');
        $akses_ubah     = $access['access_edit'];

        $data = $this->data_cabang();
        $data['akses_ubah'] = $akses_ubah;
        $data['bulan_terakhir'] = month_lang($data['tahun'][0]->bulan_terakhir_realisasi);
        render($data,'view:'.$this->path.'neraca_new/index');
    }

    function data ($anggaran="", $cabang=""){
        $this->session->unset_userdata(['dt_neraca','dt_anggaran','arrAktiva','arrPasiva','coa1801000','coa2801000','arrDpk']);
        foreach ($this->arrAdditional as $addit) {
            $key_addit      = 'additional_'.$addit['id'];
            $this->session->unset_userdata([$key_addit]);
        }
        
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();

        $bln_trakhir = $anggaran->bulan_terakhir_realisasi;
        $thn_trakhir = $anggaran->tahun_terakhir_realisasi;
        $tbl_history = 'tbl_history_'.$thn_trakhir;

        $or_neraca  = "(a.glwnco like '1%' or a.glwnco like '2%' or a.glwnco like '3%' or a.glwnco LIKE '41%' AND a.level1 = '2120011')";
        $select     = 'level0,level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus,';
        $selectJoin = 'c.id as fID,c.realisasi as frealisasi,c.changed as fchanged,';
        for ($i=1; $i <=12 ; $i++) {
            $field = sprintf("%02d", $i); 
            $selectJoin .= 'c.B_'.$field.' as fB_'.$field.', ';
        }
        $coa = get_data('tbl_m_coa a',[
            'select' => $select.$selectJoin.',b.TOT_'.$cabang,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => [
                "$tbl_history b on b.bulan = '$bln_trakhir' and a.glwnco = b.glwnco type left",
                "tbl_budget_plan_neraca c on a.glwnco = c.coa and kode_anggaran = '$anggaran->kode_anggaran' and kode_cabang = '$cabang' type left",
            ]
        ])->result();

        $query_result     = $this->db->query("CALL stored_neraca_nett('$cabang','$anggaran->kode_anggaran','$anggaran->tahun_anggaran')");
        // $query_result = $this->get_data_net($anggaran->kode_anggaran,$cabang,$anggaran->tahun_anggaran);
        $detail           = $query_result->result_array();

        //add this two line 
        $query_result->next_result(); 
        $query_result->free_result(); 
        //end of new code

        $coa = $this->get_list_coa($coa,$detail);
        // render(['coa' => $coa],'json');
        // exit();
        $this->session->set_userdata(array(
            'dt_neraca'     => $coa,
            'dt_anggaran'   => $anggaran,
        ));

        $data['coa']    = $coa['coa'];
        $data['detail'] = $coa['detail'];
        $data['cabang'] = $cabang;
        $dt_view = $this->get_view_coa($data,0);

        $response   = $dt_view;
        $response['aa'] = $coa['detail'];
        render($response,'json');
    }

    private function get_list_coa($coa,$detail){
        $data = [];
        foreach ($coa as $k => $v) {
            
            // center
            if(!$v->level0 && !$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'coa' => $v->glwnco,
                ));
                $h = $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['coa'][] = $h;
            endif;

            // level 0
            if($v->level0 && !$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'coa' => $v->glwnco,
                ));
                $h = $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['coa0'][$v->level0][] = $h;
            endif;

            // level 1
            if(!$v->level0 && $v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'level1' => $v->level1,
                    'coa'    => $v->glwnco,
                ));
                $h = (array) $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['coa1'][$v->level1][] = $h;
            endif;

            // level 2
            if(!$v->level0 && !$v->level1 && $v->level2 && !$v->level3 && !$v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'level2' => $v->level2,
                    'coa'    => $v->glwnco,
                ));
                $h = (array) $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['coa2'][$v->level2][] = $h;
            endif;

            // level 3
            if(!$v->level0 && !$v->level1 && !$v->level2 && $v->level3 && !$v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'level3' => $v->level3,
                    'coa'    => $v->glwnco,
                ));
                $h = (array) $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['coa3'][$v->level3][] = $h;
            endif;

            // level 4
            if(!$v->level0 && !$v->level1 && !$v->level2 && !$v->level3 && $v->level4 && !$v->level5):
                $key = multidimensional_search($detail, array(
                    'level4' => $v->level4,
                    'coa'    => $v->glwnco,
                ));
                $h = (array) $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['coa4'][$v->level4][] = $h;
            endif;

            // level 5
            if(!$v->level0 && !$v->level1 && !$v->level2 && !$v->level3 && !$v->level4 && $v->level5):
                $key = multidimensional_search($detail, array(
                    'level5' => $v->level5,
                    'coa'    => $v->glwnco,
                ));
                $h = (array) $v;
                if(strlen($key)>0):
                    $h = array_merge($h,$detail[$key]);
                endif;
                $data['detail']['coa5'][$v->level5][] = $h;
            endif;
        }
        return $data;
    }

    private function get_view_coa($data,$count){
        $no = $count;
        $status = false;
        $view = '';
        for ($i=$count; $i <($count+1) ; $i++) { 
            if(isset($data['coa'][$i])){
                $status = true;
                $no += 1;
                $data['key'] = $i;
                $view .= $this->loadView($data);
            }else{
                break;
            }
        }

        $res = [
            'status'    => $status,
            'view'      => $view,
            'count'     => $no,
        ];
        return $res;

    }

    function loadMore($anggaran,$cabang,$count){
        $coa = $this->session->dt_neraca;
        $data['coa']    = $coa['coa'];
        $data['detail'] = $coa['detail'];
        $data['cabang'] = $cabang;

        $dt_view = $this->get_view_coa($data,$count);

        $response   = $dt_view;
        render($response,'json');
    }

    private function loadView($data){
        $coa    = $data['coa'];
        $detail = $data['detail'];
        $key    = $data['key'];
        $cabang = $data['cabang'];

        $item = '';
        $td_transparnt = '<td class="border-none bg-transparent"></td>';

        $v = $coa[$key];
        $v = json_encode($v);$v = json_decode($v);

        $item2  = '';
        $dt2    = [];
        $dt_status2 = true;
        $minus  = $v->kali_minus;
        $arrAktiva = [];
        if($this->session->arrAktiva){ $arrAktiva = $this->session->arrAktiva; }
        $arrPasiva = [];
        if($this->session->arrPasiva){ $arrPasiva = $this->session->arrPasiva; }

        $bln_trakhir = $v->{'TOT_'.$cabang};
        if(isset($detail['coa0'][$v->glwnco])){
            $dt = $this->loadViewLoop($data,$detail['coa0'][$v->glwnco],0);
            $item2  = $dt['item'];
            $dt2    = $dt['dt'];
            $dt_status2    = $dt['dt_status'];
            $bln_trakhir = '';
            $value = 0;
        }else{
            $bln_trakhir = $v->{'TOT_'.$cabang};
            $value = kali_minus($bln_trakhir,$minus);
            $bln_trakhir = check_value($value);
        }
        $status = true;
        if($dt_status2 || isset($v->tipe)):
            $minus = 0;
            $status = true;
        endif;
        $status_update = false;
        $arrUpdate = [];
        $arrInsert = [];
        $item = '';
        if(in_array($v->glwnco, ['2000000'])):
             $item .= '<tr class="d-spaces"></tr>';
        endif;
        if(in_array($v->glwnco, $this->arrHeadAdjusment)):
            $item .= '<tr class="d-'.$v->glwnco.'">';
        else:
            $item .= '<tr>';
        endif;

        // buat variable himpunan
        foreach ($this->arrAdditional as $addit) {
            $key_addit = 'additional_'.$addit['id'];
            ${$key_addit} = [];
            $coa_addit = json_decode($addit['coa'],true);
            if(in_array($v->glwnco, $coa_addit)):
                ${$key_addit} = $this->session->{$key_addit};
            endif;
        }

        $item .= '<td class="wd-100">'.$v->glwsbi.'</td>';
        $item .= '<td class="wd-100">'.$v->glwcoa.'</td>';
        $item .= '<td class="wd-100">'.$v->glwnco.'</td>';
        $item .= '<td class="wd-230">'.remove_spaces($v->glwdes).'</td>';
        for ($i=1; $i <= 12 ; $i++) {
            $field  = 'B_' . sprintf("%02d", $i);
            
            if(count($dt2)>0){ $val = $dt2[$i]; }
            else{ 
                $val = $value; 
                if(isset($v->{$field})){ $val =  $v->{$field}; }
            }
            $val = kali_minus($val,$minus);
            if($v->glwnco == '1000000'):
                $arrAktiva[$field] = $val;
                $item .= '<td class="text-right '.$field.'">'.check_value($val).'</td>';
            elseif($v->glwnco == '2000000'):
                $arrPasiva[$field] = $val;
                $item .= '<td class="text-right '.$field.'">'.check_value($val).'</td>';
            else:
                $item .= '<td class="text-right">'.check_value($val).'</td>';
            endif;

            // sum untuk coa himpunan
            foreach ($this->arrAdditional as $addit) {
                $key_addit = 'additional_'.$addit['id'];
                $coa_addit = json_decode($addit['coa'],true);
                if(in_array($v->glwnco, $coa_addit)):
                    if(isset(${$key_addit}[$field])):
                        ${$key_addit}[$field] += $val;
                    else:
                        ${$key_addit}[$field] = $val;
                    endif;
                endif;
            }

            if($v->fID):
                if($val != $v->{'f'.$field}):
                    $status_update = true;
                    $arrUpdate[$field] = $val;
                endif;
            else:
                $status_update = true;
                $arrInsert[$field] = $val;
            endif;
        }
        if($v->fID):
            if($val != $v->{'frealisasi'}):
                $status_update = true;
                $arrUpdate['realisasi'] = $value;
            endif;
        else:
            $status_update = true;
            $arrDataInsert['realisasi'] = $value;
        endif;
        if($status_update):
            $this->update_data($arrUpdate,$v->fID);
            $this->insert_data($arrInsert,$v->glwnco,$cabang);
        endif;

        // simpan ke session himpunan dana
        foreach ($this->arrAdditional as $addit) {
            $key_addit = 'additional_'.$addit['id'];
            $coa_addit = json_decode($addit['coa'],true);
            if(in_array($v->glwnco, $coa_addit)):
                $this->session->set_userdata([$key_addit => ${$key_addit}]);
            endif;
        }

        $item .= $td_transparnt;
        $item .= '<td class="text-right">'.$bln_trakhir.'</td>';
        $item .= '</tr>';
        if(!$status):
            $item = '';
        endif;
        $item .= $item2;

        $this->session->set_userdata(array('arrAktiva' => $arrAktiva, 'arrPasiva' => $arrPasiva));

        return $item;
    }

    private function loadViewLoop($data, $data2, $kk){
        $detail = $data['detail'];
        $cabang = $data['cabang'];

        $access         = get_access('neraca_new');
        $akses_ubah     = $access['access_edit'];

        $bgedit ="";
        $contentedit ="false" ;
        $id = 'keterangan';
        if($akses_ubah == 1) {
            $bgedit ="#ffbb33";
            $contentedit ="true" ;
            $id = 'id' ;
        }

        $data2 = json_encode($data2);$data2 = json_decode($data2);

        $item   = '';
        $td_transparnt = '<td class="border-none bg-transparent"></td>';
        $dt     = [];
        $dt_status = true;
        if($kk<=5){
            foreach ($data2 as $k2 => $v2) {
                $item2      = '';
                $dt2        = [];
                $d_status2  = true;
                $minus  = $v2->kali_minus;
                if(isset($detail['coa'.($kk+1)][$v2->glwnco])){
                    $dd = $detail['coa'.($kk+1)][$v2->glwnco];
                    $dd = $this->loadViewLoop($data,$dd,($kk+1));
                    $item2  = $dd['item'];
                    $dt2    = $dd['dt'];
                    $d_status2 = $dd['dt_status'];
                    $bln_trakhir = '';
                    $value = 0;
                }else{
                    $bln_trakhir = $v2->{'TOT_'.$cabang};
                    $value = kali_minus($bln_trakhir,$minus);
                    if($v2->fID)://pengecekan untuk mengambil realisasi trakhir dari table
                        $changed = json_decode($v2->fchanged,true);
                        if(isset($changed['realisasi']) && $changed['realisasi'] == 1):
                            $value = $v2->frealisasi;
                        endif;
                    endif;
                    $bln_trakhir = check_value($value);
                }
                $status = true;
                $item3 = '';
                if($v2->glwnco == '2150000'):// cek untuk total giro+tabungan+simpanan berjakngka
                    $item3 .= '<tr class="d-total-pasiva"></tr>';
                endif;

                // buat variable adjusment
                if(in_array($v2->glwnco, $this->arrAdjusment)):
                    $arrAdjusmentSet = [];
                    if($this->session->{'coa'.$v2->glwnco}) $arrAdjusmentSet = $this->session->{'coa'.$v2->glwnco};
                    $item3  .= '<tr class="d-'.$v2->glwnco.'">';
                else:
                    $item3  .= '<tr>';
                endif;

                // buat variable himpunan
                foreach ($this->arrAdditional as $addit) {
                    $key_addit = 'additional_'.$addit['id'];
                    ${$key_addit} = [];
                    $coa_addit = json_decode($addit['coa'],true);
                    if(in_array($v2->glwnco, $coa_addit)):
                        ${$key_addit} = $this->session->{$key_addit};
                    endif;
                }

                //buat variable dpk
                if(in_array($v2->glwnco, $this->arrDpk)):
                    $arrDpkSet = [];
                    if($this->session->arrDpk): $arrDpkSet = $this->session->arrDpk; endif;
                endif;
                $item3 .= '<td class="wd-100">'.$v2->glwsbi.'</td>';
                $item3 .= '<td class="wd-100">'.$v2->glwcoa.'</td>';
                $item3 .= '<td class="wd-100">'.$v2->glwnco.'</td>';
                $item3 .= '<td class="wd-230 sb-'.($kk+1).'">'.remove_spaces($v2->glwdes).'</td>';
                if($d_status2 || isset($v2->tipe)):
                    $minus = 0;
                    $status = true;
                endif;
                $arrUpdate = [];
                $arrInsert = [];
                $named = $v2->glwnco.'-'.$cabang;
                $status_update = false;
                for ($i=1; $i <= 12 ; $i++) {
                    $field  = 'B_' . sprintf("%02d", $i);

                    if(count($dt2)>0){ $val = $dt2[$i]; }
                    else{ 
                        $val = $value; 
                        if(isset($v2->{$field})){ $val = $v2->{$field}; }
                    }
                    $val = kali_minus($val,$minus);
                    if(in_array($v2->glwnco, $this->arrAdjusment)):
                        $arrAdjusmentSet[$field] = $val;
                        $item3 .= '<td class="text-right d-'.$field.'">'.check_value($val).'</td>';
                    else:
                        $item3 .= '<td class="text-right">'.check_value($val).'</td>';
                    endif;

                    // sum untuk coa himpunan
                    foreach ($this->arrAdditional as $addit) {
                        $key_addit = 'additional_'.$addit['id'];
                        $coa_addit = json_decode($addit['coa'],true);
                        if(in_array($v2->glwnco, $coa_addit)):
                            if(isset(${$key_addit}[$field])):
                                ${$key_addit}[$field] += $val;
                            else:
                                ${$key_addit}[$field] = $val;
                            endif;
                        endif;
                    }

                    // sum coa dpk
                    if(in_array($v2->glwnco, $this->arrDpk)):
                        if(isset($arrDpkSet[$field])):
                            $arrDpkSet[$field] += $val;
                        else:
                            $arrDpkSet[$field] = $val;
                        endif;
                    endif;
                    if(isset($dt[$i])){ $dt[$i] += $val; }else{ $dt[$i] = $val; }
                    if($v2->fID):
                        if($val != $v2->{'f'.$field}):
                            $status_update = true;
                            $arrUpdate[$field] = $val;
                        endif;
                    else:
                        $status_update = true;
                        $arrInsert[$field] = $val;
                    endif;
                }
                if($v2->fID)://pengecekan untuk insert atau update
                    if($val != $v2->{'frealisasi'}):
                        $status_update = true;
                        $arrUpdate['realisasi'] = $value;
                    endif;
                else:
                    $status_update = true;
                    $arrDataInsert['realisasi'] = $value;
                endif;

                // simpan ke session adjusment
                if(in_array($v2->glwnco, $this->arrAdjusment)):
                    $this->session->set_userdata(['coa'.$v2->glwnco => $arrAdjusmentSet]);
                endif;

                // simpan ke session himpunan dana
                foreach ($this->arrAdditional as $addit) {
                    $key_addit = 'additional_'.$addit['id'];
                    $coa_addit = json_decode($addit['coa'],true);
                    if(in_array($v2->glwnco, $coa_addit)):
                        $this->session->set_userdata([$key_addit => ${$key_addit}]);
                    endif;
                }

                // simpan ke session dpk
                if(in_array($v2->glwnco, $this->arrDpk)):
                    $this->session->set_userdata(['arrDpk' => $arrDpkSet]);
                endif;


                if($status_update)://jika ada yg diinsert atau update
                    $this->update_data($arrUpdate,$v2->fID);
                    $this->insert_data($arrInsert,$v2->glwnco,$cabang);
                endif;
                $item3 .= $td_transparnt;
                if(count($dt2)>0){
                    $item3 .= '<td class="text-right"></td>';
                }
                else{
                    $name = $v2->glwnco.'-'.$cabang;
                    $item3 .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="realisasi" data-id="'.$name.'" data-value="'.$value.'">'.$bln_trakhir.'</div></td>';
                }
                
                $item3 .= '</tr>';
                if($status):
                    $dt_status = true;
                    $item .= $item3;
                endif;
                $item .= $item2;
            }
        }
        $res = [
            'item'  => $item,
            'dt'    => $dt,
            'dt_status'    => $dt_status,
        ];
        return $res;
    }

    private function get_data_net($kode_anggaran,$kode_cabang,$tahun){
        $db = $this->db->query("
        select 
            'giro' as tipe,
            b.level1,b.level2,b.level3,b.level4,b.level5,
            a.coa as coa,a.account_name as name,a.P_01 as B_01, a.P_02 as B_02, a.P_03 as B_03, a.P_04 as B_04, a.P_05 as B_05, a.P_06 as B_06, a.P_07 as B_07, a.P_08 as B_08, a.P_09 as B_09,
            a.P_10 as B_10, a.P_11 as B_11, a.P_12 as B_12
            from tbl_budget_plan_giro a 
            join tbl_m_coa b on a.coa = b.glwnco
            where a.coa in ('2101011','2101012') and a.kode_anggaran = '$kode_anggaran' and a.kode_cabang = '$kode_cabang' and a.tahun_core = '$tahun'
            
        union all
        
        select 
            'tabungan' as tipe,
            b.level1,b.level2,b.level3,b.level4,b.level5,
            a.coa as coa,a.account_name as name,a.P_01 as B_01, a.P_02 as B_02, a.P_03 as B_03, a.P_04 as B_04, a.P_05 as B_05, a.P_06 as B_06, a.P_07 as B_07, a.P_08 as B_08, a.P_09 as B_09,
            a.P_10 as B_10, a.P_11 as B_11, a.P_12 as B_12
            from tbl_budget_plan_tabungan a 
            join tbl_m_coa b on a.coa = b.glwnco
            join tbl_m_rincian_tabungan c on c.coa = a.coa
            where a.kode_anggaran = '$kode_anggaran' and a.kode_cabang = '$kode_cabang' and a.tahun_core = '$tahun'
        
        union all
        
        select 
            'deposito' as tipe,
            b.level1,b.level2,b.level3,b.level4,b.level5,
            a.coa as coa,a.account_name as name,a.P_01 as B_01, a.P_02 as B_02, a.P_03 as B_03, a.P_04 as B_04, a.P_05 as B_05, a.P_06 as B_06, a.P_07 as B_07, a.P_08 as B_08, a.P_09 as B_09,
            a.P_10 as B_10, a.P_11 as B_11, a.P_12 as B_12
            from tbl_budget_plan_deposito a 
            join tbl_m_coa b on a.coa = b.glwnco
            join tbl_m_rincian_deposit c on c.coa = a.coa
            where a.kode_anggaran = '$kode_anggaran' and a.kode_cabang = '$kode_cabang' and a.tahun_core = '$tahun'
            
        union all

        select 
            'kredit' as tipe,
            b.level1,b.level2,b.level3,b.level4,b.level5,
            a.coa as coa,a.account_name as name,a.P_01 as B_01, a.P_02 as B_02, a.P_03 as B_03, a.P_04 as B_04, a.P_05 as B_05, a.P_06 as B_06, a.P_07 as B_07, a.P_08 as B_08, a.P_09 as B_09,
            a.P_10 as B_10, a.P_11 as B_11, a.P_12 as B_12
            from tbl_budget_plan_kredit a 
            join tbl_m_coa b on a.coa = b.glwnco
            join tbl_produk_kredit c on c.coa = a.coa
            where a.kode_anggaran = '$kode_anggaran' and a.kode_cabang = '$kode_cabang' and a.tahun_core = '$tahun'
        
        union all
        
        select 
            'aktiva_inv' as tipe,
            b.level1,b.level2,b.level3,b.level4,b.level5,
            b.glwnco as coa,b.glwdes as name,a.bulan_1 as B_01, a.bulan_2 as B_02, a.bulan_3 as B_03, a.bulan_4 as B_04, a.bulan_5 as B_05, a.bulan_6 as B_06, a.bulan_7 as B_07, a.bulan_8 as B_08, a.bulan_9 as B_09,
            a.bulan_10 as B_10, a.bulan_11 as B_11, a.bulan_12 as B_12
            from tbl_formula_akt a 
            join tbl_m_coa b on a.glwnco = b.glwnco
            where a.kode_anggaran = '$kode_anggaran' and a.kode_cabang = '$kode_cabang' and a.parent_id = '0' and a.glwnco like '16%'
            
        union all
        
        select 
            'formula_kolektibilitas' as tipe,
            b.level1,b.level2,b.level3,b.level4,b.level5,
            a.coa as coa,b.glwnco as name,a.B_01 as B_01, a.B_02 as B_02, a.B_03 as B_03, a.B_04 as B_04, a.B_05 as B_05, a.B_06 as B_06, a.B_07 as B_07, a.B_08 as B_08, a.B_09 as B_09,
            a.B_10 as B_10, a.B_11 as B_11, a.B_12 as B_12
            from tbl_formula_kolektibilitas a 
            join tbl_m_coa b on a.coa = b.glwnco
            where a.kode_anggaran = '$kode_anggaran' and a.kode_cabang = '$kode_cabang' and a.tahun_core = '$tahun' and a.coa in ('1552011','1552015','1552016','1552012')");
        return $db;
    }

    private function update_data($data,$ID){
        if(count($data)>0):
            update_data($this->table,$data,'id',$ID);
        endif;
    }
    private function insert_data($data,$coa,$cabang){
        $anggaran = $this->session->dt_anggaran;
        if(count($data)>0):
            $data['kode_anggaran'] = $anggaran->kode_anggaran;
            $data['keterangan_anggaran'] = $anggaran->keterangan;
            $data['tahun'] = $anggaran->tahun_anggaran;
            $data['coa'] = $coa;
            $data['kode_cabang'] = $cabang;
            insert_data($this->table,$data);
        endif;
    }

    function checkAdjusment($anggaran,$cabang){
        // Adjusment
        $arrAktiva = $this->session->arrAktiva;
        $arrPasiva = $this->session->arrPasiva;
        $anggaran  = $this->session->dt_anggaran;
        foreach ($this->arrAdjusment as $v) {
            ${'coa'.$v} = $this->session->{'coa'.$v};
        }

        $arrUpdate = [];
        $arrDpk = $this->session->arrDpk;
        $item = '<td></td>';
        $item .= '<td></td>';
        $item .= '<td></td>';
        $item .= '<th>Total DPK</th>';
        for ($i=1; $i <=12 ; $i++) { 
            $field  = 'B_' . sprintf("%02d", $i);
            $a = $arrAktiva[$field];
            $p = $arrPasiva[$field];
            if($p>$a):
                $selisih = $p-$a;
                $coa1801000[$field] += $selisih;
                $arrAktiva[$field] += $selisih;
            elseif($a>$p):
                $selisih = $a-$p;
                $coa2801000[$field] += $selisih;
                $arrPasiva[$field] += $selisih;
            endif;

            $item .= '<td>'.check_value($arrDpk[$field]).'</td>';

            $arrUpdate['1000000'][$field] = $arrAktiva[$field];
            $arrUpdate['2000000'][$field] = $arrPasiva[$field];
            $arrUpdate['1801000'][$field] = $coa1801000[$field];
            $arrUpdate['2801000'][$field] = $coa2801000[$field];
            $arrUpdate['602'][$field]     = $arrDpk[$field];

            $arrAktiva[$field]  = check_value($arrAktiva[$field]);
            $arrPasiva[$field]  = check_value($arrPasiva[$field]);
            $coa1801000[$field] = check_value($coa1801000[$field]);
            $coa2801000[$field] = check_value($coa2801000[$field]);
        }

        // Himpunan
        $data_himpunan['arrAdditional'] = $this->arrAdditional;
        foreach ($this->arrAdditional as $addit) {
            $key_addit      = 'additional_'.$addit['id'];
            $data_himpunan[$key_addit] = $this->session->{$key_addit};
        }
        $view_additional = $this->load->view($this->path.$this->controller.'/additional',$data_himpunan,true);

        // Adjusment
        foreach ($arrUpdate as $coa => $data) {
            $ck = get_data($this->table,[
                'select'    => 'id',
                'where'     => "coa = '$coa' and kode_anggaran = '$anggaran->kode_anggaran' and tahun = '$anggaran->tahun_anggaran' and kode_cabang = '$cabang'"
            ])->row();
            if($ck):
                update_data($this->table,$data,'id',$ck->id);
            elseif($coa == '602'):
                $h = $data;
                $h['coa'] = $coa;
                $h['kode_cabang'] = $cabang;
                $h['kode_anggaran'] = $anggaran->kode_anggaran;
                $h['tahun'] = $anggaran->tahun_anggaran;
                $h['keterangan_anggaran'] = $anggaran->keterangan;
                insert_data($this->table,$h);
            endif;
        }

        $spaces = '<td><div style="color:#7fffd400">-</div></td>';
        for ($i=1; $i <=17 ; $i++) { $spaces .= '<td></td>'; }

        $res = array(
            '1000000' => $arrAktiva,
            '2000000' => $arrPasiva,
            '1801000' => ${'coa1801000'},
            '2801000' => ${'coa2801000'},
            'd-total-pasiva'     => $item,
            'd-spaces' => $spaces,
            'append_table' => $view_additional,
        );
        $this->session->unset_userdata(['dt_neraca','dt_anggaran','arrAktiva','arrPasiva','coa1801000','coa2801000','arrDpk']);
        foreach ($this->arrAdditional as $addit) {
            $key_addit      = 'additional_'.$addit['id'];
            $this->session->unset_userdata([$key_addit]);
        }
        render($res,'json');
    }

    function save_perubahan(){
        $kode_anggaran = post('kode_anggaran');
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$kode_anggaran)->row();
        $data   = json_decode(post('json'),true);
        foreach($data as $k => $record) {
            $x      = explode('-', $k);
            $coa    = $x[0];
            $cabang = $x[1];

            $ck = get_data($this->table,[
                'select'    => 'id,changed',
                'where'     => "coa = '$coa' and kode_cabang = '$cabang' and  kode_anggaran = '$kode_anggaran' and tahun = '$anggaran->tahun_anggaran'",
            ])->row();
            if($ck):
                $changed = json_decode($ck->changed,true);
                foreach ($record as $k2 => $v2) {
                    $value = filter_money($v2);
                    $changed[$k2] = 1;
                    $record[$k2] = insert_view_report($value);
                }
                $record['changed'] = json_encode($changed);
                $where = [
                    'coa'           => $coa,
                    'tahun'         => $anggaran->tahun_anggaran,
                    'kode_cabang'   => $cabang,
                    'kode_anggaran' => $kode_anggaran,
                ];
                update_data($this->table,$record,$where);
            endif;
        }
    }
}