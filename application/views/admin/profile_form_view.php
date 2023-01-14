<?php $this->load->view('common/admin_header');?>
<div class="layui-fluid">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
      <div class="layui-card">
        <?php $this->load->view('common/admin_notify');?>
        <div class="layui-card-header">
          <div class="layui-row pt-1 pb-1">
            <div class="layui-col-xs6"><h3>編輯賬戶資料</h3></div>
          </div>
        </div><!--layui-card-header-->
        <div class="layui-card-body">
          <form id="form" action="<?=site_url('admin/profile/modify');?>" method="post" class="layui-form">
            <input type="hidden" name="edit_id" value='<?=set_value('edit_id',isset($edit_id)?$edit_id:'')?>' />
            <div class="layui-row layui-col-space15">
              <div class="layui-col-md-12">
                <div class="layui-form-item required">
                  <label class="layui-form-label required">稱呼</label>
                  <div class="layui-input-block">
                    <input type="text" class="layui-input" name="nickname" value="<?=set_value('nickname',isset($nickname)?$nickname:'')?>" required lay-verify="required" lay-verType="tips"/>
                  </div>
                </div>
                <div class="layui-form-item required">
                  <label class="layui-form-label">登入名</label>
                  <div class="layui-input-block">
                    <input type="text" class="layui-input" name="login_name" value="<?=set_value('login_name',isset($login_name)?$login_name:'')?>" required lay-verify="required" lay-verType="tips"/>
                  </div>
                </div><!-- layui-form-item -->
                <div class="layui-form-item required">
                  <label class="layui-form-label">電郵</label>
                  <div class="layui-input-block">
                    <input type="text" class="layui-input" name="email" value="<?=set_value('email',isset($email)?$email:'')?>" required lay-verify="required" lay-verType="tips"/>
                  </div>
                </div><!-- layui-form-item -->
                <div class="layui-form-item required">
                  <label class="layui-form-label">登入密碼</label>
                  <div class="layui-input-block">
                    <input type="password" class="layui-input" name="password"/>
                    <div class="layui-form-mid layui-word-aux"><i class="fas fa-info-circle"></i> 如無需更改密碼則留空!</div>
                  </div>
                </div><!-- layui-form-item -->
                <div class="layui-form-item">
                  <label class="layui-form-label">確認密碼</label>
                  <div class="layui-input-block">
                    <input type="password" class="layui-input" name="confirm_pwd"/>
                  </div>
                </div><!-- layui-form-item -->
                <div class="layui-form-item">
                  <div class="layui-input-block">
                    <div class="layui-footer-controls">
                      <button lay-submit class="layui-btn layui-btn-with-icon layui-btn-lg" name="btn_save"><i class="fas fa-check"></i>存儲</button>
                      <button type="reset" class="layui-btn layui-btn-primary layui-btn-lg">重置</button>
                    </div>
                  </div>
                </div><!-- layui-form-item -->
              </div><!-- layui-col -->
            </div><!-- layui-row -->
          </form>
        </div><!-- layui-card-body -->
      </div><!-- layui-card -->
    </div>
    <!-- col -->
  </div><!-- layui-row -->
</div><!-- layui-fluid -->
<?php $this->load->view('common/admin_footer');?>