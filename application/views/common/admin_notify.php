<?php
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-10 09:27:56
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-09-13 17:13:42
 * @email             :  info@clickrweb.com
 * @description       :  後台操作匯總提示
 */
echo $this->session->flashdata('success')?'<div class="alert alert-success message success"><p>' . $this->session->flashdata('success') . '</p><span class="fas fa-times close"></span></div>':'';
echo $this->session->flashdata('error')?'<div class="alert alert-danger message errormsg"><p>' . $this->session->flashdata('error') . '</p><span class="fas fa-times close"></span></div>':'';
echo isset($error) && !empty($error)?'<div class="alert alert-danger message errormsg">'.validation_errors('<p>', '</p>').'<span class="fas fa-times close"></span></div>':'';
echo isset($custom_error) && !empty($custom_error)?'<div class="alert alert-danger message errormsg"><p>'.$custom_error.'</p><span class="fas fa-times close"></span></div>':'';
echo isset($custom_warning) && !empty($custom_warning)?'<div class="alert alert-danger message warning"><p>'.$custom_warning.'</p><span class="fas fa-times close"></span></div>':'';