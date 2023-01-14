/*
 * File: d:\AppServ\www\step-form\js\application.js
 * Project: d:\AppServ\www\step-form\js
 * Created: 2020-03-12 10:22:53
 * Author: Jason
 * -----
 * Last Modified: 2020-04-20 Monday 12:01:15
 * Modified By: Jason
 * -----
 * Copyright (c) 2020 Clickr
 */


// use jquery
$(document).ready(function(){

  // donate money
  if($(".form-radio-money").length){
    // default
    $("input[name='donate_money']").each(function(){
      if($(this).is(':checked')){
        $(this).parent().addClass('active');
      }
    });
    // other
    if($("#donate_money_other_trigger").is(':checked')){
      $("#donate_money_other_trigger").parent().addClass('active');
      $(".donate_money_other").show();
      $("#donate_money_other").addClass("other-active");
    }
    // change
    $("input[name='donate_money']:radio").change(function() {
      $(this).parent().addClass('active').siblings().removeClass('active');
      // checked
      if($("#donate_money_other_trigger").is(':checked')){
        $(".donate_money_other").fadeIn();
        $("#donate_money_other").addClass("other-active");
      }else{
        $(".donate_money_other").fadeOut();
        $("#donate_money_other").removeClass("other-active");
        $('#donate_money_other').val('');
      }
    });
  }
  
  // donate item
  if($(".donate_opt_other").length){
    var donateItemVal = $(".donate_opt_other").prev().find("input[type='checkbox']");
    // default
    if(donateItemVal.is(':checked')){
      $(".donate_opt_other").show();
      $("#donate_opt_other").addClass("other-active");
    }
    // change
    donateItemVal.change(function(){
      if($(this).is(':checked')){
        $(".donate_opt_other").fadeIn();
        $("#donate_opt_other").addClass("other-active");
      }else{
        $(".donate_opt_other").fadeOut();
        $("#donate_opt_other").removeClass("other-active");
        $('#donate_opt_other').val('');
      }
    });
  }
  
  // payment
  if($(".form-radio-payment").length){
    var paymentTrigger = $("input[name='payment_method']");
    // default
    paymentTrigger.each(function(){
      if($(this).is(':checked')){
        $(this).parent().addClass('active');
      }
    });
    // change
    $("input[name='payment_method']:radio").change(function() {
      $(this).parent().addClass('active').siblings().removeClass('active');
    });
  }
  
  // additional
  if($(".additional-form-trigger").length){

    var need_receipt_trigger = $("input[name='need_receipt']"),
        need_subscribe_trigger = $("input[name='need_subscribe']"),
        donate_email = $("input[name='donate_email']");

    // default
    if(need_receipt_trigger.is(':checked')){
      $("#need_receipt").show();
    }
    if(need_subscribe_trigger.is(':checked')){
      $("#need_subscribe").show();
    }
    // change
    need_receipt_trigger.change(function(){
      $("input[name='payment_receipt_type']").prop('checked', false);
      $('#payment_receipt_note').val('');
      if($(this).is(':checked')){
        $("#need_receipt").fadeIn();
        // email default
        if(donate_email.val() !== '' && $("input[name='payment_receipt_type']:checked").val()==1){
          $('#payment_receipt_note').val(donate_email.val());
        }else{
          $('#payment_receipt_note').val('');
        }
        // radio change
        $("input[name='payment_receipt_type']").change(function(){
          if($(this).val()==2){
            $('#payment_receipt_note').val('');
          }else{
            $('#payment_receipt_note').val(donate_email.val());
          }
        });
      }else{
        $("#need_receipt").fadeOut();
        $('#payment_receipt_note').val('');
      }
    });
    need_subscribe_trigger.change(function(){
      $("input[name='subscribe_type']").prop('checked', false);
      $('#subscribe_note').val('');
      if($(this).is(':checked')){
        $("#need_subscribe").fadeIn();
        // email default
        if(donate_email.val() !== '' && $("input[name='subscribe_type']:checked").val()==1){
          $('#subscribe_note').val(donate_email.val());
        }else{
          $('#subscribe_note').val('');
        }
        // radio change
        $("input[name='subscribe_type']").change(function(){
          if($(this).val()==2){
            $('#subscribe_note').val('');
          }else{
            $('#subscribe_note').val(donate_email.val());
          }
        });
      }else{
        $("#need_subscribe").fadeOut();
        $('#subscribe_note').val('');
      }
    });
  }
  
  // agreement
  if($("input[name='agree']").length){
    var agreementTrigger = $("input[name='agree']");
    // default
    if(agreementTrigger.is(':checked')){
      $("#verify_code").show();
    }
    // change
    agreementTrigger.change(function(){
      if($(this).is(':checked')){
        $("#verify_code").fadeIn();
      }else{
        $("#verify_code").fadeOut();
      }
    });
  }
  
});