<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-20 12:31:18
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-05-07 15:38:30
 * @email             :  info@clickrweb.com
 * @description       :  前台首頁控制器,Home Controller
 */
class Home extends Lang_Controller {

  public function __construct(){
    parent::__construct();
    $this->data['active']='home';
    $this->_initialize_data();
  }

  public function index(){
    $this->load->view('home_list_view',$this->data);
  }

  //頁面資料.
  private function _get_page_content($unique_url='terms-of-use'){
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
    return $query['content'];
  }

  //提交捐款資料
  public function modify(){
    $donate_item_array=$this->input->post('donate_item',TRUE);
    $this->_load_validation_rules();
    if($this->form_validation->run() == FALSE){
      $this->data['error']=true;
      $this->data['donate_item_array']=$donate_item_array;
      $this->load->view('home_list_view',$this->data);
    }else{

      $session_array=array(
        'order_session'=>array(
          'donate_money'         =>$this->input->post('donate_money',TRUE),
          'donate_money_other'   =>$this->input->post('donate_money_other',TRUE),
          'donate_item'          =>!empty($donate_item_array)?json_encode($donate_item_array):'',
          'donate_item_other'    =>$this->input->post('donate_item_other',TRUE),

          'donate_gender'        =>$this->input->post('donate_gender',TRUE),
          'donate_firstname'     =>$this->input->post('donate_firstname',TRUE),
          'donate_lastname'      =>$this->input->post('donate_lastname',TRUE),
          'donate_email'         =>$this->input->post('donate_email',TRUE),
          'donate_country'       =>$this->input->post('donate_country',TRUE),
          'donate_phone'         =>$this->input->post('donate_phone',TRUE),
          'donate_address'       =>$this->input->post('donate_address',TRUE),
          'payment_method'       =>$this->input->post('payment_method',TRUE),
          
          'need_receipt'         =>$this->input->post('need_receipt',TRUE),
          'payment_receipt_type' =>$this->input->post('payment_receipt_type',TRUE),
          'payment_receipt_note' =>$this->input->post('payment_receipt_note',TRUE),

          'need_subscribe'       =>$this->input->post('need_subscribe',TRUE),
          'subscribe_type'       =>$this->input->post('subscribe_type',TRUE),
          'subscribe_note'       =>$this->input->post('subscribe_note',TRUE),
          'agree'                =>$this->input->post('agree',TRUE),
        )
      );
      $this->session->set_userdata($session_array);
      redirect('order');
    }
  }

  //驗證數據格式
  private function _load_validation_rules(){

    $this->form_validation->set_rules('donate_money',lang('donate_block_money'),'trim|required|max_length[20]');
    $donate_money = $this->input->post('donate_money',TRUE);
    $donate_money_required = $donate_money=='other'?'|required':'';
    $this->form_validation->set_rules('donate_money_other',lang('donate_block_money_other'),'trim|numeric'.$donate_money_required.'|max_length[10]');

    $this->form_validation->set_rules('donate_item[]',lang('donate_block_item'),'trim|required');
    $this->form_validation->set_rules('donate_item_other',lang('donate_block_item_other'),'trim|max_length[100]');
    

    $this->form_validation->set_rules('donate_gender',lang('donate_block_gender'),'trim|required|max_length[50]');
    $this->form_validation->set_rules('donate_firstname',lang('donate_block_lname'),'trim|required|max_length[50]');
    $this->form_validation->set_rules('donate_lastname',lang('donate_block_fname'),'trim|required|max_length[50]');
    $this->form_validation->set_rules('donate_email',lang('donate_block_email'),'trim|required|valid_email|max_length[100]');
    $this->form_validation->set_rules('donate_country',lang('donate_block_country'),'trim|required|max_length[100]');
    $this->form_validation->set_rules('donate_phone',lang('donate_block_tel'),'trim|required|max_length[100]');
    $this->form_validation->set_rules('donate_address',lang('donate_block_address'),'trim|max_length[300]');
    $this->form_validation->set_rules('payment_method',lang('donate_block_title03'),'trim|required|max_length[100]');

    $this->form_validation->set_rules('need_receipt',lang('donate_block_receipt'),'trim|exact_length[1]');
    $need_receipt = $this->input->post('need_receipt',TRUE);
    $need_receipt_required = $need_receipt=='1'?'|required':'';
    $this->form_validation->set_rules('payment_receipt_type',lang('donate_block_receipt_type'),'trim|numeric'.$need_receipt_required.'|exact_length[1]');
    $this->form_validation->set_rules('payment_receipt_note',lang('donate_block_receipt_content'),'trim'.$need_receipt_required.'|max_length[300]');

    $this->form_validation->set_rules('need_subscribe',lang('donate_block_subscribe03_title'),'trim|exact_length[1]');
    $need_subscribe = $this->input->post('need_subscribe',TRUE);
    $need_subscribe_required = $need_subscribe=='1'?'|required':'';
    $this->form_validation->set_rules('subscribe_type',lang('donate_block_subscribe03_type'),'trim|numeric'.$need_subscribe_required.'|exact_length[1]');
    $this->form_validation->set_rules('subscribe_note',lang('donate_block_subscribe03_content'),'trim'.$need_subscribe_required.'|max_length[300]');

   
    $this->form_validation->set_rules('agree',lang('donate_block_agree02'),'trim|required|max_length[50]',array('required'=>lang('donate_block_agree02_long')));

    $this->form_validation->set_rules('authcode',lang('form_code'),'trim|required|exact_length[4]|callback__verify_captcha');
  }

  //驗證碼驗證是否正確.
  public function _verify_captcha($captcha=''){
    $authcode=$this->session->userdata('authcode');
    if(strtoupper($captcha) != $authcode){
      $this->form_validation->set_message('_verify_captcha',lang('code_error'));
      return FALSE;
    }
    return TRUE;
  }


  //初始化獲取資料
  private function _initialize_data(){
    //頁面內容
    $this->data['terms_user_content']=$this->_get_page_content('terms-of-use');
    $this->data['privacy_policy_content']=$this->_get_page_content('privacy-policy');

    //支付列表
    $this->_get_payment();

    //讀取國家/地區json
    $country_region_option = array();
    $countryRegionPath     = FCPATH.'themes/front/js/country-region'.$this->data['lang_id'].'.json';
    if(file_exists($countryRegionPath) && is_readable($countryRegionPath)){
      $fileResource = fopen($countryRegionPath,"r");
      while(!feof($fileResource)){
        array_push($country_region_option,trim(fgets($fileResource)));
      }
      fclose($fileResource);
      $country_region_option = array_filter($country_region_option);
    }
    $this->data['country_region_option']=$country_region_option;

    //捐款項目
    $default_donate_item=array(
      '國際救災扶危',
      '難民救助',
      '内地慈善工作',
      '葡語係國家及地區支援',
      '開展本地慈善項目',
      '開設本地安老院舍',
      '其他',
    );
    $donate_item_string = $this->data['donate_item'.$this->data['lang_id']];
    $this->data['donate_item_option'] = !empty($donate_item_string)?explode(',',$donate_item_string):$default_donate_item;

    $order_session=$this->session->userdata('order_session');
    if(!empty($order_session) && is_array($order_session)){
      foreach ($order_session as $key => $value) {
        $this->data[$key]=$value;
        if($key=='donate_item' && !empty($value)){
          $this->data['donate_item_array']=json_decode($value,TRUE);
        }
      }
    }

  }

  //獲取支付列表
  private function _get_payment(){
    $this->load->model('payment_mdl');
    $this->data['payment_option']=$this->payment_mdl
      ->order_by('n.sort_order','ASC')
      ->join_description($this->data['lang_id'])
      ->get_many_by(array('n.status'=>1));
  }

  //加載驗證碼
  public function captcha(){
    require APPPATH.'third_party/Captcha.php';
    $Captcha = new ValidateCode(100,36);    //实例化一个对象
    $Captcha->doimg();
    $this->session->set_userdata('authcode',$Captcha->getCode());//验证码保存到SESSION中
  }

}