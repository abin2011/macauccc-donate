<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-19 17:27:13
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2022-08-03 20:50:50
 * @email             :  info@clickrweb.com
 * @description       :  捐款管理控制器 Order Controller
 */
class Order extends Admin_Controller {

  private $unique_id;

  public function __construct(){
    parent::__construct();
    $this->data['currentPage'] ='manages';
    $this->data['subPage']     ='order';
    $this->load->model('order_mdl');
    $this->_get_option();
  }

  public function index(){
    $this->_get_list();
    $this->load->view('admin/order_list_view',$this->data);
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
    $this->data['export_url']      =site_url('admin/order/export').'?'.$url_query;
    
    $this->data['number']          =$this->input->get('number');
    $this->data['donate_name']     =$this->input->get('donate_name');
    $this->data['donate_email']    =$this->input->get('donate_email');
    $this->data['donate_country']  =$this->input->get('donate_country');
    $this->data['donate_phone']    =$this->input->get('donate_phone');
    $this->data['order_status_id'] =$this->input->get('order_status_id');
    $this->data['status']          =$this->input->get('status');

    $this->data['field']           =$this->input->get('field');
    $this->data['sort']            =$this->input->get('sort');
    $this->data['field']           =!empty($this->data['field'])?$this->data['field']:'created_at';
    $this->data['sort']            =!empty($this->data['sort'])?$this->data['sort']:'desc';
    
    $where_data=array(
      'order_status_id !=' => 0,
      'status'=>$this->data['status'],
      'order_status_id' => $this->data['order_status_id'],
      !empty($this->data['number'])?"number LIKE '%{$this->data['number']}%' ":NULL,
      !empty($this->data['donate_name'])?"(CONCAT(donate_firstname,' ',donate_lastname) LIKE '%{$this->data['donate_name']}%')":NULL,
      !empty($this->data['donate_email'])?"donate_email LIKE '%{$this->data['donate_email']}%' ":NULL,
      !empty($this->data['donate_country'])?"donate_country LIKE '%{$this->data['donate_country']}%' ":NULL,
      !empty($this->data['donate_phone'])?"donate_phone LIKE '%{$this->data['donate_phone']}%' ":NULL,
    );

    $where_data=array_filter($where_data,function($item){
      return !empty($item)||is_numeric($item);
    });

    $this->data['lists_count']=$this->order_mdl->count_by($where_data);
    $pagination = '';
    if($this->data['lists_count'] > $limit)
    {
      $this->dpagination->changeClass('pagination');
      $this->dpagination->currentPage($page);
      $this->dpagination->items($this->data['lists_count']);
      $this->dpagination->limit($limit);
      $this->dpagination->adjacents(2);
      $this->dpagination->target(site_url('admin/order').'?'.$url_query);
      $this->dpagination->parameterName('p');
      $this->dpagination->nextLabel('下一頁');
      $this->dpagination->prevLabel('上一頁');
      $pagination = $this->dpagination->getOutput();
    }
    $this->data['pagination'] = $pagination;
    $this->data['lists']=$this->order_mdl
      ->order_by($this->data['field'],$this->data['sort'])
      ->limit($limit,$offset)
      ->get_many_by($where_data);
  }

  //顯示詳細
  public function view($view_id=0){
    if(!empty($view_id) && is_numeric($view_id)){

      $query=$this->order_mdl->get($view_id);
      if(empty($query) || !is_array($query))
        show_error('參數不對.沒有該ID的列表項信息');
      
      $this->data['order_id']=$view_id;
      foreach ($query as $key => $value) {
        $this->data[$key]=$value;
        if($key=='donate_item' && !empty($value)){
          $this->data['donate_item_array']=json_decode($value,TRUE);
        }
      }

      // $this->output->enable_profiler(TRUE);
      $this->load->view('admin/order_display_view',$this->data);
    }
  }

  //修改會員信息
  public function edit($edit_id=0){
    $field=$this->input->get('field');
    $value=$this->input->get('value');
    if(!empty($edit_id) && is_numeric($edit_id) && in_array($field,array('status','bind_wechat')) && in_array($value,array(1,2))){
      $result=$this->order_mdl->update(array($field=>$value),$edit_id);
      $this->operator_log('編輯捐款管理->ID:'.$edit_id,'編輯',$result);
      $this->message_redirect($result,'admin/order/view/'.$edit_id);
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }

  //刪除
  public function delete($delete_id=0){
    if(!empty($delete_id) && is_numeric($delete_id)){
      $result=$this->order_mdl->delete($delete_id);
      $this->operator_log('刪除捐款管理->ID:'.$delete_id,'刪除',$result);
      $this->message_redirect($result,'admin/order');
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
      $result=$this->order_mdl->delete_many($delete_array);
      $this->operator_log('批量刪除捐款管理->ID:'.$delete_string,'批量刪除',$result);
      $this->message_redirect($result,'admin/order');
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }

  //訂單狀態選項.
  public function _get_option(){
    $this->data['order_status_option'] = helper_type_parameter('order_status_option');
    $this->data['order_status_class'] = helper_type_parameter('order_status_class');
  }

  //結果導出execl
  public function export(){
    header("Content-Type:application/vnd.ms-excel");
    header('Content-Disposition:attachment;filename="Order-'.date('YmdHis').'.xls"');
    header("Pragma: no-cache");

    $this->data['donate_name']=$this->input->get('donate_name');
    $this->data['donate_email']=$this->input->get('donate_email');
    $this->data['donate_country']=$this->input->get('donate_country');
    $this->data['donate_phone']=$this->input->get('donate_phone');
    $this->data['order_status_id'] =$this->input->get('order_status_id');

    $this->data['status']=$this->input->get('status');
    $this->data['field']=$this->input->get('field');
    $this->data['sort']=$this->input->get('sort');
    $this->data['field']=!empty($this->data['field'])?$this->data['field']:'created_at';
    $this->data['sort']=!empty($this->data['sort'])?$this->data['sort']:'desc';
    $where_data=array(
      'order_status_id !=' => 0,
      'status'=>$this->data['status'],
      'order_status_id' => $this->data['order_status_id'],
      !empty($this->data['donate_name'])?"(CONCAT(donate_firstname,' ',donate_lastname) LIKE '%{$this->data['donate_name']}%')":NULL,
      !empty($this->data['donate_email'])?"donate_email LIKE '%{$this->data['donate_email']}%' ":NULL,
      !empty($this->data['donate_country'])?"donate_country LIKE '%{$this->data['donate_country']}%' ":NULL,
      !empty($this->data['donate_phone'])?"donate_phone LIKE '%{$this->data['donate_phone']}%' ":NULL,
    );

    $where_data=array_filter($where_data,function($item){
      return !empty($item)||is_numeric($item);
    });

    $export=$this->order_mdl
      ->order_by($this->data['field'],$this->data['sort'])
      ->get_many_by($where_data);

    $this->data['export']=$export;
    $this->operator_log('捐款管理結果匯出Execl,一共匯出'.count($export).'條數據','匯出Execl',1);
    $this->load->view('admin/order_export_execl',$this->data);
  }

}