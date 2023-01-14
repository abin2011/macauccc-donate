<?php
//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
date_default_timezone_set("Asia/chongqing");
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");

$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);

//只取路径
$file_path=dirname(__FILE__);
$base_file=str_replace('themes/admin/js/ueditor1_4_3/php','',$file_path);
$base_file=str_replace('themes\admin\js\ueditor1_4_3\php','',$base_file);
define('BASEPATH',TRUE);
include $base_file.'application'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';
$base_url=$config['base_url'];

$base_url_arr=explode('/',$base_url);

$project_name='';
if(!empty($base_url_arr) && count($base_url_arr)>=3){
    foreach ($base_url_arr as $key => $path) {
        if($key>2){
            $project_name.='/'.$path;
        }
    }
}
$project_name=!empty($project_name)?substr($project_name,1):'';

if(!empty($project_name)){
    foreach ($CONFIG as $key => $value) {
        if(!is_array($value) && strstr($value,'/uploads/ueditor/')){
            $CONFIG[$key]='/'.$project_name.$value;
        }
    }
}

// print_r($CONFIG);

$action = $_GET['action'];

switch ($action) {
    case 'config':
        $CONFIG['base_url_arr']=$base_url_arr;
        $result =  json_encode($CONFIG);
        break;

    /* 上传图片 */
    case 'uploadimage':
    /* 上传涂鸦 */
    case 'uploadscrawl':
    /* 上传视频 */
    case 'uploadvideo':
    /* 上传文件 */
    case 'uploadfile':
        $result = include("action_upload.php");
        break;

    /* 列出图片 */
    case 'listimage':
        $result = include("action_list.php");
        break;
    /* 列出文件 */
    case 'listfile':
        $result = include("action_list.php");
        break;

    /* 抓取远程文件 */
    case 'catchimage':
        $result = include("action_crawler.php");
        break;

    default:
        $result = json_encode(array(
            'state'=> '请求地址出错'
        ));
        break;
}

/* 输出结果 */
if (isset($_GET["callback"])) {
    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
    } else {
        echo json_encode(array(
            'state'=> 'callback参数不合法'
        ));
    }
} else {
    echo $result;
}