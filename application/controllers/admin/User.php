<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-11 18:20:22
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:57:28
 * @email             :  info@clickrweb.com
 * @description       :  系統管理:用戶管理控制器
 */
class User extends Admin_Controller {

  private $unique_id;

  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='system';
    $this->data['subPage']='user';
    $this->load->model('user_mdl');
    $this->_get_options();
  }

  //默認訪問網站設定
  public function index(){
    $this->_get_list();
    $this->load->view('admin/user_list_view',$this->data);
  }

  //獲取用戶列表
  private function _get_list(){
    $page   = $this->input->get('p',TRUE);
    $page   = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
    $limit  = $this->data['default_admin_limit'];
    $offset = ($page - 1) * $limit;
    $offset = $offset < 0 ? 0:$offset;
    
    //保留查詢參數;
    $url_query=$_SERVER['QUERY_STRING'];
    $cookie = array(
      'name'   => 'url_query',
      'value'  => base64_encode($url_query),
      'expire' => '0',
      'path'   => '/',
    );
    $this->input->set_cookie($cookie);
    if(!empty($url_query)){
      $url_query=preg_replace('/&p=(\d+)/','',$url_query);
    }
    $this->data['login_name']=$this->input->get('login_name');
    $this->data['email']=$this->input->get('email');
    $this->data['group_id']=$this->input->get('group_id');
    $this->data['field']=$this->input->get('field');
    $this->data['sort']=$this->input->get('sort');
    $this->data['field']=!empty($this->data['field'])?$this->data['field']:'n.created_at';
    $this->data['sort']=!empty($this->data['sort'])?$this->data['sort']:'desc';
    $where_data=array(
      'n.email'=>$this->data['email'],
      'n.login_name'=>$this->data['login_name'],
      'n.group_id'=>$this->data['group_id'],
    );
    $where_data=array_filter($where_data);//刪除空值數組元素.
    $this->data['lists_count']=$this->user_mdl
      ->join_user_group()
      ->count_by($where_data);
    $pagination = '';
    if($this->data['lists_count'] > $limit){
      $this->dpagination->currentPage($page);
      $this->dpagination->items($this->data['lists_count']);
      $this->dpagination->limit($limit);
      $this->dpagination->adjacents(2);
      $this->dpagination->target(site_url('admin/user').'?'.$url_query);
      $this->dpagination->parameterName('p');
      $this->dpagination->nextLabel('下一頁');
      $this->dpagination->prevLabel('上一頁');
      $pagination = $this->dpagination->getOutput();
    }
    $this->data['pagination'] = $pagination;
    $this->data['lists']=$this->user_mdl
      ->order_by($this->data['field'],$this->data['sort'])
      ->limit($limit,$offset)
      ->join_user_group()
      ->get_many_by($where_data);
  }
  //新增用戶
  public function add(){
    $this->load->view('admin/user_form_view',$this->data);
  }
  //編輯用戶組
  public function edit($edit_id=''){
    if(!empty($edit_id) && is_numeric($edit_id)){
      //基本信息
      $query=$this->user_mdl->get($edit_id);
      if(empty($query) || !is_array($query))
        show_error('參數不對.沒有該ID的列表項信息');
      $this->data['edit_id']=$edit_id;
      foreach ($query as $key => $value) {
        $this->data[$key]=$value;
      }
      $this->load->view('admin/user_form_view',$this->data);
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }
  //執行編輯和刪除
  public function modify(){
    $edit_id=$this->input->post('edit_id');
    $this->unique_id=$edit_id;
    $this->_load_validation_rules($edit_id);
    if ($this->form_validation->run() == FALSE){
      $this->data['error']=true;
      $this->load->view('admin/user_form_view',$this->data);
    }else{
      $password=$this->input->post('password');
      $data=array(
        'nickname'=>$this->input->post('nickname'),
        'login_name'=>$this->input->post('login_name'),
        'group_id'=>$this->input->post('group_id'),
        'email'=>$this->input->post('email'),
        'status'=>$this->input->post('status'),
        'login_pwd'=>!empty($password)?md5(sha1($password)):'',
      );
      $data=array_filter($data);
      if(!empty($edit_id) && $edit_id>0){
        $operator_title='修改用戶->'.$data['login_name'];
        $action='修改';
        $result=$this->user_mdl->update($data,$edit_id);
      }else{
        $operator_title='新增用戶->'.$data['login_name'];
        $action='新增';
        $result=$this->user_mdl->insert($data);
      }
      $this->operator_log($operator_title,$action,$result);
      $this->message_redirect($result,'admin/user');
    }
  }
  //驗證數據格式
  private function _load_validation_rules($edit_id=0){
    $this->form_validation->set_rules('edit_id','修改ID','trim|numeric');
    $this->form_validation->set_rules('nickname','稱呼','trim|required|max_length[100]');
    $this->form_validation->set_rules('login_name','登入名','trim|required|alpha_dash|is_unique['.$this->user_mdl->_table.'.login_name.id.'.$this->unique_id.']|min_length[3]|max_length[20]');
    if(!empty($edit_id) && is_numeric($edit_id)){
      $this->form_validation->set_rules('password','登入密碼','trim|min_length[6]|max_length[100]');
      $this->form_validation->set_rules('confirm_pwd','確認密碼','trim|matches[password]|max_length[100]');
    }else{
      $this->form_validation->set_rules('password','登入密碼','trim|required|min_length[6]|max_length[100]');
      $this->form_validation->set_rules('confirm_pwd','確認密碼','trim|required|matches[password]|max_length[100]');
    }
    $this->form_validation->set_rules('group_id','組別','trim|required|numeric');
    $this->form_validation->set_rules('email','電郵','trim|valid_email|required|is_unique['.$this->user_mdl->_table.'.email.id.'.$this->unique_id.']|max_length[100]');
    $this->form_validation->set_rules('status','狀態','trim|required|numeric|max_length[1]');
  }
  //刪除管理員
  public function delete($delete_id=''){
    $current_user_id=$this->session->userdata('user_id');
    if(!empty($delete_id) && is_numeric($delete_id) && $delete_id!=$current_user_id){
      $result=$this->user_mdl->delete($delete_id);
      $this->operator_log('刪除用戶->ID:'.$delete_id,'刪除',$result);
      $this->message_redirect($result,'admin/user');
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }
  //獲取選項權限.
  private function _get_options(){
    $this->load->model('user_group_mdl');
    $this->data['user_groups']=$this->user_group_mdl->get_all();
  }
}