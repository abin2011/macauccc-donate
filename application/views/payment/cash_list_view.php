<?php $this->load->view('common/front_header');?>
<div class="container text-center">
  <div class="jumper-box text-center mt-3">
    <div class="jumper-title">
      <i class="fas fa-exclamation-circle"></i> 
      <span><?=lang('donate_jumper_cash');?></span>
    </div>
  </div>
  <!-- jumper-box  -->

  <?php if(isset($payment_data) && !empty($payment_data) && isset($payment_action) && !empty($payment_action)):?>
  <form action="<?php echo $payment_action;?>" method="post" id="pay_form" name="pay_form" accept-charset="UTF-8">
    <?php foreach ($payment_data as $key => $value):?>
    <input type="hidden" name="<?php echo $key;?>" id="<?php echo $key;?>" value="<?php echo $value;?>" />
    <?php endforeach?>
    <button type='submit' class="btn btn-style">點擊提交</button>
  </form>
  <?php endif;?>

</div>
<!--container-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
  // $('#pay_form').submit();
});
</script>
<?php $this->load->view('common/front_footer');?>