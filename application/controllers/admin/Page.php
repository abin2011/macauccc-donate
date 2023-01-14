<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-10 09:47:09
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-02-24 01:45:58
 * @email             :  info@clickrweb.com
 * @description       :  基本頁面 cms page
 */
class Page extends Admin_Controller {

  private $unique_id;

  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='manages';
    $this->data['subPage']='page';
    $this->load->model('page_mdl');
    // $this->_get_menus();
    $this->data['menus']=array(
      '1'=>'條款告示聲明',
    );
  }

  public function index(){
    $this->_get_list();
    // $this->output->enable_profiler(TRUE);
    $this->load->view('admin/page_list_view',$this->data);
  }

  //獲取主要的menu歸屬
  private function _get_menus(){
    $filter=array(
      'language_id'=>$this->data['default_language'],
      'parent_id'=>0,
      'status'=>1,
    );
    $query=$this->page_mdl->get_many_by($filter);
    $result=array();
    foreach ($query as $key => $list) {
      $result[$list['id']]=$list['title'];
    }
    $this->data['menus']=$result;
  }

  //獲取頁面列表
  private function _get_list(){
    $page   = $this->input->get('p',TRUE);
    $page   = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
    $limit  = $this->data['default_admin_limit'];
    $offset = ($page - 1) * $limit;
    $offset = $offset < 0 ? 0:$offset;

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
    $this->data['title']=$this->input->get('title');
    $this->data['unique_url']=$this->input->get('unique_url');
    $this->data['parent_id']=$this->input->get('parent_id');
    $this->data['field']=$this->input->get('field');
    $this->data['sort']=$this->input->get('sort');
    $this->data['field']=!empty($this->data['field'])?$this->data['field']:'n.created_at';
    $this->data['sort']=!empty($this->data['sort'])?$this->data['sort']:'desc';
    $where_data=array(
      !empty($this->data['title'])?"nd.title LIKE '%{$this->data['title']}%'":NULL,
      !empty($this->data['unique_url'])?"n.unique_url LIKE '%{$this->data['unique_url']}%'":NULL,
    );
    $where_data=array_filter($where_data);//刪除空值數組元素.
    $where_data=is_numeric($this->data['parent_id']) && $this->data['parent_id']>=0?array_merge($where_data,array('n.parent_id'=>trim($this->data['parent_id']))):$where_data;
    $this->data['lists_count']=$this->page_mdl
      ->join_description($this->data['default_language'])
      ->count_by($where_data);
    $pagination = '';
    if($this->data['lists_count'] > $limit){
      $this->dpagination->currentPage($page);
      $this->dpagination->items($this->data['lists_count']);
      $this->dpagination->limit($limit);
      $this->dpagination->adjacents(2);
      $this->dpagination->target(site_url('admin/page').'?'.$url_query);
      $this->dpagination->parameterName('p');
      $this->dpagination->nextLabel('下一頁');
      $this->dpagination->prevLabel('上一頁');
      $pagination = $this->dpagination->getOutput();
    }
    $this->data['pagination'] = $pagination;
    $this->data['lists']=$this->page_mdl
      ->order_by($this->data['field'],$this->data['sort'])
      ->limit($limit,$offset)
      ->join_description($this->data['default_language'])
      ->get_many_by($where_data);
  }

  //新增頁面
  public function add(){
    $this->load->view('admin/page_form_view',$this->data);
  }

  //修改頁面
  public function edit($edit_id=''){
    if(!empty($edit_id) && is_numeric($edit_id)){
      $query=$this->page_mdl->get($edit_id);
      if(empty($query) || !is_array($query))
        show_error('參數不對.沒有該ID的列表項信息');
      $this->data['edit_id']=$edit_id;
      foreach ($query as $key => $value) {
        $this->data[$key]=$value;
      }
      //資料 descriptions
      $description=$this->page_mdl->get_description($edit_id);
      foreach($description as $item){
        $this->data['descriptions'][$item['language_id']]=$item;
      }//end foreach;
      $this->load->view('admin/page_form_view',$this->data);
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }

  //執行修改或添加
  public function modify(){
    $edit_id=$this->input->post('edit_id');
    $this->unique_id=$edit_id;
    $this->_load_validation_rules();
    if ($this->form_validation->run() == FALSE){
      $this->data['error']=true;
      $this->load->view('admin/page_form_view',$this->data);
    }else{
      $data=array(
        'unique_url'=>$this->input->post('unique_url'),
        'main_image'=>$this->input->post('main_image'),
        'sort_order'=>$this->input->post('sort_order'),
        'parent_id'=>$this->input->post('parent_id'),
        'is_menu'=>$this->input->post('is_menu'),
        'status'=>$this->input->post('status'),
        'updated_at'=>$this->input->post('updated_at'),
        'created_at'=>$this->input->post('created_at'),
      );
      //多語言描述
      $descriptions=$this->input->post('descriptions');
      if(!empty($edit_id) && is_numeric($edit_id)){
        $operator_title='修改基本頁面->'.$descriptions[$this->data['default_language']]['title'];
        $action='修改';
        $result=$this->page_mdl->update($data,$edit_id);
      }else{
        $operator_title='新增基本頁面->'.$descriptions[$this->data['default_language']]['title'];
        $action='新增';
        $result=$this->page_mdl->insert($data);
        $edit_id=$result;
      }
      $result+=$this->page_mdl->modify_description($descriptions,$edit_id);
      $this->operator_log($operator_title,$action,$result);
      $this->message_redirect($result,'admin/page');
    }
  }

  //數據格式驗證
  public function _load_validation_rules(){
    $this->form_validation->set_rules('unique_url','靜態網址','trim|required|alpha_dash|is_unique['.$this->page_mdl->_table.'.unique_url.id.'.$this->unique_id.']|max_length[20]');
    $this->form_validation->set_rules('sort_order','排序','trim|numeric|max_length[10]');
    $this->form_validation->set_rules('status','狀態','trim|required|numeric|max_length[1]');
    $this->form_validation->set_rules('parent_id','所屬類別','trim|numeric|max_length[5]');
    $this->form_validation->set_rules('is_menu','菜單顯示','trim|numeric|max_length[5]');
    $this->form_validation->set_rules('main_image','封面圖片','trim|max_length[100]');
    $this->form_validation->set_rules('edit_id','修改ID','trim|numeric');
    //多語言判斷
    foreach($this->data['lang_array'] as $id=>$name){
      $title='descriptions['.$id.'][title]';
      $introduction='descriptions['.$id.'][introduction]';
      $content='descriptions['.$id.'][content]';
      $this->form_validation->set_rules($title,$name.' 標題','trim|max_length[300]');
      $this->form_validation->set_rules($introduction,$name.' 簡介','trim|max_length[500]');
      $this->form_validation->set_rules($content,$name.' 內容','trim');
    }
  }

  //執行刪除功能
  public function delete($delete_id=0){
    if(!empty($delete_id) && is_numeric($delete_id)){
      $result=$this->page_mdl->delete($delete_id);
      $this->operator_log('刪除基本頁面->ID:'.$delete_id,'刪除',$result);
      $this->message_redirect($result,'admin/page');
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }
  
  //重置檢視數
  public function reset($page_id=''){
    if(!empty($page_id) && is_numeric($page_id)){
      $result=$this->page_mdl->update(array('view_num'=>0),$page_id);
      $this->operator_log('重置基本頁面檢視數->ID:'.$page_id,'重置',$result);
      $this->message_redirect($result,'admin/page');
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }
}