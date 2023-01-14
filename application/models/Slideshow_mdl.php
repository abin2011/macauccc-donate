<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-15 16:15:35
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-09-13 17:10:55
 * @email             :  info@clickrweb.com
 * @description       :  幻燈片數據模型 Slideshow model
 */
class Slideshow_mdl extends MY_Model{
  public $_table='slideshow';//操作表
  public $primary_key='id';// 主鍵
  public $return_type='array';//返回類型
  public $before_create = array('created_at','updated_at');
  public $before_update = array('updated_at');
  public $after_get=array('status_format');//get后格式化狀態
  public $after_delete=array('delete_description');//刪除對應的語言
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
  //lang,列表查詢描述
  public function join_description($lang_id='1'){
    if(!empty($lang_id) && is_numeric($lang_id)){
      $this->db->where('nd.language_id',$lang_id);
      $this->db->select('n.*,nd.title,nd.introduction');
      $this->db->from($this->_table.' AS n');
      $this->has_from_alias=TRUE;//判斷是否使用了from 表別名.
      $this->db->join('slideshow_description AS nd', 'n.id = nd.slideshow_id','LEFT');
      return $this;
    }
  }
  //lang 處理多語言新增/修改
  public function modify_description($descriptions=array(),$slideshow_id=''){
    if(!empty($descriptions) && is_array($descriptions)){
      if(!empty($slideshow_id) && is_numeric($slideshow_id)){
        $this->db->where('slideshow_id',$slideshow_id);
        $this->db->delete('slideshow_description');
      }
      foreach ($descriptions as $language_id => $item) {
        $item['slideshow_id']=$slideshow_id;
        $item['language_id']=$language_id;
        $this->db->insert('slideshow_description',$item);
      } //end foreach;
    }
    return ($this->db->affected_rows()>0)?true:false;
  }
  //lang 編輯獲取描述
  public function get_description($slideshow_id){
    if(!empty($slideshow_id) && is_numeric($slideshow_id)){
      $this->_table='slideshow_description';
      return $this->get_many_by(array('slideshow_id'=>$slideshow_id));
    }
  }
  //lang 描述delete
  public function delete_description($result_data=array()){
    if(!empty($result_data) && is_array($result_data) && count($result_data)==2){
      if(isset($result_data[1]) && $result_data[1]>0){
        $this->_table='slideshow_description';
        return $this->delete_by(array('slideshow_id'=>$result_data[0]));
      }
    }
  }
}