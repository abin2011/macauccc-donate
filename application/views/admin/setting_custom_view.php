<?php $this->load->view('common/admin_header');?>
<div class="layui-fluid">
<form class="layui-form" action="<?=site_url('admin/setting/save')?>" method="post">
<input type="hidden" name="setting_group" value="<?=set_value('setting_group',isset($setting_group)?$setting_group:'')?>">
<div class="layui-row layui-col-space15">
  <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
    <?php $this->load->view('common/admin_notify');?>
    <div class="layui-card">
      <div class="layui-card-body">
        <?php if(isset($setting_array) && !empty($setting_array)):?>
        <?php foreach($setting_array as $setting_key => $item):?>         
        <div class="layui-form-item">
          <label class="layui-form-label"><?php echo $item['description'];?></label>
          <div class="layui-input-block">
            <?php if(in_array($setting_key,array('site_description_cn','site_description_en','site_description_pt','donate_item1','donate_item2','donate_item3','donate_item4','donate_church1'))):?>
            <textarea name="<?php echo $setting_key;?>" placeholder="请输入<?php echo $item['description'];?>" class="layui-textarea"><?php echo $item['setting_value'];?></textarea>
            <?php else:?>
            <input type="text" name="<?php echo $setting_key;?>" placeholder="请输入<?php echo $item['description'];?>" class="layui-input" value="<?php echo $item['setting_value'];?>">
            <?php endif;?>
            <?php if(strstr($setting_key,'donate_item')):?>
            <div class="layui-form-mid layui-word-aux"><i class="fas fa-info-circle"></i> 回車隔開捐款項目,最後一項為其他,帶輸入框補充!</div>
            <?php endif;?>
            <?php if(strstr($setting_key,'donate_church')):?>
            <div class="layui-form-mid layui-word-aux"><i class="fas fa-info-circle"></i> 回車隔開堂會選項!</div>
            <?php endif;?>
          </div>
        </div><!--/layui-form-item-->
        <?php endforeach;?>
        <?php endif;?>
        <div class="layui-controls">
          <button class="layui-btn layui-btn-with-icon layui-btn-lg"><i class="fas fa-check"></i>存儲</button>
        </div>
        <!-- layui-controls -->
      </div><!--layui-card-body-->
    </div>
  </div><!-- layui-col -->
</div><!--layui-row-->
</form>
</div><!--layui-fluid-->
<script src="<?=base_url('themes/admin/vendor/jQuery-Tags-Input/jquery.tagsinput.min.js')?>"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
  function tagsChange() {
    $('.tag a').html("<i class='fas fa-times'></i>");
  }
  // tags input
  $('textarea[name^="donate_item"],textarea[name^="donate_church"]').tagsInput({
    'width':'100%',
    'defaultText':'请输入選項',
    'onChange' : tagsChange
  });
});
</script>
<?php $this->load->view('common/admin_footer');?>