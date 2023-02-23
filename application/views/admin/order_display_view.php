<?php $this->load->view('common/admin_header');?>
<div class="layui-fluid">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
      <?php $this->load->view('common/admin_notify');?>
      <div class="layui-card">
        <div class="layui-card-header">
          <div class="layui-row pt-1 pb-1">
            <div class="layui-col-xs6"><h3>查看捐款管理詳情 (<?php echo $donate_firstname.' '.$donate_lastname;?>)</h3></div>
            <div class="layui-col-xs6 text-right">
              <a href="javascript:;" class="layui-btn layui-btn-primary layui-btn-with-icon hide" id="btnPrint"><i class="fas fa-print"></i> <span class="layui-hide-xs">打印履歷</span></a>
              <a href="<?=site_url('admin/order').'?'.base64_decode($this->input->cookie('url_query'))?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-arrow-left"></i> <span class="layui-hide-xs">返回</span></a>
            </div>
          </div>
        </div><!-- layui-card-header -->
        <div class="layui-card-body">
          <div class="layui-tab">
            <ul class="layui-tab-title">
              <li class="layui-this"><i class="fas fa-file"></i> 捐款詳情</li>
            </ul>
            <!-- layui-tab-title -->
            <div class="layui-tab-content pl-0 pr-0">
              <div class="layui-tab-item layui-show">
                <h3>捐款相關</h3>
                <table class="layui-table">
                  <tbody>
                    <tr>
                      <td width="20%"><b>訂單編號：</b></td>
                      <td colspan="3"><?php echo isset($number)?$number:''?></td>
                    </tr>
                    <tr>
                      <td width="20%"><b>捐款金額：</b></td>
                      <td colspan="3">MOP <?php echo isset($donate_money)?$donate_money:''?></td>
                    </tr>
                    <tr>
                      <td width="20%"><b>堂會：</b></td>
                      <td colspan="3"><?php echo isset($donate_church)?$donate_church:''?></td>
                    </tr>
                    <tr>
                      <td><b>捐款項目:</b></td>
                      <td colspan="3">
                        <?php if(isset($donate_item_array) && !empty($donate_item_array)):?>
                        <?php foreach($donate_item_array as $item_name):?>
                        <span class="donate-item-confirm"><?php echo $item_name;?></span>
                        <?php endforeach;?>
                        <?php endif;?>
                        <span class="donate-item-confirm"><?php echo $donate_item_other;?></span>
                      </td>
                    </tr>
                    <tr>
                      <td><b>狀態：</b></td>
                      <td colspan="3">
                        <span style="margin-right: 15px;"><?php echo isset($status_format)?$status_format:''?></span>
                        <?php if(isset($status) && $status==1):?>
                        <a href="<?=site_url('admin/order/edit/'.$id).'?field=status&value=2'?>" class="layui-btn layui-btn-danger layui-btn-sm">標記為已處理</a>
                        <?php elseif(isset($status) && $status==2):?>
                        <a href="<?=site_url('admin/order/edit/'.$id).'?field=status&value=1'?>" class="layui-btn layui-btn-sm">標記為待處理</a>
                        <?php endif;?>
                      </td>
                    </tr>
                    <tr>
                      <td><b>編輯於：</b></td>
                      <td colspan="3"><?php echo isset($updated_at)?$updated_at:''?></td>
                    </tr>
                    <tr>
                      <td><b>提交於：</b></td>
                      <td colspan="3"><?php echo isset($created_at)?$created_at:''?></td>
                    </tr>
                    <tr>
                      <td><b>支付狀態：</b></td>
                      <td colspan="3">
                        <span class="layui-badge layui-bg-<?=$order_status_class[$order_status_id]?>"><?=helper_type_parameter('order_status_option',$order_status_id)?></span>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <h3>個人資料</h3>
                <table class="layui-table">
                  <tbody>
                    <tr>
                      <td width="20%"><b>捐款人：</b></td>
                      <td>
                        <div class="layui-row">
                          <div class="layui-col-md4">稱呼：<br><strong><?php echo isset($donate_gender)?$donate_gender:''?></strong></div>
                          <div class="layui-col-md4">姓氏：<br><strong><?php echo isset($donate_firstname)?$donate_firstname:''?></strong></div>
                          <div class="layui-col-md4">名字：<br><strong><?php echo isset($donate_lastname)?$donate_lastname:''?></strong></div>
                        </div>
                        <!-- layui-row -->
                      </td>
                    </tr>
                    <tr>
                      <td><b>電郵地址：</b></td>
                      <td><?php echo isset($donate_email)?$donate_email:''?></td>
                    </tr>
                    <tr>
                      <td><b>國家/地區：</b></td>
                      <td><?php echo isset($donate_country)?$donate_country:''?></td>
                    </tr>
                    <tr>
                      <td><b>電話：</b></td>
                      <td><?php echo isset($donate_phone)?$donate_phone:''?></td>
                    </tr>
                    <tr>
                      <td><b>地址：</b></td>
                      <td><?php echo isset($donate_address)?$donate_address:''?></td>
                    </tr>
                  </tbody>
                </table>

                <h3>支付方式</h3>
                <table class="layui-table">
                  <tbody>
                    <tr>
                      <td width="20%"><b>支付方式：</b></td>
                      <td><?php echo isset($payment_method)?$payment_method:''?></td>
                    </tr>
                  </tbody>
                </table>

                <?php if((isset($need_receipt) && $need_receipt==1) || (isset($need_subscribe) && $need_subscribe==1)):?>
                <h3>其他</h3>
                <table class="layui-table">
                  <tbody>
                    <tr>
                      <td width="20%"><b>寄發收據：</b></td>
                      <td>
                        <?php 
                          if(isset($payment_receipt_type) && $payment_receipt_type==1){
                            echo '(電郵)';
                          }elseif(isset($payment_receipt_type) && $payment_receipt_type==2){
                            echo '(郵寄地址)';
                          }
                        ?>
                        <?php echo isset($payment_receipt_note)?$payment_receipt_note:''?>
                      </td>
                    </tr>
                    <tr>
                      <td><b>同意接收基督教宣道堂之資訊：</b></td>
                      <td>
                        <?php 
                          if(isset($subscribe_type) && $subscribe_type==1){
                            echo '(電郵)';
                          }elseif(isset($subscribe_type) && $subscribe_type==2){
                            echo '(郵寄地址)';
                          }
                        ?>
                        <?php echo isset($subscribe_note)?$subscribe_note:''?>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <?php endif;?>

              </div>
              <!-- layui-tab-item -->
            </div>
            <!-- layui-tab-content -->
          </div>
          <!-- layui-tab -->
        </div>
        <!-- layui-card-body -->
      </div>
      <!-- layui-card -->
    </div>
    <!-- layui-col -->
  </div>
  <!-- layui-row -->
</div>
<!-- layui-fluid -->
<script src="<?=base_url('themes/admin/vendor/lightbox2/js/lightbox.min.js')?>"></script>
<?php $this->load->view('common/admin_footer');?>