<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2020-02-24 17:42:12
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-02-24 17:43:15
 * @email             :  info@clickrweb.com
 * @description       :  網站前台多語言切換 參數處理.
 */
class MY_Router extends CI_Router {

  protected function _parse_routes(){

    $first_param = $this->uri->segments[1];
    $lang_array  = $this->config->item('seo_lang_array');
    
    if(!empty($first_param) && array_key_exists($first_param,$lang_array)){
      $current_lang=$lang_array[$first_param];
      $this->config->set_item('language',$current_lang['seo']);
      if(!isset($_COOKIE['language_id']) || $_COOKIE['language_id']!=$current_lang['id']){
        setcookie('language_id',$current_lang['id'],time()+86500*30,'/');
        $_COOKIE['language_id'] = $current_lang['id'];
      }
      unset($this->uri->segments[1]);
    }
    return parent::_parse_routes();
  }  
}