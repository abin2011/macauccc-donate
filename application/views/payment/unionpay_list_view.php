<?php $this->load->view('common/front_header');?>
<div class="container">
  <div class="jumper-box text-center mt-3">
    <?php if(isset($unionpay_test) && intval($unionpay_test)==1):?>
    <div class="alert alert-info alert-dismissible">
      <i class="fas fa-info-circle"></i>警告:該支付模式為＂沙盒測試＂模式,您的賬戶將不會扣費.(Warning: The payment gateway is in 'Sandbox Mode'. Your account will not be charged.)
    </div>
    <?php endif;?>
    <div class="jumper-title">
      <i class="fas fa-exclamation-circle"></i> <?=lang('donate_jumper_title');?>
      <span><?php echo $payment['title']?></span> <?=lang('donate_jumper_title02');?>
    </div>
    <div class="jumper-calling">
      <span><?=lang('donate_call');?></span>
      <span class="name"><?php echo isset($donate_name)?$donate_name:'';?></span>
      <span class="gender"><?php echo isset($donate_gender)?$donate_gender:'';?></span>
    </div>
    <?php if(isset($payment) && !empty($payment)):?>
    <div class="jumper-payment">
      <h5><?=lang('donate_jumper_pay');?></h5>
      <div class="jumper-payment-detail">
        <img src="<?=base_url($payment['main_image'])?>" alt="<?php echo $payment['title']?>" class="img-responsive">
        <span><?php echo $payment['title']?></span>
      </div>
    </div>
    <?php endif;?>
  </div>
  <div class="hidden">
    <?php if(isset($payment_data) && !empty($payment_data) && isset($payment_action) && !empty($payment_action)):?>
    <form action="<?php echo $payment_action;?>" method="post" id="pay_form" name="pay_form" accept-charset="UTF-8">
      <?php foreach ($payment_data as $key => $value):?>
      <input type="hidden" name="<?php echo $key;?>" id="<?php echo $key;?>" value="<?php echo $value;?>" />
      <?php endforeach?>
    </form>
    <?php endif;?>
  </div>
  <!-- jumper-box -->
</div>
<!-- container -->
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
  $('#pay_form').submit();
});
</script>
<?php $this->load->view('common/front_footer');?>