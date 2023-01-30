<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2023-01-30 11:28:59
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2023-01-30 12:45:44
 * @email             :  info@clickrweb.com
 * @description       :  Mpay澳門錢包 前台控制器 Mpay Controller
 */
class Mpay extends Lang_Controller {
  ## MD5加密的key【需要更新】
  private $_md5_key = "EABHZF90EFODBNLQSOZUOBRS1UEE9C3V"; //A676C46717530A6FBA94F69979F59482E1
  ## 加密方式
  private $_sign_type = 'RSA2';


  public function __construct(){
    parent::__construct();
    $this->data['active']='mpay';
    $this->load->model('order_mdl');
    $this->load->model('payment_mdl');
  }

  public function index(){
    $order_id=$this->session->userdata('order_id');
    if(empty($order_id) || !is_numeric($order_id)){
      $this->session->set_flashdata('error','訂單ID出錯,無法支付！Payment Order ID Error！');
      redirect('order');
    }

    $filter = array(
      'id'              =>intval($order_id),
      'order_status_id' =>0,
    );
    $order_data = $this->order_mdl->get_by($filter);
    if(empty($order_data) || !is_array($order_data) || $order_data['payment_method']!='mpay'){
      $this->session->set_flashdata('error','訂單記錄出錯,無法支付！');
      redirect('order');
    }

    $this->data['payment']=$this->payment_mdl
      ->join_description($this->data['lang_id'],TRUE)
      ->get_by(array('n.payment_key'=>$order_data['payment_method']));

    $this->_before_payment_data($order_data);

    $this->load->view('payment/mpay_list_view',$this->data);
  }

  //準備好支付的數據.
  private function _before_payment_data($order_data = array()){
    if(!empty($order_data) && is_array($order_data)){

      $this->data['order_id']      = $order_data['id'];
      $this->data['donate_name']   = $order_data['donate_firstname'].' '.$order_data['donate_lastname'];
      $this->data['donate_gender'] = $order_data['donate_gender'];

      $this->data['order_data']    = $order_data;

      $order_total   =number_format($order_data['donate_money'],2,'.','');
      $extend_params =trim($this->data['mpay_extend_params']);

      $this->load->library('user_agent');

      //初始化mpay需要的參數.
      $mpay_data=array(
        'service'             => 'mpay.online.trade.precreate',                    //操作類別. //若使用本地收銀台請置換接口為mpay.trade.precreate
        'channel_type'        => '1',                                              //渠道類型(1-系统 2-智能设备 N 1 3-其他)
        'format'              => 'JSON',                                           //數據格式
        'org_id'              => trim($this->data['mpay_orgID']),                  //商戶ID
        'version'             => '1.1.0',                                          //版本號
        'timestamp'           => intval($this->get_microtime()),                   //當前時間戳
        'nonce_str'           => $this->get_nonceStr(),                            //隨機數
        'notify_url'          => site_url('payment/mpay/callback'),                //异步通知回调地址
        'return_url'          => site_url('payment/mpay/front'),                   //商戶同步回调地址
        'currency'            => 'MOP',                                            //交易貨幣
        'extend_params'       => htmlspecialchars_decode($extend_params),          //商家的特定商業信息
        'out_trans_id'        => sprintf("%09d",trim($order_data['id'])),          //商家網站中的唯一訂單ID
        'subject'             => html_entity_decode($this->data['donate_name'].' 在線捐款', ENT_QUOTES, 'UTF-8'),//商品名称/交易牌/订单主题/订单关键词
        'body'                => html_entity_decode($this->data['donate_name'].' 在線捐款', ENT_QUOTES, 'UTF-8'),  //交易的具体描述
        'total_fee'           => $order_total,                                        //此訂單總費用 元做單位
        'it_b_pay'            => '5m',                                                //订单有效时间 支付限時.5分鐘
        'product_code'        => $this->agent->is_mobile()?'MP_WAP_PAY':'MP_WEB_PAY', //支付方式. MP_WEB_PAY:PC;MP_WAP_PAY:H5
        'pay_channel'         => 'mpay',                                              //支付渠道.支持 alipay 支付寶和 mpay-澳門錢包
      );

      //參數排序
      $mpay_data_sort   = $this->parameter_sort($mpay_data,false);
      //獲取預簽名字符串
      $mpay_data_string = $this->parameter_link_string($mpay_data_sort);

      if($this->data['mpay_debug']){
        log_message('error','MPAY :: Sign Before: ' .$mpay_data_string);
        log_message('error','MPAY :: Sign Before json: ' .json_encode($mpay_data_sort,JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE));
      }
      //執行加密
      if(strcasecmp($this->_sign_type,"MD5")==0){ ##MD5加密模式
        $mpay_data['sign'] = $this->md5_sign($mpay_data_string,$this->_md5_key);
      }else if(strcasecmp($this->_sign_type,"RSA2")==0){ ##RSA2簽名模式
        $mpay_data['sign'] = $this->rsa2_sign($mpay_data_string);
      }

      if($this->data['mpay_debug']){
        log_message('error','MPAY :: Sign string: ' .$mpay_data['sign']);
      }

      $mpay_data['sign_type'] = $this->_sign_type;

      if($this->data['mpay_debug']){
        log_message('error','MPAY ::FORM REQUEST: ' .var_export($mpay_data,true));
      }
      
      if (!$this->data['mpay_test']) { //PROD
        $this->data['payment_action'] = 'https://openapi.macaupay.com.mo/scanpay/onlineCreate.do';
      } else {//UAT
        $this->data['payment_action'] = 'https://uatopenapi.macaupay.com.mo/scanpay/onlineCreate.do';
      }

      $this->data['payment_data'] = $mpay_data;
    }
  }

  //return_urlt- 前台明面顯示給客戶看的.
  public function front(){
    $get_data = $this->input->get(NULL,TRUE);
    if(isset($_GET) && !empty($get_data)){
      $out_trans_id   = $this->input->get('out_trans_id',TRUE);
      $trans_status   = $this->input->get('trans_status',TRUE);
      $trans_amount   = $this->input->get('trans_amount',TRUE);
      $order_id       = intval($out_trans_id);
      $verify_result  = FALSE;
      if($this->data['mpay_debug']){
        log_message('error','MPAY :: front GET RESPONSE: ' .var_export($get_data,true));
      }
      $order_data = $this->order_mdl->get($order_id);
      if(!empty($order_data) && is_array($order_data)){
        $order_total=number_format($order_data['donate_money'],2,'.','');
        if(strcmp($trans_status,'SUCCESS')==0 && floatval($order_total)==floatval($trans_amount)){
          $verify_result = TRUE;
          redirect('order/result/'.$order_id);
        }
      }
    }
    $message = $verify_result > 0?lang('operate_success'):'對不起,您的支付操作失敗,請重新支付!(Front MPay Payment Error)';
    $notify  = $verify_result > 0?'success':'error';
    $this->session->set_flashdata($notify,$message);
    redirect('order');
  }

  //Mpay callback 返回驗證處理
  public function callback() {
    if($_SERVER['REQUEST_METHOD'] === 'POST' && false !== strpos($_SERVER["CONTENT_TYPE"],'application/json')){

      $content   = file_get_contents('php://input');
      $content   = trim($content);
      $post_data = (array)json_decode($content,true);

      if($this->data['mpay_debug']){
        log_message('error','MPAY :: callback RESPONSE: ' .var_export($post_data,true));
      }

      if(empty($post_data) || !is_array($post_data)){
        return false;
      }

      $sign      = trim($post_data['sign']);
      $sign_type = trim($post_data["sign_type"]);
      unset($post_data['sign']);
      unset($post_data['sign_type']);

      $verifySignResult = FALSE;//驗簽結果
      //參數排序
      $mpay_data_sort   = $this->parameter_sort($post_data,false);
      //獲取預簽名字符串
      $mpay_data_string = $this->parameter_link_string($mpay_data_sort);

      if($this->data['mpay_debug']){
        log_message('error','MPAY :: callback verify string: ' .$mpay_data_string);
        log_message('error','MPAY :: callback sort json: ' .json_encode($mpay_data_sort,JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE));
      }


      if(strcasecmp($sign_type,"MD5")==0){
        $verifySignResult = $this->md5_verify($mpay_data_string,$this->_md5_key,$sign);
      }else if(strcasecmp($this->_sign_type, "RSA2") == 0){ ##RSA2簽名模式
        $verifySignResult = $this->rsa2_verify($mpay_data_string,$sign);
      }

      if($this->data['mpay_debug']){
        log_message('error','MPAY :: verify sign result: '.($verifySignResult?'true':'false'));
      }

      if($verifySignResult){
        $order_id     = intval($post_data['out_trans_id']);  //訂單編號
        $trans_amount = floatval($post_data['trans_amount']);//訂單總金額
        $trans_status = $post_data['trans_status'];          //交易狀態.UNKNOW 未知或處理中 SUCCESS 成功 FAILED 失败 CLOSED 交易关闭

        $order_data = $this->order_mdl->get($order_id);
        if(empty($order_data) || !is_array($order_data)){
          log_message('error','MPAY :: callback order_data is empty !');
          return false;
        }
        $order_total = $order_data['donate_money'];
        $order_total = number_format($order_total,2,'.','');
        if(strcmp($trans_status,'SUCCESS')==0 && floatval($order_total)==floatval($trans_amount)){
          // $defalut_order_status = $this->data['defalut_order_status'];
          if($order_data['order_status_id']==0){
            $update_data=array(
              'number'          =>$post_data['out_trans_id'],
              'order_status_id' =>$this->data['mpay_order_status_id'],
            );
            $result=$this->order_mdl->update($update_data,$order_id);
            if($result){
              helper_order_send_email($order_data,$order_id);//電郵通知客戶,商戶
            }
          }
          header('HTTP/1.1 200 OK');
          header('Server: Apache/2');
          header('Content-Type: text/html; charset=utf-8');
          header('Content-Length:'.strlen('SUCCESS'));
          die('SUCCESS');//通知mpay主機 停止callback報文
        }
      }else{
        log_message('error','MPAY :: callback verify Sign Error!');
      }
    }
  }

  //訂單查詢
  public function query($order_id=''){
    $order_id = $this->security->xss_clean($order_id);//過濾xss攻擊
    $order_id = !empty($order_id) && is_numeric($order_id)?$order_id:$this->input->get('order_id',TRUE);
    if(!empty($order_id) && is_numeric($order_id)){
      $verify_result  = FALSE;
      $order_data = $this->order_mdl->get($order_id);
      if(!empty($order_data) && is_array($order_data)){

        $order_total=number_format($order_data['donate_money'],2,'.','');

        if (!$this->data['mpay_test']) { //PROD
          $query_url = 'https://openapi.macaupay.com.mo/masl/umpg/gateway';
        } else {//UAT
          $query_url = 'https://uatopenapi.macaupay.com.mo/masl/umpg/gateway';
        }

        $query_data=array(
          'service'      => 'mpay.trade.query',                               //操作類別. 查詢接口名
          'channel_type' => '1',                                              //渠道類型(1-系统 2-智能设备 N 1 3-其他)
          'format'       => 'JSON',                                           //數據格式
          'org_id'       => trim($this->data['mpay_orgID']),                  //商戶ID
          'version'      => '1.1.0',                                          //版本號
          'timestamp'    => intval($this->get_microtime()),                   //當前時間戳
          'nonce_str'    => $this->get_nonceStr(),                            //隨機字符
          'out_trans_id' => sprintf("%09d",$order_id),                        //訂單號
        );

        //參數排序
        $query_data_sort   = $this->parameter_sort($query_data,false);
        //獲取預簽名字符串
        $query_data_string = $this->parameter_link_string($query_data_sort);

        if($this->data['mpay_debug']){
          log_message('error','MPAY :: query data string Before: ' .$query_data_string);
          log_message('error','MPAY :: query data sort json: ' .json_encode($query_data_sort,JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE));
        }

        //執行加密
        if(strcasecmp($this->_sign_type,"MD5")==0){ ##MD5加密模式
          $query_data['sign'] = $this->md5_sign($query_data_string,$this->_md5_key);
        }else if(strcasecmp($this->_sign_type,"RSA2")==0){ ##RSA2簽名模式
          $query_data['sign'] = $this->rsa2_sign($query_data_string);
        }

        if($this->data['mpay_debug']){
          log_message('error','MPAY :: Query Sign string: ' .$query_data['sign']);
        }

        $query_data['sign_type'] = $this->_sign_type;

        //提交到mpay執行查詢
        $query_result=$this->post_curl($query_url,$query_data);

        if($this->data['mpay_debug']){
          log_message('error','MPAY :: Query result string: ' .$query_result);
        }

        $verify_result_array=$this->_verify_query_result($query_result);

        if(!empty($verify_result_array) && is_array($verify_result_array) && isset($verify_result_array['data'])){
          $verify_response=$verify_result_array['data'];
          if(isset($verify_response['out_trans_id']) && isset($verify_response['trans_amount']) && isset($verify_response['trans_status']) && isset($verify_response['result_code'])){
            $out_trans_id = intval($verify_response['out_trans_id']);  //訂單編號
            $trans_amount = floatval($verify_response['trans_amount']);//訂單總金額
            $trans_status = $verify_response['trans_status'];          //交易狀態.UNKNOW 未知或處理中 SUCCESS 成功 FAILED 失败 CLOSED 交易关闭
            $result_code  = $verify_response['result_code'];
            if($out_trans_id==$order_id && strcasecmp($trans_status,"SUCCESS")==0 && strcasecmp($result_code,"0000")==0){
              // $defalut_order_status = $this->data['defalut_order_status'];
              if($order_data['order_status_id']==0 && floatval($trans_amount)==floatval($order_total)){
                $update_data=array(
                  'number'          =>$verify_response['out_trans_id'],
                  'order_status_id' =>$this->data['mpay_order_status_id'],
                );
                $result=$this->order_mdl->update($update_data,$order_id);
                if($result){
                  helper_order_send_email($order_data,$order_id);//電郵通知客戶,商戶
                }
                $verify_result  = TRUE;
              }
              redirect('order/result/'.$order_id);//跳轉支付成功!
            }
          }else{
            log_message('error','MPAY :: Mpay query result verify ERROR: ' .var_export($verify_response,true));
          }
        }
        $message = $verify_result > 0?lang('operate_success'):'對不起,您的支付操作失敗,請重新支付!(Front MPay Payment Error)';
        $notify  = $verify_result > 0?'success':'error';
        $this->session->set_flashdata($notify,$message);
        redirect('order');
      }
    }
    redirect('page_404'); //跳轉參數錯誤!
  }

  //查詢結果mpay反饋進行驗簽
  private function _verify_query_result($query_result){
    if(!empty($query_result)){
      $query_result_array=json_decode($query_result,TRUE);
      if(!empty($query_result_array) && is_array($query_result_array) && !empty($query_result_array['sign'])){

        if($this->data['mpay_debug']){
          log_message('error','MPAY :: query result array: ' .var_export($query_result_array,true));
        }

        $sign      = $query_result_array["sign"];
        $sign_type = $query_result_array["sign_type"];
        unset($query_result_array["sign"]);
        unset($query_result_array["sign_type"]);

        //參數排序
        $query_data_sort   = $this->parameter_sort($query_result_array,false);
        //獲取預簽名字符串
        $query_data_string = $this->parameter_link_string($query_data_sort);

        if($this->data['mpay_debug']){
          log_message('error','MPAY :: query result string Before: ' .$query_data_string);
          log_message('error','MPAY :: query result sort json: ' .json_encode($query_data_sort,JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE));
        }

        //執行驗密
        $verify_signature = FALSE;
        if(strcasecmp($sign_type,"MD5")==0){ ##MD5驗簽模式
          $verify_signature = $this->md5_verify($query_data_string,$this->_md5_key,$sign);
        }else if(strcasecmp($sign_type,"RSA2")==0){ ##RSA2驗簽模式
          $verify_signature = $this->rsa2_verify($query_data_string,$sign);
        }

        if($this->data['mpay_debug']){
          log_message('error','MPAY :: query result verify_signature: '.($verify_signature?'true':'false'));
        }

        if($verify_signature){
          $query_result_array["sign"]      =$sign;
          $query_result_array["sign_type"] =$sign_type;
          return $query_result_array;
        }
      }
    }
  }

  /**
   * 定時輪詢 MPay支付訂單,防止出現callback異常導致訂單丟失.
   * @Author   Clickr Abin
   * @DateTime 2022-09-02T10:12:45+0800
   * @return   [type] [description]
   */
  public function polling(){
    //查詢出還沒有得到狀態的mpay支付訂單
    $filter = array(
      'order_status_id' =>0,
      'payment_key'     =>'mpay',
      "(created_at >= '".date('Y-m-d 00:00:00',strtotime('-3 day'))."' AND created_at <= '".date('Y-m-d H:i:s',strtotime('-5 minute'))."')",
      "transfer_receipt IS NULL",
    );
    $missingOrders = $this->order_mdl->get_many_by($filter);
    if(!empty($missingOrders) && is_array($missingOrders)){
      foreach($missingOrders as $item){
        $order_id    = intval($item['id']);
        $order_total = $item['donate_money'];
        $query_data=array(
          'service'      => 'mpay.trade.query',                               //操作類別. 查詢接口名
          'channel_type' => '1',                                              //渠道類型(1-系统 2-智能设备 N 1 3-其他)
          'format'       => 'JSON',                                           //數據格式
          'org_id'       => trim($this->data['mpay_orgID']),                  //商戶ID
          'version'      => '1.1.0',                                          //版本號
          'timestamp'    => intval($this->get_microtime()),                   //當前時間戳
          'nonce_str'    => $this->get_nonceStr(),                            //隨機字符
          'out_trans_id' => sprintf("%09d",$order_id),                        //訂單號
        );
        //參數排序
        $query_data_sort   = $this->parameter_sort($query_data,false);
        //獲取預簽名字符串
        $query_data_string = $this->parameter_link_string($query_data_sort);

        if($this->data['mpay_debug']){
          log_message('error','MPAY :: Polling data string Before: ' .$query_data_string);
          log_message('error','MPAY :: Polling data sort json: ' .json_encode($query_data_sort,JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE));
        }
        //執行加密
        if(strcasecmp($this->_sign_type,"MD5")==0){ ##MD5加密模式
          $query_data['sign'] = $this->md5_sign($query_data_string,$this->_md5_key);
        }else if(strcasecmp($this->_sign_type,"RSA2")==0){ ##RSA2簽名模式
          $query_data['sign'] = $this->rsa2_sign($query_data_string);
        }

        if($this->data['mpay_debug']){
          log_message('error','MPAY :: Polling Sign string: ' .$query_data['sign']);
        }

        $query_data['sign_type'] = $this->_sign_type;

        if(!$this->data['mpay_test']){ //PROD
          $query_url = 'https://openapi.macaupay.com.mo/masl/umpg/gateway';
        } else {//UAT
          $query_url = 'https://uatopenapi.macaupay.com.mo/masl/umpg/gateway';
        }
        //提交到mpay執行查詢
        $query_result=$this->post_curl($query_url,$query_data);

        if($this->data['mpay_debug']){
          log_message('error','MPAY :: Polling result string: ' .$query_result);
        }

        $verify_result_array=$this->_verify_query_result($query_result);

        if(!empty($verify_result_array) && is_array($verify_result_array) && isset($verify_result_array['data'])){
          $verify_response=$verify_result_array['data'];
          if(isset($verify_response['out_trans_id']) && isset($verify_response['trans_amount']) && isset($verify_response['trans_status']) && isset($verify_response['result_code'])){
            $out_trans_id = intval($verify_response['out_trans_id']);  //訂單編號
            $trans_amount = floatval($verify_response['trans_amount']);//訂單總金額
            $trans_status = $verify_response['trans_status'];          //交易狀態.UNKNOW 未知或處理中 SUCCESS 成功 FAILED 失败 CLOSED 交易关闭
            $result_code  = $verify_response['result_code'];
            if($out_trans_id==$order_id && strcasecmp($trans_status,"SUCCESS")==0 && strcasecmp($result_code,"0000")==0){

              if($item['order_status_id']==0 && floatval($trans_amount)==floatval($order_total)){
                $update_data=array(
                  'number'          =>$verify_response['out_trans_id'],
                  'order_status_id' =>$this->data['mpay_order_status_id'],
                );
                $result=$this->order_mdl->update($update_data,$order_id);
                if($result){
                  helper_order_send_email($item,$order_id);//電郵通知客戶,商戶
                }
              }

            //處理訂單已關閉, 則輪詢不再查詢該訂單
            }else if($out_trans_id==$order_id && strcasecmp($trans_status,"CLOSED")==0 && strcasecmp($result_code,"0000")==0 && strcasecmp($verify_response['result_msg'],"訂單已關閉")==0){ 
              $transfer_receipt = $trans_status.'=>'.$verify_response['result_msg'];
              $this->order_mdl->update(['transfer_receipt'=>$transfer_receipt],$order_id);
            }
          }else{
            //處理交易不存在, 則輪詢不再查詢該訂單
            if(isset($verify_response['result_code']) && strcasecmp($verify_response['result_code'],"0011")==0 && isset($verify_response['result_msg']) && strcasecmp($verify_response['result_msg'],"交易不存在")==0){
              $transfer_receipt = $verify_response['result_code'].'=>'.$verify_response['result_msg'];
              $this->order_mdl->update(['transfer_receipt'=>$transfer_receipt],$order_id);
            }
            log_message('error','MPAY :: Polling result verify ERROR: ' .var_export($verify_response,true));
          }
        }

      }
    }else{
      log_message('error','MPAY :: Polling missingOrders: ' .var_export($missingOrders,true));
    }
    die('Polling Finish');
  }


  /**
   * [post_curl 提交到mpay主機進行證書文件簽名或是執行驗簽]
   * JSON_UNESCAPED_UNICODE 不對中文進行編譯.
   * @Author   Clickr Abin
   * @DateTime 2019-07-10T17:24:26+0800
   * @param    string $post_url [java主機.]
   * @return   [type] [提交參數]
   */
  public function post_curl($post_url,$post_data){
    $post_data=json_encode($post_data, JSON_UNESCAPED_UNICODE);
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
    curl_setopt($ch,CURLOPT_TIMEOUT,60);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
    curl_setopt($ch,CURLOPT_URL,$post_url);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data); // http_build_query($post_data)
    curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/json; charset=utf-8','Content-Length:'.strlen($post_data)));
    $result=curl_exec($ch);
    if($result === false){
      log_message('error','Mpay Pay post_curl Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
  }


  /**
   * md5簽名 工具類
   */
  private function md5_sign($text,$key){
    if(!empty($text) && !empty($key)){
      $content = $text . $key;
      return md5($content);
    }
  }

  /**
   * MD5驗簽 工具類
   */
  private function md5_verify($text, $key, $sign){
    $content = $text . $key;
    $mySign  = md5($content);
    if(strcasecmp($mySign,$sign) == 0){
      return true;
    } else {
      return false;
    }
  }

  /**
   * rsa2簽名 工具類
   */
  private function rsa2_sign($data){
    $private_key = trim($this->data['mpay_merCert']); //"商戶私鑰private.pem文件內容."
    if(!empty($data) && !empty($private_key)){
      $signature = "";
      openssl_sign($data, $signature, $private_key,"sha256WithRSAEncryption");
      return base64_encode($signature);
    }
  }

  /**
   * rsa2驗簽 工具類
   */
  private function rsa2_verify($data,$sign){
    $public_key = trim($this->data['mpay_bankCert']); //"銀行提供公鑰public.pem文件內容."
    if(!empty($data) && !empty($public_key) && !empty($sign)){
      $sign = base64_decode($sign);
      return openssl_verify($data, $sign, $public_key, "sha256WithRSAEncryption");
    }
  }

  //工具類
  //參數重新排序處理 按字母排序
  private function parameter_sort($obj,$deep){
    if (is_object($obj) && json_encode($obj) != "{}") {
      $arr = (array)$obj;
      ksort($arr);
      $obj = $arr;
    } elseif (is_array($obj)) {
      ksort($obj);
    }
    if ($deep) {
      foreach ($obj as $key => $value) {
        if (is_object($value) || is_array($value)) {
          $obj[$key] = $this->parameter_sort($obj[$key], true);
        }
      }
    }
    return $obj;
  }

  // 工具類
  // 數組轉化成URL參數【加簽時使用】【驗簽時使用】
  private function parameter_link_string($data){
    $returnvalue = "";
    foreach ($data as $key => $value) {
      if (empty($value) && !is_numeric($value)) {
        continue;
      }
      $val = $value;
      if (is_array($value)) {
        $val = json_encode($value, JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE);
      }
      $returnvalue .= "{$key}={$val}&";
    }
    $returnvalue = substr($returnvalue, 0, -1);
    return $returnvalue;
  }

  /**
   * 獲取毫秒級的時間戳
   * 工具類
   */
  private function get_microtime(){
    list($microsecond,$time) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($microsecond)+floatval($time))*1000);
  }

  /**
   * 獲取隨機字符串
   * 工具類
   */
  private function get_nonceStr(){
    $str = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
    str_shuffle($str);
    $nonceStr = substr(str_shuffle($str), 26, 32);
    return $nonceStr;
  }

}

/* End of file Mpay.php */
/* Location: ./application/controllers/payment/Mpay.php */
