<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-08 18:18:11
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2023-01-30 11:40:52
 * @email             :  info@clickrweb.com
 * @description       :  ClickrCMS公共功能函數
 * @version [<4.0>] [<ClickrCMS公共功能函數>]
 */

//中文截取字符串 函數聲明
if (!function_exists('mb_substr')) {
  function mb_substr($str, $start, $len = '', $encoding="UTF-8"){
    return iconv_substr($str,$start,$len,$encoding);
  }
}

if (!function_exists('mb_strlen')) {
  function mb_strlen($str,$charset='UTF-8'){
    return iconv_strlen($str,$charset);
  }
}

//組合函數 截取
if(!function_exists('helper_strcut')){
  function helper_strcut($str,$length=60){
    $str_cut=mb_substr($str,0,$length,'utf-8');
    if(mb_strlen($str)>$length){
      $str_cut.='...';
    }
    return $str_cut;
  }
}

//計算兩個日期相差天數 #對比現在距離的天數
if(!function_exists('date_gap')){
  function date_gap($date='',$only_gap=FALSE){
    if(!empty($date)){
      $today=strtotime(date('Y-m-d'));
      $date=strtotime($date);
      $date_diff=ceil(($date-$today)/86400);
      if($only_gap){
        return abs($date_diff).'天';
      }
      if($date<$today)
        return '-'.$date_diff.'天';
      else{
        return $date_diff.'天'; //3600*24
      }
    }
  }
}

//根據生日 計算年齡
if(!function_exists('helper_age')){
  function helper_age($birthday='',$tag='歲'){
    if(!empty($birthday) && $birthday!='0000-00-00'){
      list($year,$month,$day) = explode("-",$birthday);
      $day_diff   = date("d") - $day; // 9 - 19 = -10
      $month_diff = date("m") - $month; // 3 - 2 = 1
      $year_diff  = date("Y") - $year; // 2012 - 1994 = 18
      if ($day_diff < 0) {
        $month_diff--;
      }
      if ($month_diff < 0) {
        $year_diff--;
      }
      return '('.$year_diff.$tag.')';
    }
  }
}


//隨機生成密碼
if(!function_exists('helper_password')){
  function helper_password($length = 6,$type='mixed'){
    // 密码字符集，可任意添加你需要的字符
    $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
    'i', 'j', 'k','m', 'n', 'p', 'q', 'r', 's',
    't', 'u', 'v', 'w', 'x', 'y','z',
    '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $numbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    // 在 $chars 中随机取 $length 个数组元素键名
    $keys = array_rand($chars, $length);
    if($type!='mixed'){
      return str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);
    }
    $password = '';
    for($i = 0; $i < $length; $i++){
      // 将 $length 个数组元素连接成字符串
      if($type!='mixed'){
        $password .= $numbers[$keys[$i]];
      }else{
        $password .= $chars[$keys[$i]];
      }
    }
    return $password;
  }
}

//發送手機短信.
if(!function_exists('helper_send_sms')){
  //執行發送短信
  function helper_send_sms($phone_number,$message_txt,$timer=''){
    if (!empty($phone_number) && strlen($phone_number)==8 && substr($phone_number,0,1)=='6' && !empty($message_txt)){
      $url = 'https://smsmgr01.three.com.mo/servlet/_xml';
      $message_txt='【聖羅撒】'.htmlspecialchars($message_txt);
      $timer=!empty($timer)?'<sctime>'.$timer.'</sctime>':'';//是否定時.
      $data=<<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <!DOCTYPE jds SYSTEM "/home/httpd/html/dtd/jds2.dtd">
        <jds>
        <account acid="clickr" loginid="admin" passwd="xxxxxx">
        <msg_send>
          <recipient>{$phone_number}</recipient>
          <content>{$message_txt}</content>
          <language>C</language>
          {$timer}
        </msg_send>
        </account>
        </jds>
XML;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      $response = curl_exec($ch);
      if(curl_errno($ch)){
        die('helper_send_sms Curl error: ' . curl_error($ch));
      }
      $xml = simplexml_load_string($response);
      curl_close($ch);
      return $xml;
    }else{
      return (object)array('jerr'=>'005');
    }
  }
}

//自定義參數加密/解密
if(!function_exists('helper_custom_encrypt')){
  function helper_custom_encrypt($string,$operation,$key='abin-codeigniter-custom-encrypt'){
    $key=md5($key);
    $key_length=strlen($key);
    $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
    $string_length=strlen($string);
    $rndkey=$box=array();
    $result='';
    for($i=0;$i<=255;$i++){
      $rndkey[$i]=ord($key[$i%$key_length]);
      $box[$i]=$i;
    }
    for($j=$i=0;$i<256;$i++){
      $j=($j+$box[$i]+$rndkey[$i])%256;
      $tmp=$box[$i];
      $box[$i]=$box[$j];
      $box[$j]=$tmp;
    }
    for($a=$j=$i=0;$i<$string_length;$i++){
      $a=($a+1)%256;
      $j=($j+$box[$a])%256;
      $tmp=$box[$a];
      $box[$a]=$box[$j];
      $box[$j]=$tmp;
      $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
    }
    if($operation=='D'){
      if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
        return substr($result,8);
      }else{
        return'';
      }
    }else{
      return str_replace('=','',base64_encode($result));
    }
  }
}

//生成縮圖
/**
 * 創建圖片縮圖
 * @Author   Clickr  Abin
 * @DateTime 2019-07-19T11:25:52+0800
 * @param    string  $image_path [圖片路徑]
 * @param    [type]  $thumb_param[縮圖參數,width,height,ratio]
 * @return   [type]  [thumb_image]
 */
if(!function_exists('helper_create_thumb')){
  function helper_create_thumb($image_path='',$thumb_param=array(200,200,TRUE),$default_image=''){
    $CI =& get_instance();
    $is_admin=$CI->uri->slash_segment(1);//獲取操作段
    $thumb_image='themes/front/img/no-image.png';
    if($is_admin=='admin/')
      $thumb_image='themes/admin/img/noimage.png';
    $thumb_image=!empty($default_image)&&file_exists($default_image)?$default_image:$thumb_image;
    if(!empty($image_path) && file_exists($image_path)){
      list($width,$height,$ratio)=$thumb_param;
      $thumb_image=imagelib::resize_thumb($image_path,$width,$height,$ratio);
    }
    return $thumb_image;
  }
}


//自動生成後台麵包屑
if(!function_exists('helper_admin_breadcrumb')){
  function helper_admin_breadcrumb($return_key=''){
    $CI =& get_instance();
    $ignore_class = array('login','upload','home');

    $current_directory = $CI->router->directory;
    $current_class     = $CI->router->class;
    $current_method    = $CI->router->method;

    $method_description = array(
      'add'    => '新增',
      'edit'   => '編輯',
      'view'   => '詳情',
      'modify' => '編輯/新增',
      'config' => '網站設定',
      'mail'   => '通知設定',
      'custom' => '自定義設定',//以上無需更改

      'setting'=> '配置接口', //特別方法描述.
      'update' => '更新資料'
    );

    $parent_folder_description = array(
      'manages' => '網頁內容',
      'shop'    => '站點反饋',
      'system'  => '系統管理',
      'webapp'  => '站點小應用',
    ); 

    $class_description = array(
      'backup'          => '數據備份',
      'home'            => '控制台',
      'feedback'        => '通知消息',
      'language'        => '語言設定',
      'operator'        => '操作日誌',
      'user'            => '用戶管理',
      'user_group'      => '用戶組管理',
      'page'            => '基本頁面',
      'slideshow'       => '幻燈片管理',
      'profile'         => '編輯賬戶資料',
      'visit'           => '瀏覽記錄',
      'setting'         => '基本設定', //以上無需更改

      'payment'         => '支付管理',
      'order'           => '捐款管理',
    );

    if(!empty($return_key) && in_array($return_key,array('class_description','method_description')))
      return $$return_key;

    $current_class = isset($CI->data['currentPage']) && $CI->data['currentPage']=='webapp'?$CI->data['subPage']:$current_class;

    if(!in_array($current_class,$ignore_class)){
      $breadcrumb_start='<div class="layui-card layadmin-header"><div class="layui-breadcrumb"><ul>';

      $breadcrumb_content='';

      if(strpos($current_directory,'admin/')!==FALSE){
        $breadcrumb_content.='<li><a href="'.site_url($current_directory).'">'.$class_description['home'].'</a></li>';
      }
      $parent_folder=$CI->data['currentPage'];

      if(array_key_exists($parent_folder,$parent_folder_description)){
        $breadcrumb_content.='<li><a><cite>'.$parent_folder_description[$parent_folder].'</cite></a></li>';
      }
      $class_text=isset($class_description[$current_class])?$class_description[$current_class]:$current_class;
      $breadcrumb_content.='<li><a href="'.site_url($current_directory.$current_class).'">'.$class_text.'</a></li>';
      if(array_key_exists($current_method,$method_description)){
        $breadcrumb_content.='<li><a><cite>'.$method_description[$current_method].'</cite></a></li>';
      }
      $breadcrumb_end='</ul></div></div>';
      return !empty($breadcrumb_content)?$breadcrumb_start.$breadcrumb_content.$breadcrumb_end:'';
    }
  }
}

//自動生成後台頁面標題名稱
if(!function_exists('helper_admin_page_title')){
  function helper_admin_page_title(){
    $CI =& get_instance();
    $class_description  = helper_admin_breadcrumb('class_description');
    $method_description = helper_admin_breadcrumb('method_description');
    $current_class      = $CI->router->class;
    $current_method     = $CI->router->method;
    $current_directory  = $CI->router->directory;
    $page_title = '';
    if(array_key_exists($current_method,$method_description)){
      $page_title.=$method_description[$current_method].' ';
    }
    if($current_directory!='admin/' && $current_class=='home'){
      $temp_array=explode('/',$current_directory);
      $current_class = $temp_array[2];
    }
    if(array_key_exists($current_class,$class_description)){
      $page_title.=$class_description[$current_class];
    }
    return $page_title;
  }
}

/**
 * 捐款狀態
 * 生成選項參數.
 */
if(!function_exists('helper_type_parameter')){

  function helper_type_parameter($parent_key='',$child_key=''){
    $data['order_status_class']=array(
      '1'=>'orange',
      '2'=>'green',
      '3'=>'red',
      '4'=>'black',
      '5'=>'cyan'
    );
    $data['order_status_option']=array(
      '1'=>'待付款',
      '2'=>'已支付',
      '3'=>'失败',
      '4'=>'已取消',
      '5'=>'已完成'
    );
    
    //前台多語言版本
    // $data['order_status_lang_option']=array(
    //   '1'=>lang('order_status_type01'),
    //   '2'=>lang('order_status_type02'),
    //   '3'=>lang('order_status_type03'),
    //   '4'=>lang('order_status_type03'),
    //   '5'=>lang('order_status_type03'),
    // );

    if(!empty($parent_key) && !empty($child_key)){
      $temp_data='';
      if(array_key_exists($parent_key,$data) && array_key_exists($child_key,$data[$parent_key]))
        $temp_data=$data[$parent_key][$child_key];
      return $temp_data;
    }else if(!empty($parent_key)){
      $temp_data='';
      if(array_key_exists($parent_key,$data))
        $temp_data=$data[$parent_key];
      return $temp_data;
    }

    return $data;
  }
}

/**
 * checkbox 多選參數 轉 文字描述.
 */
if(!function_exists('helper_checkbox_string')){
  function helper_checkbox_string($checkbox_field_option='',$checkbox_field_string='',$other_field_string=''){
    if(!empty($checkbox_field_option) && is_array($checkbox_field_option) && !empty($checkbox_field_string)){
      $checkbox_string = '';
      $key_array=explode(',',$checkbox_field_string);
      foreach($key_array as $key_name){
        $temp_value=array_key_exists($key_name,$checkbox_field_option)?$checkbox_field_option[$key_name]:'';
        if(!empty($temp_value)){
          $checkbox_string.='，'.$temp_value;
        }
      }
      return !empty($checkbox_string)?substr($checkbox_string,3):'';
    }
  }
}

/**
 * 多種支付方式,訂單支付成功 發送電郵通知商戶,客戶
 */
if(!function_exists('helper_order_send_email')){
  function helper_order_send_email($order_data=array(),$order_id=''){
    $CI =& get_instance();
    $config['protocol']=$CI->data['mail_protocol'];
    if($CI->data['mail_protocol']=='smtp'){
      $config['smtp_host']                 = $CI->data['mail_smtp_hostname'];
      $config['smtp_user']                 = $CI->data['mail_smtp_username'];
      $config['smtp_pass']                 = $CI->data['mail_smtp_password'];
      $config['smtp_port']                 = $CI->data['mail_smtp_port'];
      $config['smtp_timeout']              = '60';
      $CI->data['mail_sender']             = $CI->data['mail_smtp_username'];
    }else if($CI->data['mail_protocol']=='sendmail'){
      $config['mailpath']                  = $CI->data['mail_sendmail_path'];
    }

    $config['charset']  = 'utf-8';
    $config['mailtype'] = 'html';
    $config['newline']  = "\r\n";

    $order_data['full_name'] = $order_data['donate_firstname'].' '.$order_data['donate_lastname'].$order_data['donate_gender'];
    $donate_item_array = !empty($order_data['donate_item'])?json_decode($order_data['donate_item'],TRUE):array();
    if(!empty($order_data['donate_item_other'])){
      array_push($donate_item_array,$order_data['donate_item_other']);
    }
    $order_data['donate_item_format'] =!empty($donate_item_array)?implode('，',$donate_item_array):'';
    $order_data['donate_money']=number_format($order_data['donate_money']);

    $email_client_subject=$CI->data['site_copyright'].'：感謝您的捐贈，該電郵來自捐贈成功后系統發送';
    $email_client_message=$CI->load->view('order_email_notify',$order_data,TRUE);
    $CI->load->library('email',$config);

    //發送給客戶.
    $CI->email->from($CI->data['mail_sender'],$CI->data['site_copyright']);
    $CI->email->to($order_data['donate_email']);
    $CI->email->subject($email_client_subject);
    $CI->email->message($email_client_message);
    $send_client=$CI->email->send();
    if(!$send_client){
      log_message('error','_send_email function send_client not work:'.$CI->email->print_debugger());
    }
    $CI->email->clear(TRUE); //email 变量清空 包括附件

    //發送給商家.
    if(empty($CI->data['mail_alert_email']))//不啟動emai提示
      return;
    $order_data['email_merchant']=TRUE;
    $email_merchant_subject=$CI->data['site_copyright'].'：有客戶通過在線捐贈系統進行捐贈,請查收！';
    $email_merchant_message=$CI->load->view('order_email_notify',$order_data,TRUE);
    $CI->email->from($CI->data['mail_sender'],$CI->data['site_copyright']);
    $CI->email->to($CI->data['mail_alert_email']);
    $CI->email->subject($email_merchant_subject);
    $CI->email->message($email_merchant_message);
    $send_merchant=$CI->email->send();
    if(!$send_merchant){
      log_message('error','_send_email function send_merchant not work:'.$CI->email->print_debugger());
    }
    return $send_client+$send_merchant;
  }
}