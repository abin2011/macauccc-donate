<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-12 13:58:12
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-11-05 13:59:51
 * @email             :  info@clickrweb.com
 * @description       :  系統管理:數據庫備份控制器
 */
class Backup extends Admin_Controller{

  private $backup_path;//備份路徑

  public function __construct(){
    parent::__construct();
    $this->data['currentPage']='system';
    $this->data['subPage'] = 'backup';
    $this->load->helper('file');//文件函數
    $this->load->helper('directory'); //目錄函數
    $this->backup_path=FCPATH."uploads/backup/";
  }

  public function index(){
    $this->_get_list();//獲取備份列表
    $this->load->view('admin/backup_list_view',$this->data);
  }

  //讀取備份列表
  private function _get_list(){
    $backup_files=directory_map($this->backup_path);
    if(!empty($backup_files) && is_array($backup_files)){
      foreach($backup_files as $key=>$file){
        $file_name=basename($file,".sql");
        $file_info=explode('_',$file_name);
        $data[$key]=array(
          'filename'=>$file,
          'created_at'=>$file_info[0],
          'author'=>$file_info[1],
        );
      }
      $this->data['lists']=$this->array_sort($data,'created_at');
    }
  }

  //二維數組排序.
  private function array_sort($multi_array,$sort_key,$sort=SORT_DESC){
    if(is_array($multi_array) && count($multi_array)>0){
      foreach ($multi_array as $row_array){
        if(is_array($row_array)){
          $key_array[] = $row_array[$sort_key];
        }
      }
      array_multisort($key_array,$sort,$multi_array);
      return $multi_array;
    }
  }

  //執行備份.
  public function add($format='txt'){
    header("Content-Type: text/plain; charset=UTF-8");
    $this->load->dbutil();
    $login_name=$this->session->userdata('login_name');
    $filename=time().'_'.$login_name.'.sql';
    $prefs = array(
      'ignore'      => array('clickrcms_ci_sessions'),// 备份时需要被忽略的表
      'format'      => $format,           // gzip, zip, txt
      'filename'    => $filename,         // 文件名 - 如果选择了ZIP压缩,此项就是必需的
      'add_drop'    => TRUE,              // 是否要在备份文件中添加 DROP TABLE 语句
      'add_insert'  => TRUE,              // 是否要在备份文件中添加 INSERT 语句
      'newline'     => "\n"               // 备份文件中的换行符
    );
    $backup = $this->dbutil->backup($prefs);
    Imagelib::mkdir_file($this->backup_path); //創建備份路徑
    $result=write_file($this->backup_path.$filename,$backup);
    $this->operator_log('備份數據庫:'.$filename,'備份',$result);
    $this->message_redirect($result,'admin/backup');
  }

  //還原備份文件
  public function recover($filename=''){
    if(!empty($filename) && is_file($this->backup_path.$filename)){
      $host = $this->db->hostname;
      $user = $this->db->username;
      $pwd  = $this->db->password;
      $db   = $this->db->database;
      $sql_content = read_file($this->backup_path.$filename);
      $mysqli = new mysqli($host,$user,$pwd,$db);
      $mysqli->set_charset("utf8");
      $result = $mysqli->multi_query($sql_content);
      $this->message_redirect($result,'admin/backup');
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }

  //下載備份文件
  public function download($filename=''){
    if(!empty($filename) && is_file($this->backup_path.$filename)){
      header("Content-Type: text/plain; charset=UTF-8");
      $this->load->helper('download');
      $this->operator_log('下載備份數據庫:'.$filename,'下載',1);
      $data = read_file($this->backup_path.$filename); // 读文件内容
      force_download($filename, $data);
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }

  //執行備份文件刪除
  public function delete($filename=''){
    if(!empty($filename) && is_file($this->backup_path.$filename)){
      $result=unlink($this->backup_path.$filename);
      $this->operator_log('刪除備份數據庫:'.$filename,'刪除',$result);
      $this->message_redirect($result,'admin/backup');
    }else{
      show_error('對不起,參數出錯');
      exit;
    }
  }
  
}