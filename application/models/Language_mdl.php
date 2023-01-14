<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-09 17:34:33
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:26:26
 * @email             :  info@clickrweb.com
 * @description       :  語言數據模型 language model
 */
class Language_mdl extends MY_Model{

  public $_table        = 'language';//操作表
  public $primary_key   = 'id';// 主鍵
  public $return_type   = 'array';//返回類型

  public $before_create = array('created_at','updated_at');
  public $before_update = array('updated_at');
  public $after_get     = array('status_format');//get后格式化狀態

  //格式化每行的狀態
  public function status_format($row){
    if(!empty($row) && is_array($row)){
      if(isset($row['status']) && $row['status']==1){
        $row['status_format']='<span class="label label-success">啟用</span>';
      }else if(isset($row['status']) && $row['status']==2){
        $row['status_format']='<span class="label label-danger">禁用</span>';
      }
    }
    return $row;
  }
  
}