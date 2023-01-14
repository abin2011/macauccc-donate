<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2020-04-10 17:39:38
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-04-11 17:46:54
 * @email             :  info@clickrweb.com
 * @description       :  中銀Paydollar visa/mastercard 支付 Paydollar Controller
 */

//引入 Paydollar SDK
require_once(APPPATH.'third_party/SHAPaydollarSecure.php');

class Paydollar extends Lang_Controller {

  public function __construct(){
    parent::__construct();
    $this->data['active']='paydollar';
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
    if(empty($order_data) || !is_array($order_data) || $order_data['payment_method']!='paydollar'){
      $this->session->set_flashdata('error','Order data Error,訂單信息獲取出錯！');
      redirect('order');
    }

    $this->data['payment']=$this->payment_mdl
      ->join_description($this->data['lang_id'],TRUE)
      ->get_by(array('payment_key'=>$order_data['payment_method']));

    $this->_before_payment_data($order_data);

    $this->load->view('payment/paydollar_list_view',$this->data);
  }

  //準備好支付的數據.
  //paydollar 的callback url 需要在paydollar提供的平台設定.
  private function _before_payment_data($order_data = array()){
    if(!empty($order_data) && is_array($order_data)){
      if(!empty($this->data['paydollar_merchant_id']) && !empty($this->data['paydollar_security'])){

        $payment_action = 'https://www.paydollar.com/b2c2/eng/payment/payForm.jsp';
        if($this->data['paydollar_test']){ //沙盒測試模式.
          $payment_action = 'https://test.paydollar.com/b2cDemo/eng/payment/payForm.jsp';          
        }

        $secureHashSecret=$this->data['paydollar_security'];
        $payment_data=array(
          'merchantId'    => $this->data['paydollar_merchant_id'],
          'amount'        => $order_data['donate_money'],//轉成MOP支付
          'orderRef'      => $order_data['id'],
          'currCode'      => '446', //446 MOP 344 HKD 156 CNY
          'payType'       => 'N',//N 消費交易,H預授權交易
          'successUrl'    => site_url('payment/paydollar/result/success'),
          'failUrl'       => site_url('payment/paydollar/result/failure'),
          'cancelUrl'     => site_url('order'),
          'payMethod'     => 'ALL',
          'mpsMode'       => 'NIT',
          'lang'          => $this->data['lang_id']==1?'C':'E',
          'redirect'      => '3',
          'oriCountry'    => '',
          'destCountry'   => '',
        );
        $paydollarSecure=new SHAPaydollarSecure();
        $payment_data['secureHash']   = $paydollarSecure->generatePaymentSecureHash($payment_data['merchantId'], $payment_data['orderRef'], $payment_data['currCode'], $payment_data['amount'], $payment_data['payType'], $secureHashSecret);
        
        $this->data['payment_data']   = $payment_data;
        $this->data['payment_action'] = $payment_action;
      }
    }
  }


  //callback 隱式校對支付結果.
  //Paydollar 的callback url 需要在Paydollar的平台設定.
  public function callback(){
    //get post data start
    $successcode = isset($_POST['successcode']) ? $_POST['successcode']  : '' ; 
    $src = isset($_POST['src']) ? $_POST['src']  : '' ; 
    $prc = isset($_POST['prc']) ? $_POST['prc']  : '' ; 
    $ref = isset($_POST['Ref']) ? $_POST['Ref']  : '' ; //商戶訂單id
    $payRef = isset($_POST['PayRef']) ? $_POST['PayRef']  : '' ;//支付訂單id
    $amt = isset($_POST['Amt']) ? $_POST['Amt']  : '' ; 
    $cur = isset($_POST['Cur']) ? $_POST['Cur']  : '' ; 
    $payerAuth = isset($_POST['payerAuth']) ? $_POST['payerAuth']  : '' ; 
    $ord = isset($_POST['Ord']) ? $_POST['Ord']  : '' ; 
    $holder = isset($_POST['Holder']) ? $_POST['Holder']  : '' ; 
    $remark = isset($_POST['remark']) ? $_POST['remark']  : '' ; 
    $authId = isset($_POST['AuthId']) ? $_POST['AuthId']  : '' ; 
    $eci = isset($_POST['eci']) ? $_POST['eci']  : '' ; 
    $sourceIp = isset($_POST['sourceIp']) ? $_POST['sourceIp']  : '' ; 
    $ipCountry = isset($_POST['ipCountry']) ? $_POST['ipCountry']  : '' ;     
    $mpsAmt = isset($_POST['mpsAmt']) ? $_POST['mpsAmt']  : '' ;
    $mpsCur = isset($_POST['mpsCur']) ? $_POST['mpsCur']  : '' ;
    $mpsForeignAmt = isset($_POST['mpsForeignAmt']) ? $_POST['mpsForeignAmt']  : '' ;
    $mpsForeignCur = isset($_POST['mpsForeignCur']) ? $_POST['mpsForeignCur']  : '' ;
    $mpsRate = isset($_POST['mpsRate']) ? $_POST['mpsRate']  : '' ; 
    $cardlssuingCountry = isset($_POST['cardlssuingCountry']) ? $_POST['cardlssuingCountry']  : '' ; 
    $payMethod = isset($_POST['payMethod']) ? $_POST['payMethod']  : '' ;
    
    $secureHash = isset($_POST['secureHash']) ? $_POST['secureHash']  : '' ;

    $secureHashSecret = $this->data['paydollar_security'];

    echo 'OK';

    $verifyResult = FALSE;
    if(isset($_POST['secureHash']) && !empty($secureHash) && $secureHashSecret){

      $paydollarSecure=new SHAPaydollarSecure();
      $secureHash_array = explode(',',$secureHash);
      while(list($key,$value) = each($secureHash_array)){
        $verifyResult = $paydollarSecure->verifyPaymentDatafeed($src,$prc,$successcode,$ref,$payRef,$cur,$amt,$payerAuth,$secureHashSecret,$value);
        echo '$secureHash=[' . $value . ']';
        if ($verifyResult) {
          echo ' - verifyResult= true';
          break;
        } else {
          echo ' - verifyResult= false';
        }
      } //end while

      if(!$verifyResult){
        echo ' - Verify Fail';
        return;
      } else {
        echo ' - Verify Success';
      }
    }
    log_message('debug','Paydollar callback Array:'.var_export($_POST,TRUE));
    $paramsReceived = '';
    while ( list ( $key, $value ) = each ( $_POST ) ) {
      $paramsReceived .= '[' . $key . ']=[' . $value . '],';
    }
    log_message('debug','Paydollar callback String:'.$paramsReceived);
    echo $paramsReceived;

    //已支付成功
    if(isset($_POST['successcode']) && $successcode == "0" && !empty($ref) && $verifyResult){
      $order_id = trim($ref);
      $order_data=$this->order_mdl->get($order_id);
      if(!empty($order_data) && is_array($order_data) && $order_data['order_status_id']=='0' && floatval($amt)==floatval($order_data['total'])){
        $result=$this->order_mdl->update(array('order_status_id'=>$this->data['paydollar_order_status_id']),$order_id);
        if($result){
          helper_order_send_email($order_data,$order_id);//電郵通知客戶,商戶
        }
      }
    }
  }

}