<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2020-04-07 16:49:11
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2023-01-30 11:15:14
 * @email             :  info@clickrweb.com
 * @description       :  支付管理後台控制器 payment controller
 */
class Payment extends Admin_Controller {

  private $unique_id;

  public function __construct(){
    parent::__construct();
    $this->data['currentPage'] ='manages';
    $this->data['subPage']     ='payment';
    $this->load->model('payment_mdl');
    $this->_get_option();
  }

  public function index(){
    $this->_get_list();
    $this->load->view('admin/payment_list_view',$this->data);
  }

  //獲取列表
  private function _get_list(){
    $page = $this->input->get('p',TRUE);
    $page = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
    $limit = $this->data['default_admin_limit'];
    $offset = ($page - 1) * $limit;
    if($offset < 0){
      redirect('admin/payment');
    }
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
    $this->data['title']=$this->input->get('title');

    $this->data['field']=$this->input->get('field');
    $this->data['sort']=$this->input->get('sort');
    $this->data['field']=!empty($this->data['field'])?$this->data['field']:'n.updated_at';
    $this->data['sort']=!empty($this->data['sort'])?$this->data['sort']:'desc';

    $where_data=array(
      !empty($this->data['title'])?"nd.title LIKE '%{$this->data['title']}%' ":NULL,
    );
    $where_data=array_filter($where_data);//刪除空值數組元素.

    $this->data['lists_count']=$this->payment_mdl
      ->join_description($this->data['default_language'])
      ->count_by($where_data);

    $pagination = '';
    if($this->data['lists_count'] > $limit){
      $this->dpagination->changeClass('pagination text-center');
      $this->dpagination->currentPage($page);
      $this->dpagination->items($this->data['lists_count']);
      $this->dpagination->limit($limit);
      $this->dpagination->adjacents(2);
      $this->dpagination->target(site_url('admin/payment').'?'.$url_query);
      $this->dpagination->parameterName('p');
      $this->dpagination->nextLabel('下一頁');
      $this->dpagination->PrevLabel('上一頁');
      $pagination = $this->dpagination->getOutput();
    }
    $this->data['pagination'] = $pagination;
    $this->data['lists']=$this->payment_mdl
      ->order_by($this->data['field'],$this->data['sort'])
      ->limit($limit,$offset)
      ->join_description($this->data['default_language'])
      ->get_many_by($where_data);
  }

  //添加按鈕頁面跳轉
  public function add(){
    $this->load->view('admin/payment_form_view',$this->data);
  }

  //修改按鈕頁面跳轉
  public function edit($edit_id=0){
    if(!empty($edit_id) && is_numeric($edit_id)){
      $query=$this->payment_mdl->get($edit_id);
      if(empty($query) || !is_array($query))
        show_error('參數不對.沒有該ID的列表項信息');

      $this->data['edit_id']=$edit_id;
      foreach ($query as $key => $value) {
        $this->data[$key]=$value;
      }
      //payment資料 descriptions
      $description=$this->payment_mdl->get_description($edit_id);
      foreach($description as $item){
        $this->data['descriptions'][$item['language_id']]=$item;
      }//end foreach;

      $this->load->view('admin/payment_form_view',$this->data);
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }

  //執行修改或添加功能
  public function modify(){
    $edit_id=$this->input->post('edit_id');
    $this->unique_id=$edit_id;
    $this->_load_validation_rules();
    if ($this->form_validation->run() == FALSE){
      $this->data['error']=true;
      $this->load->view('admin/payment_form_view',$this->data);
    }else{
      $data=array(
        'sort_order'  =>$this->input->post('sort_order'),
        'main_image'  =>$this->input->post('main_image'),
        'payment_key' =>$this->input->post('payment_key'),
        'status'      =>$this->input->post('status'),
        'updated_at'  =>$this->input->post('updated_at'),
        'created_at'  =>$this->input->post('created_at'),
      );
      $data=array_filter($data);//刪除空白.
      //多語言描述
      $descriptions=$this->input->post('descriptions');
      if(!empty($edit_id) && is_numeric($edit_id)){
        $operator_title='修改支付管理 ID->'.$edit_id;
        $action='修改';
        $result=$this->payment_mdl->update($data,$edit_id);
      }else{
        $operator_title='新增支付管理->'.$descriptions[$this->data['default_language']]['title'];
        $action='新增';
        $result=$this->payment_mdl->insert($data);
        $edit_id=$result;
      }
      $result+=$this->payment_mdl->modify_description($descriptions,$edit_id);
      $this->operator_log($operator_title,$action,$result);
      $this->message_redirect($result,'admin/payment');
    }
  }

  //驗證數據格式
  private function _load_validation_rules(){
    $this->form_validation->set_rules('edit_id','修改ID','trim|numeric');
    $this->form_validation->set_rules('add_time','添加時間','trim|max_length[20]');
    $this->form_validation->set_rules('update_time','修改時間','trim|max_length[20]');
    $this->form_validation->set_rules('sort_order','排序','trim|numeric|max_length[10]');
    $this->form_validation->set_rules('status','狀態','trim|required|numeric|max_length[1]');
    $this->form_validation->set_rules('main_image','封面圖片','trim|max_length[100]');
    $this->form_validation->set_rules('payment_key','配置key','trim|alpha_dash|is_unique['.$this->payment_mdl->_table.'.payment_key.id.'.$this->unique_id.']|min_length[3]|max_length[50]');
    //多語言判斷
    foreach($this->data['languages'] as $lg){
      $title='descriptions['.$lg["id"].'][title]';
      $introduction='descriptions['.$lg["id"].'][introduction]';
      $content='descriptions['.$lg["id"].'][content]';
      $this->form_validation->set_rules($title,$lg['name'].' 標題','trim|required|max_length[150]');
      $this->form_validation->set_rules($introduction,$lg['name'].' 內容簡介','trim|max_length[200]');
      $this->form_validation->set_rules($content,$lg['name'].' 內容','trim');
    }
  }

  //執行刪除功能
  public function delete($delete_id=0){
    if(!empty($delete_id) && is_numeric($delete_id)){
      $result=$this->payment_mdl->delete($delete_id);
      $this->operator_log('刪除支付管理->ID:'.$delete_id,'刪除',$result);
      $this->message_redirect($result,'admin/payment');
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
      $result=$this->payment_mdl->delete_many($delete_array);
      if($result){
        $result+=$this->payment_mdl->delete_description(array('payment_id'=>$delete_array,'result'=>$result));
      }
      $this->operator_log('批量刪除支付管理->ID:'.$delete_string,'批量刪除',$result);
      $this->message_redirect($result,'admin/payment');
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }

  //設置支付
  public function setting($payment_id=''){
    if(!empty($payment_id) && is_numeric($payment_id)){

      $payment=$this->payment_mdl
        ->join_description($this->data['default_language'])
        ->get_by(array('n.id'=>$payment_id));

      if(empty($payment) || !is_array($payment))
        show_error('參數不對.沒有該ID的列表項信息');

      $this->data['list']          = $payment;
      $this->data['payment_title'] = $payment['title'];

      $filter=array(
        'status'        =>1,
        'setting_group' =>'payment'
      );
      $this->load->model('setting_mdl');
      $query=$this->setting_mdl->get_many_by($filter);
      if(!empty($query) && is_array($query)){
        foreach($query as $key=>$value){
          $this->data[$key]=$value;
        }
      }
      $this->load->view('admin/payment_setting_'.$payment['payment_key'], $this->data);
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }

  //當面付設置保存
  public function modify_setting(){
    $result=FALSE;
    $data=$this->input->post();
    if(!empty($data) && is_array($data)){
      $this->load->model('setting_mdl');
      foreach($data as $setting_key=>$setting_value){
        $setting_data=array(
          'setting_group' =>'payment',
          'status'        =>1,//啟用
          'setting_key'   =>$setting_key,
          'setting_value' =>$setting_value,
          'updated_at'   =>date('Y-m-d H:i:s')
        );
        $result+=$this->db->replace($this->setting_mdl->_table,$setting_data);
      }
    }
    $this->operator_log('配置支付管理信息:'.serialize($data),'配置',$result);
    $this->message_redirect($result,'admin/payment');
  }

  //訂單狀態選項.
  public function _get_option(){
    $this->data['order_status_option'] = helper_type_parameter('order_status_option');
  }

}