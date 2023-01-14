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
          <a href="<?=site_url('admin/feedback').'?'.base64_decode($this->input->cookie('url_query'))?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-arrow-left"></i> <span class="layui-hide-xs">返回</span></a>
        </div>
      </div>
    </div><!--layui-card-header-->
    <div class="layui-card-body">
      <div class="layui-row layui-col-space15">
        <div class="layui-col-md-12">
          <table class="layui-table" lay-even>
            <tbody>
              <tr>
                <th width="100"><b>主題</b></th>
                <td><?php echo isset($subject)?$subject:'';?></td>
              </tr>
              <tr>
                <th><b>稱呼</b></th>
                <td><?php echo isset($name)?$name:'';?></td>
              </tr>
              <tr>
                <th><b>聯絡電郵</b></th>
                <td>
                  <a href="mailto:<?php echo isset($email)?$email:'';?>"><?php echo isset($email)?$email:'';?></a>
                  <span class="layui-hide"><?php echo isset($email)?$email:'';?></span>
                </td>
              </tr>
              <tr>
                <th><b>聯絡電話</b></th>
                <td><a href="tel:<?php echo isset($phone)?$phone:'';?>"><?php echo isset($phone)?$phone:'';?></a></td>
              </tr>
              <!--備用字段-->
              <?php if(isset($address) && !empty($address)):?>
              <tr>
                <th><b>聯絡地址</b></th>
                <td><?php echo isset($address)?$address:'';?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th><b>創建於</b></th>
                <td><?php echo $created_at;?></td>
              </tr>
              <tr>
                <th><b>內容</b></th>
                <td><?php echo nl2br($content);?></td>
              </tr>
              <!--備用字段-->
              <?php if(isset($other_content) && !empty($other_content)):?>
              <tr>
                <th><b>其他內容</b></th>
                <td><?php echo isset($other_content)?$other_content:'';?></td>
              </tr>
              <?php endif;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div><!-- layui-card -->
</div><!--layui-col-xs12-->
</div><!-- layui-row -->
</div><!-- layui-fluid -->
<?php $this->load->view('common/admin_footer');?>