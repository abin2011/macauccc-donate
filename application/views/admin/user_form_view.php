<?php $this->load->view('common/admin_header');?>
<div class="layui-fluid">
<div class="layui-row layui-col-space15">
  <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
    <?php $this->load->view('common/admin_notify');?>
    <div class="layui-card">
      <div class="layui-card-header">
        <div class="layui-row pt-1 pb-1">
          <div class="layui-col-xs6"><h3><?php echo $CI_page_title;?></h3></div>
          <div class="layui-col-xs6 text-right">
            <a href="<?=site_url('admin/user').'?'.base64_decode($this->input->cookie('url_query'))?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-arrow-left"></i> <span class="layui-hide-xs">返回</span></a>
          </div>
        </div>
      </div><!--layui-card-header-->
      <div class="layui-card-body">
        <form id="form" action="<?=site_url('admin/user/modify');?>" method="post" class="layui-form">
          <input type="hidden" name="edit_id" value='<?=set_value('edit_id',isset($edit_id)?$edit_id:'')?>' />
          <div class="layui-row layui-col-space15">
            <div class="layui-col-md-12">
              <div class="layui-form-item required">
                <label class="layui-form-label required">稱呼</label>
                <div class="layui-input-block">
                  <input type="text" class="layui-input" name="nickname" value="<?=set_value('nickname',isset($nickname)?$nickname:'')?>" required lay-verify="required" lay-verType="alert"/>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">登入名</label>
                <div class="layui-input-block">
                  <input type="text" class="layui-input" name="login_name" value="<?=set_value('login_name',isset($login_name)?$login_name:'')?>" required lay-verify="required" lay-verType="alert"/>
                </div>
              </div><!-- layui-form-item -->
              <?php if(!isset($edit_id) || $edit_id<=0):?>
              <div class="layui-form-item required">
                <label class="layui-form-label">登入密碼</label>
                <div class="layui-input-block">
                  <input type="password" class="layui-input" name="password" required lay-verify="required" lay-verType="alert"/>
                </div>
              </div><!--layui-form-item-->
              <div class="layui-form-item required">
                <label class="layui-form-label">確認密碼</label>
                <div class="layui-input-block">
                  <input type="password" class="layui-input" name="confirm_pwd" required lay-verify="required" lay-verType="alert"/>
                </div>
              </div><!--layui-form-item-->
              <?php endif;?>
              <div class="layui-form-item required">
                <label class="layui-form-label">所屬組別</label>
                <div class="layui-input-block">
                  <select name="group_id" id="group_id" required lay-verify="required" lay-verType="alert">
                    <option value="">請選擇組別</option>
                    <?php if(isset($user_groups) && is_array($user_groups)):?>
                    <?php foreach($user_groups as $group):?>
                    <option value="<?=$group['id']?>" <?=set_select('group_id',$group['id'])?> <?=isset($group_id) && $group_id==$group['id']?'selected':''?>><?=$group['name']?></option>
                    <?php endforeach;?>
                    <?php endif;?>
                  </select>
                </div>
              </div><!-- layui-form-item -->
              <div class="layui-form-item required">
                <label class="layui-form-label">電郵</label>
                <div class="layui-input-block">
                  <input type="text" class="layui-input" name="email" value="<?=set_value('email',isset($email)?$email:'')?>" required lay-verify="required" lay-verType="alert"/>
                </div>
              </div><!-- layui-form-item -->
              <?php if(isset($edit_id) && !empty($edit_id) && is_numeric($edit_id)):?>
              <div class="layui-form-item required">
                <label class="layui-form-label">登入密碼</label>
                <div class="layui-input-block">
                  <input type="password" class="layui-input" name="password"/>
                  <div class="layui-form-mid layui-word-aux"><i class="fas fa-info-circle"></i> 如無需更改密碼則留空!</div>
                </div>
              </div><!--layui-form-item-->
              <div class="layui-form-item">
                <label class="layui-form-label">確認密碼</label>
                <div class="layui-input-block">
                  <input type="password" class="layui-input" name="confirm_pwd"/>
                </div>
              </div><!--layui-form-item-->
              <?php endif;?>
              <div class="layui-form-item">
                <label class="layui-form-label">狀態</label>
                <div class="layui-input-block">
                  <input type="radio" name="status" value="1" title="啟用" <?=set_radio('status', '1', TRUE); ?> <?=isset($status) && $status==1?'checked':''?>>
                  <input type="radio" name="status" value="2" title="禁用" <?=set_radio('status', '2'); ?> <?=isset($status) && $status==2?'checked':''?>>
                </div>
              </div><!-- layui-form-item -->
              <div class="layui-controls">
                <button lay-submit class="layui-btn layui-btn-with-icon layui-btn-lg" name="btn_save"><i class="fas fa-check"></i>存儲</button>
                <button type="reset" class="layui-btn layui-btn-primary layui-btn-lg">重置</button>
              </div>
              <!-- layui-controls -->
            </div>
            <!-- layui-col -->
          </div>
          <!-- layui-row -->
        </form>
      </div><!-- layui-card-body -->
    </div><!-- layui-card -->
  </div>
  <!-- col -->
</div><!-- layui-row -->
</div><!-- layui-fluid -->
<?php $this->load->view('common/admin_footer');?>