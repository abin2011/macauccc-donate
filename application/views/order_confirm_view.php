<?php $this->load->view('common/front_header');?>
<div class="container">

  <div class="steps-flow mt-3 mb-3">
    <div class="row">
      <div class="col-md-4">
        <div class="item active done">
          <div class="icon"><span>1</span><i class="fas fa-check"></i></div>
          <div class="main">
            <div class="cn"><?=lang('donate_flow01');?></div>
          </div>
        </div>
        <!-- item -->
      </div>
      <!-- col -->
      <div class="col-md-4">
        <div class="item active">
          <div class="icon"><span>2</span><i class="fas fa-check"></i></div>
          <div class="main">
            <div class="cn"><?=lang('donate_flow02');?></div>
          </div>
        </div>
        <!-- item -->
      </div>
      <!-- col -->
      <div class="col-md-4">
        <div class="item">
          <div class="icon"><span>3</span><i class="fas fa-check"></i></div>
          <div class="main">
            <div class="cn"><?=lang('donate_flow03');?></div>
          </div>
        </div>
        <!-- item -->
      </div>
      <!-- col -->
    </div>
    <!-- row -->
  </div>
  <!-- steps-flow -->
  
  <?php $this->load->view('common/front_notify');?>
  
  <form action="<?=site_url('order/modify')?>" method="post">
    
    <div class="step-form-title-style02"><span class="cn"><?=lang('donate_block_title');?></span></div>
    <!-- step-form-title-style02 -->

    <div class="step-form-style step-form-confirm mt-3">
      <div class="row">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_money');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="form-group">
            <div class="confirm-info">
              <?php $donate_money = isset($donate_money)&&$donate_money=='other'?$donate_money_other:$donate_money;?>
              <?php echo 'MOP '.$donate_money;?>
            </div>
          </div>
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row">
        <div class="col-md-3 title text-right"><span>堂會</span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="form-group">
            <div class="confirm-info">
              <?php echo isset($donate_church)?$donate_church:'';?>
            </div>
          </div>
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_item');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="form-group">
            <div class="confirm-info">
              <?php if(isset($donate_item_array) && !empty($donate_item_array)):?>
              <?php foreach($donate_item_array as $item_name):?>
              <span class="donate-item-confirm"><?php echo $item_name;?></span>
              <?php endforeach;?>
              <?php endif;?>
              <span class="donate-item-confirm"><?php echo $donate_item_other;?></span>
            </div>
          </div>
        </div>
        <!-- col -->
      </div>
      <!-- row -->
    </div>
    <!-- step-form-style -->

    <div class="step-form-title-style02 mt-3"><span class="cn"><?=lang('donate_block_title02');?></span></div>
    <!-- step-form-title-style02 -->

    <div class="step-form-style step-form-confirm mt-3">
      <div class="row">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_person');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <p class="help-block"><?=lang('donate_block_gender');?></p>
                <div class="confirm-info"><?php echo isset($donate_gender)?$donate_gender:'';?></div>
              </div>
            </div>
            <!-- col -->
            <div class="col-md-4">
              <div class="form-group">
                <p class="help-block"><?=lang('donate_block_lname');?></p>
                <div class="confirm-info"><?php echo isset($donate_firstname)?$donate_firstname:'';?></div>
              </div>
            </div>
            <!-- col -->
            <div class="col-md-4">
              <div class="form-group">
                <p class="help-block"><?=lang('donate_block_fname');?></p>
                <div class="confirm-info"><?php echo isset($donate_lastname)?$donate_lastname:'';?></div>
              </div>
            </div>
            <!-- col -->
          </div>
          <!-- row -->
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_email');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="confirm-info"><?php echo isset($donate_email)?$donate_email:'';?></div>
              </div>
            </div>
            <!-- col -->
          </div>
          <!-- row -->
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_country');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="confirm-info"><?php echo isset($donate_country)?$donate_country:'';?></div>
              </div>
            </div>
            <!-- col -->
          </div>
          <!-- row -->
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_tel');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="confirm-info"><?php echo isset($donate_phone)?$donate_phone:'';?></div>
              </div>
            </div>
            <!-- col -->
          </div>
          <!-- row -->
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_address');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="confirm-info"><?php echo isset($donate_address)?$donate_address:'';?></div>
              </div>
            </div>
            <!-- col -->
          </div>
          <!-- row -->
        </div>
        <!-- col -->
      </div>
      <!-- row -->
    </div>
    <!-- step-form-style -->

    <div class="step-form-title-style02 mt-3"><span class="cn"><?=lang('donate_block_title03');?></span></div>
    <!-- step-form-title-style02 -->

    <div class="alert alert-info alert-style mt-3">
      <i class="fas fa-info-circle"></i> <?=lang('donate_alert03');?>
    </div>
    <!-- alert -->

    <div class="step-form-style step-form-confirm mt-3">
      <div class="row">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_payment_select');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <?php if(isset($payment) && !empty($payment)):?>
          <input type="hidden" name="payment_cover" value="<?php echo $payment['main_image'];?>">
          <div class="form-group form-radio-payment-confirm">
            <label>
              <figure><img src="<?=base_url($payment['main_image'])?>" alt="<?php echo $payment['title']?>" class="img-responsive"></figure>
              <span><?php echo $payment['title']?></span>
            </label>
          </div>
          <?php endif;?>
        </div>
        <!-- col -->
      </div>
      <!-- row -->
    </div>
    <!-- step-form-style -->
  
    <?php if((isset($need_receipt) && $need_receipt==1) || (isset($need_subscribe) && $need_subscribe==1)):?>
    <div class="step-form-title-style02 mt-3"><span class="cn"><?=lang('donate_block_other');?></span></div>
    <div class="step-form-style step-form-confirm mt-3">
      <div class="row <?=!isset($need_receipt)||$need_receipt!=1?'hidden':''?>">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_receipt');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="form-group">
            <div class="confirm-info"><?php echo isset($payment_receipt_note)?$payment_receipt_note:'';?></div>
          </div>
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row <?=!isset($need_subscribe)||$need_subscribe!=1?'hidden':''?>">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_subscribe02');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="form-group">
            <div class="confirm-info"><?php echo isset($subscribe_note)?$subscribe_note:'';?></div>
          </div>
        </div><!-- col -->
      </div>
      <!-- row -->
    </div><!-- step-form-style -->
    <?php endif;?>

    <div class="step-form-btns text-center mt-3">
      <a href="<?=site_url('home')?>" class="btn btn-default"><?=lang('donate_block_prev');?></a>
      <button type="submit" class="btn btn-primary"><?=lang('donate_block_btn_pay');?></button>
    </div>
    <!-- step-form-btns -->
  </form>
</div>
<?php $this->load->view('common/front_footer');?>