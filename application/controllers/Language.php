<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-08-05 18:52:15
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-02-27 16:55:40
 * @email             :  info@clickrweb.com
 * @description       :  多語言切換控制器 Language Controller
 */
class Language extends CI_Controller {
  public function __construct(){
    parent::__construct();
  }

  //轉換語言
  public function change($lang_id=''){
    $lang_path = '';
    $back=$this->input->get('back');
    if(!empty($lang_id) && is_numeric($lang_id)){

      $this->load->model('language_mdl');
      $query=$this->language_mdl
        ->order_by('sort_order','ASC')
        ->get_many_by(array('status'=>1));

      if(!empty($query) && is_array($query)){
        $choice=current($query); //默認選中第一個.
        foreach($query as $item){

          if($item['id']==$lang_id){
            $choice=$item;//匹配當前使用的語言
          }
        }

        $lang_array = $this->config->item('seo_lang_array');
        if(!empty($choice) && is_array($choice)){
          if(array_key_exists($choice['code_path'],$lang_array)){
            $lang_path = $choice['code_path'].'/';
          }
          delete_cookie('language_id');
          $cookie_language_id= array(
            'name'   => 'language_id',
            'value'  => $choice['id'],
            'expire' => 86500*30,
            'path'   => '/'
          );
          $this->input->set_cookie($cookie_language_id);
        }

        //批量替換lang_path
        foreach ($lang_array as $seo => $option) {
          $back= (!empty($back)) ? str_replace($seo.'/','',$back) : $back;
        }

      }
    }
    $url_suffix=$this->config->item('url_suffix');
    $back= (!empty($back)) ? str_replace(array($url_suffix,'index.php','/modify'),'',$back) : $back;

    $back=!empty($back)?$back:'home';
    $back=site_url($lang_path.$back);
    redirect($back);
  }

  //貨幣切換
  public function currency($currency_code){
    if(!empty($currency_code)){
      delete_cookie('currency_code');
      $cookie_currency_code= array(
        'name'   => 'currency_code',
        'value'  => $currency_code,
        'expire' => 86500*30,
        'path'   => '/'
      );
      $this->input->set_cookie($cookie_currency_code);
      $back=$this->input->get('back');
      $base_url = $this->config->item('base_url');
      $url_arr=explode('/',$base_url);
      if(!empty($url_arr) && count($url_arr)==4){
        $back=str_replace('/'.$url_arr[3].'/','/',$back);
      }
      $back= (!empty($back)) ? str_replace('.html','',$back) : $back;
      $back = (!empty($back)) ? str_replace('index.php','',$back) : $back;
      $back=!empty($back)?substr($back,1):'home';

      $back_array=explode('?',$back);

      $back=count($back_array)==2?site_url($back_array[0]).'?'.$back_array[1]:site_url($back_array[0]);
      redirect($back);
    }
  }

}