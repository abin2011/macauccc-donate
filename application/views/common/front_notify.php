<?php
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-22 12:59:49
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time: 2019-11-29 15:21:48
 * @email             :  info@clickrweb.com
 * @description       :  前台操作結果提示.
 */
echo $this->session->flashdata('success')?'<div class="alert alert-success message success clearfix"><p>' . $this->session->flashdata('success') . '</p><span class="close"><i class="fas fa-times"></i></span></div>':'';
echo $this->session->flashdata('error')?'<div class="alert alert-danger message errormsg clearfix"><p>' . $this->session->flashdata('error') . '</p><span class="close"><i class="fas fa-times"></i></span></div>':'';
echo isset($error) && !empty($error)?'<div class="alert alert-danger message errormsg clearfix">'.validation_errors('<p>', '</p>').'<span class="close"><i class="fas fa-times"></i></span></div>':'';
echo isset($custom_error) && !empty($custom_error)?'<div class="alert alert-danger message errormsg clearfix"><p>'.$custom_error.'</p><span class="close"><i class="fas fa-times"></i></span></div>':'';
echo isset($custom_warning) && !empty($custom_warning)?'<div class="alert alert-danger message warning clearfix"><p>'.$custom_warning.'</p><span class="close"><i class="fas fa-times"></i></span></div>':'';