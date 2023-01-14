<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-22 13:21:53
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-02-24 16:27:55
 * @email             :  info@clickrweb.com
 * @description       :  基本頁面展示 cms page Controller
 */
class Page extends Lang_Controller {

  public function __construct(){
    parent::__construct();
    $this->data['active']='page';
    $this->load->model('page_mdl');
  }
  
  public function index($unique_url=''){
    $this->_get_page_info($unique_url);
    $this->load->view('page_list_view',$this->data);
  }

  //獲取頁面詳細資料.
  private function _get_page_info($unique_url=''){
    $where_data=array(
      'status'=>1,
      'unique_url'=>$unique_url,
    );
    $query=$this->page_mdl
      ->join_description($this->data['lang_id'],TRUE)
      ->get_by($where_data);
    if(empty($query) || !is_array($query))
      redirect('page_404');
    $this->page_mdl->add_view_num($query['id']); //更新瀏覽次數
    $this->data['title']  = $query['title'];
    $this->data['content']= $query['content'];
    $this->data['active'] = $unique_url;
    $this->data['list']   = $query;
    $this->_get_parent_list($query['parent_id']); //獲取同級別的頁面
  }

  private function _get_parent_list($parent_id=''){
    if(!empty($parent_id) && is_numeric($parent_id)){
      $filter=array(
        'n.status'=>1,//啟用
        'n.parent_id'=>$parent_id,//條款告示聲明頁面
      );
      $this->data['parent_list']=$this->page_mdl
        ->join_description($this->data['lang_id'])
        ->order_by('n.sort_order','ASC')
        ->get_many_by($filter);
    }
  }

}