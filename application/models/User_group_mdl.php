<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-12 12:51:16
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:28:39
 * @email             :  info@clickrweb.com
 * @description       :  系統管理 用戶組數據模型 user group model
 */
class User_group_mdl extends MY_Model{

  public $_table        = 'user_group';//操作表
  public $primary_key   = 'id';// 主鍵
  public $return_type   = 'array';//返回類型

  public $before_create = array('created_at','updated_at');
  public $before_update = array('updated_at');

}