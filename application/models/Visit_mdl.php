<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-20 14:38:15
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:29:26
 * @email             :  info@clickrweb.com
 * @description       :  網站訪問統計數據模型 Visit model
 */
class Visit_mdl extends MY_Model{

  public $_table        = 'visit';//操作表
  public $primary_key   = 'id';// 主鍵
  public $return_type   = 'array';//返回類型

  public $before_create = array('created_at','updated_at');
  public $before_update = array('updated_at');
  public $after_get     = array('status_format');//get后格式化狀態

  //格式化每行的狀態
  public function status_format($row){
    if(!empty($row) && is_array($row)){
      if(isset($row['device']) && $row['device']=='computer'){
        $row['device_format']='<span class="layui-badge layui-bg-green"><i class="fas fa-desktop"></i> 電腦</span>';
      }else if(isset($row['device']) && $row['device']=='mobile'){
        $row['device_format']='<span class="layui-badge layui-bg-gray"><i class="fas fa-mobile-alt"></i> 手機</span>';
      }
    }
    $this->has_from_alias=FALSE;//重置 from 表別名. 對應修改時丟失表名
    return $row;
  }
  
}