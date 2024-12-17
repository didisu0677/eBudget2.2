<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Valas extends BE_Controller {
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
        render($data,'view:'.$this->path.'valas/index');
    }

     function dataLaba ($anggaran="", $cabang=""){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran)->row();

        $bln_trakhir = $anggaran->bulan_terakhir_realisasi;
        $thn_trakhir = $anggaran->tahun_terakhir_realisasi;
        $tbl_history = 'tbl_history_'.$thn_trakhir;

        $or_neraca  = "(a.glwnco like '4%' or a.glwnco like '5%')";
        $select     = 'level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus';
        $coa = get_data('tbl_m_coa a',[
            'select' => $select.',b.VAL_'.$cabang,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => "$tbl_history b on b.bulan = '$bln_trakhir' and a.glwnco = b.glwnco type left"
        ])->result();
        $coa = $this->get_list_coa($coa);

        $data['coa']    = $coa['coa'];
        $data['detail'] = $coa['detail'];
        $data['cabang'] = $cabang;

        $response   = array(
            'table'     => $this->load->view($this->path.'valas/tableLaba',$data,true),
            'data' => $data,
        );
        render($response,'json');
    }

    function dataNeraca ($anggaran1="", $cabang=""){
        $anggaran = get_data('tbl_tahun_anggaran','kode_anggaran',$anggaran1)->row();

        $bln_trakhir = $anggaran->bulan_terakhir_realisasi;
        $thn_trakhir = $anggaran->tahun_terakhir_realisasi;
        $tbl_history = 'tbl_history_'.$thn_trakhir;

        $or_neraca  = "(a.glwnco like '1%' or a.glwnco like '2%' or a.glwnco like '3%')";
        $select     = 'level1,level2,level3,level4,level5,
                    a.glwsbi,a.glwnob,a.glwcoa,a.glwnco,a.glwdes,a.kali_minus';
        $coa = get_data('tbl_m_coa a',[
            'select' => $select.',b.VAL_'.$cabang,
            'where' => "
                a.is_active = '1' and $or_neraca
                ",
            'order_by' => 'a.id',
            'join' => "$tbl_history b on b.bulan = '$bln_trakhir' and a.glwnco = b.glwnco type left"
        ])->result();
        $coa = $this->get_list_coa($coa);
        $this->session->set_userdata(array('dt_neraca' => $coa));


        $data['save'] = get_data('tbl_valas_neraca',[
            'where' => "kode_cabang =  '".$cabang."' and kode_anggaran = '".$anggaran1."'"
        ])->result_array();

        $this->session->set_userdata(array('dataSave' => $data['save']));
        $data['coa']    = $coa['coa'];
        $data['detail'] = $coa['detail'];
        $data['cabang'] = $cabang;
        $dt_view = $this->get_view_coa($data,0);

        $response   = $dt_view;
        render($response,'json');
    }

    private function get_list_coa($coa){
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

    private function get_view_coa($data,$count){

        // echo $data;
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
        $save = $this->session->dataSave;
        $data['coa']    = $coa['coa'];
        $data['detail'] = $coa['detail'];
        $data['save']   = $save;
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
        $save   = $data['save'];

        // print_r($save);

        $item = '';
        $td_transparnt = '<td class="border-none bg-transparent"></td>';

        $v = $coa[$key];

        $item2  = '';
        $dt2    = [];
        $minus  = $v->kali_minus;

        $bln_trakhir = $v->{'VAL_'.$cabang};
        if(isset($detail['1'][$v->glwnco])){
            $dt = $this->loadViewLoop($data,$detail['1'][$v->glwnco],1);
            $item2  = $dt['item'];
            $dt2    = $dt['dt'];
            $bln_trakhir = '';
            $value = 0;
        }else{
            $bln_trakhir = $v->{'VAL_'.$cabang};
            $value = $bln_trakhir;
            // $bln_trakhir = check_min_value($bln_trakhir,$minus);
            $cekPerbulan = array_search($v->glwnco, array_column($save, 'glwnco'));
            if(in_array($v->glwnco, array_column($save, 'glwnco'))){
                
                if(!empty($val = $save[$cekPerbulan]['perbulan'])){
                    $value = $save[$cekPerbulan]['perbulan'];
                }
            
            }
            $bln_trakhir = $value;
        }

        $item .= '<tr>';
        $item .= '<td>'.$v->glwsbi.'</td>';
        $item .= '<td>'.$v->glwcoa.'</td>';
        $item .= '<td>'.$v->glwnco.'</td>';
        $item .= '<td>'.remove_spaces($v->glwdes).'</td>';
        for ($i=1; $i <= 12 ; $i++) { 
            if(count($dt2)>0){
                $edit = 'contenteditable="false"';
                $val = $dt2[$i]; 
                $item .= '<td class="text-right test">'.check_min_value($val,$minus).'</td>';
            }
            else{
                $edit = 'contenteditable="true"';
                $val = $value; 
                $cek = array_search($v->glwnco, array_column($save, 'glwnco'));
                if(in_array($v->glwnco, array_column($save, 'glwnco'))){
                    if($save[$cek]['last_edit'] == '1'){
                        $val = $save[$cek]['bulan_'.$i];
                    }else {
                        if(!empty($val = $save[$cek]['perbulan'])){
                            $val = $save[$cek]['perbulan'];
                        }
                    }
                }
                $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="bulan_'.$i.'" data-id="'.$v->glwnco.'|'.$minus.'" data-value="'.$val.'">'.check_min_value($val,$minus).'</div></td>';

            }
            // $item .= '<td class="text-right">'.check_min_value($val,$minus).'</td>';
        }
        $item .= $td_transparnt;
        $item .= '<td class="text-right"><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edit-bulan" data-name="perbulan" data-id="'.$v->glwnco.'|'.$minus.'" data-value="'.$bln_trakhir.'">'.check_min_value($bln_trakhir,$minus).'</td>';
        // $item .= '<td class="text-right">'.$bln_trakhir.'</td>';
        $item .= '</tr>';
        $item .= $item2;

        return $item;
    }

    private function loadViewLoop($data, $data2, $kk){
        $detail = $data['detail'];
        $cabang = $data['cabang'];
        $save   = $data['save'];

        $item   = '';
        $td_transparnt = '<td class="border-none bg-transparent"></td>';
        $dt     = [];
        if($kk<=3){
            foreach ($data2 as $k2 => $v2) {
                $item2  = '';
                $dt2    = [];
                $minus  = $v2->kali_minus;
                if(isset($detail[($kk+1)][$v2->glwnco])){
                    $dd = $detail[($kk+1)][$v2->glwnco];
                    $dd = $this->loadViewLoop($data,$dd,($kk+1));
                    $item2  = $dd['item'];
                    $dt2    = $dd['dt'];
                    $bln_trakhir = '';
                    $value = 0;
                }else{
                    $bln_trakhir = $v2->{'VAL_'.$cabang};
                    $value = $bln_trakhir;
                    $cekPerbulan = array_search($v2->glwnco, array_column($save, 'glwnco'));
                    if(in_array($v2->glwnco, array_column($save, 'glwnco'))){
                        
                        if(!empty($val = $save[$cekPerbulan]['perbulan'])){
                            $value = $save[$cekPerbulan]['perbulan'];
                        }
                    
                    }
                    $bln_trakhir = $value;
                }

                $item .= '<tr>';
                $item .= '<td>'.$v2->glwsbi.'</td>';
                $item .= '<td>'.$v2->glwcoa.'</td>';
                $item .= '<td>'.$v2->glwnco.'</td>';
                $item .= '<td class="sb-'.($kk+1).'">'.remove_spaces($v2->glwdes).'</td>';
                for ($i=1; $i <= 12 ; $i++) { 
                    if(count($dt2)>0){

                        $edit = 'contenteditable="false"';
                        $val = $dt2[$i]; 

                        $item .= '<td class="text-right test">'.check_min_value($val,$minus).'</td>';

                    }
                    else{
                        $edit = 'contenteditable="true"';
                        $val = $value;
                        $cek = array_search($v2->glwnco, array_column($save, 'glwnco'));
                        // if(in_array($v2->glwnco, array_column($save, 'glwnco'))){
                        //     $val = $save[$cek]['bulan_'.$i];
                        // }
                        if(in_array($v2->glwnco, array_column($save, 'glwnco'))){
                            if($save[$cek]['last_edit'] == '1'){
                                $val = $save[$cek]['bulan_'.$i];
                            }else {
                                if(!empty($val = $save[$cek]['perbulan'])){
                                    $val = $save[$cek]['perbulan'];
                                }
                            }
                        }

                         $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="bulan_'.$i.'" data-id="'.$v2->glwnco.'|'.$minus.'" data-value="'.$val.'">'.check_min_value($val,$minus).'</div></td>';
                    }
                    // $item .= '<td class="text-right">'.check_min_value($val,$minus).'</td>';
                    if(isset($dt[$i])){ $dt[$i] += $val; }else{ $dt[$i] = $val; }
                }
                $item .= $td_transparnt;
                $item .= '<td class="text-right"><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edit-bulan" data-name="perbulan" data-id="'.$v2->glwnco.'|'.$minus.'" data-value="'.$bln_trakhir.'">'.check_min_value($bln_trakhir,$minus).'</td>';
                // $item .= '<td class="text-right">'.$bln_trakhir.'</td>';
                $item .= '</tr>';
                $item .= $item2;
            }
        }
        $res = [
            'item'  => $item,
            'dt'    => $dt,
        ];
        return $res;
    }


  function save_perubahan($anggaran="",$cabang="") {       

        $data   = json_decode(post('json'),true);

        // echo post('json');
        foreach($data['bulan'] as $getId => $record) {
            $cekId = $getId;

            $cekExp = explode("|", $getId);
            $cekId = $cekExp[0];


            $dataRecord = insert_view_report_arr($record);
            $dataRecord ['last_edit'] = '1';
            $cek  = get_data('tbl_valas_neraca a',[
                'select'    => 'a.id',
                'where'     => [
                    'a.glwnco'             => $cekId,
                    'a.kode_anggaran'   => $anggaran,
                    'a.kode_cabang'   => $cabang,
                ]
            ])->result_array();
     
            if(count($cek) > 0){
                update_data('tbl_valas_neraca', $dataRecord ,'id',$cek[0]['id']);
            }else {
                    // echo $cekId."<br>";
                    // echo $anggaran."<br>";
                    // echo $cabang."<br>";
                    $dataRecord ['glwnco'] = $cekId;
                    $dataRecord ['kode_anggaran'] = $anggaran;
                    $dataRecord ['kode_cabang'] = $cabang;
                    insert_data('tbl_valas_neraca',$dataRecord);
            } 
         } 
         if(!empty($data['perbulan'])){

                // print_r($data['perbulan']);
                foreach($data['perbulan'] as $getId => $record) {
                    $cekId = $getId;

                    $cekExp = explode("|", $getId);
                    $cekId = $cekExp[0];

                    
                    $dataRecord  = insert_view_report_arr($record);

                    for($a=1;$a<=12;$a++){
                        $dataRecord['bulan_'.$a] = $dataRecord['perbulan'];
                    }

                    $cek  = get_data('tbl_valas_neraca a',[
                        'select'    => 'a.id',
                        'where'     => [
                            'a.glwnco'             => $cekId,
                            'a.kode_anggaran'   => $anggaran,
                            'a.kode_cabang'   => $cabang,
                        ]
                    ])->result_array();
             
                    if(count($cek) > 0){
                        $dataRecord['last_edit'] = '2';
                        update_data('tbl_valas_neraca', $dataRecord,'id',$cek[0]['id']);
                    }else {
                            // echo $cekId."<br>";
                            // echo $anggaran."<br>";
                            // echo $cabang."<br>";
                            $dataRecord['last_edit'] = '2';
                            $dataRecord['glwnco'] = $cekId;
                            $dataRecord['kode_anggaran'] = $anggaran;
                            $dataRecord['kode_cabang'] = $cabang;
                            insert_data('tbl_valas_neraca',$dataRecord);
                    } 
                 } 
             }
       
    }
}