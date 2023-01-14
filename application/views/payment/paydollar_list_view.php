<?php $this->load->view('common/front_header');?>
<div class="container">
  <div class="jumper-box text-center mt-3">
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
</div>
<!--container-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
  $('#pay_form').submit();
});
</script>
<?php $this->load->view('common/front_footer');?>