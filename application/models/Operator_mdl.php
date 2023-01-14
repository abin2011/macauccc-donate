<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-10 10:14:17
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:25:43
 * @email             :  info@clickrweb.com
 * @description       :  操作日誌數據模型 Operator model
 */
class Operator_mdl extends MY_Model{

  public $_table        = 'operator';//操作表
  public $primary_key   = 'id';// 主鍵
  public $return_type   = 'array';//返回類型
  
  public $before_create = array('created_at','updated_at');

}