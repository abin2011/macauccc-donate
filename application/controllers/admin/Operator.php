<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-10 10:11:47
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:58:24
 * @email             :  info@clickrweb.com
 * @description       :  後台操作日誌 operator log
 */
class Operator extends Admin_Controller {

  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='system';
    $this->data['subPage']='operator';
    $this->load->model('operator_mdl');
  }

  public function index(){
    $this->_get_list();
    //$this->output->enable_profiler(true);
    $this->load->view('admin/operator_list_view',$this->data);
  }

  //獲取列表
  private function _get_list(){
    $page   = $this->input->get('p',TRUE);
    $page   = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
    $limit  = $this->data['default_admin_limit'];
    $offset = ($page - 1) * $limit;
    $offset = $offset < 0 ? 0:$offset;
    
    $url_query=$_SERVER['QUERY_STRING'];
    if(!empty($url_query)){
      $url_query=preg_replace('/&p=(\d+)/','',$url_query);
    }
    $this->data['operator']=$this->input->get('operator');
    $this->data['title']=$this->input->get('title');
    $this->data['action']=$this->input->get('action');
    $this->data['field']=$this->input->get('field');
    $this->data['sort']=$this->input->get('sort');
    $this->data['field']=!empty($this->data['field'])?$this->data['field']:'created_at';
    $this->data['sort']=!empty($this->data['sort'])?$this->data['sort']:'desc';
    $where_data=array(
      !empty($this->data['operator'])?"operator LIKE '%{$this->data['operator']}%' ":NULL,
      !empty($this->data['title'])?"title LIKE '%{$this->data['title']}%' ":NULL,    
      !empty($this->data['action'])?"action LIKE '%{$this->data['action']}%' ":NULL,
    );
    $where_data=array_filter($where_data);//刪除空值數組元素.
    $this->data['lists_count']=$this->operator_mdl->count_by($where_data);
    $pagination = '';
    if($this->data['lists_count'] > $limit)
    {
      $this->dpagination->currentPage($page);
      $this->dpagination->items($this->data['lists_count']);
      $this->dpagination->limit($limit);
      $this->dpagination->adjacents(2);
      $this->dpagination->target(site_url('admin/operator').'?'.$url_query);
      $this->dpagination->parameterName('p');
      $this->dpagination->nextLabel('下一頁');
      $this->dpagination->prevLabel('上一頁');
      $pagination = $this->dpagination->getOutput();
    }
    $this->data['pagination'] = $pagination;
    $this->data['lists']=$this->operator_mdl
      ->order_by($this->data['field'],$this->data['sort'])
      ->limit($limit,$offset)
      ->get_many_by($where_data);
  }

  //刪除
  public function delete($delete_id=0){
    if(!empty($delete_id) && is_numeric($delete_id)){
      $result=$this->operator_mdl->delete($delete_id);
      $this->message_redirect($result,'admin/operator');
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }

  //批量刪除
  public function delete_batch(){
    $delete_string=$this->input->get('delete_string');
    if(!empty($delete_string)){
      $del_arr=explode(',',$delete_string);
      $result=$this->operator_mdl->delete_many($del_arr);
      $this->message_redirect($result,'admin/operator');
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }

  //清空日誌
  public function delete_all(){
    $result=$this->db->empty_table($this->operator_mdl->_table);
    $this->message_redirect($result,'admin/operator');
  }

}