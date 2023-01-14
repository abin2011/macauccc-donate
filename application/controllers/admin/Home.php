<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-08 18:38:01
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-04-11 18:39:27
 * @email             :  info@clickrweb.com
 * @description       :  後台控制台
 */
class Home extends Admin_Controller {
  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='home';
  }
  public function index(){
    $this->_get_statistics_data();//獲取統計數據
    // $this->output->enable_profiler(TRUE);
    $this->load->view('admin/home_list_view',$this->data);
  }
  //獲取統計數據
  private function _get_statistics_data(){
    $filter=array(
      'created_at >='=>date('Y-m-d 00:00:00'),
      'created_at <='=>date('Y-m-d 23:59:59'),
      'order_status_id !='=>0,
    );
    
    //新企業
    // $this->load->model('company_mdl');
    // $this->data['today_company']=$this->company_mdl->count_by($filter);
    // $this->data['total_company']=$this->company_mdl->count_all();

    //新消息
    // $this->load->model('news_mdl');
    // $this->data['today_news']=$this->news_mdl->count_by($filter);
    // $this->data['total_news']=$this->news_mdl->count_all();

    //新捐款单
    $this->load->model('order_mdl');
    $this->data['today_order']=$this->order_mdl->count_by($filter);
    $this->data['total_order']=$this->order_mdl->count_by(array('order_status_id !='=>0));

    //新征集展品
    // $this->load->model('exhibit_mdl');
    // $this->data['today_exhibit']=$this->exhibit_mdl->count_by($filter);
    // $this->data['total_exhibit']=$this->exhibit_mdl->count_all();


    ##網站瀏覽統計
    //昨天
    $this->load->model('visit_mdl');
    $this->data['yesterday_visit']=$this->visit_mdl->count_by(array('visit_date'=>date("Y-m-d",strtotime("-1 day"))));
    //今天
    $this->data['today_visit']=$this->visit_mdl->count_by(array('visit_date'=>date("Y-m-d")));
    //7天內
    $week_filter=array(
      'visit_date >='=>date('Y-m-d',strtotime('-7 day')),
      'visit_date <='=>date('Y-m-d'),
    );
    $this->data['week_visit']=$this->visit_mdl->count_by($week_filter);
    //總數
    $this->data['total_visit']=$this->data['site_visit_count'];
    //最近訪問
    $this->data['recent_visit']=$this->visit_mdl
      ->order_by('created_at','DESC')
      ->limit(7)
      ->get_all();
  }
}