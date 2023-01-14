<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-09 11:39:27
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-04-13 11:02:00
 * @email             :  info@clickrweb.com
 * @description       :  前後台的總控制器.啟動器,獲取語言,設置.
 */
##後台管理控制器
class Admin_Controller extends CI_Controller{

  public $data=array();

  public function __construct() {
    parent::__construct();
    $this->CI_get_setting();//獲取設置
    $this->CI_check_user_online();
    $this->CI_get_language();//獲取語言.
    $this->CI_get_unread_feedbook();//獲取未讀通知.
    $this->data['CI_page_title']=helper_admin_page_title();
  }

  #獲取設置參數.
  protected function CI_get_setting(){
    $this->load->model('setting_mdl');
    $query=$this->setting_mdl->get_all();
    if(!empty($query) && is_array($query)){
      foreach($query as $item){
        $this->data[$item['setting_key']]=$item['setting_value'];
      }
    }
  }

  #檢測是否登入
  protected function CI_check_user_online(){
    $this->load->model('user_mdl');
    $user_id=$this->session->userdata('user_id');
    $user_token=$this->session->userdata('user_token');
    $group_id=$this->session->userdata('group_id');
    $login_name=$this->session->userdata('login_name');
    $check_data=array();
    if(!empty($user_id) && !empty($group_id) && !empty($login_name)){
      $check_data=array(
        'id'=>$user_id,
        'group_id'=>$group_id,
        'login_name'=>$login_name
      );
    }else{
      if($this->input->cookie('adminLogin_remember',TRUE)){ //如果存在cookie 存儲的登入信息
        $cookie_adminLogin_remember=$this->input->cookie('adminLogin_remember',TRUE);
        $cookie_adminLogin=!empty($cookie_adminLogin_remember)?unserialize($cookie_adminLogin_remember):'';
        if(!empty($cookie_adminLogin) && is_array($cookie_adminLogin)){
          $check_data=array(
            'id'=>$cookie_adminLogin['user_id'],
            'login_name'=>$cookie_adminLogin['login_name'],
            'group_id'=>$cookie_adminLogin['group_id'],
          );
        }
      }
    }

    if(empty($check_data))
      redirect('admin/login?ref='.urlencode($this->uri->uri_string()));

    $result=$this->user_mdl->get_by($check_data);
    if($result && is_array($result)){
      $this->data['user_profile']=$result;

      $this->session->set_userdata(array(
        'user_id'=>$result['id'],
        'group_id'=>$result['group_id'],
        'user_token'=>$result['token'],
        'login_name'=>$result['login_name'],
        'nick_name'=>!empty($result['nickname'])?$result['nickname']:$result['login_name']
      ));

      $this->load->model('user_group_mdl');
      $query=$this->user_group_mdl->get($result['group_id']); //獲取用戶組權限

      $urls=$this->uri->segment(2,0);//獲取操作段
      $current_class = $this->router->class;
      $permissions=unserialize($query['permission']);
      $this->data['user_permissions']=$permissions;
      if(!is_array($permissions) || (!in_array($urls,$permissions) && !in_array($current_class,$permissions))){
        redirect('admin/login/permission');
      }
    }else{
      redirect('admin/login?ref='.urlencode($this->uri->uri_string()));
    }
  }

  #基本配置里獲取語言列表
  protected function CI_get_language(){
    $this->load->model('language_mdl');
    $this->data['lang_array']=$this->language_mdl->dropdown(array('status'=>1),array('id','name'));
  }

  //獲取客戶反饋
  protected function CI_get_unread_feedbook(){
    $this->load->model('feedback_mdl');
    $unread_num=$this->feedback_mdl->count_by(array('status'=>1));
    $this->data['unread_num']=intval($unread_num)>99?'99+':$unread_num;
  }

  #操作日誌
  protected function operator_log($title='',$action='',$result=1){
    $this->load->model('operator_mdl');
    if(isset($this->data['operator_log']) && $this->data['operator_log']>0){
      $expired=date('Y-m-d H:i:s',strtotime('-'.$this->data['operator_log'].' day'));
      $this->operator_mdl->delete_by(array('created_at <'=>$expired));
      $data=array(
        'operator'=>$this->session->userdata('login_name'),
        'title'=>$title,
        'action'=>$action,
        'urls'=>$this->uri->uri_string(),
        'result'=>$result>0?'成功':'失敗',
      );
      $this->operator_mdl->insert($data);
    }
  }

  //操作之後,跳轉和顯示結果
  protected function message_redirect($result='',$controller='dashboard'){
    $msg = $result > 0?'恭喜，您的操作成功！':'對不起，您的操作失敗！';
    $notify = $result > 0?'success':'error';
    $this->session->set_flashdata($notify, $msg);
    //獲取列表篩選參數
    $cookie_url_query=$this->input->cookie('url_query');
    if(!empty($cookie_url_query)){
      $url_query=base64_decode($cookie_url_query);
      if(filter_var($controller,FILTER_VALIDATE_URL)){
        $controller.=strpos($controller,'?')!==false?'?'.$url_query:'&'.$url_query;
      }else{
        $controller=site_url($controller).'?'.$url_query;
      }
    }
    redirect($controller);
  }
}

##前台管理語言控制器
class Lang_Controller extends CI_Controller{
  public $data=array();

  public function __construct(){
    parent::__construct();
    $this->CI_get_setting();//獲取配置參數
    $this->CI_get_language();//獲取網站語言
    $this->CI_website_visit();//瀏覽統計.
    // $this->CI_get_parent_page();//獲取底部page
  }

  //獲取配置參數.
  protected function CI_get_setting(){
    $this->load->model('setting_mdl');
    $query=$this->setting_mdl->get_all();
    if(!empty($query) && is_array($query)){
      foreach($query as $item){
        $this->data[$item['setting_key']]=$item['setting_value'];
      }
    }
  }

  //獲取網站語言
  protected function CI_get_language(){
    $lang_id=$this->input->cookie('language_id');
    $lang_id=!empty($lang_id)?$lang_id:$this->data['default_language'];
    // $lang_id=$this->data['default_language'];
    $this->load->model('language_mdl');
    $query=$this->language_mdl->get_many_by(array('status'=>1));
    if(!empty($query) && is_array($query)){
      $choice=current($query); //默認選中第一個.
      foreach($query as $item){
        if($item['id']==$lang_id){
          $choice=$item;//匹配當前使用的語言
          break;
        }
      }
      $this->data['lang_id']=$choice['id'];
      $this->data['lang']=$choice['filename'];
      $this->data['lang_name']=$choice['front_name'];
      $this->data['lang_code']=$choice['code_path'];
      $this->data['seo_lang_array'] = $this->config->item('seo_lang_array');
      $this->config->set_item('language',$choice['filename']);
      $this->load->language('global',$choice['filename']);
      $this->data['lang_array']=$query;

      //load other lang site title and description
      if(!empty($choice['code_path']) && array_key_exists($choice['code_path'],$this->data['seo_lang_array']) &&
         isset($this->data['site_title_'.$choice['code_path']]) && !empty($this->data['site_title_'.$choice['code_path']])){
        $this->data['site_title']=$this->data['site_title_'.$choice['code_path']];
        unset($this->data['site_keyword']);
        $this->data['site_description']=$this->data['site_description_'.$choice['code_path']];
      }

    }
  }

  //瀏覽統計 過濾搜索引擎爬蟲
  protected function CI_website_visit(){
    $this->load->library('user_agent');
    if($this->agent->is_robot())
      return FALSE;

    $this->load->model('visit_mdl');
    //刪除過期訪問記錄
    $site_visit_log=$this->data['site_visit_log'];//訪問記錄保留多少天
    $expired_date=date('Y-m-d',strtotime('-'.$site_visit_log.' day'));
    $this->visit_mdl->delete_by('visit_date<="'.$expired_date.'"');

    $ip_address=$this->input->ip_address();
    $filter=array('ip_address'=>$ip_address,'visit_date'=>date('Y-m-d'));
    $result=$this->visit_mdl->get_by($filter);
    if(!$result){
      $visit_data=array(
        'ip_address'=>$ip_address,
        'visit_date'=>date('Y-m-d'),
        'source_url'=>$this->agent->is_referral()?$this->agent->referrer():'',
        'visit_url' =>$this->uri->uri_string(),
        'device'    =>$this->agent->is_mobile()?'mobile':'computer',
        'user_agent'=>$this->input->user_agent(),
      );
      $result=$this->visit_mdl->insert($visit_data);
      if($result){
        $visit_count=$this->data['site_visit_count'];
        $where_data=array('setting_group'=>'config','setting_key'=>'site_visit_count');
        $data=array('setting_value'=>intval($visit_count)+1);
        $this->setting_mdl->update_by($where_data,$data);
      }
    }
  }

  //獲取會員信息
  protected function CI_get_member(){
    $member_id=$this->session->userdata('member_id');
    $login_phone=$this->session->userdata('login_phone');
    $member_token=$this->session->userdata('member_token');
    $member_remember=$this->input->cookie('memberLogin_remember',TRUE);
    $check_data=array();
    if(!empty($member_id) && !empty($login_phone) && !empty($member_token)){
      $check_data=array(
        'id'=>$member_id,
        'login_phone'=>$login_phone,
      );
    }else if(!empty($member_remember) && is_array(unserialize($member_remember))){
      $cookie_memberLogin=unserialize($member_remember);
      $check_data=array(
        'id'=>$cookie_memberLogin['member_id'],
        'login_phone'=>$cookie_memberLogin['login_phone'],
      );
      $this->session->set_userdata($cookie_memberLogin);
      $member_token='cookie_token';     
    }

    if(!empty($check_data) && is_array($check_data)){
      $this->load->model('member_mdl');
      $result=$this->member_mdl->get_by($check_data); //驗證在線
      if(!empty($result) && is_array($result) && in_array($member_token,array('cookie_token',$result['token']))){
        $result['avatar']=base_url('themes/front/img/avatar.png');
        if(!empty($result['main_image']) && file_exists($result['main_image'])){
          $result['avatar']=base_url().imagelib::resize_thumb($result['main_image'],300,300);
        }else if(!empty($result['main_image']) && filter_var($result['main_image'],FILTER_VALIDATE_URL)){
          $result['avatar']=$result['main_image'];
        }
        $this->data['CI_member']=$result;
        $this->session->set_userdata('member_token',$result['token']);
      }else{
        $data=array(
          'member_id'=>'',
          'member_name'=>'',
          'login_phone'=>'',
          'member_token'=>'',
        );
        $this->session->unset_userdata($data);
        delete_cookie('memberLogin_remember');
      }
    }
  }

  //獲取底部條款告示聲明頁面組
  protected function CI_get_parent_page($parent_id=1){
    $filter=array(
      'n.status'=>1,//啟用
      'n.parent_id'=>$parent_id,//條款告示聲明頁面
    );
    $this->load->model('page_mdl');
    $this->data['CI_parent_page']=$this->page_mdl
      ->join_description($this->data['lang_id'])
      ->order_by('n.sort_order','ASC')
      ->limit(10)
      ->get_many_by($filter);
  }

  //加載引導頁
  public function CI_guide_page(){
    $init_guide = $this->uri->uri_string();
    $total_segments = $this->uri->total_segments();
    if(empty($init_guide) && empty($total_segments)){
      die($this->load->view('guide_display_view',$this->data,TRUE));
    }
  }

}

//會員在線控制器
class Front_Controller extends Lang_Controller{

  public function __construct() {
    parent::__construct();
    $this->load->model('member_mdl');
    $this->check_member_online();
  }

  //檢查是否登陸.
  protected function check_member_online(){
    $member_id    = $this->session->userdata('member_id');
    $login_phone  = $this->session->userdata('login_phone');
    $member_token = $this->session->userdata('member_token');
    if(empty($login_phone) || empty($member_id) || empty($member_token)){
      $this->_handle_check_error();
    }
    $check_data=array(
      'login_phone'=>$login_phone,
      'id'=>$member_id,
    );
    $result=$this->member_mdl->get_by($check_data); //驗證在線
    if(empty($result) || !is_array($result)){
      $this->_handle_check_error();
    }

    if($result['token']!=$member_token){
      $this->_handle_check_error(TRUE);
    }
  }
  
  //處理登入失敗.
  private function _handle_check_error($has_error=FALSE){
    $data=array('member_id'=>'','member_name'=>'','login_phone'=>'','member_token'=>'');
    $this->session->unset_userdata($data);
    delete_cookie('memberLogin_remember');
    if($this->input->is_ajax_request()){
      $error_data=array(
        'status'=>'error',
        'message'=>lang('account_not_login_error'),
      );
      header('Content-Type: application/json; charset=utf-8');
      die(json_encode($error_data));
    }else{
      if($has_error)
        $this->session->set_flashdata('error', lang('account_login_other_error'));
      $ref = $this->input->get('ref',TRUE);
      $ref = !empty($ref)?$ref:$this->uri->uri_string();
      redirect('login?ref='.urlencode($ref));
    }
  }
}