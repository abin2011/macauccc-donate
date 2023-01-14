<!DOCTYPE html>
<html lang="zh_TW">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>登入 Clickr CMS</title>
<meta name="author" content="https://www.clickrweb.com">
<meta name="description" content="ClickrCms Admin Application"/>
<link rel="shortcut icon" href="<?=base_url('themes/admin/img/ico/ico.ico')?>">
<script src="<?=base_url('themes/admin/vendor/jquery-1.12.4.min.js')?>"></script>
<script src="<?=base_url('themes/admin/vendor/layui/layui.js')?>"></script>
<link href="<?=base_url('themes/admin/css/style.css')?>" rel="stylesheet">
<style type="text/css">
html{background: none;}
</style>
</head>
<body class="sign-in-bg">
<div class="sign-in-box">
<div class="sign-in-logo">Clickr CMS</div>
<div class="sign-in-title">登入後臺管理系統</div>
<?php $this->load->view('common/admin_notify');?>
<form action="<?=site_url('admin/login/modify').'?ref='.urlencode($this->referrer)?>" method="post" class="layui-form">
  <div class="layui-row">
    <div class="layui-col-xs-12 required">
      <div class="layui-form-item sign-in-item">
        <label><i class="fas fa-user"></i></label>
        <input type="text" name="user_name" value="<?=set_value('user_name',isset($user_name)?$user_name:'')?>" class="layui-input" placeholder="請輸入用戶名" required lay-verify="required" lay-verType="tips"/>
      </div>
    </div>
    <div class="layui-col-xs-12 required">
      <div class="layui-form-item sign-in-item">
        <label><i class="fas fa-lock"></i></label>
        <input type="password" name="user_pwd" class="layui-input" placeholder="請輸入密碼" required lay-verify="required" lay-verType="tips"/>
      </div>        
    </div>
    <?php $error_login=$this->session->userdata('error_login');?>
    <?php if(!empty($error_login) && intval($error_login)==3):?>
    <div class="layui-col-xs-12 required">
      <div class="layui-form-item sign-in-item">
        <div class="layui-row layui-col-space5">
          <div class="layui-col-xs8 sign-in-verify">
            <label><i class="fas fa-shield-alt"></i></label>
            <input type="text" name="authcode" placeholder="請輸入輸入驗證碼" class="layui-input" required lay-verify="required" lay-verType="tips"/>
          </div>
          <div class="layui-col-xs4 text-right">
            <div class="note" style="cursor:pointer;">
              <img src="<?php echo site_url('admin/login/load_auth');?>" onclick="this.src='<?=site_url('admin/login/load_auth')?>?'+Math.random();"/>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
    <div class="layui-col-xs-12 text-left">
      <div class="layui-form-item">
        <input type="checkbox" name="remember" lay-skin="primary" title="7天内免登入" value="1" />
      </div>
    </div>
    <div class="layui-col-xs-12">
      <div class="layui-form-item">
        <button lay-submit class="layui-btn layui-btn-fluid">登入</button>
      </div>
      <div class="layui-form-item">
        <a href="<?=base_url()?>" title="返回首頁" class="layui-btn layui-btn-primary layui-btn-fluid">返回首頁</a>
      </div>
    </div>
  </div><!-- layui-row -->
</form>
</div>
<div class="sign-in-decorate">Clickr CMS</div>
<div class="sign-in-footer">Copyright © 2013-<?=date('Y')?> Clickr CMS. All rights reserved.</div>
<script src="<?=base_url('themes/admin/js/common.js')?>"></script>
</body>
</html>