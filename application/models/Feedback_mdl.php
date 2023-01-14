<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-09 17:36:19
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:26:45
 * @email             :  info@clickrweb.com
 * @description       :  通知消息數據模型 Feedback model
 */
class Feedback_mdl extends MY_Model{
  
  public $_table        = 'feedback';//操作表
  public $primary_key   = 'id';// 主鍵
  public $return_type   = 'array';//返回類型

  public $before_create = array('created_at','updated_at');
  public $before_update = array('updated_at');
  public $after_get     = array('status_format');//get后格式化狀態

  //格式化每行的狀態
  public function status_format($row){
    if(!empty($row) && is_array($row)){
      if(isset($row['status']) && $row['status']==1){
        $row['status_format']='<span class="layui-badge layui-bg-green">未讀</span>';
      }else if(isset($row['status']) && $row['status']==2){
        $row['status_format']='<span class="layui-badge layui-bg-gray">已讀</span>';
      }
    }
    $this->has_from_alias=FALSE;//重置 from 表別名. 對應修改時丟失表名
    return $row;
  }

}