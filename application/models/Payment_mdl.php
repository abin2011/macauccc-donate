<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2020-04-07 17:11:38
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-04-08 11:30:40
 * @email             :  info@clickrweb.com
 * @description       :  支付管理數據模型 payment model
 */
class Payment_mdl extends MY_Model{

  public $_table        = 'payment';//操作表
  public $primary_key   = 'id';// 主鍵
  public $return_type   = 'array';//返回類型

  public $before_create = array('created_at','updated_at');
  public $before_update = array('updated_at');
  public $after_get     = array('status_format');//get后格式化狀態
  
  public $after_delete  = array('delete_description');//刪除對應的語言

  //格式化每行的數據
  public function status_format($row){
    if(!empty($row) && is_array($row)){
      if(isset($row['status']) && $row['status']==1){
        $row['status_format']='<span class="layui-badge layui-bg-green">啟用</span>';
      }else if(isset($row['status']) && $row['status']==2){
        $row['status_format']='<span class="layui-badge layui-bg-gray">禁用</span>';
      }
    }
    $this->has_from_alias=FALSE;//重置 from 表別名. 對應修改時丟失表名
    return $row;
  }

  //lang,列表查詢描述
  public function join_description($lang_id='',$is_full=FALSE){
    if(!empty($lang_id) && is_numeric($lang_id)){
      $this->db->where('nd.language_id',$lang_id);
      $this->db->from($this->_table.' AS n');
      $this->has_from_alias=TRUE;//判斷是否使用了from 表別名.
      $this->db->join('payment_description AS nd', 'n.id = nd.payment_id','LEFT');
      if(!$is_full)
        $this->db->select('n.*,nd.title,nd.introduction');
      else
        $this->db->select('n.*,nd.*');
      return $this;
    }
  }

  //lang 處理多語言新增/修改
  public function modify_description($descriptions=array(),$payment_id=''){
    if(!empty($descriptions) && is_array($descriptions)){
      if(!empty($payment_id) && is_numeric($payment_id)){
        $this->db->where('payment_id',$payment_id);
        $this->db->delete('payment_description');
      }
      foreach ($descriptions as $language_id => $item) {
        $item['payment_id']=$payment_id;
        $item['language_id']=$language_id;
        $this->db->insert('payment_description',$item);
      } //end foreach;
    }
    return ($this->db->affected_rows()>0)?true:false;
  }

  //lang 編輯獲取描述
  public function get_description($payment_id){
    if(!empty($payment_id) && is_numeric($payment_id)){
      $this->_table='payment_description';
      return $this->get_many_by(array('payment_id'=>$payment_id));
    }
  }

  //lang 描述delete
  public function delete_description($result_data=array()){
    if(!empty($result_data) && is_array($result_data) && count($result_data)==2){
      if(isset($result_data[1]) && $result_data[1]>0){
        $this->_table='payment_description';
        return $this->delete_by(array('payment_id'=>$result_data[0]));
      }
    }
  }

}