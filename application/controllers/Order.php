<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2020-03-14 11:22:24
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2023-02-23 14:59:06
 * @email             :  info@clickrweb.com
 * @description       :  在線捐款控制器 Order controller
 */
class Order extends Lang_Controller {

  public function __construct(){
    parent::__construct();
    $this->data['active']='order';
    $this->load->model('order_mdl');
    $this->load->model('payment_mdl');
  }

  public function index(){
    $this->_initialize_data();
    $this->load->view('order_confirm_view',$this->data);
  }

  //提交session參數
  public function modify(){
    $order_session=$this->session->userdata('order_session');
    if(empty($order_session) || !is_array($order_session))
      redirect('home');

    $donate_money = $order_session['donate_money']=='other'?$order_session['donate_money_other']:$order_session['donate_money'];

    $order_data=array(
      'order_status_id'      =>0, //狀態為0 等待反饋結果.
      'donate_money'         =>$donate_money,
      'donate_church'        =>$order_session['donate_church'],
      'donate_item'          =>$order_session['donate_item'],
      'donate_item_other'    =>$order_session['donate_item_other'],
      'donate_gender'        =>$order_session['donate_gender'],
      'donate_firstname'     =>$order_session['donate_firstname'],
      'donate_lastname'      =>$order_session['donate_lastname'],
      'donate_email'         =>$order_session['donate_email'],
      'donate_country'       =>$order_session['donate_country'],
      'donate_phone'         =>$order_session['donate_phone'],
      'donate_address'       =>$order_session['donate_address'],
      'payment_method'       =>$order_session['payment_method'],
      
      'need_receipt'         =>$order_session['need_receipt'],
      'payment_receipt_type' =>$order_session['payment_receipt_type'],
      'payment_receipt_note' =>$order_session['payment_receipt_note'],
      
      'need_subscribe'       =>$order_session['need_subscribe'],
      'subscribe_type'       =>$order_session['subscribe_type'],
      'subscribe_note'       =>$order_session['subscribe_note'],
      
      'user_ip'              =>$this->input->ip_address(),
      'user_agent'           =>$this->input->user_agent(),
      'user_lang'            =>$this->input->server('HTTP_ACCEPT_LANGUAGE'),
    );

    //執行刪除一天前的狀態為0的訂單信息
    $filter = array(
      'order_status_id'=>0,
      'created_at <="'.date('Y-m-d H:i:s',strtotime('-1 day')).'"',//一天前創建
    );
    $this->order_mdl->delete_by($filter);

    $order_id = $this->order_mdl->insert($order_data);
    if($order_id){
      $this->session->set_userdata('order_id',$order_id);
      redirect('payment/'.$order_data['payment_method']);
    }else{
      redirect('error_404');
    }
  }


  //訂單支付結果
  public function result($result=''){
    $this->data['notify']=$result > 0?'success':'error';
    if($result){
      $this->session->unset_userdata('order_id');
      $this->session->unset_userdata('order_session');
    }
    $this->_get_page_content();
    $this->load->view('order_result_view',$this->data);
  }

  //列表資料.
  private function _get_page_content($unique_url='payment-result'){
    $this->load->model('page_mdl');
    $filter_data=array(
      'status'=>1,
      'unique_url'=>$unique_url,
    );
    $query=$this->page_mdl
      ->join_description($this->data['lang_id'],TRUE)
      ->get_by($filter_data);
    if(empty($query) || !is_array($query))
      return FALSE;
    $this->page_mdl->add_view_num($query['id']); //更新瀏覽次數
    $this->data['page_content']=$query['content'];
  }

  //初始化獲取資料
  private function _initialize_data(){
    $order_session=$this->session->userdata('order_session');
    if(empty($order_session) || !is_array($order_session))
      redirect('home');

    foreach ($order_session as $key => $value) {
      $this->data[$key]=$value;
      if($key=='donate_item' && !empty($value)){
        $this->data['donate_item_array']=json_decode($value,TRUE);
      }
    }

    //獲取對應的支付方式信息
    $this->data['payment']=$this->payment_mdl
      ->join_description($this->data['lang_id'],TRUE)
      ->get_by(array('payment_key'=>$order_session['payment_method']));

  }

}