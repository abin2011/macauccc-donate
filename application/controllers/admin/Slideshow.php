<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-15 15:57:39
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-02-29 14:45:57
 * @email             :  info@clickrweb.com
 * @description       :  幻燈片控制器
 */
class Slideshow extends Admin_Controller {

  private $unique_id;

  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='manages';
    $this->data['subPage']='slideshow';
    $this->load->model('slideshow_mdl');
    //廣告頁面.
    $this->data['controller_option']=array(
      // 'home'=>'首頁幻燈片',
      // 'job'=>'職位右欄廣告',
    );
    //廣告位置.
    // $this->data['position_option']=array(
    //   'banner1'=>'首頁幻燈片',
    // );
  }

  public function index(){
    $this->_get_list();
    $this->load->view('admin/slideshow_list_view',$this->data);
  }

  //get list
  public function _get_list(){
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
    $this->data['page_controller']=$this->input->get('page_controller');
    $this->data['page_position']=$this->input->get('page_position');
    $this->data['field']=$this->input->get('field');
    $this->data['sort']=$this->input->get('sort');
    $this->data['field']=!empty($this->data['field'])?$this->data['field']:'n.sort_order';
    $this->data['sort']=!empty($this->data['sort'])?$this->data['sort']:'asc';
    $where_data=array(
      !empty($this->data['title'])?"nd.title LIKE '%{$this->data['title']}%' ":NULL,
      'n.page_controller'=>$this->data['page_controller'],
      'n.page_position'=>$this->data['page_position'],
    );
    $where_data=array_filter($where_data);//刪除空值數組元素.
    $this->data['lists_count']=$this->slideshow_mdl
      ->join_description($this->data['default_language'])
      ->count_by($where_data);
    $pagination = '';
    if($this->data['lists_count'] > $limit){
      $this->dpagination->changeClass('pagination text-center');
      $this->dpagination->currentPage($page);
      $this->dpagination->items($this->data['lists_count']);
      $this->dpagination->limit($limit);
      $this->dpagination->adjacents(2);
      $this->dpagination->target_method(site_url('admin/slideshow').'?'.$url_query);
      $this->dpagination->parameterName('p');
      $this->dpagination->nextLabel('下一頁');
      $this->dpagination->prevLabel('上一頁');
      $pagination = $this->dpagination->getOutput();
    }
    $this->data['pagination'] = $pagination;
    $this->data['lists']=$this->slideshow_mdl
      ->order_by($this->data['field'],$this->data['sort'])
      ->limit($limit,$offset)
      ->join_description($this->data['default_language'])
      ->get_many_by($where_data);
  }
  //添加管理員.
  public function add(){
    $this->load->view('admin/slideshow_form_view',$this->data);
  }
  //修改信息
  public function edit($edit_id=0){
    if(!empty($edit_id) && is_numeric($edit_id)){
      $query=$this->slideshow_mdl->get($edit_id);
      if(empty($query) || !is_array($query))
        show_error('參數不對.沒有該ID的列表項信息');
      $this->data['edit_id']=$edit_id;
      foreach ($query as $key => $value) {
        $this->data[$key]=$value;
      }
      $description=$this->slideshow_mdl->get_description($edit_id);
      foreach($description as $item){
        $this->data['descriptions'][$item['language_id']]=$item;
      }
      // $this->output->enable_profiler(TRUE);
      $this->load->view('admin/slideshow_form_view',$this->data);
    }else{
      show_error('對不起,參數出錯!');
      exit;
    }
  }
  //執行添加/修改.
  public function modify(){
    $edit_id=$this->input->post('edit_id');
    $this->unique_id=$edit_id;
    $this->_load_validation_rules();
    if ($this->form_validation->run() == FALSE){
      $this->data['error']=true;
      $this->load->view('admin/slideshow_form_view',$this->data);
    }else{
      $data=array(
        'main_image'=>$this->input->post('main_image'),
        'main_image_cn'=>$this->input->post('main_image_cn'),
        'main_image_en'=>$this->input->post('main_image_en'),
        'main_image_pt'=>$this->input->post('main_image_pt'),
        'support_mobile'=>$this->input->post('support_mobile'),
        'mobile_image'=>$this->input->post('mobile_image'),
        'sort_order'=>$this->input->post('sort_order'),
        'target_link'=>$this->input->post('target_link'),
        'target_method'=>$this->input->post('target_method'),
        'page_controller'=>$this->input->post('page_controller'),
        'page_position'=>$this->input->post('page_position'),
        'status'=>$this->input->post('status'),
        'updated_at'=>$this->input->post('updated_at'),
        'created_at'=>$this->input->post('created_at'),
      );
      $descriptions=$this->input->post('descriptions');//多語言
      if(!empty($edit_id) && $edit_id>0){
        $operator_title='修改幻燈片 ID->'.$edit_id;
        $action='修改';
        $result=$this->slideshow_mdl->update($data,$edit_id);
      }else{
        $operator_title='新增幻燈片->'.$descriptions[$this->data['default_language']]['title'];
        $action='新增';
        $result=$this->slideshow_mdl->insert($data);
        $edit_id=$result;
      }
      $result+=$this->slideshow_mdl->modify_description($descriptions,$edit_id);
      $this->operator_log($operator_title,$action,$result);
      $this->message_redirect($result,'admin/slideshow');
    }
  }
  //驗證數據格式
  private function _load_validation_rules(){
    $this->form_validation->set_rules('edit_id','修改ID','trim|numeric');
    // $this->form_validation->set_rules('page_controller','對應頁面','trim|required|max_length[50]');
    // $this->form_validation->set_rules('page_position','頁面位置','trim|required|max_length[50]');
    $this->form_validation->set_rules('sort_order','排序','trim|required|numeric|max_length[5]');
    $this->form_validation->set_rules('main_image','繁體版圖片','trim|required|max_length[100]');
    $this->form_validation->set_rules('main_image_cn','簡體版圖片','trim|required|max_length[100]');
    $this->form_validation->set_rules('main_image_en','英文版圖片','trim|required|max_length[100]');
    $this->form_validation->set_rules('main_image_pt','葡文版圖片','trim|required|max_length[100]');
    $this->form_validation->set_rules('support_mobile','兼容手機版','trim|numeric|max_length[2]');
    $this->form_validation->set_rules('mobile_image','手機版圖案','trim|max_length[100]');
    $this->form_validation->set_rules('target_link','網站','trim|max_length[100]');
    $this->form_validation->set_rules('target_method','鏈接打開方式','trim|numeric|max_length[2]');
    $this->form_validation->set_rules('created_at','開始於','trim|required|max_length[100]');
    $this->form_validation->set_rules('status','狀態','trim|required|numeric|max_length[2]');
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
  //刪除
  public function delete($delete_id=''){
    if(!empty($delete_id) && is_numeric($delete_id)){
      $result=$this->slideshow_mdl->delete($delete_id);
      $this->operator_log('刪除幻燈片->ID:'.$delete_id,'刪除',$result);
      $this->message_redirect($result,'admin/slideshow');
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }
  //批量刪除
  public function delete_batch(){
    $delete_string=$this->input->get('delete_string');
    if(!empty($delete_string)){
      $del_arr=explode(',',$delete_string);
      $result=$this->slideshow_mdl->delete_many($del_arr);
      $this->operator_log('批量刪除幻燈片->ID:'.$delete_string,'批量刪除',$result);
      $this->message_redirect($result,'admin/slideshow');
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }
}