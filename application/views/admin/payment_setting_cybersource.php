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
            <a href="<?=site_url('admin/payment').'?'.base64_decode($this->input->cookie('url_query'))?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-arrow-left"></i> <span class="layui-hide-xs">返回</span></a>
          </div>
        </div>
      </div><!--layui-card-header-->
      <div class="layui-card-body">
        <form id="form" action="<?=site_url('admin/payment/modify_setting');?>" method="post" class="layui-form">
          <div class="layui-row layui-col-space15">
            <div class="layui-col-md-12">
              <div class="layui-form-item required">
                <label class="layui-form-label">Profile ID (商戶代號)</label>
                <div class="layui-input-block">
                  <input type="text" class="layui-input" name="cybersource_profile_id" value="<?=set_value('cybersource_profile_id',isset($cybersource_profile_id)?$cybersource_profile_id:'')?>" required lay-verify="required" lay-verType="alert"/>
                  <div class="layui-form-mid layui-word-aux layui-orange">支付接口方提供</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">密钥 (Access Key)</label>
                <div class="layui-input-block">
                  <input type="text" class="layui-input" name="cybersource_access_key" value="<?=set_value('cybersource_access_key',isset($cybersource_access_key)?$cybersource_access_key:'')?>" required lay-verify="required" lay-verType="alert"/>
                  <div class="layui-form-mid layui-word-aux layui-orange">支付接口方提供</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">秘密密钥 (Secret Key)</label>
                <div class="layui-input-block">
                  <textarea class="layui-textarea" required lay-verify="required" lay-verType="alert" name="cybersource_secret_key"><?=set_value('cybersource_secret_key',isset($cybersource_secret_key)?$cybersource_secret_key:'')?></textarea>
                  <div class="layui-form-mid layui-word-aux layui-orange">支付接口方提供</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">測試模式</label>
                <div class="layui-input-block">
                  <select name="cybersource_test" required lay-verify="required" lay-verType="alert">
                    <option value="0" <?=set_select('cybersource_test',0)?> <?=isset($cybersource_test)&&$cybersource_test==0?'selected':''?> >否</option>
                    <option value="1" <?=set_select('cybersource_test',1)?> <?=isset($cybersource_test)&&$cybersource_test==1?'selected':''?> >是</option>
                  </select>
                  <div class="layui-form-mid layui-word-aux layui-orange">使用生產或測試（沙盒）網關伺服器處理交易付款?</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">訂單狀態</label>
                <div class="layui-input-block">
                  <select name="cybersource_order_status_id" required lay-verify="required" lay-verType="alert">
                    <?php if(isset($order_status_option) && !empty($order_status_option)):?>
                    <?php foreach($order_status_option as $option_id=>$option_text):?>
                    <option value="<?php echo $option_id;?>" <?=set_select('cybersource_order_status_id',$option_id)?> <?=isset($cybersource_order_status_id)&&$cybersource_order_status_id==$option_id?'selected':''?> ><?php echo $option_text;?></option>
                    <?php endforeach;?>
                    <?php endif;?>
                  </select>
                  <div class="layui-form-mid layui-word-aux layui-orange">交易完成后的默認狀態</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">訂單合計</label>
                <div class="layui-input-block">
                  <input type="text" class="layui-input" name="cybersource_total" value="<?=set_value('cybersource_total',isset($cybersource_total)?$cybersource_total:'')?>"/>
                  <div class="layui-form-mid layui-word-aux"><i class="fas fa-info-circle"></i> 當交易時訂單合計必須大於此金額才可使用本支付方式。</div>
                </div>
              </div>
              <div class="layui-controls">
                <button lay-submit class="layui-btn layui-btn-with-icon layui-btn-lg"><i class="fas fa-check"></i>存儲</button>
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