<?php $this->load->view('common/admin_header');?>
<div class="layui-fluid">
<form class="layui-form" action="<?=site_url('admin/setting/save')?>" method="post">
<input type="hidden" name="setting_group" value="<?=set_value('setting_group',isset($setting_group)?$setting_group:'')?>">
<div class="layui-row layui-col-space15">
  <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
    <?php $this->load->view('common/admin_notify');?>
    <div class="layui-card">
      <div class="layui-card-body">
        <div class="layui-form-item">
          <label class="layui-form-label">郵件發送協議</label>
          <div class="layui-input-block">
            <select name="mail_protocol" id="mail_protocol" lay-filter="filter">
              <option value="mail" <?=$setting_array['mail_protocol']=='mail'?'selected':''?>>Mail</option>
              <option value="smtp" <?=$setting_array['mail_protocol']=='smtp'?'selected':''?>>SMTP</option>
              <option value="sendmail" <?=$setting_array['mail_protocol']=='sendmail'?'selected':''?>>SendMail</option>
            </select>
          </div>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">通知電郵</label>
          <div class="layui-input-block">
            <textarea name="mail_alert_email" placeholder="请输入通知電郵，按回車鍵完成單個郵箱錄入" class="layui-textarea tags"><?php echo $setting_array['mail_alert_email'];?></textarea>
            <input type="hidden" name="tags_send" value="">
          </div>
        </div><!--/layui-form-item-->
        <div id="smtp_setting" style="<?=$setting_array['mail_protocol']!='smtp'?'display:none;':''?>">
          <div class="layui-form-item">
            <label class="layui-form-label">SMTP主機地址</label>
            <div class="layui-input-block">
              <input type="text" name="mail_smtp_hostname" value="<?php echo $setting_array['mail_smtp_hostname']?>" placeholder="请输入SMTP主機地址" class="layui-input">
            </div>
          </div><!--/layui-form-item-->
          <div class="layui-form-item">
            <label class="layui-form-label">SMTP電郵帳號</label>
            <div class="layui-input-block">
              <input type="text" name="mail_smtp_username" value="<?php echo $setting_array['mail_smtp_username']?>" placeholder="请输入SMTP電郵帳號" class="layui-input">
            </div>
          </div><!--/layui-form-item-->
          <div class="layui-form-item">
            <label class="layui-form-label">SMTP電郵密碼</label>
            <div class="layui-input-block">
              <input type="text" name="mail_smtp_password" value="<?php echo $setting_array['mail_smtp_password']?>" placeholder="请输入SMTP電郵密碼" class="layui-input">
            </div>
          </div><!--/layui-form-item-->
          <div class="layui-form-item">
            <label class="layui-form-label">SMTP端口</label>
            <div class="layui-input-block">
              <input type="text" name="mail_smtp_port" value="<?php echo $setting_array['mail_smtp_port']?>" placeholder="请输入SMTP端口" class="layui-input">
            </div>
          </div><!--/layui-form-item-->
        </div><!--/smtp_setting-->
        <div id="sendmail_setting" style="<?=$setting_array['mail_protocol']!='sendmail'?'display:none;':''?>">
          <div class="layui-form-item">
            <label class="layui-form-label">伺服器SendMail路徑</label>
            <div class="layui-input-block">
              <input type="text" name="mail_sendmail_path" value="<?php echo $setting_array['mail_sendmail_path']?>" placeholder="请输入伺服器SendMail路徑" class="layui-input">
            </div>
          </div><!--/layui-form-item-->
        </div><!--/sendmail_setting-->
        <div class="layui-controls">
          <button class="layui-btn layui-btn-with-icon layui-btn-lg"><i class="fas fa-check"></i>存儲</button>
          <a id="btn_check_mail" href="<?=site_url('admin/setting/check_mail')?>" target="__blank" class="layui-btn layui-btn-primary layui-btn-lg layui-btn-with-icon"><i class="fas fa-paper-plane"></i>發送測試郵件</a>
        </div>
      </div> <!--/layui-card-body-->
    </div>
  </div>
</div><!-- layui-row -->
</form>
</div><!-- layui-fluid -->
<script src="<?=base_url('themes/admin/vendor/jQuery-Tags-Input/jquery.tagsinput.min.js')?>"></script>
<script type="text/javascript" charset="utf-8">
layui.use(['form','jquery'],function(){
  var form = layui.form;
  form.on('select(filter)', function(data){
    $('#smtp_setting').hide();
    $('#sendmail_setting').hide();
    if(data.value=='smtp' || data.value=='sendmail'){
      $('#'+data.value+'_setting').show();
    }
    $('#btn_check_mail').addClass('layui-btn-disabled').hide();
  });
  // 如果改變過input框值 則隱藏發送測試郵件button
  $('form.layui-form :input').change(function() {
    $('#btn_check_mail').addClass('layui-btn-disabled').hide();
  });
  function tagsChange() {
      $('.tag a').html("<i class='fas fa-times'></i>");
  }
  // tags input
  $('.tags').tagsInput({
    'width':'100%',
    'defaultText':'请输入通知電郵，按回車鍵完成單個郵箱錄入',
    'onChange' : tagsChange
  });
});
</script>
<?php $this->load->view('common/admin_footer');?>
