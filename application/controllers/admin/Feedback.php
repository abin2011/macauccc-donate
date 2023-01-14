<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-19 18:12:15
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:59:12
 * @email             :  info@clickrweb.com
 * @description       :  留言反饋控制器,聯絡我們表單,Feedback Controller
 */
class Feedback extends Admin_Controller {
  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='feedback';
    $this->load->model('feedback_mdl');
  }
  public function index(){
    $this->_get_list();
    //$this->output->enable_profiler(true);
    $this->load->view('admin/feedback_list_view',$this->data);
  }
  //獲取列表
  private function _get_list(){
    $page   = $this->input->get('p',TRUE);
    $page   = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
    $limit  = $this->data['default_admin_limit'];
    $offset = ($page - 1) * $limit;
    $offset = $offset < 0 ? 0:$offset;
    
    $url_query=$_SERVER['QUERY_STRING'];
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
    $this->data['name']=$this->input->get('name');
    $this->data['phone']=$this->input->get('phone');
    $this->data['email']=$this->input->get('email');
    $this->data['status']=$this->input->get('status');
    $this->data['field']=$this->input->get('field');
    $this->data['sort']=$this->input->get('sort');
    $this->data['field']=!empty($this->data['field'])?$this->data['field']:'created_at';
    $this->data['sort']=!empty($this->data['sort'])?$this->data['sort']:'desc';
    $where_data=array(
      !empty($this->data['name'])?"name LIKE '%{$this->data['name']}%' ":NULL,
      !empty($this->data['phone'])?"phone LIKE '%{$this->data['phone']}%' ":NULL,
      !empty($this->data['email'])?"email LIKE '%{$this->data['email']}%' ":NULL,
      'status'=>$this->data['status'],
    );
    $where_data=array_filter($where_data);//刪除空值數組元素.
    $this->data['lists_count']=$this->feedback_mdl->count_by($where_data);
    $pagination = '';
    if($this->data['lists_count'] > $limit){
      $this->dpagination->currentPage($page);
      $this->dpagination->items($this->data['lists_count']);
      $this->dpagination->limit($limit);
      $this->dpagination->adjacents(2);
      $this->dpagination->target(site_url('admin/feedback').'?'.$url_query);
      $this->dpagination->parameterName('p');
      $this->dpagination->nextLabel('下一頁');
      $this->dpagination->prevLabel('上一頁');
      $pagination = $this->dpagination->getOutput();
    }
    $this->data['pagination'] = $pagination;
    $this->data['lists']=$this->feedback_mdl
      ->order_by($this->data['field'],$this->data['sort'])
      ->limit($limit,$offset)
      ->get_many_by($where_data);
  }
  //啓用詳細
  public function view($view_id=0){
    if(!empty($view_id) && is_numeric($view_id)){
      $query=$this->feedback_mdl->get($view_id);
      if(empty($query) || !is_array($query))
        show_error('參數不對.沒有該ID的列表項信息');
      $this->feedback_mdl->update(array('status'=>2),$view_id);
      foreach ($query as $key => $value) {
        $this->data[$key]=$value;
      }
      $this->data['list']=$query;
      $this->operator_log('查看通知消息->ID:'.$view_id,'查看',1);
      $this->load->view('admin/feedback_display_view',$this->data);
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }
  //刪除諮詢
  public function delete($delete_id=0){
    if(!empty($delete_id)){
      $result=$this->feedback_mdl->delete($delete_id);
      $this->operator_log('刪除通知消息->ID:'.$delete_id,'刪除',$result);
      $this->message_redirect($result,'admin/feedback');
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
      $result=$this->feedback_mdl->delete_many($delete_array);
      $this->operator_log('批量刪除通知消息->ID:'.$delete_string,'批量刪除',$result);
      $this->message_redirect($result,'admin/feedback');
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }
}