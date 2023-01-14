<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2020-04-10 15:36:35
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-04-11 17:45:46
 * @email             :  info@clickrweb.com
 * @description       :  貨到付款現金交易控制器 Cash controller
 */
class Cash extends Lang_Controller {

  public function __construct(){
    parent::__construct();
    $this->data['active']='cash';
    $this->load->model('order_mdl');
    $this->load->model('payment_mdl');
  }

  public function index(){

    $order_id=$this->session->userdata('order_id');
    if(empty($order_id) || !is_numeric($order_id)){
      $this->session->set_flashdata('error','Order ID Error,訂單ID出錯！');
      redirect('order');
    }

    $order_data = $this->order_mdl->get($order_id);
    if(empty($order_data) || !is_array($order_data) || $order_data['payment_method']!='cash'){
      $this->session->set_flashdata('error','Order data Error,訂單信息獲取出錯！');
      redirect('order');
    }

    //更新狀態
    $result=$this->order_mdl->update(array('order_status_id'=>1),$order_id);
    if($result){
      helper_order_send_email($order_data,$order_id);//電郵通知客戶,商戶
    }
    redirect('order/result/'.$result); //跳轉到訂單結果
    
    // $this->data['payment']=$this->payment_mdl
    //   ->join_description($this->data['lang_id'])
    //   ->get_by(array('payment_key'=>$order_data['payment_method']));

    // $this->_before_payment_data($order_data);

    // $this->load->view('payment/cash_list_view',$this->data);
  }

  //準備好支付的數據.
  private function _before_payment_data($order_data = array()){
    if(!empty($order_data) && is_array($order_data)){
      $this->data['order_id']      = $order_data['id'];
      $this->data['donate_name']   = $order_data['donate_firstname'].' '.$order_data['donate_lastname'];
      $this->data['donate_gender'] = $order_data['donate_gender'];
    }
  }

  //提交現金結算 <棄用>
  public function modify(){
    $order_id=$this->input->post('order_id',TRUE);
    $session_order_id=$this->session->userdata('order_id');
    if(!empty($order_id) && is_numeric($order_id) && $session_order_id==$order_id){
      $order_data=$this->order_mdl->get($order_id);
      if(empty($order_data) || !is_array($order_data))
        redirect('error_404');
      //更新狀態
      $result=$this->order_mdl->update(array('order_status_id'=>$this->data['cash_order_status_id']),$order_id);
      if($result){
        helper_order_send_email($order_data,$order_id);//電郵通知客戶,商戶
      }
      // redirect('order/result/'.$result); //跳轉到訂單結果
    }else{
      show_error(lang('parameter_error'));
      exit;
    }
  }

}