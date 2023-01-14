<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=isset($title)&&!empty($title)?$title.'｜'.$site_title:$site_title;?></title>
<?php if(isset($site_keyword) && !empty($site_keyword)):?>
<meta name="keywords" content="<?=isset($site_keyword) && !empty($site_keyword)?$site_keyword:lang('donate_page_title')?>">
<?php endif;?>
<meta name="description" content="<?=isset($site_description) && !empty($site_description)?$site_description:lang('donate_page_title')?>">
<meta name="author" content="<?=lang('donate_page_title');?>">
<meta name="robots" content="all"/>
<link rel="icon" href="<?=base_url('themes/front/images/favicon/favicon.png')?>" type="image/png">
<link href="<?=base_url('themes/front/css/style.css')?>" rel="stylesheet">
<link href="<?=base_url('themes/front/css/responsive.css')?>" rel="stylesheet">
<!--[if lt IE 9]>
<script src="<?=base_url('themes/front/vendor/polyfill/html5shiv.min.js')?>"></script>
<script src="<?=base_url('themes/front/vendor/polyfill/respond.min.js')?>"></script>
<![endif]-->
<script src="<?=base_url('themes/front/vendor/jquery/jquery-1.12.4.min.js')?>"></script>
</head>
<body>
<div class="wrapper">
<div class="step-form-header clearfix">
  <div class="container">
    <a href="<?=site_url('home')?>" class="step-form-logo"><img src="<?=base_url('themes/front/images/logo.png')?>" alt="" class="img-responsive"></a>
    <!-- step-form-logo -->
    <div class="step-form-title"><span><?=lang('donate_header_title');?></span></div>
    <!-- step-form-title -->
    <div class="btn-area">
      <div>
        <a href="http://macauccc.org" class="btn btn-primary ml-1"><?=lang('donate_back_home');?></a>
        <!-- back to home -->
        <div class="dropdown language ml-1 hidden">
          <button class="btn btn-blue dropdown-toggle" type="button" data-toggle="dropdown">
            <?=lang('donate_language');?>
            <span class="caret"></span>
          </button>
          <?php if(isset($lang_array) && !empty($lang_array)):?>
          <ul class="dropdown-menu dropdown-menu-right">
            <?php foreach($lang_array as $item):?>
            <?php $is_active = isset($lang_id)&&$lang_id==$item['id']?'class="active"':'';?>
            <li><a href="<?=base_url('language/change/'.$item['id']).'?back='.urlencode(uri_string()); ?>" <?php echo $is_active;?>><?php echo $item['name'];?></a></li>
            <?php endforeach;?>
          </ul>
          <?php endif;?>
        </div><!-- languege -->
      </div>
    </div><!-- btn-area -->
  </div>
</div>