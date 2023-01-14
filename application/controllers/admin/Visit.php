<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-08-08 16:12:39
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:57:28
 * @email             :  info@clickrweb.com
 * @description       :  網站瀏覽記錄控制器 Visit Controller
 */
class Visit extends Admin_Controller {

  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='system';
    $this->data['subPage']='visit';
    $this->load->model('visit_mdl');
  }

  public function index(){
    $this->_get_list();
    //$this->output->enable_profiler(true);
    $this->load->view('admin/visit_list_view',$this->data);
  }

  //獲取列表
  private function _get_list(){
    $page   = $this->input->get('p',TRUE);
    $page   = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
    $limit  = $this->data['default_admin_limit'];
    $offset = ($page - 1) * $limit;
    $offset = $offset < 0 ? 0:$offset;
    
    $url_query=$_SERVER['QUERY_STRING'];
    // 保留查詢參數;
    $cookie = array(
      'name'   => 'url_query',
      'value'  => base64_encode($url_query),
      'expire' => '0',
      'path'   => '/',
    );
    $this->input->set_cookie($cookie);
    if(!empty($url_query)){
      $url_query=preg_replace('/&p=(\d+)/','',$url_query);
    }
    $this->data['ip_address']=$this->input->get('ip_address');
    $this->data['visit_date']=$this->input->get('visit_date');
    $this->data['device']=$this->input->get('device');
    $this->data['status']=$this->input->get('status');
    $this->data['field']=$this->input->get('field');
    $this->data['sort']=$this->input->get('sort');
    $this->data['field']=!empty($this->data['field'])?$this->data['field']:'created_at';
    $this->data['sort']=!empty($this->data['sort'])?$this->data['sort']:'desc';
    $where_data=array(
      'device'    =>$this->data['device'],
      'visit_date'=>$this->data['visit_date'],
      'ip_address'=>$this->data['ip_address'],
    );
    $where_data=array_filter($where_data);//刪除空值數組元素.
    $this->data['lists_count']=$this->visit_mdl->count_by($where_data);
    $pagination = '';
    if($this->data['lists_count'] > $limit){
      $this->dpagination->currentPage($page);
      $this->dpagination->items($this->data['lists_count']);
      $this->dpagination->limit($limit);
      $this->dpagination->adjacents(2);
      $this->dpagination->target(site_url('admin/visit').'?'.$url_query);
      $this->dpagination->parameterName('p');
      $this->dpagination->nextLabel('下一頁');
      $this->dpagination->prevLabel('上一頁');
      $pagination = $this->dpagination->getOutput();
    }
    $this->data['pagination'] = $pagination;
    $this->data['lists']=$this->visit_mdl
      ->order_by($this->data['field'],$this->data['sort'])
      ->limit($limit,$offset)
      ->get_many_by($where_data);
  }
  //刪除諮詢
  public function delete($delete_id=0){
    if(!empty($delete_id)){
      $result=$this->visit_mdl->delete($delete_id);
      $this->operator_log('刪除瀏覽記錄->ID:'.$delete_id,'刪除',$result);
      $this->message_redirect($result,'admin/visit');
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }
  //批量刪除
  public function delete_batch(){
    $delete_string=$this->input->get('delete_string');
    if(!empty($delete_string)){
      $delete_array=explode(',',$delete_string);
      $result=$this->visit_mdl->delete_many($delete_array);
      $this->operator_log('批量刪除瀏覽記錄->ID:'.$delete_string,'批量刪除',$result);
      $this->message_redirect($result,'admin/visit');
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }
}