<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-25 12:23:36
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-09-13 17:07:00
 * @email             :  info@clickrweb.com
 * @description       :  page 404 錯誤控制器 page 404
 */
class Page_404 extends Lang_Controller {
  public function __construct(){
    parent::__construct();
    $this->data['active']='page_404';
  }
  public function index(){
    $this->load->view('page_404_view',$this->data);
  }
}
