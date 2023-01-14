<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2020-04-10 15:21:39
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-08-31 13:04:54
 * @email             :  info@clickrweb.com
 * @description       :  中國銀行 銀聯支付控制器 Unionpay Controller
 */

//引入中銀銀聯支付 SDK
require_once(APPPATH.'third_party/BocUnionpay/acp_service.php');

class Unionpay extends Lang_Controller {

  public function __construct(){
    parent::__construct();
    $this->data['active']='unionpay';
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
    if(empty($order_data) || !is_array($order_data) || $order_data['payment_method']!='unionpay'){
      $this->session->set_flashdata('error','Order data Error,訂單信息獲取出錯！');
      redirect('order');
    }

    $this->data['payment']=$this->payment_mdl
      ->join_description($this->data['lang_id'])
      ->get_by(array('payment_key'=>$order_data['payment_method']));

    $this->_before_payment_data($order_data);

    $this->load->view('payment/unionpay_list_view',$this->data);
  }

  //準備好支付的數據.
  private function _before_payment_data($order_data = array()){
    if(!empty($order_data) && is_array($order_data)){
      $this->data['order_id']      = $order_data['id'];
      $this->data['donate_name']   = $order_data['donate_firstname'].' '.$order_data['donate_lastname'];
      $this->data['donate_gender'] = $order_data['donate_gender'];

      $BocUnionpay_lang = array(
        'zh-cn'   =>'zh_CN',
        'zh-tw'   =>'zh_TW',
        'english' =>'en_US',
      );

      //中國銀行 銀聯 unionpay支付
      $BocUnionpay_data=array(
        'version'      => '5.0.0',      //版本号
        'encoding'     => 'UTF-8',      //编码方式
        'txnType'      => '01',         //交易类型
        'txnSubType'   => '01',         //交易子类
        'bizType'      => '000201',     //业务类型
        'signMethod'   => '01',         //签名方法
        'channelType'  => '07',         //渠道类型，07-PC，08-手机
        'accessType'   => '0',          //接入类型
        'currencyCode' => '446',        //貨幣代碼 446 澳門幣MOP 156//RMB, 344//HKD,840//USD

        'merId'        => $this->data['unionpay_merchant_id'],        //商戶MID
        'frontUrl'     => site_url('payment/unionpay/front'),         //中銀 付款后 跳轉界面.
        'backUrl'      => site_url('payment/unionpay/callback'),      //中銀 付款后 銀聯系統調用 回調函數
        'orderId'      => date('YmdHis').'Ord'.$order_data['id'],     //中銀 訂單號
        'txnTime'      => date('YmdHis'),                             //訂單提交 時間
        'txnAmt'       => floatval($order_data['donate_money'])*100,  //訂單金額 单位分.
        // 'payTimeout'   => date('YmdHis',strtotime('+5 second')),      //ANO_ESH_PER_003 測試用例使用
        // 'reserved'     => '{pageLanguage=zh_TW}',                     //ANO_ESH_PER_008 測試用例使用
      );

      //執行SDK簽名
      AcpService::sign($BocUnionpay_data);

      log_message('debug','BocUnionpay Request Array:'.var_export($BocUnionpay_data,TRUE));
      $this->data['payment_data']   = $BocUnionpay_data;
      $this->data['payment_action'] = SDK_FRONT_TRANS_URL;
    }
  }


  //支付后直接跳轉函數
  public function front(){
    $signature    = $this->input->post('signature');
    $order_string = $this->input->post('orderId');
    $response     = $this->input->post();
    log_message('debug','BocUnionpay front Array:'.var_export($response,TRUE));
    $result       = FALSE;
    if(!empty($signature) && !empty($order_string)){
      $respCode=$this->input->post('respCode'); //判断respCode=00或A6即可认为交易成功
      $result=AcpService::validate($response);
      if($result && ($respCode=='A6' || $respCode=='00')){
        redirect('order/result/'.$result);
      }
    }
    $msg = $result > 0?lang('operate_success'):'對不起,您的支付操作失敗,請重新支付!(Front UnionPay Payment error)';
    $notify = $result > 0?'success':'error';
    $this->session->set_flashdata($notify, $msg);
    redirect('order');
  }

  //支付后銀行回調函數
  public function callback(){
    $signature    = $this->input->post('signature');
    $order_string = $this->input->post('orderId');
    log_message('debug','BocUnionpay callback Array:'.var_export($_REQUEST,TRUE));
    if(!empty($signature) && !empty($order_string) && strstr($order_string,'Ord')){
      $order_string_array = explode('Ord',$order_string);
      $order_id           = end($order_string_array);
      $txnAmt             = $this->input->post('txnAmt');
      $order_data         = $this->order_mdl->get($order_id);
      if(empty($order_data) || !is_array($order_data))
        return FALSE;

      $order_total = $order_data['donate_money']*100;//轉成分

      if($txnAmt==$order_total && $order_data['order_status_id']==0){
        $response     = $this->input->post();
        $respCode     = $this->input->post('respCode'); //判断respCode=00或A6即可认为交易成功
        $valid_result = AcpService::validate($response);
        if($valid_result && ($respCode=='A6' || $respCode=='00')){
          $update_data=array(
            'number' =>$order_string,
            'order_status_id'=>$this->data['unionpay_order_status_id'],
          );
          $update_result=$this->order_mdl->update($update_data,$order_id);
          if($update_result){
            helper_order_send_email($order_data,$order_id);//電郵通知客戶,商戶
          }
        }
      }
    }
  }


  //交易訂單查詢
  //測試用例需要使用
  public function query($txnTime,$orderId){
    if(!empty($txnTime) && !empty($orderId) && is_numeric($orderId)){
      $param = array(
        'version'     => '5.0.0',  //版本号
        'encoding'    => 'UTF-8',  //编码方式
        'signMethod'  => '01',     //签名方法
        'txnType'     => '00',     //交易类型
        'txnSubType'  => '00',     //交易子类
        'bizType'     => '000000', //业务类型
        'accessType'  => '0',      //接入类型
        // 'channelType' => '07',     //渠道类型
        'orderId'     => $txnTime.'Ord'.$orderId,                   //请修改被查询的交易的订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数
        'merId'       => $this->data['unionpay_merchant_id'],       //商戶MID
        'txnTime'     => $txnTime,                                  //请修改被查询的交易的订单发送时间，格式为YYYYMMDDhhmmss
      );

      AcpService::sign($param); //签名

      $result = AcpService::post($param,SDK_SINGLE_QUERY_URL);
      log_message('debug','BocUnionpay query Array:'.var_export($result,TRUE));
      if(empty($result) || !is_array($result)){//没收到200应答的情况
        die('對不起,銀聯訂單查詢無反饋結果.(Sorry, unionpay query order error)');
      }

      //執行驗簽名
      if(!AcpService::validate($result)){
        die('對不起,銀聯訂單查詢結果驗簽失敗,請檢查參數.(Sorry, the UnionPay order query result verification failed, please check the parameters)');
      }
      var_dump(var_export($result,TRUE));
      echo "应答报文验签成功<br>\n";
      if ($result["respCode"] == "00"){
        if ($result["origRespCode"] == "00"){ //交易成功

          $order_data = $this->order_mdl->get($orderId);
          if(empty($order_data) || !is_array($order_data))
            return FALSE;
          if(!empty($order_data) && $order_data['order_status_id']==0){
            $update_data=array(
              'number' =>$result['orderId'],
              'order_status_id'=>$this->data['unionpay_order_status_id'],
            );
            $update_result=$this->order_mdl->update($update_data,$orderId);
            if($update_result){
              helper_order_send_email($order_data,$orderId);//電郵通知客戶,商戶
            }
          }

          echo "交易成功。<br>\n";
        } else if ($result["origRespCode"] == "03"
            || $result["origRespCode"] == "04"
            || $result["origRespCode"] == "05"){ //后续需发起交易状态查询交易确定交易状态
          echo "交易处理中，请稍後查询。<br>\n";
        } else { //其他应答码做以失败处理
          echo "交易失败：" . $result["origRespMsg"] . "。<br>\n";
        }
      } else if ($result["respCode"] == "03"
          || $result["respCode"] == "04"
          || $result["respCode"] == "05" ){ //后续需发起交易状态查询交易确定交易状态
        echo "处理超时，请稍微查询。<br>\n";
      } else { //其他应答码做以失败处理
        echo "失败：" . $result["respMsg"] . "。<br>\n";
      }
    }else{
      show_error(lang('parameter_error'));
      exit;
    }
  }  

}