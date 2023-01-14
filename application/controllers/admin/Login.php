<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-16 11:27:52
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-09-30 16:55:29
 * @email             :  info@clickrweb.com
 * @description       :  後台登入控制器 admin login
 */
class Login extends CI_Controller{

  private $data=array();

  public $referrer;

  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='login';
    $this->load->model('user_mdl');
    $this->_check_referrer();
  }

  //登入界面
  public function index(){
    $this->load->view('admin/login_form_view',$this->data);
  }

  //獲取登入后跳轉目標網址
  private function _check_referrer(){
    $ref = $this->input->get('ref', TRUE);
    $url_suffix=$this->config->item('url_suffix');
    $this->referrer = (!empty($ref)) ? str_replace($url_suffix,'',$ref) : '/admin/home';
  }

  //登出操作.
  public function login_out(){
    $nick_name=$this->session->userdata('nick_name');
    $this->operator_log('管理員登出->Name:'.$nick_name,'登出',1);
    $array_items = array(
      'user_id'=>'',
      'nick_name'=>'',
      'login_name'=>'',
      'group_id'=>''
    );
    $this->session->unset_userdata($array_items);
    delete_cookie('adminLogin_remember');
    redirect('admin/login');
  }

  //登陸操作
  public function modify(){
    $this->_load_validation_rules();
    $error_login=$this->session->userdata('error_login');
    $error_login==3?$this->form_validation->set_rules('authcode','驗證碼','trim|required|exact_length[4]|callback__verify_captcha'):NULL;
    if ($this->form_validation->run() == FALSE){
      $this->data['error']=true;
      $error_login_num=$error_login<3?($error_login+1):3;
      $this->session->set_userdata('error_login',$error_login_num);
      $this->load->view('admin/login_form_view',$this->data);
    }else{
      $this->_modify_login($error_login);
    }
  }

  //處理登入.
  private function _modify_login($error_login){
    $user_pwd=$this->input->post('user_pwd');
    $query=$this->user_mdl->get_by(array(
      'login_name'=>$this->input->post('user_name'),
      'login_pwd'=>md5(sha1($user_pwd)),
      'status'=>1
    ));
    if(!empty($query) && is_array($query)){
      $token_data=array(
        'last_ip'=>$this->input->ip_address(),
        'token'=>sha1(time().rand())
      );
      $this->user_mdl->update($token_data,$query['id']);
      $data=array(
        'user_id'=>$query['id'],
        'group_id'=>$query['group_id'],
        'user_token'=>$token_data['token'],
        'login_name'=>$query['login_name'],
        'nick_name'=>!empty($query['nickname'])?$query['nickname']:$query['login_name']
      );
      $this->session->set_userdata($data);
      $this->session->unset_userdata('error_login');
      $this->_is_remember($data);//下次自動登入 保留7天
      redirect($this->referrer);
    }else{
      $this->data['custom_error']='用戶名或者密碼錯誤,或帳戶被禁用!';
      $error_login_num=$error_login<3?($error_login+1):3;
      $this->session->set_userdata('error_login',$error_login_num);
      $this->load->view('admin/login_form_view',$this->data);
    }
  }

  //驗證數據
  private function _load_validation_rules(){
    $this->form_validation->set_rules('user_name','用戶名','trim|required|htmlspecialchars|max_length[32]');
    $this->form_validation->set_rules('user_pwd','密 碼','trim|required|max_length[50]');
  }

  //驗證碼驗證是否正確.
  public function _verify_captcha($captcha=''){
    $authcode=$this->session->userdata('authcode');
    if(strtoupper($captcha) != $authcode){
      $this->form_validation->set_message('_verify_captcha','對不起,驗證碼輸入錯誤!');
      return FALSE;
    }
    return TRUE;
  }

  //加載驗證碼
  public function load_auth(){
    require APPPATH.'third_party/Captcha.php';
    $Captcha = new ValidateCode(100,36);    //实例化一个对象
    $Captcha->doimg();
    $this->session->set_userdata('authcode',$Captcha->getCode());//验证码保存到SESSION中
  }

  //操作日誌
  public function operator_log($title='',$action='',$result=1){
    $this->load->model('operator_mdl');
    $data=array(
      'operator'=>$this->session->userdata('login_name'),
      'title'=>$title,
      'action'=>$action,
      'urls'=>$this->uri->uri_string(),
      'result'=>$result>0?'成功':'失敗'
    );
    $this->operator_mdl->insert($data);
  }

  //權限不夠
  public function permission(){
    $this->load->view('admin/permission_error_view',$this->data);
  }


  //是否下次7天內自動登入
  private function _is_remember($data){
    $remember=$this->input->post('remember');
    if($remember && !empty($data)){
      delete_cookie('adminLogin_remember');
      $cookie_adminLogin_remember= array(
        'name'   => 'adminLogin_remember',
        'value'  => serialize($data),
        'expire' => 60*60*24*7, //保存7天.
        'path'   => '/'
      );
      $this->input->set_cookie($cookie_adminLogin_remember);
    }
  }

}