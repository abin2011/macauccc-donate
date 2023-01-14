<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-10 09:48:59
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:25:23
 * @email             :  info@clickrweb.com
 * @description       :  基本頁面數據模型 cms page model
 */
class Page_mdl extends MY_Model{
  
  public $_table        = 'page';//操作表
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
      $this->db->from('page AS n');
      $this->has_from_alias=TRUE;//判斷是否使用了from 表別名.
      $this->db->join('page_description AS nd', 'n.id = nd.page_id','LEFT');
      if(!$is_full)
        $this->db->select('n.*,nd.title,nd.introduction');
      else
        $this->db->select('n.*,nd.*');
      return $this;
    }
  }

  //lang 處理多語言新增/修改
  public function modify_description($descriptions=array(),$page_id=''){
    if(!empty($descriptions) && is_array($descriptions)){
      if(!empty($page_id) && is_numeric($page_id)){
        $this->db->where('page_id',$page_id);
        $this->db->delete('page_description');
      }
      foreach ($descriptions as $language_id => $item) {
        $item['page_id']=$page_id;
        $item['language_id']=$language_id;
        $this->db->insert('page_description',$item);
      } //end foreach;
    }
    return ($this->db->affected_rows()>0)?true:false;
  }

  //lang 編輯獲取描述
  public function get_description($page_id){
    if(!empty($page_id) && is_numeric($page_id)){
      $this->_table='page_description';
      return $this->get_many_by(array('page_id'=>$page_id));
    }
  }

  //lang 描述delete
  public function delete_description($result_data=array()){
    if(!empty($result_data) && is_array($result_data) && count($result_data)==2){
      if(isset($result_data[1]) && $result_data[1]>0){
        $this->_table='page_description';
        return $this->delete_by(array('page_id'=>$result_data[0]));
      }
    }
  }

  //(前臺) 更新瀏覽次數
  public function add_view_num($news_id=''){
    if(!empty($news_id) && is_numeric($news_id)){
      $this->db->where('id',$news_id);
      $this->db->set('view_num', 'view_num+1', FALSE);
      $this->db->update($this->_table);
    }
  }

}