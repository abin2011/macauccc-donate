<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-11-11 14:53:36
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-02-24 18:10:49
 * @email             :  info@clickrweb.com
 * @description       :  重寫site_url函數 支持lang seo 多語言 20191111
 */
//重寫site_url函數 支持lang seo 多語言 20191111
if(!function_exists('site_url')){
  function site_url($uri = '', $protocol = NULL){
    $CI =& get_instance();
    $current_directory = $CI->router->directory;
    if($current_directory!='admin/' && isset($CI->data['lang_code'])){
      $lang_code=$CI->data['lang_code'];
      $lang_array=$CI->config->item('seo_lang_array');
      if(!empty($lang_code) && array_key_exists($lang_code,$lang_array)){
        $uri = $lang_code.'/'.$uri;
      }
    }
    return $CI->config->site_url($uri, $protocol);
  }
}