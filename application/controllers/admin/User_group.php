<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-12 12:49:48
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-12-30 14:28:03
 * @email             :  info@clickrweb.com
 * @description       :  系統管理:用戶組控制器
 */
class User_group extends Admin_Controller {

  private $unique_id;

  public function __construct(){
    parent::__construct();
    $this->data['currentPage'] ='system';
    $this->data['subPage']     ='user_group';
    $this->load->model('user_group_mdl');
    $this->_get_options();
  }

  //默認訪問網站設定
  public function index(){
    $this->_get_list();
    $this->load->view('admin/user_group_list',$this->data);
  }

  //獲取用戶列表
  private function _get_list(){
    $page   = $this->input->get('p',TRUE);
    $page   = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
    $limit  = $this->data['default_admin_limit'];
    $offset = ($page - 1) * $limit;
    $offset = $offset < 0 ? 0:$offset;

    $url_query=$_SERVER['QUERY_STRING'];
    //保留查詢參數;
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
    $this->data['name']  =$this->input->get('name');
    $this->data['email'] =$this->input->get('email');
    $this->data['field'] =$this->input->get('field');
    $this->data['sort']  =$this->input->get('sort');
    $this->data['field'] =!empty($this->data['field'])?$this->data['field']:'created_at';
    $this->data['sort']  =!empty($this->data['sort'])?$this->data['sort']:'desc';
    $where_data=array(
      'name'=>$this->data['name'],
    );
    $where_data=array_filter($where_data);//刪除空值數組元素.
    $this->data['lists_count']=$this->user_group_mdl->count_by($where_data);
    $pagination = '';
    if($this->data['lists_count'] > $limit){
      $this->dpagination->currentPage($page);
      $this->dpagination->items($this->data['lists_count']);
      $this->dpagination->limit($limit);
      $this->dpagination->adjacents(2);
      $this->dpagination->target(site_url('admin/user_group').'?'.$url_query);
      $this->dpagination->parameterName('p');
      $this->dpagination->nextLabel('下一頁');
      $this->dpagination->prevLabel('上一頁');
      $pagination = $this->dpagination->getOutput();
    }
    $this->data['pagination'] = $pagination;
    $this->data['lists']=$this->user_group_mdl
      ->order_by($this->data['field'],$this->data['sort'])
      ->limit($limit,$offset)
      ->get_many_by($where_data);
  }

  //新增用戶組
  public function add(){
    $this->load->view('admin/user_group_form',$this->data);
  }

  //編輯用戶組
  public function edit($edit_id=''){
    if(!empty($edit_id) && is_numeric($edit_id)){
      //基本信息
      $query=$this->user_group_mdl->get($edit_id);
      if(empty($query) || !is_array($query))
        show_error('參數不對.沒有該ID的列表項信息');
      $this->data['edit_id']=$edit_id;
      foreach ($query as $key => $value) {
        $this->data[$key]=$value;
        if($key=='permission' && !empty($value))
          $this->data['permission']=unserialize($value);
      }
      $this->load->view('admin/user_group_form',$this->data);
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
      $this->load->view('admin/user_group_form',$this->data);
    }else{
      $permission=$this->input->post('permission');
      array_push($permission,'home');
      $data=array(
        'name'=>$this->input->post('name'),
        'permission'=>!empty($permission)?serialize($permission):'',
      );
      if(!empty($edit_id) && $edit_id>0){
        $operator_title='修改用戶組->'.$data['name'];
        $action='修改';
        $result=$this->user_group_mdl->update($data,$edit_id);
      }else{
        $operator_title='新增用戶組->'.$data['name'];
        $action='新增';
        $result=$this->user_group_mdl->insert($data);
      }
      $this->operator_log($operator_title,$action,$result);
      $this->message_redirect($result,'admin/user_group');
    }
  }

  //驗證數據格式
  private function _load_validation_rules($edit_id=0){
    $this->form_validation->set_rules('edit_id','修改ID','trim|numeric|max_length[10]');
    $this->form_validation->set_rules('name','用戶組','trim|required|is_unique['.$this->user_group_mdl->_table.'.name.id.'.$this->unique_id.']|max_length[50]');
    $this->form_validation->set_rules('permission[]','用戶組權限','trim|required');
  }

  //獲取選項權限.
  private function _get_options(){

    $files = array();
    // Make path into an array
    $path = array(APPPATH . 'controllers/admin/*');
    // While the path array is still populated keep looping through
    while (count($path) != 0) {
      $next = array_shift($path);
      foreach (glob($next) as $file) {
        // If directory add to path array
        if (is_dir($file)) {
          $path[] = $file . '/*';
        }
        // Add the file to the files to be deleted array
        if (is_file($file)) {
          $files[] = $file;
        }
      }
    }
    // $files = glob(APPPATH.'controllers/admin/*.php');
    $this->data['permissions'] = array();
    $ignore = array('Login','Upload','Home');
    foreach ($files as $file) {
      $permission = basename($file, '.php');
      if (!in_array($permission, $ignore)) {
        $this->data['permissions'][] = strtolower($permission);
      }
    }
    $this->data['perstring']=helper_admin_breadcrumb('class_description');
  }
  
}