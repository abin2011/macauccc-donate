<?php
// ######(以下配置为PM环境：入网测试环境用，生产环境配置见文档说明)#######
// 签名证书路径
define('SDK_SIGN_CERT_PATH',APPPATH.'third_party/BocUnionpay/cer/prod/acp_prod_sign.pfx');
// 签名证书密码
const SDK_SIGN_CERT_PWD = 'CARITAS-MACAU';
// 密码加密证书（这条一般用不到的请随便配）
define('SDK_ENCRYPT_CERT_PATH',APPPATH.'third_party/BocUnionpay/cer/prod/acp_pord_enc_20181130.cer');
// 验签证书路径（请配到文件夹，不要配到具体文件）
define('SDK_VERIFY_CERT_DIR',APPPATH.'third_party/BocUnionpay/cer/prod/');
// 前台请求地址
const SDK_FRONT_TRANS_URL = 'https://gateway.95516.com/gateway/api/frontTransReq.do'; //生產模式

// 后台请求地址
const SDK_BACK_TRANS_URL = 'https://gateway.95516.com/gateway/api/backTransReq.do';

//单笔查询请求地址
const SDK_SINGLE_QUERY_URL = 'https://gateway.95516.com/gateway/api/queryTrans.do';

// 批量交易
const SDK_BATCH_TRANS_URL = 'https://101.231.204.80:5000/gateway/api/batchTrans.do';

//文件传输请求地址
const SDK_FILE_QUERY_URL = 'https://101.231.204.80:9080/';
//有卡交易地址
const SDK_Card_Request_Url = 'https://101.231.204.80:5000/gateway/api/cardTransReq.do';
//App交易地址
const SDK_App_Request_Url = 'https://101.231.204.80:5000/gateway/api/appTransReq.do';

//文件下载目录 <棄用>
#const SDK_FILE_DOWN_PATH = 'D:/file/';
define('SDK_FILE_DOWN_PATH',APPPATH.'third_party/BocUnionpay/cer/file/');

//日志 目录  <棄用>
#const SDK_LOG_FILE_PATH = 'D:/logs/';
define('SDK_LOG_FILE_PATH',APPPATH.'third_party/BocUnionpay/cer/logs/');

//日志级别，关掉的话改PhpLog::OFF
const SDK_LOG_LEVEL = 6;


/** 以下缴费产品使用，其余产品用不到，无视即可 */
// 前台请求地址
//const JF_SDK_FRONT_TRANS_URL = 'https://101.231.204.80:5000/jiaofei/api/frontTransReq.do';
const JF_SDK_FRONT_TRANS_URL = 'https://101.231.204.80:5000/gateway/api/frontTransReq.do';
// 后台请求地址
// const JF_SDK_BACK_TRANS_URL = 'https://101.231.204.80:5000/jiaofei/api/backTransReq.do';
const JF_SDK_BACK_TRANS_URL = 'https://101.231.204.80:5000/gateway/api/backTransReq.do';
// 单笔查询请求地址
// const JF_SDK_SINGLE_QUERY_URL = 'https://101.231.204.80:5000/jiaofei/api/queryTrans.do';
const JF_SDK_SINGLE_QUERY_URL = 'https://101.231.204.80:5000/gateway/api/queryTrans.do';

// 有卡交易地址
const JF_SDK_CARD_TRANS_URL = 'https://101.231.204.80:5000/jiaofei/api/cardTransReq.do';
// App交易地址
const JF_SDK_APP_TRANS_URL = 'https://101.231.204.80:5000/jiaofei/api/appTransReq.do';

?>