<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-12 13:58:12
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2023-01-30 10:50:33
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
      $data = array();
      foreach($backup_files as $key=>$file){
        if(is_array($file) || !is_file($this->backup_path.$file) || strpos($file,'.sql')===FALSE) 
          continue;
        $file_name=basename($file,".sql");
        if(strpos($file,'.gz')!==FALSE)
          $file_name=basename($file,".sql.gz");
        $file_info=explode('_',$file_name);
        if(!empty($file_info) && count($file_info)==2){
          $author =!empty($file_info[1])&&ctype_xdigit($file_info[1])?pack('H*',$file_info[1]):$file_info[1];
          $data[$key]=array(
            'filename'   =>$file,
            'created_at' =>filectime($this->backup_path.$file),
            'author'     =>$author,
          );
        }
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
  public function add($format='gzip'){
    header("Content-Type: text/plain; charset=UTF-8");
    @ini_set('max_execution_time','0');
    @ini_set('memory_limit','-1');
    $this->db->save_queries = false;
    $this->load->dbutil();
    $login_name=$this->session->userdata('login_name');
    $filename=date('YmdHis').'_'.bin2hex($login_name).'.sql.gz';
    $prefs = array(
      'ignore'      => array(),           // 备份时需要被忽略的表
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
      @ini_set('max_execution_time','0');
      @ini_set('memory_limit','-1');
      $this->db->save_queries = false;
      $login_name   =$this->session->userdata('login_name');
      $new_filename =date('YmdHis').'_'.bin2hex($login_name).'.sql.gz';
      $prefs = array(
        'ignore'       => array(),           // 备份时需要被忽略的表
        'format'       => 'gzip',            // gzip, zip, txt
        'filename'     => $new_filename,     // 文件名 - 如果选择了ZIP压缩,此项就是必需的
        'add_drop'     => TRUE,              // 是否要在备份文件中添加 DROP TABLE 语句
        'add_insert'   => TRUE,              // 是否要在备份文件中添加 INSERT 语句
        'newline'      => "\n"               // 备份文件中的换行符
      );
      $this->load->dbutil();
      $backup = $this->dbutil->backup($prefs);
      Imagelib::mkdir_file($this->backup_path); //創建備份路徑
      $result=write_file($this->backup_path.$new_filename,$backup);
      if($result){
        $host = $this->db->hostname;
        $user = $this->db->username;
        $pwd  = $this->db->password;
        $db   = $this->db->database;
        $sql_content = '';
        if(strpos($filename,'.gz')!==FALSE){
          $gzfile = gzopen($this->backup_path.$filename,"r");
          while(!gzeof($gzfile)) {
            $sql_content.=gzread($gzfile,4096);
          }
          gzclose($gzfile);
        }else{
          $sql_content = read_file($this->backup_path.$filename);
        }
        $mysqli = new mysqli($host,$user,$pwd,$db);
        $mysqli->set_charset("utf8");

        $sql_content = preg_replace("/#(.*)\s#(.*)TABLE(.*)(.*)\s#/i","",$sql_content);//去掉注释部分
        $sql_array   = explode(";\n",$sql_content);
        $result      = FALSE;
        foreach($sql_array as $key=>$sql){
          if(str_replace(" ","",trim($sql))){
            $tempResult=$mysqli->query(trim($sql));
            if(!$tempResult){
              log_message('error','recover sql error=='.mysqli_error($mysqli));
            }
            $result+=$tempResult;
          }
        }
        if($result){
          $this->load->driver('cache', array('backup' => 'file', 'key_prefix' => 'cache_'));
          $result += $this->cache->file->clean();
          $user_id    =$this->session->userdata('user_id');
          $user_token =$this->session->userdata('user_token');
          $this->user_mdl->update(['token'=>$user_token],$user_id);
        }
        $this->operator_log('還原數據庫:'.$filename,'還原',$result);
        $this->message_redirect($result,'admin/backup');
      }else{
        show_error('對不起,還原數據庫前,未能成功備份現有數據,還原失敗！');
      }
    }else{
      show_error('對不起,參數出錯');
    }
  }

  //下載備份文件
  public function download($filename=''){
    if(!empty($filename) && is_file($this->backup_path.$filename)){
      header("Content-Type: text/plain; charset=UTF-8");
      $this->load->helper('download');
      $this->operator_log('下載備份數據庫:'.$filename,'下載',1);
      $data = @file_get_contents($this->backup_path.$filename); // 读文件内容
      force_download($filename, $data);
    }else{
      show_error('對不起,參數出錯');
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
    }
  }
  
}