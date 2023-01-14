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
          <label class="layui-form-label">網站標題</label>
          <div class="layui-input-block">
            <input type="text" name="site_title" placeholder="请输入網站标题" class="layui-input" value="<?php echo $setting_array['site_title'];?>" required lay-verify="required" lay-verType="tips">
          </div>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">網站關鍵字</label>
          <div class="layui-input-block">
            <textarea name="site_keyword" placeholder="请输入網站關鍵字" class="layui-textarea tags"><?php echo $setting_array['site_keyword'];?></textarea>
          </div>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">網站描述</label>
          <div class="layui-input-block">
            <textarea name="site_description" placeholder="请输入網站網站描述" class="layui-textarea"><?php echo $setting_array['site_description'];?></textarea>
          </div>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">網站狀態</label>
          <input type="radio" name="website_status" value="1" title="開啓網站" <?php echo $setting_array['website_status']==1?'checked':''?>>
          <input type="radio" name="website_status" value="2" title="關閉網站" <?php echo $setting_array['website_status']==2?'checked':''?>>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">操作日誌存留</label>
          <div class="layui-input-block">
            <select name="operator_log">
              <option value="">请选择</option>
              <option value="15" <?=$setting_array['operator_log']==15?'selected':''?>>15天</option>
              <option value="30" <?=$setting_array['operator_log']==30?'selected':''?>>30天</option>
              <option value="60" <?=$setting_array['operator_log']==60?'selected':''?>>60天</option>
              <option value="90" <?=$setting_array['operator_log']==90?'selected':''?>>90天</option>
              <option value="180" <?=$setting_array['operator_log']==180?'selected':''?>>180天</option>
            </select>
          </div>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">瀏覽記錄存留</label>
          <div class="layui-input-block">
            <select name="site_visit_log">
              <option value="">请选择</option>
              <option value="15" <?=$setting_array['site_visit_log']==15?'selected':''?>>15天</option>
              <option value="30" <?=$setting_array['site_visit_log']==30?'selected':''?>>30天</option>
              <option value="60" <?=$setting_array['site_visit_log']==60?'selected':''?>>60天</option>
              <option value="90" <?=$setting_array['site_visit_log']==90?'selected':''?>>90天</option>
              <option value="180" <?=$setting_array['site_visit_log']==180?'selected':''?>>180天</option>
            </select>
          </div>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">網站版權信息</label>
          <div class="layui-input-block">
            <input type="text" name="site_copyright" value="<?php echo $setting_array['site_copyright']?>" placeholder="请输入網站版權信息" class="layui-input">
          </div>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">默認語言</label>
          <div class="layui-input-block">
            <select name="default_language">
              <option value="">请选择</option>
              <?php if(isset($lang_array) && !empty($lang_array)):?>
              <?php foreach($lang_array as $lang_id=>$lang_name):?>
              <option value="<?php echo $lang_id;?>" <?=isset($setting_array['default_language'])&&$setting_array['default_language']==$lang_id?'selected':''?>><?php echo $lang_name;?></option>
              <?php endforeach;?>
              <?php endif;?>
            </select>
          </div>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">後台列表個數</label>
          <div class="layui-input-block">
            <input type="text" name="default_admin_limit" value="<?php echo $setting_array['default_admin_limit']?>" placeholder="请输入後臺列表個數" class="layui-input" required lay-verify="required|number" lay-verType="tips">
          </div>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">前臺時間格式</label>
          <div class="layui-input-block">
            <select name="default_front_time">
              <option value="">请选择</option>
              <option value="Y/m/d" <?=$setting_array['default_front_time']=='Y/m/d'?'selected':''?>><?=date('Y/m/d')?></option>
              <option value="Y/m/d H:i" <?=$setting_array['default_front_time']=='Y/m/d H:i'?'selected':''?>><?=date('Y/m/d H:i')?></option>
              <option value="Y/m/d H:i:s" <?=$setting_array['default_front_time']=='Y/m/d H:i:s'?'selected':''?>><?=date('Y/m/d H:i:s')?></option>
              <option value="Y-m-d" <?=$setting_array['default_front_time']=='Y-m-d'?'selected':''?>><?=date('Y-m-d')?></option>
              <option value="Y-m-d H:i" <?=$setting_array['default_front_time']=='Y-m-d H:i'?'selected':''?>><?=date('Y-m-d H:i')?></option>
              <option value="Y-m-d H:i:s" <?=$setting_array['default_front_time']=='Y-m-d H:i:s'?'selected':''?>><?=date('Y-m-d H:i:s')?></option>
            </select>
          </div>
        </div><!--/layui-form-item-->
        <div class="layui-form-item">
          <label class="layui-form-label">前台列表個數</label>
          <div class="layui-input-block">
            <input type="text" name="default_front_limit" value="<?php echo $setting_array['default_front_limit']?>" placeholder="请输入後臺列表個數" class="layui-input" required lay-verify="required|number" lay-verType="tips">
          </div>
        </div><!--layui-form-item-->
        <?php if(isset($setting_key_array) && in_array('google_map_link',$setting_key_array)):?>
        <div class="layui-form-item">
          <label class="layui-form-label">Google Map鏈接</label>
          <div class="layui-input-block">
            <input type="text" name="google_map_link" value="<?php echo $setting_array['google_map_link']?>" placeholder="輸入Google Map鏈接" autocomplete="off" class="layui-input" lay-verify="url" lay-verType="tips">
          </div>
          <div class="layui-input-block">
            <div class="layui-form-mid layui-word-aux"><i class="fas fa-info-circle"></i> 如“Google Map鏈接”與“Google Map經緯”都有填寫，則優先使用Google Map鏈接</div>
          </div>
        </div><!--layui-form-item-->
        <?php endif;?>
        <?php if(isset($setting_key_array) && in_array('google_maps',$setting_key_array)):?>
        <div class="layui-form-item">
          <label class="layui-form-label">Google Map經緯</label>
          <div class="layui-input-block">
            <input type="text" id="google_maps" name="google_maps" value="<?php echo $setting_array['google_maps']?>" placeholder="Google Map經緯度 (維度在前,經度在后)" autocomplete="off" class="layui-input google_maps">
          </div>
          <div class="layui-input-block mt-1">
            <a href="http://map.clickrweb.com" target="_blank" class="layui-btn layui-btn-warm layui-btn-with-icon"><i class="fas fa-map-marker-alt"></i>獲取經緯度</a>
          </div>
        </div><!--layui-form-item-->
        <?php endif;?>
        <div class="layui-controls">
          <button lay-submit class="layui-btn layui-btn-with-icon layui-btn-lg"><i class="fas fa-check"></i>存儲</button>
        </div><!-- layui-controls -->
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
  $('.tags').tagsInput({
    'width':'100%',
    'defaultText':'请输入網站關鍵字',
    'onChange' : tagsChange
  });
  //google Map
  $('#google_maps').tagsInput({
    'width':'100%',
    'defaultText':'请输入Google Map經緯度 (維度在前,經度在后)',
    'onChange' : tagsChange
  });
});
</script>
<?php $this->load->view('common/admin_footer');?>