<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=lang('donate_page_title');?></title>
</head>
<body style="background-color: #f6f6f6; font-family:'Microsoft JhengHei','Microsoft Yahei',Arial, Helvetica, sans-serif; font-size: 14px; ">

  <div style="max-width: 760px; margin: 30px auto; background-color: #FFF; border-radius: 10px; box-shadow: 0 0 10px 0 rgba(0,0,0,0.125); overflow: hidden; border-top: 5px solid #d37b92;">
    <div style="text-align: left; border-bottom: 1px solid #e6e6e6; padding: 15px; margin: 0 15px;">
      <table style="border: 0; margin: 0; width: 100%;">
        <tr>
          <td width="50%"><a href="http://macauccc.org"><img src="<?=base_url('themes/front/images/logo.png')?>" alt="<?=lang('donate_page_title');?>"></a></td>
          <td style="text-align: right;" width="50%">
            <a href="http://macauccc.org" style="background: #d37b92; color: #FFF; text-decoration: none; display: inline-block; border-radius: 5px; font-size: 14px; padding: 10px 15px;"><?=lang('donate_official');?></a>
            <?php if(isset($email_merchant) && $email_merchant):?>
            <a href="<?=base_url('admin')?>" style="background: #089AD8; color: #FFF; text-decoration: none; display: inline-block; border-radius: 5px; font-size: 14px; padding: 10px 15px; margin-left: 10px;"><?=lang('donate_admin');?></a>
            <?php endif;?>
          </td>
        </tr>
      </table>
    </div>
    <!-- header -->
    <div style="color: #333;">
      <?php if(isset($email_merchant) && $email_merchant):?>
        <!--管理員端 Start-->
        <div style="padding: 25px 25px 40px 25px;">
          <h5 style="font-weight: 700; color: #333; font-size: 18px; margin: 15px 0;"><?=lang('donate_block_title06');?></h5>
          <table style="width: 100%; border-collapse: collapse;">
            <tr>
              <th style="text-align: left; border: 1px solid #e6e6e6; padding: 10px; background: #f6f6f6;" width="30%"><?=lang('donate_block_person');?>:</th>
              <td style="border: 1px solid #e6e6e6; padding: 10px;"><?php echo isset($full_name)?$full_name:'';?></td>
            </tr>
            <tr>
              <th style="text-align: left; border: 1px solid #e6e6e6; padding: 10px; background: #f6f6f6;"><?=lang('donate_block_money');?>:</th>
              <td style="border: 1px solid #e6e6e6; padding: 10px;">MOP <?php echo isset($donate_money)?$donate_money:'';?></td>
            </tr>
            <tr>
              <th style="text-align: left; border: 1px solid #e6e6e6; padding: 10px; background: #f6f6f6;"><?=lang('donate_block_item');?>:</th>
              <td style="border: 1px solid #e6e6e6; padding: 10px;"><?php echo isset($donate_item_format)?$donate_item_format:'';?></td>
            </tr>
            <tr>
              <th style="text-align: left; border: 1px solid #e6e6e6; padding: 10px; background: #f6f6f6;"><?=lang('donate_block_email');?>:</th>
              <td style="border: 1px solid #e6e6e6; padding: 10px;"><?php echo isset($donate_email)?$donate_email:'';?></td>
            </tr>
            <tr>
              <th style="text-align: left; border: 1px solid #e6e6e6; padding: 10px; background: #f6f6f6;"><?=lang('donate_block_country');?>:</th>
              <td style="border: 1px solid #e6e6e6; padding: 10px;"><?php echo isset($donate_country)?$donate_country:'';?></td>
            </tr>
            <tr>
              <th style="text-align: left; border: 1px solid #e6e6e6; padding: 10px; background: #f6f6f6;"><?=lang('donate_block_tel');?>:</th>
              <td style="border: 1px solid #e6e6e6; padding: 10px;"><?php echo isset($donate_phone)?$donate_phone:'';?></td>
            </tr>
            <tr>
              <th style="text-align: left; border: 1px solid #e6e6e6; padding: 10px; background: #f6f6f6;"><?=lang('donate_block_address');?>:</th>
              <td style="border: 1px solid #e6e6e6; padding: 10px;"><?php echo isset($donate_address)?$donate_address:'';?></td>
            </tr>
          </table>
          <h5 style="font-weight: 700; color: #333; font-size: 18px; margin: 15px 0; "><?=lang('donate_block_title03');?></h5>
          <table style="width: 100%; border-collapse: collapse;">
            <tr>
              <th style="text-align: left; border: 1px solid #e6e6e6; padding: 10px; background: #f6f6f6;" width="30%"><?=lang('donate_block_title03');?>:</th>
              <td style="border: 1px solid #e6e6e6; padding: 10px;"><?php echo isset($payment_method)?$payment_method:'';?></td>
            </tr>
          </table>
        </div>
      <?php else:?>
        <!--用戶端 Start  -->
        <div style="padding: 40px 25px;">
          <div style="font-size: 18px; text-align: center;"><?=lang('donate_call');?> <span style="font-weight: 700;"><?=lang('donate_email_call')?>, <?php echo isset($full_name)?$full_name:'';?></span></div>
          <h2 style="font-size: 24px; text-align: center;">—— <?=lang('donate_email_thanks')?> ——</h2>
          <div style="margin-bottom:20px; font-weight: 700; text-align: center;">
            <span style="display: inline-block; padding: 5px 10px; border-radius: 5px; background: #f6f6f6;"><?=lang('donate_block_item');?>：<?php echo isset($donate_item_format)?$donate_item_format:'';?></span>
          </div>
          <div style="color: #333; font-size: 14px; font-weight: 700; margin: 0 0 20px 0; text-align: center;">
            <div style="margin-bottom: 20px; color: #666;"><?=lang('donate_email_count');?>: </div>
            <div style="font-family: Arial, Helvetica, sans-serif; color: #d37b92; font-size: 20px; ">MOP <span style="font-size: 40px;"><?php echo isset($donate_money)?$donate_money:'';?></span></div>
          </div>
          <div style="text-align: center;">
            <a href="http://macauccc.org" style="background: #d37b92; color: #FFF; text-decoration: none; display: inline-block; border-radius: 5px; font-size: 16px; padding: 15px 30px;"><?=lang('donate_email_link');?></a>
          </div>
        </div>
      <?php endif;?>
    </div>
    <!-- main -->
    <div style="text-align: center; color: #666; border-top: 1px solid #e6e6e6; padding: 15px; margin: 0 15px; font-size: 12px;">
      <span>&copy; 2020 <?=lang('donate_page_title');?></span>
      <span>技術支援：<a href="https://clickrweb.com" style="color:#666; text-decoration: none;">力嘉Clickr</a></span>
    </div>
    <!-- footer -->
  </div>
  <!-- container -->

</body>
</html>