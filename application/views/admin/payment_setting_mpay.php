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
        <?php echo form_open('admin/payment/modify_setting',['id'=>'form','class'=>'layui-form']);?>
          <div class="layui-row layui-col-space15">
            <div class="layui-col-md-12">
              <div class="layui-form-item layui-bg-cyan text-center" style="padding:10px;">
                <p>現正配置 <?php echo isset($payment_title)?$payment_title:'' ?> 支付方式參數</p>
              </div>
            </div>
            <div class="layui-col-md-12">
              <div class="layui-form-item required">
                <label class="layui-form-label">商戶編號orgID</label>
                <div class="layui-input-block">
                  <input type="text" class="layui-input" name="mpay_orgID" value="<?=set_value('mpay_orgID',isset($mpay_orgID)?$mpay_orgID:'')?>" required lay-verify="required" lay-verType="alert"/>
                  <div class="layui-form-mid layui-word-aux layui-orange">支付接口方提供</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">商戶RAS私鑰證書 (Secret Key)</label>
                <div class="layui-input-block">
                  <textarea class="layui-textarea" required lay-verify="required" lay-verType="alert" name="mpay_merCert"><?=set_value('mpay_merCert',isset($mpay_merCert)?$mpay_merCert:'')?></textarea>
                  <div class="layui-form-mid layui-word-aux layui-orange">支付接口方提供</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">銀行RAS公鑰證書 (Public Key)</label>
                <div class="layui-input-block">
                  <textarea class="layui-textarea" required lay-verify="required" lay-verType="alert" name="mpay_bankCert"><?=set_value('mpay_bankCert',isset($mpay_bankCert)?$mpay_bankCert:'')?></textarea>
                  <div class="layui-form-mid layui-word-aux layui-orange">支付接口方提供</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">商戶特定商业信息JSON</label>
                <div class="layui-input-block">
                  <textarea class="layui-textarea" required lay-verify="required" lay-verType="alert" name="mpay_extend_params"><?=set_value('mpay_extend_params',isset($mpay_extend_params)?$mpay_extend_params:'')?></textarea>
                  <div class="layui-form-mid layui-word-aux layui-orange">支付接口方提供</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">測試模式</label>
                <div class="layui-input-block">
                  <select name="mpay_test" required lay-verify="required" lay-verType="alert">
                    <option value="0" <?=set_select('mpay_test',0)?> <?=isset($mpay_test)&&$mpay_test==0?'selected':''?> >否</option>
                    <option value="1" <?=set_select('mpay_test',1)?> <?=isset($mpay_test)&&$mpay_test==1?'selected':''?> >是</option>
                  </select>
                  <div class="layui-form-mid layui-word-aux layui-orange">使用生產或測試（沙盒）網關伺服器處理交易付款?</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">Debug模式</label>
                <div class="layui-input-block">
                  <select name="mpay_debug" required lay-verify="required" lay-verType="alert">
                    <option value="0" <?=set_select('mpay_debug',0)?> <?=isset($mpay_debug)&&$mpay_debug==0?'selected':''?> >否</option>
                    <option value="1" <?=set_select('mpay_debug',1)?> <?=isset($mpay_debug)&&$mpay_debug==1?'selected':''?> >是</option>
                  </select>
                  <div class="layui-form-mid layui-word-aux layui-orange">將交易信息記錄到網站日誌?</div>
                </div>
              </div>
              <div class="layui-form-item required">
                <label class="layui-form-label">支付完成狀態</label>
                <div class="layui-input-block">
                  <select name="mpay_order_status_id" required lay-verify="required" lay-verType="alert">
                    <option value="">請選擇</option>
                    <?php if(isset($order_status_option) && !empty($order_status_option)):?>
                    <?php foreach($order_status_option as $option_id=>$option_text):?>
                    <option value="<?php echo $option_id;?>" <?=set_select('mpay_order_status_id',$option_id)?> <?=isset($mpay_order_status_id)&&$mpay_order_status_id==$option_id?'selected':''?> ><?php echo $option_text;?></option>
                    <?php endforeach;?>
                    <?php endif;?>
                  </select>
                  <div class="layui-form-mid layui-word-aux layui-orange">交易完成后的默認狀態</div>
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
        <?php echo form_close();?>
      </div><!-- layui-card-body -->
    </div><!-- layui-card -->
  </div>
  <!-- col -->
</div><!-- layui-row -->
</div><!-- layui-fluid -->
<?php $this->load->view('common/admin_footer');?>