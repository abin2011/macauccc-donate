<?php $this->load->view('common/front_header');?>
<div class="container">

  <div class="step-form-finish text-center mt-10 mb-10">

    <?php if(isset($notify) && $notify=='success'):?>
    <div class="icon"><i class="fas fa-check"></i></div>
    <?php echo isset($page_content)?$page_content:'';?>
    <?php else:?>
    <div class="icon icon-fail"><i class="fas fa-times"></i></div>
    <h2 class="mt-3 mb-3"><?php echo lang('operate_error');?></h2>
    <?php endif;?>
    <div class="btn-area"><a href="http://macauccc.org" class="btn btn-primary"><?=lang('donate_back_home');?></a></div>

  </div>
  <!-- step-form-finish -->

</div>
<?php $this->load->view('common/front_footer');?>