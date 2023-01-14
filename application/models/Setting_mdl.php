<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-09 17:33:31
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:27:55
 * @email             :  info@clickrweb.com
 * @description       :  設定數據模型 setting model
 */
class Setting_mdl extends MY_Model{

  public $_table        = 'setting';//操作表
  public $primary_key   = 'key';// 主鍵
  public $return_type   = 'array';//返回類型

  public $before_create = array('updated_at');
  public $before_update = array('updated_at');

}