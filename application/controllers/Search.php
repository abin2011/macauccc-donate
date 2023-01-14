<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-11-12 17:18:37
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2020-03-19 12:49:02
 * @email             :  info@clickrweb.com
 * @description       :  搜索控制器 search controller
 */
class Search extends Lang_Controller {

  public function __construct(){
    parent::__construct();
    $this->data['active']='search';
    $this->load->model('enrolment_mdl');
  }

  public function index(){
    $student_identity = $this->input->get('student_identity',TRUE);
    $student_birthday = $this->input->get('student_birthday',TRUE);
    if(!empty($student_identity) && !empty($student_birthday)){
      $this->data['student_identity'] = $student_identity;
      $this->data['student_birthday'] = $student_birthday;
      $student_birthday = date('Y-m-d',strtotime(str_replace('/', '-',$student_birthday)));
      $this->data['search_list'] = $this->enrolment_mdl
        ->select_fields('id,student_name_en,student_name_cn,candidate_no,student_identity')
        ->get_by(array('student_identity'=>$student_identity,'student_birthday'=>$student_birthday));
    }
    $this->load->view('search_list_view',$this->data);
  }


}