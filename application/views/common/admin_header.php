<!DOCTYPE html>
<html lang="zh_TW">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Clickr CMS</title>
<meta name="author" content="https://www.clickrweb.com">
<meta name="description" content="ClickrCms Admin Application"/>
<link rel="shortcut icon" href="<?=base_url('themes/admin/img/ico/ico.ico')?>">
<link href="<?=base_url('themes/admin/css/style.css')?>" rel="stylesheet">
<script src="<?=base_url('themes/admin/vendor/jquery-1.12.4.min.js')?>"></script>
<script src="<?=base_url('themes/admin/vendor/layui/layui.js')?>"></script>
</head>
<body class="layui-layout-body">
<div class="layadmin-tabspage-none">
<div class="layui-layout layui-layout-admin">
<div class="layui-header">
  <ul class="layui-nav layui-layout-left">
    <li class="layui-nav-item layadmin-flexible layui-hide-xs layui-hide-sm layui-show-md-inline-block  ">
      <a href="javascript:;" title="側邊收縮">
        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
      </a>
    </li>
    <li class="layui-nav-item layui-show-xs-inline-block layui-show-sm-inline-block layui-hide-md layui-hide-lg">
      <a href="javascript:;" title="側邊收縮">
        <i class="layui-icon layui-icon-spread-left" id="sideMenuMobileTrigger"></i>
      </a>
    </li>
    <li class="layui-nav-item">
      <a href="<?=base_url()?>" target="_blank" title="瀏覽網站">
        <i class="layui-icon layui-icon-website"></i> <span class="layui-hide-xs">瀏覽網站</span>
      </a>
    </li>
  </ul>
  <ul class="layui-nav layui-layout-right">
    <li class="layui-nav-item">
      <a href="<?=site_url('admin/feedback')?>" class="msg-trigger">
        <i class="fas fa-bell"></i> <span class="layui-hide-xs">通知消息</span> 
        <?php if(isset($unread_num) && !empty($unread_num)):?>
        <span class="msg-counter"><?php echo $unread_num;?></span>
        <?php endif;?>
      </a>
    </li>
    <li class="layui-nav-item">
      <a href="javascript:;">
        <i class="layui-icon layui-icon-username"></i> 
        歡迎,<?php echo isset($user_profile)?$user_profile['nickname']:''?>
      </a>
      <dl class="layui-nav-child layui-anim layui-anim-upbit">
        <dd><a href="<?=site_url('admin/profile')?>">編輯帳戶資料</a></dd>
        <hr>
        <dd><?=anchor(site_url('admin/login/login_out'),'安全登出',array('title'=>'登出'));?></dd>
      </dl>
    </li>
  </ul>
</div><!--layui-header -->
<div class="layui-side layui-side-menu">
  <div class="layui-side-scroll">
    <?php $currentPage=isset($currentPage)&&!empty($currentPage)?$currentPage:'';?>
    <?php $subPage=isset($subPage)&&!empty($subPage)?$subPage:''?>
    <div class="layui-logo"><span><?=anchor(site_url('admin/home'),'Clickr CMS',array('title'=>'網頁內容管理系統'));?></span> </div>
    <ul class="layui-nav layui-nav-tree" lay-shrink="all">
      <li class="layui-nav-item <?=$currentPage=='home'?'layui-this':''?>">
        <a href="<?=site_url('admin/home')?>" title="控制台" id="nav-home"><i class="fas fa-tachometer-alt"></i> <cite>控制台</cite></a>
      </li>
      <li class="layui-nav-item <?=$currentPage=='setting'?'layui-nav-itemed':''?>">
        <a href="javascript:;" id="nav-settings" title="基本設定"> <i class="fas fa-cog"></i> <cite>基本設定</cite> <span class="layui-nav-more"></span></a>
        <dl class="layui-nav-child">
          <dd class="<?=$subPage=='config'?'layui-this':''?>">
            <a href="<?=site_url('admin/setting/config')?>" title="網站設定">網站設定</a>
          </dd>
          <dd class="<?=$subPage=='mail'?'layui-this':''?>">
            <a href="<?=site_url('admin/setting/mail')?>" title="通知設定">通知設定</a>
          </dd>
          <dd class="<?=$subPage=='custom'?'layui-this':''?>">
            <a href="<?=site_url('admin/setting/custom')?>" title="自定義設定">自定義設定</a>
          </dd>
        </dl>
      </li>
      <li class="layui-nav-item <?=$currentPage=='manages'?'layui-nav-itemed':''?>">
        <a href="javascript:;" id="nav-manages" title="網頁內容"> <i class="fas fa-layer-group"></i> <cite>網頁內容</cite> <span class="layui-nav-more"></span></a>
        <dl class="layui-nav-child">
          <dd class="<?=$subPage=='page'?'layui-this':''?>">
            <a href="<?=site_url('admin/page')?>" title="基本頁面">基本頁面</a>
          </dd>
          <dd class="<?=$subPage=='payment'?'layui-this':''?>">
            <a href="<?=site_url('admin/payment')?>" title="支付管理">支付管理</a>
          </dd>
          <dd class="<?=$subPage=='order'?'layui-this':''?>">
            <a href="<?=site_url('admin/order')?>" title="捐款管理">捐款管理</a>
          </dd>

          <!-- <dd class="<?=$subPage=='slideshow'?'layui-this':''?>">
            <a href="<?=site_url('admin/slideshow')?>" title="幻燈片管理">幻燈片管理</a>
          </dd> -->
        </dl>
      </li>
      <li class="layui-nav-item <?=$currentPage=='system'?'layui-nav-itemed':''?>">
        <a href="javascript:;" id="nav-system" title="系統管理"> <i class="fas fa-cogs"></i> <cite>系統管理</cite> <span class="layui-nav-more"></span></a>
        <dl class="layui-nav-child">
          <dd class="<?=$subPage=='user'?'layui-this':''?>">
            <a href="<?=site_url('admin/user')?>" title="用戶管理">用戶管理</a>
          </dd>
          <dd class="<?=$subPage=='user_group'?'layui-this':''?>">
            <a href="<?=site_url('admin/user_group')?>" title="用戶組管理">用戶組管理</a>
          </dd>
          <dd class="<?=$subPage=='operator'?'layui-this':''?>">
            <a href="<?=site_url('admin/operator')?>" title="操作日誌">操作日誌</a>
          </dd>
          <dd class="<?=$subPage=='backup'?'layui-this':''?>">
            <a href="<?=site_url('admin/backup')?>" title="數據備份">數據備份</a>
          </dd>
          <dd class="<?=$subPage=='visit'?'layui-this':''?>">
            <a href="<?=site_url('admin/visit')?>" title="瀏覽記錄">瀏覽記錄</a>
          </dd>
        </dl>
      </li>
      <!-- <li class="layui-nav-item <?=$currentPage=='feedback'?'layui-this':''?>">
        <a href="<?=site_url('admin/feedback')?>" title="通知消息">
          <i class="fas fa-bell"></i> <cite>通知消息</cite>
          <?php if(isset($unread_num) && !empty($unread_num)):?>
          <span class="layui-badge layui-bg-gray"><?php echo $unread_num;?></span>
          <?php endif;?>
        </a>
      </li> -->
    </ul>
  </div>
</div><!--layui-side -->
<div class="layui-body">
<div class="layadmin-tabsbody-item layui-show">
<?php echo helper_admin_breadcrumb();?>
