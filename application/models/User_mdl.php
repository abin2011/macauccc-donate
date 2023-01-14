<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-12 09:33:25
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:29:07
 * @email             :  info@clickrweb.com
 * @description       :  系統管理 用戶數據模型 user model
 */
class User_mdl extends MY_Model{

  public $_table        ='user';//操作表
  public $primary_key   ='id';// 主鍵
  public $return_type   ='array';//返回類型
  
  public $before_create = array('created_at','updated_at');
  public $before_update = array('updated_at');
  public $after_get     = array('status_format');//get后格式化狀態
  
  //一對一
  public $belongs_to    = array(
    'user_group'=> array('model'=>'user_group_mdl','primary_key' =>'id'),
  );

  //格式化每行的狀態
  public function status_format($row){
    if(!empty($row) && is_array($row)){
      if(isset($row['status']) && $row['status']==1){
        $row['status_format']='<span class="label label-success">啟用</span>';
      }else if(isset($row['status']) && $row['status']==2){
        $row['status_format']='<span class="label label-danger">禁用</span>';
      }
    }
    $this->has_from_alias=FALSE;//重置 from 表別名. 對應修改時丟失表名
    return $row;
  }

  //join
  public function join_user_group(){
    $this->db->select('n.*,nd.name AS group_name');
    $this->db->from($this->_table.' AS n');
    $this->has_from_alias=TRUE;//判斷是否使用了from 表別名.
    $this->db->join('user_group AS nd', 'n.group_id=nd.id','LEFT');
    return $this;
  }
  
}