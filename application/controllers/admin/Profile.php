<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-15 15:47:14
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2021-01-22 17:05:50
 * @email             :  info@clickrweb.com
 * @description       :  後台管理賬戶個人資料編輯控制器
 */
class Profile extends Admin_Controller {

  private $unique_id;

  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='system';
    $this->data['subPage']='user';
    $this->load->model('user_mdl');
  }

  //修改個人賬戶信息
  public function index(){
    $current_user_id=$this->session->userdata('user_id');
    if(!empty($current_user_id) && is_numeric($current_user_id)){
      //基本信息
      $query=$this->user_mdl->get($current_user_id);
      if(empty($query) || !is_array($query))
        show_error('參數不對.沒有該ID的列表項信息');
      $this->data['edit_id']=$current_user_id;
      foreach ($query as $key => $value) {
        $this->data[$key]=$value;
      }
      $this->load->view('admin/profile_form_view',$this->data);
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }

  //執行修改
  public function modify(){
    $edit_id=$this->input->post('edit_id',TRUE);
    $this->unique_id = $edit_id;
    $this->_load_validation_rules($edit_id);
    if ($this->form_validation->run() == FALSE){
      $this->data['error']=true;
      $this->load->view('admin/profile_form_view',$this->data);
    }else{
      $password=$this->input->post('password',TRUE);
      $data=array(
        'nickname'   =>$this->input->post('nickname',TRUE),
        'login_name' =>$this->input->post('login_name',TRUE),
        'email'      =>$this->input->post('email',TRUE),
        'login_pwd'  =>!empty($password)?md5(sha1($password)):'',
      );
      $result=$this->user_mdl->update(array_filter($data),$edit_id);
      $this->session->unset_userdata('login_name');
      $this->session->set_userdata('login_name',$data['login_name']);
      $this->operator_log('編輯賬戶資料->'.$data['login_name'],'編輯',$result);
      $this->message_redirect($result,'admin/profile');
    }
  }

  //驗證數據格式
  private function _load_validation_rules(){
    $this->form_validation->set_rules('edit_id','修改ID','trim|numeric|max_length[10]|callback__verify_user_id');
    $this->form_validation->set_rules('nickname','稱呼','trim|required|max_length[100]');
    $this->form_validation->set_rules('login_name','登入名','trim|required|alpha_dash|is_unique['.$this->user_mdl->_table.'.login_name.id.'.$this->unique_id.']|min_length[3]|max_length[50]');
    $this->form_validation->set_rules('password','登入密碼','trim|min_length[6]|max_length[100]');
    $this->form_validation->set_rules('confirm_pwd','確認密碼','trim|matches[password]|max_length[100]');
    $this->form_validation->set_rules('email','電郵','trim|valid_email|required|is_unique['.$this->user_mdl->_table.'.email.id.'.$this->unique_id.']|max_length[100]');
  }
  
  //驗證編輯的是否為當前賬戶信息
  public function _verify_user_id($edit_user_id){
    $current_user_id=$this->session->userdata('user_id');
    if($edit_user_id != $current_user_id){
      $this->form_validation->set_message('_verify_user_id','對不起,您編輯的賬戶信息ID出錯!');
      return FALSE;
    }
    return TRUE;
  }

  
}