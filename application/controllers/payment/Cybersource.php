<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2020-04-10 15:33:10
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2022-08-03 20:46:49
 * @email             :  info@clickrweb.com
 * @description       :  中國銀行 cybersource visa/mastercard支付控制器 cybersource controller
 */
class Cybersource extends Lang_Controller {

  public function __construct(){
    parent::__construct();
    $this->data['active']='cybersource';
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
    if(empty($order_data) || !is_array($order_data) || $order_data['payment_method']!='cybersource'){
      $this->session->set_flashdata('error','Order data Error,訂單信息獲取出錯！');
      redirect('order');
    }

    $this->data['payment']=$this->payment_mdl
      ->join_description($this->data['lang_id'])
      ->get_by(array('payment_key'=>$order_data['payment_method']));

    $this->_before_payment_data($order_data);

    $this->load->view('payment/cybersource_list_view',$this->data);
  }

  //準備好支付的數據.
  //cybersource 的callback url 和 front url 需要在cybersource的平台設定.
  private function _before_payment_data($order_data = array()){
    if(!empty($order_data) && is_array($order_data)){
      $this->data['order_id']      = $order_data['id'];
      $this->data['donate_name']   = $order_data['donate_firstname'].' '.$order_data['donate_lastname'];
      $this->data['donate_gender'] = $order_data['donate_gender'];

      $action = 'https://secureacceptance.cybersource.com/pay';
      if($this->data['cybersource_test']){ //如果是測試
        $action = 'https://testsecureacceptance.cybersource.com/pay';
      }
      $lang_array  = array('1'=>'zh-MO','2'=>'zh-CN','3'=>'en-US');
      $order_no    = date('YmdHis').'Ord'.$order_data['id'];

      //組裝cybersource parameter
      $parameter=array(
        'bill_to_forename'            => $order_data['donate_firstname'],
        'bill_to_surname'             => $order_data['donate_lastname'],
        'bill_to_company_name'        => '',
        'bill_to_address_line1'       => $order_data['donate_address'],
        'bill_to_address_line2'       => '',
        'bill_to_address_city'        => 'Mountain View',
        'bill_to_address_state'       => 'CA',
        'bill_to_address_postal_code' => '94043',
        'bill_to_address_country'     => 'US',
        'bill_to_phone'               => $order_data['donate_phone'],
        'bill_to_email'               => $order_data['donate_email'],
        'ignore_avs'                  => 'true',
        'locale'                      => isset($lang_array[$this->data['lang_id']])?$lang_array[$this->data['lang_id']]:'en-US',//页面语言 zh-MO / zh-CN / en-US
        'profile_id'                  => $this->data['cybersource_profile_id'],
        'access_key'                  => $this->data['cybersource_access_key'],
        'transaction_type'            => 'sale',//消费,非预授权
        'currency'                    => 'MOP',
        'reference_number'            => $order_no,//訂單號
        'amount'                      => $order_data['donate_money'],//金額
        'signed_date_time'            => gmdate("Y-m-d\TH:i:s\Z"),
        'transaction_uuid'            => uniqid().$order_no,
      );
      ksort($parameter);//排序

      $parameter['signed_field_names'] = implode(',',array_keys($parameter)).',signed_field_names';//签名字段
      
      $cybersource_data                = $parameter;
      
      $cybersource_data['signature']   = $this->sign($cybersource_data,$this->data['cybersource_secret_key']);
      
      $this->data['payment_data']      = $cybersource_data;
      $this->data['payment_action']    = $action;
    }
  }

  //front 前台明面顯示給客戶看的.
  //進行cybersource支付后的驗證.
  public function front(){
    $sybersourceSecretKey = $this->data['cybersource_secret_key'];
    $verify_result        = FALSE;
    if(0 === strcmp($_REQUEST["signature"],$this->sign($_REQUEST,$sybersourceSecretKey))){
      $reasonCode         = isset($_REQUEST['reason_code'])?trim($_REQUEST['reason_code']):FALSE;
      $decisionCode       = trim($_REQUEST['decision']);
      $reqAmount          = trim($_REQUEST['req_amount']); // 返回的支付金额
      $reqReferenceNumber = trim($_REQUEST['req_reference_number']);//定单号
      if('100' === $reasonCode && 'ACCEPT' === $decisionCode && strstr($reqReferenceNumber,'Ord')){
        $verify_result = TRUE;
        $order_string_array = explode('Ord',$reqReferenceNumber);
        $order_id           = end($order_string_array);
        $order_data         = $this->order_mdl->get($order_id);
        if(empty($order_data) || !is_array($order_data)){
          log_message('debug','Cybersource  Visa/Mastercard Payment front() order_data error.(跳轉函數獲取訂單信息出錯!)');
        }
        if(!empty($order_data) && is_array($order_data) && floatval($order_data['donate_money'])==floatval($reqAmount))
          redirect('order/result/'.$order_id);
      }
    }
    $message = $verify_result > 0?lang('operate_success'):'對不起,您的支付操作失敗,請重新支付!(Cybersource Visa/Mastercard Payment front Error)';
    $notify  = $verify_result > 0?'success':'error';
    $this->session->set_flashdata($notify,$message);
    redirect('order');
  }

  //確認訂單信息 //cybersource 的callback url 需要在cybersource的平台設定.
  //後台確認並且修改狀態.
  public function callback() {
    $sybersourceSecretKey = $this->data['cybersource_secret_key'];
    log_message('debug','Cybersource Visa/Mastercard callback Array:'.var_export($_REQUEST,TRUE));
    if(0 === strcmp($_REQUEST["signature"],$this->sign($_REQUEST,$sybersourceSecretKey))){
      $reasonCode         = isset($_REQUEST['reason_code'])?trim($_REQUEST['reason_code']):FALSE;
      $decisionCode       = trim($_REQUEST['decision']);
      $reqAmount          = trim($_REQUEST['req_amount']); // 返回的支付金额
      $reqReferenceNumber = trim($_REQUEST['req_reference_number']);//定单号

      if('100' === $reasonCode && 'ACCEPT' === $decisionCode && strstr($reqReferenceNumber,'Ord')){
        $order_string_array = explode('Ord',$reqReferenceNumber);
        $order_id           = end($order_string_array);
        $order_data         = $this->order_mdl->get($order_id);
        if(empty($order_data) || !is_array($order_data)){
          log_message('debug','Cybersource Visa/Mastercard Payment callback() order_data error.(回調函數獲取訂單信息出錯!)');
          return FALSE;
        }
        //交易金额
        $order_total=floatval($order_data['donate_money']);
        if($order_total==floatval($reqAmount) && $order_data['order_status_id']==0){
          $update_data=array(
            'number' =>$reqReferenceNumber,
            'order_status_id'=>$this->data['cybersource_order_status_id'],
          );
          $update_result=$this->order_mdl->update($update_data,$order_id);
          if($update_result){
            helper_order_send_email($order_data,$order_id);//電郵通知客戶,商戶
          }
          die("success");
        }
      }
    }
  }


  /**
   * CyberSource Payment Class 支付类
   * @Author   Clickr   Abin
   * @DateTime 2020-04-10T18:10:55+0800
   */
  private function sign($params, $secretKey){
    return $this->signData($this->buildDataToSign($params), $secretKey);
  }

  private function signData($data, $secretKey){
    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
  }

  private function buildDataToSign($params){
    $signedFieldNames = explode(",",$params["signed_field_names"]);
    foreach ($signedFieldNames as $field){
      $dataToSign[] = $field . "=" . $params[$field];
    }
    return $this->commaSeparate($dataToSign);
  }

  private function commaSeparate($dataToSign){
    return implode(",",$dataToSign);
  }

}