<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-09 11:37:04
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-07-09 11:38:57
 * @email             :  info@clickrweb.com
 * @description       :  多貨幣轉換.CMS 備用
 * @source [opencart] [<多國貨幣間的轉換.CMS 備用>]
 */
final class Toolcurrency {
  public static $code;
  public static $currencies = array();

  public function __construct() {
    $CI =& get_instance();
    $CI->load->model('currency_mdl');

    $query = $CI->currency_mdl
      ->order_by('add_time','DESC')
      ->get_many_by(array('status'=>1));

    foreach ($query as $result) {
      self::$currencies[$result['currency_code']] = array(
        'currency_id'   => $result['id'],
        'currency_name' => $result['currency_name'],
        'currency_code' => $result['currency_code'],
        'symbol_right'  => $result['symbol_right'],
        'symbol_left'   => $result['symbol_left'],
        'decimal_place' => $result['decimal_place'],
        'currency_value'=> $result['currency_value']
      );
    }
  }

  public function set($currency) {
    self::$code = $currency;
  }

  //轉換貨幣
  public static function format($number, $currency, $value = '', $format = true) {
    if(empty($currency))
      return false;
    $symbol_left = self::$currencies[$currency]['symbol_left'];
    $symbol_right = self::$currencies[$currency]['symbol_right'];
    $decimal_place = self::$currencies[$currency]['decimal_place'];

    if (!$value) {
      $value = self::$currencies[$currency]['currency_value'];
    }

    $amount = $value ? (float)$number * $value : (float)$number;
    
    $amount = round($amount, (int)$decimal_place);
    
    if (!$format) {
      return $amount;
    }

    $string = '';

    if ($symbol_left) {
      $string .= $symbol_left.' <b>';
    }

    $decimal_point = '.';
    $thousand_point = ',';

    $string .= number_format($amount, (int)$decimal_place,$decimal_point,$thousand_point);

    if ($symbol_right || 1) {
      $string .= $symbol_right.'</b>';
    }

    return $string;
  }

  public function convert($number, $from, $to) {
    $value = $this->Toolcurrency->getValue($from) / $this->Toolcurrency->getValue($to);
    
    return $number * $value;
  }

  public function getId() {
    return self::$currencies[self::$code]['currency_id'];
  }

  public function getCode() {
    return self::$code;
  }

  public function getValue($currency) {
    return (isset(self::$currencies[$currency]) ? self::$currencies[$currency]['currency_value'] : NULL);
  }
  
  public function has($currency) {
    return isset(self::$currencies[$currency]);
  }

  //獲取全部貨幣
  //code 根據貨幣符號來獲取對應的貨幣信息.
  public function get($code=''){
    if(!empty($code) && isset(self::$currencies[$code]))
      return self::$currencies[$code];
    else{
      $CI =& get_instance();
      $query = $CI->currency_mdl
        ->order_by('add_time','DESC')
        ->get_many_by(array('status'=>1));
      $temp_currencies=array();
      foreach ($query as $result) {
        $temp_currencies[$result['currency_code']] = array(
          'currency_id'   => $result['id'],
          'currency_name' => $result['currency_name'],
          'currency_code' => $result['currency_code'],
          'symbol_right'  => $result['symbol_right'],
          'symbol_left'   => $result['symbol_left'],
          'decimal_place' => $result['decimal_place'],
          'currency_value'=> $result['currency_value']
        );
      }
      return $temp_currencies;
    }
  }

  //獲取左邊貨幣顯示符號
  public function getSymbolLeft($pragma_code=''){
    if (!empty($pragma_code) && isset(self::$currencies[$pragma_code]))
      return self::$currencies[$pragma_code]['symbol_left'];
    else
      return self::$currencies[self::$code]['symbol_left'];
  }
}