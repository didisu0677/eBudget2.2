<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Neraca_new extends BE_Controller {
    var $path = 'transaction/budget_planner/';
    function __construct() {
        parent::__construct();
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
        $data = $this->data_cabang();
        $data['bulan_terakhir'] = month_lang($data['tahun'][0]->bulan_terakhir_realisasi);
        render($data,'view:'.$this->path.'neraca_new/index');
    }

    function data ($anggaran="", $cabang=""){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();

        $bln_trakhir = $anggaran->bulan_terakhir_realisasi;
        $thn_trakhir = $anggaran->tahun_terakhir_realisasi;
        $tbl_history = 'tbl_history_'.$thn_trakhir;

        $or_neraca  = "(a.glwnco like '1%' or a.glwnco like '2%' or a.glwnco like '3%' or a.glwnco LIKE '41%' AND a.level1 = '2120011')";
        $select     = 'level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus';
        $coa = get_data('tbl_m_coa a',[
            'select' => $select.',b.TOT_'.$cabang,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => "$tbl_history b on b.bulan = '$bln_trakhir' and a.glwnco = b.glwnco type left"
        ])->result();

        $query_result     = $this->db->query("CALL stored_neraca_nett('$cabang','$anggaran->kode_anggaran','$anggaran->tahun_anggaran')");
        $detail           = $query_result->result_array();

        //add this two line 
        $query_result->next_result(); 
        $query_result->free_result(); 
        //end of new code

        $coa = $this->get_list_coa($coa,$detail);
        $this->session->set_userdata(array('dt_neraca' => $coa));

        $data['coa']    = $coa['coa'];
        $data['detail'] = $coa['detail'];
        $data['cabang'] = $cabang;
        $dt_view = $this->get_view_coa($data,0);

        $response   = $dt_view;
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

    private function get_view_coa($data,$count){
        $no = $count;
        $status = false;
        $view = '';
        for ($i=$count; $i <($count+10) ; $i++) { 
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
        $dt_status2 = false;
        $minus  = $v->kali_minus;

        $bln_trakhir = $v->{'TOT_'.$cabang};
        if(isset($detail['1'][$v->glwnco])){
            $dt = $this->loadViewLoop($data,$detail['1'][$v->glwnco],1);
            $item2  = $dt['item'];
            $dt2    = $dt['dt'];
            $dt_status2    = $dt['dt_status'];
            $bln_trakhir = '';
            $value = 0;
        }else{
            $bln_trakhir = $v->{'TOT_'.$cabang};
            $value = $bln_trakhir;
            $bln_trakhir = check_min_value($bln_trakhir,$minus);
        }
        $status = false;
        if($dt_status2 || isset($v->tipe)):
            $status = true;
        endif;
        $item = '<tr>';
        $item .= '<td>'.$v->glwsbi.'</td>';
        $item .= '<td>'.$v->glwcoa.'</td>';
        $item .= '<td>'.$v->glwnco.'</td>';
        $item .= '<td>'.remove_spaces($v->glwdes).'</td>';
        for ($i=1; $i <= 12 ; $i++) {
            $field  = 'B_' . sprintf("%02d", $i);
            
            if(count($dt2)>0){ $val = $dt2[$i]; }
            else{ 
                $val = $value; 
                if(isset($v->{$field})){ $val =  $v->{$field}; }
            }
            $item .= '<td class="text-right">'.check_min_value($val,$minus).'</td>';
        }
        $item .= $td_transparnt;
        $item .= '<td class="text-right">'.$bln_trakhir.'</td>';
        $item .= '</tr>';
        if(!$status):
            $item = '';
        endif;
        $item .= $item2;

        return $item;
    }

    private function loadViewLoop($data, $data2, $kk){
        $detail = $data['detail'];
        $cabang = $data['cabang'];

        $data2 = json_encode($data2);$data2 = json_decode($data2);

        $item   = '';
        $td_transparnt = '<td class="border-none bg-transparent"></td>';
        $dt     = [];
        $dt_status = false;
        if($kk<=5){
            foreach ($data2 as $k2 => $v2) {
                $item2      = '';
                $dt2        = [];
                $d_status2  = false;
                $minus  = $v2->kali_minus;
                if(isset($detail[($kk+1)][$v2->glwnco])){
                    $dd = $detail[($kk+1)][$v2->glwnco];
                    $dd = $this->loadViewLoop($data,$dd,($kk+1));
                    $item2  = $dd['item'];
                    $dt2    = $dd['dt'];
                    $d_status2 = $dd['dt_status'];
                    $bln_trakhir = '';
                    $value = 0;
                }else{
                    $bln_trakhir = $v2->{'TOT_'.$cabang};
                    $value = $bln_trakhir;
                    $bln_trakhir = check_min_value($bln_trakhir,$minus);
                }
                $status = false;
                $item3  = '<tr>';
                $item3 .= '<td>'.$v2->glwsbi.'</td>';
                $item3 .= '<td>'.$v2->glwcoa.'</td>';
                $item3 .= '<td>'.$v2->glwnco.'</td>';
                $item3 .= '<td class="sb-'.($kk+1).'">'.remove_spaces($v2->glwdes).'</td>';
                if($d_status2 || isset($v2->tipe)):
                    $status = true;
                endif;
                for ($i=1; $i <= 12 ; $i++) {
                    $field  = 'B_' . sprintf("%02d", $i);

                    if(count($dt2)>0){ $val = $dt2[$i]; }
                    else{ 
                        $val = $value; 
                        if(isset($v2->{$field})){ $val = $v2->{$field}; }
                    }
                    $item3 .= '<td class="text-right">'.check_min_value($val,$minus).'</td>';
                    if(isset($dt[$i])){ $dt[$i] += $val; }else{ $dt[$i] = $val; }
                }
                $item3 .= $td_transparnt;
                $item3 .= '<td class="text-right">'.$bln_trakhir.'</td>';
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
}