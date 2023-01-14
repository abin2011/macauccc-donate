<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-16 17:21:16
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-09-13 17:08:26
 * @email             :  info@clickrweb.com
 * @description       :  後台上傳控制器 Upload Controller
 */
class Upload extends CI_Controller {
  private $data = array();
  public function __construct(){
    parent::__construct();    
  }
  //刪除文件
  public function delete(){
    $json_data=array(
      "status"=>'error',
      "message"=>'對不起,操作失敗！'
    );
    $user_token=$this->session->userdata('user_token');
    $token = $this->input->get('token',TRUE);
    $path = $this->input->get('path',TRUE);
    if($token!=$user_token){
      $json_data['user_token']=$user_token;
      $json_data['message']='對不起,您的驗證令牌已過期,請刷新頁面！';
    }
    $result=FALSE;
    if($user_token==$token && !empty($path)){
      // $result=Imagelib::delete_image($path);
      $result+=Imagelib::delete_thumb_image($path);
    }
    if($result){
      $json_data=array(
        "status"=>'success',
        "message"=>'操作成功！',
        'result'=>$result,
      );
    }
    if($this->input->is_ajax_request()){
      header('Content-Type: application/json; charset=utf-8');
      die(json_encode($json_data));
    }else{
      return $json_data;
    }
  }
  //單個上傳 封面圖或文件
  public function single_upload(){
    $json_data=array(
      'status'  =>'error',
      'message' =>'對不起,操作失敗！'
    );
    $forder     = $this->input->post('folder');//文件上傳目录
    $element_id = $this->input->post('element_id');//jquery id
    $thumb_size = $this->input->post('thumb_size');//縮圖尺寸
    $is_file    = $this->input->post('is_file');//是否文件
    $token      = $this->input->post('token',TRUE);//驗證令牌
    $user_id    = $this->session->userdata('user_id'); //session id
    $user_token = $this->session->userdata('user_token'); //session token
    if($token!=$user_token){
      $json_data['user_token']=$user_token;
      $json_data['message']='對不起,您的驗證令牌已過期,請刷新頁面！';
    }else{
      $forder = !empty($forder)?urldecode($forder):'uploads/single/';
      Imagelib::mkdir_file($forder);
      $new_file_name=date('Ymd').uniqid();//以微秒计的当前时间,生成一个唯一的 ID
      $config['upload_path']    = FCPATH.$forder; //文件保存路径
      $config['allowed_types']  = 'jpg|jpeg|gif|png|bmp|pdf|txt|zip|xls|doc';//允许上传格式
      $config['max_size']       = '20480';//允许上传文件大小的最大值单位KB,设置为0表示无限制 1024KB*20 20MB
      $config['file_name']      = $new_file_name;
      $this->load->library('upload',$config);
      if($this->upload->do_upload('file')){
        $data       = $this->upload->data();
        $file_path  = $forder.$data['file_name']; //上傳后的地址.相對地址
        $thumb_image='';//縮圖地址
        if(!$is_file && $data['is_image']){
          if(!empty($thumb_size) && preg_match("/(\d+)(,\s*\d+)/",$thumb_size)){
            $temp_array=explode(',',$thumb_size);
            $thumb_image=Imagelib::resize_thumb($file_path,current($temp_array),end($temp_array));
          }else{
            $thumb_image=Imagelib::resize_thumb($file_path,200,200);
          }
        }
        $uploaded_data=array(
          'file_path'   => $file_path,
          'http_path'   => base_url($file_path),
          'element_id'  => $element_id,
          'is_file'     => $is_file,
          'thumb_image' => !empty($thumb_image)?base_url($thumb_image):'',
        );
        $json_data['status']='success';//上傳成功
        $json_data['message']=$uploaded_data;
      }else{
        $json_data['message']=$this->upload->display_errors();
      }
    }
    if($this->input->is_ajax_request()){
      header('Content-Type: application/json; charset=utf-8');
      die(json_encode($json_data));
    }else{
      return $json_data;
    }
  }
}