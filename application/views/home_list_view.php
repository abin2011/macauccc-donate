<?php $this->load->view('common/front_header');?>
<div class="container">

  <div class="steps-flow mt-3 mb-3">
    <div class="row">
      <div class="col-md-4">
        <div class="item active">
          <div class="icon"><span>1</span><i class="fas fa-check"></i></div>
          <div class="main">
            <div class="cn"><?=lang('donate_flow01');?></div>
          </div>
        </div>
        <!-- item -->
      </div>
      <!-- col -->
      <div class="col-md-4">
        <div class="item">
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

  <form action="<?=site_url('home/modify')?>" id="form_donate" method="post">
    
    <?php $this->load->view('common/front_notify');?>

    <div class="alert alert-warning alert-style mt-3">
      <i class="fas fa-info-circle"></i> <?=lang('donate_alert01');?>
    </div>
    <!-- alert -->

    <div class="step-form-title-style02 mt-3"><span class="cn"><?=lang('donate_block_title');?></span></div>
    <!-- step-form-title-style02 -->

    <div class="step-form-style mt-3">
      <div class="row required">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_money');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="form-group form-radio-money">
            <label class="radio-inline">
              <input type="radio" name="donate_money" value="100" <?=set_radio('donate_money','100')?> <?=isset($donate_money)&&$donate_money==100?'checked':''?>> MOP 100
            </label>
            <label class="radio-inline">
              <input type="radio" name="donate_money" value="500" <?=set_radio('donate_money','500')?> <?=isset($donate_money)&&$donate_money==500?'checked':''?>> MOP 500
            </label>
            <label class="radio-inline">
              <input type="radio" name="donate_money" value="1000" <?=set_radio('donate_money','1000')?> <?=isset($donate_money)&&$donate_money==1000?'checked':''?>> MOP 1000
            </label>
            <label class="radio-inline">
              <input type="radio" name="donate_money" value="other" <?=set_radio('donate_money','other')?> <?=isset($donate_money)&&$donate_money=='other'?'checked':''?> id="donate_money_other_trigger"> <?=lang('donate_block_other');?>
            </label>
          </div>
          <!-- form-group -->
          <div class="form-group <?=!isset($donate_money)||$donate_money!='other'?'donate_money_other':''?>">
            <input type="text" class="form-control" id="donate_money_other" name="donate_money_other" value="<?=set_value('donate_money_other',isset($donate_money_other)?$donate_money_other:'')?>" placeholder="<?=lang('donate_block_money_other');?>" >
            <p class="help-block"><?=lang('donate_alert02');?></p>
          </div>
          <!-- form-group -->
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row required">
        <div class="col-md-3 title text-right"><span>堂會</span></div>
        <!-- col -->
        <div class="col-md-3 main">
          <div class="form-group form-radio-money">
            <select name="donate_church" class="form-control" required>
              <option value="">請選擇堂會</option>
              <?php if(isset($donate_church_option) && !empty($donate_church_option)):?>
              <?php foreach($donate_church_option as $optionText):?>
              <option value="<?php echo $optionText;?>" <?=set_select('donate_church',$optionText)?> <?=isset($donate_church)&&$donate_church==$optionText?'selected':'';?>><?php echo $optionText;?></option>
              <?php endforeach;?>
              <?php endif;?>
            </select>
          </div>
        </div>
        <!-- col -->
      </div>
      <div class="row required">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_item');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row">
            <div class="col-md-12">
              <div class="row checkbox-style02">
                <?php if(isset($donate_item_option) && !empty($donate_item_option)):?>
                <?php foreach($donate_item_option as $item_name):?>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="custom-checkbox">
                      <input type="checkbox" name="donate_item[]" value="<?php echo $item_name;?>" <?=set_checkbox('donate_item[]',$item_name)?> <?=isset($donate_item_array)&&in_array($item_name,$donate_item_array)?'checked':''?>>
                      <span class="checkmark"></span>
                      <?php echo $item_name;?>
                    </label>
                  </div>
                </div>
                <?php endforeach;?>
                <?php endif;?>
                <div class="col-md-3 <?=!isset($donate_item_array)||!in_array(lang('donate_block_other'),$donate_item_array)?'donate_opt_other':''?>">
                  <div class="form-group">
                    <input type="text" class="form-control" id="donate_opt_other" name="donate_item_other" value="<?=set_value('donate_item_other',isset($donate_item_other)?$donate_item_other:'')?>" placeholder="<?=lang('donate_block_item_other');?>" >
                  </div>
                </div>
              </div>
              <!-- row -->
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

    <div class="step-form-title-style02 mt-3"><span class="cn"><?=lang('donate_block_title02');?></span> </div>
    <!-- step-form-title-style02 -->

    <div class="step-form-style mt-3">
      <div class="row required">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_person');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <select name="donate_gender" class="form-control">
                      <option value=""><?=lang('donate_block_select');?></option>
                      <option value="先生" <?=set_select('donate_gender','先生')?> <?=isset($donate_gender)&&$donate_gender=='先生'?'selected':'';?>><?=lang('donate_block_genderM');?></option>
                      <option value="女士" <?=set_select('donate_gender','女士')?> <?=isset($donate_gender)&&$donate_gender=='女士'?'selected':'';?>><?=lang('donate_block_genderF');?></option>
                    </select>
                    <p class="help-block"><?=lang('donate_block_gender');?></p>
                  </div>
                </div>
                <!-- col -->
                <div class="col-md-4">
                  <div class="form-group">
                    <input type="text" name="donate_firstname" value="<?=set_value('donate_firstname',isset($donate_firstname)?$donate_firstname:'')?>" class="form-control">
                    <p class="help-block"><?=lang('donate_block_lname');?></p>
                  </div>
                </div>
                <!-- col -->
                <div class="col-md-4">
                  <div class="form-group">
                    <input type="text" name="donate_lastname" value="<?=set_value('donate_lastname',isset($donate_lastname)?$donate_lastname:'')?>" class="form-control">
                    <p class="help-block"><?=lang('donate_block_fname');?></p>
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
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row required">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_email');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="email" name="donate_email" value="<?=set_value('donate_email',isset($donate_email)?$donate_email:'')?>" class="form-control">
              </div>
            </div>
            <!-- col -->
          </div>
          <!-- row -->
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row required">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_country');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row" id="donate_info_nd">
            <div class="col-md-12 col-xs-12">
              <div class="form-group">
                <select class="form-control" name="donate_country" required>
                  <option value=""><?=lang('donate_block_select');?></option>
                  <?php if(isset($country_region_option) && !empty($country_region_option)):?>
                  <?php foreach ($country_region_option as $item_name):?>
                  <option value="<?php echo $item_name;?>" <?=set_select('donate_country',$item_name)?> <?=isset($donate_country)&&$donate_country==$item_name?'selected':'';?> ><?php echo $item_name;?></option>
                  <?php endforeach;?>
                  <?php endif;?>
                </select>
              </div>
            </div>
            <!-- col -->
          </div>
          <!-- row -->
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row required">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_tel');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="text" name="donate_phone" value="<?=set_value('donate_phone',isset($donate_phone)?$donate_phone:'')?>" class="form-control">
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
                <input type="text" name="donate_address" value="<?=set_value('donate_address',isset($donate_address)?$donate_address:'')?>" class="form-control">
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

    <div class="step-form-title-style02 mt-3"><span class="cn"><?=lang('donate_block_title03');?></span> </div>
    <!-- step-form-title-style02 -->

    <div class="alert alert-success alert-style mt-3">
      <i class="fas fa-info-circle"></i> <?=lang('donate_alert03');?>
    </div>
    <!-- alert -->

    <div class="step-form-style mt-3">
      <div class="row required">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_payment_select');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <?php if(isset($payment_option) && !empty($payment_option)):?>
          <div class="form-group form-radio-payment">
            <?php foreach($payment_option as $item):?>
            <label class="radio-inline">
              <input type="radio" name="payment_method" value="<?php echo $item['payment_key'];?>" <?=set_radio('payment_method',$item['payment_key'])?> <?=isset($payment_method)&&$payment_method==$item['payment_key']?'checked':''?>>
              <figure><img src="<?=base_url($item['main_image'])?>" alt="<?php echo $item['title']?>" class="img-responsive"></figure>
              <span><?php echo $item['title']?></span>
            </label>
            <?php endforeach;?>
          </div>
          <?php endif;?>
        </div><!-- col-md-9 main -->
      </div>
      <!-- row -->
    </div>
    <!-- step-form-style -->

    <div class="step-form-title-style02"><span class="cn"><?=lang('donate_block_title04');?></span></div>
    <!-- step-form-title-style02 -->

    <div class="step-form-agreement mt-3">
      <?php echo isset($terms_user_content)?$terms_user_content:''?>
    </div>
    <!-- step-form-agreement -->

    <div class="step-form-title-style02" style="display:none;"><span class="cn"><?=lang('donate_block_title05');?></span></div>
    <!-- step-form-title-style02 -->

    <div class="step-form-agreement mt-3" style="display:none;">
      <?php echo isset($privacy_policy_content)?$privacy_policy_content:''?>
    </div>
    <!-- step-form-agreement -->

    <div class="step-form-check mt-3">
      <div class="checkbox additional-form-trigger">
        <label class="custom-checkbox">
          <input type="checkbox" name="need_receipt" value="1" <?=set_checkbox('need_receipt','1')?> <?=isset($need_receipt)&&$need_receipt=='1'?'checked':''?>>
          <span class="checkmark"></span>
          <?=lang('donate_block_receipt');?>
        </label>
      </div>
      <!-- checkbox -->
      <div class="step-additional-form form-inline form-group" id="need_receipt" <?=isset($need_receipt)&&$need_receipt=='1'?'style="display:block"':''?>>
        <div class="form-group">
          <label class="custom-radio">
            <input type="radio" name="payment_receipt_type" value="1" <?=set_radio('payment_receipt_type','1',TRUE)?> <?=isset($payment_receipt_type)&&$payment_receipt_type=='1'?'checked':''?>>
            <span class="checkmark"></span>
            <?=lang('donate_block_email02');?>
          </label>
        </div>
        <!-- form-group -->
        <div class="form-group">
          <label class="custom-radio">
            <input type="radio" name="payment_receipt_type" value="2" <?=set_radio('payment_receipt_type','2')?> <?=isset($payment_receipt_type)&&$payment_receipt_type=='2'?'checked':''?>>
            <span class="checkmark"></span>
            <?=lang('donate_block_address02');?>
          </label>
        </div>
        <!-- form-group -->
        <div class="form-group">
          <input type="text" class="form-control" id="payment_receipt_note" name="payment_receipt_note" value="<?=set_value('payment_receipt_note',isset($payment_receipt_note)?$payment_receipt_note:'')?>" placeholder="">
        </div>
        <!-- form-group -->
      </div>
      <!-- step-additional-form -->
      <div class="checkbox additional-form-trigger">
        <label class="custom-checkbox">
          <input type="checkbox" name="need_subscribe" value="1" <?=set_checkbox('need_subscribe','1')?> <?=isset($need_subscribe)&&$need_subscribe=='1'?'checked':''?>>
          <span class="checkmark"></span>
          <?=lang('donate_block_subscribe');?>
        </label>
      </div>
      <!-- checkbox -->
      <div class="step-additional-form form-inline" id="need_subscribe" <?=isset($need_subscribe)&&$need_subscribe=='1'?'style="display:block"':''?>>
        <div class="form-group">
          <label class="custom-radio">
            <input type="radio" name="subscribe_type" value="1" <?=set_radio('subscribe_type','1',TRUE)?> <?=isset($subscribe_type)&&$subscribe_type=='1'?'checked':''?>>
            <span class="checkmark"></span>
            <?=lang('donate_block_email02');?>
          </label>
        </div>
        <!-- form-group -->
        <div class="form-group">
          <label class="custom-radio">
            <input type="radio" name="subscribe_type" value="2" <?=set_radio('subscribe_type','2')?> <?=isset($subscribe_type)&&$subscribe_type=='2'?'checked':''?>>
            <span class="checkmark"></span>
            <?=lang('donate_block_address02');?>
          </label>
        </div>
        <!-- form-group -->
        <div class="form-group">
          <input type="text" class="form-control" id="subscribe_note" name="subscribe_note" value="<?=set_value('subscribe_note',isset($subscribe_note)?$subscribe_note:'')?>">
        </div>
        <!-- form-group -->
      </div>
      <!-- step-additional-form -->
    </div>
    <!-- step-form-check -->

    <div class="step-form-check step-form-check-style02 mt-3">
      <div class="checkbox">
        <label class="custom-checkbox">
          <input type="checkbox" name="agree" value="yes" <?=set_radio('agree','yes')?> <?=isset($agree)&&$agree=='yes'?'checked':''?>>
          <span class="checkmark"></span>
          <?=lang('donate_block_agree');?>
        </label>
      </div>
      <!-- checkbox -->
    </div>

    <div class="step-form-style mt-3" id="verify_code" <?=isset($agree)&&$agree=='yes'?'style="display:block"':''?>>
      <div class="row required">
        <div class="col-md-3 title text-right"><span><?=lang('donate_block_code');?></span></div>
        <!-- col -->
        <div class="col-md-9 main">
          <div class="row">
            <div class="col-md-12">
              <div class="form-inline form-group">
                <div class="form-group">
                  <input type="text" name="authcode" required class="form-control" placeholder="<?=lang('donate_block_code_input');?>" autocomplete="off">
                </div>
                <!-- form-group -->
                <div class="form-group">
                  <div class="step-form-code"><img title="<?=lang('form_code_reload')?>" src="<?php echo site_url('home/captcha');?>" onclick="this.src='<?=site_url('home/captcha')?>?'+Math.random();" class="img-responsive"></div>
                </div>
                <!-- form-group -->
              </div>
              <!-- form-inline -->
            </div>
            <!-- col -->
          </div>
          <!-- row -->
        </div>
        <!-- col -->
      </div>
      <!-- row -->
    </div>

    <div class="step-form-btns text-center mt-3">
      <button type="submit" class="btn btn-primary"><?=lang('donate_block_next');?></button>
    </div>
  </form>
</div>
<script src="<?=base_url('themes/front/vendor/jquery.validation/jquery.validate.min.js')?>"></script>
<script src="<?=base_url('themes/front/vendor/jquery.validation/localization/messages_zh_TW.min.js')?>"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
  // valicator
  $.validator.setDefaults({
    submitHandler: function(form) {
      form.submit();
    }
  });

  $("#form_donate").validate({
    rules: {
      donate_money: {
        required: true
      },
      donate_money_other: {
        digits: true
      },
      'donate_item[]': {
        required: true
      },
      donate_gender: {
        required: true
      },
      donate_lastname: {
        required: true
      },
      donate_firstname: {
        required: true
      },
      donate_email: {
        required: true
      },
      donate_country: {
        required: true
      },
      donate_phone: {
        required: true
      },
      payment_method: {
        required: true
      },
      payment_receipt_note: {
        required: true
      },
      subscribe_note: {
        required: true
      },
      authcode: {
        required: true
      },
      agree: {
        required: true
      }
    },
    messages: {
      donate_money: "<?=lang('donate_validate_money');?>",
      donate_money_other: "<?=lang('donate_validate_money_other');?>",
      'donate_item[]': "<?=lang('donate_validate_item');?>",
      donate_gender: "<?=lang('donate_validate_gender');?>",
      donate_lastname: "<?=lang('donate_validate_lastname');?>",
      donate_firstname: "<?=lang('donate_validate_firstname');?>",
      donate_email: "<?=lang('donate_validate_email');?>",
      donate_country: "<?=lang('donate_validate_country');?>",
      donate_phone: "<?=lang('donate_validate_phone');?>",
      payment_method: "<?=lang('donate_validate_method');?>",
      payment_receipt_note: "<?=lang('donate_validate_payment_receipt');?>",
      subscribe_note: "<?=lang('donate_validate_subscribe');?>",
      authcode: "<?=lang('donate_validate_authcode');?>",
      agree: "<?=lang('donate_validate_agree');?>",
    },
    errorPlacement: function(error, element) {
      if (element.attr("name") == "donate_money" || element.attr("name") == "payment_method" || element.attr("name") == "authcode" || element.attr("name") == "agree") {
        error.insertAfter(element.parent().parent());
      } 
      else if (element.attr("name") == "donate_item[]") {
        error.insertAfter(".checkbox-style02");
      }
      else {
        error.insertAfter(element.parent());
      }
    }
  });

});
</script>
<?php $this->load->view('common/front_footer');?>