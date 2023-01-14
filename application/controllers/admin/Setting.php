<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-09 17:53:43
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-04-13 15:49:33
 * @email             :  info@clickrweb.com
 * @description       :  基本設定控制器
 */
class Setting extends Admin_Controller {
  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='setting';
    $this->load->model('setting_mdl');
  }
  
  //默認訪問網站設定
  public function index(){
    $this->config();
  }

  //網站設定
  public function config(){
    $this->_get_setting_by_group('config');
    $this->load->view('admin/setting_config_view',$this->data);
  }

  //通知設定
  public function mail(){
    $this->_get_setting_by_group('mail');
    $this->load->view('admin/setting_mail_view',$this->data);
  }

  //自定義設定
  public function custom(){
    $this->_get_custom_setting('custom');
    // $this->output->enable_profiler(TRUE);
    $this->load->view('admin/setting_custom_view',$this->data);
  }

  //執行設定保存
  public function save(){
    $setting_group=$this->input->post('setting_group',TRUE);
    $post_data=$this->input->post();
    $result=FALSE;
    if(!empty($setting_group) && !empty($post_data)){
      foreach ($post_data as $key => $value) {
        $where_data=array('setting_group'=>$setting_group,'setting_key'=>$key);
        $data=array('setting_value'=>$value);
        $result+=$this->setting_mdl->update_by($where_data,$data);
      }
    }
    $this->message_redirect($result,'admin/setting/'.$setting_group);
  }

  
  /**
   * @Author   Clickr Abin
   * @DateTime 2019-11-19T15:11:07+0800
   * @return   [array] [自定義設置的key和value和description]
   */
  private function _get_custom_setting($setting_group='custom'){
    $filter=array(
      'status'=>1,
      'setting_group'=>$setting_group
    );
    $query=$this->setting_mdl->get_many_by($filter);
    if(!empty($query) && is_array($query)){
      foreach($query as $item){
        $data[$item['setting_key']]=$item;
      }
      $this->data['setting_group']=$setting_group;
      $this->data['setting_array']=$data;
    }
    $this->data['subPage']=$setting_group;//二級頁面.
  }

  /**
   * 獲取對應的組別設定
   * @Author   Clickr  Abin
   * @DateTime 2019-07-09T18:07:37+0800
   * @param    string  $setting_group [description]
   * @return   [type]  [description]
   */
  private function _get_setting_by_group($setting_group='config'){
    $filter=array(
      'status'=>1,
      'setting_group'=>$setting_group
    );
    $query=$this->setting_mdl->get_many_by($filter);
    if(!empty($query) && is_array($query)){
      foreach($query as $item){
        $data[$item['setting_key']]=$item['setting_value'];
      }
      $this->data['setting_group']=$setting_group;
      $this->data['setting_array']=$data;
      $this->data['setting_key_array']=array_keys($data);
    }
    $this->data['subPage']=$setting_group;//二級頁面.
  }

  //檢測是否能正常發送電郵
  public function check_mail(){
    $mail_alert_email=$this->data['mail_alert_email'];
    if(!empty($mail_alert_email)){
      header('Content-Type: text/html; charset=UTF-8');
      $config['protocol']=$this->data['mail_protocol'];
      $email_from='';#$this->data['mail_sender'];
      if($config['protocol']=='smtp'){
        $config['smtp_host']    = $this->data['mail_smtp_hostname'];
        $config['smtp_user']    = $this->data['mail_smtp_username'];
        $config['smtp_pass']    = $this->data['mail_smtp_password'];
        $config['smtp_port']    = $this->data['mail_smtp_port'];
        $config['smtp_timeout'] = '30';
        $email_from             = $this->data['mail_smtp_username'];
      }else if($config['protocol']=='sendmail'){
        $config['mailpath'] = $this->data['mail_sendmail_path'];
      }
      $config['charset']      = 'utf-8';
      $config['mailtype']     = 'html';
      $config['newline']      = "\r\n";
      $email_subject=$this->data['site_title'].'::通知設定>郵件測試';
      $email_message=$this->data['site_title'].'::通知設定>郵件測試';
      $email_message.='<hr />收到此郵件表示您的通知設定成功!<br/>';
      $email_message.='Received this message indicates that your notification has been successful!<br/>';
      $email_message.='<hr><p><span style="color: #ff0000;"><strong>請注意,此郵件為系統代發,請勿直接回復! (Please note that this message for the system on behalf of the hair, do not directly reply!)</strong></span></p>';
      $this->load->library('email',$config);
      $this->email->from($email_from,$this->data['site_title']);
      $this->email->to($mail_alert_email);
      $this->email->subject($email_subject);
      $this->email->message($email_message);
      $result=$this->email->send();
      if($result){
        die('發送成功!(send success)');
      }
      echo $this->email->print_debugger();
    }else{
      show_error('參數出錯,請設置好通知電郵和對應的郵件參數！');
    }
  }
}