<?php $this->load->view('common/front_header');?>
<div class="container">

  <form id="search_form" action="<?=site_url('search')?>" method="get">

    <div class="step-form-search mt-10 mb-10">
      <div class="row required">
        <div class="col-md-3 col-xs-12 title text-right"><span>身份證號碼 Identity Card No.</span></div>
        <!-- col -->
        <div class="col-md-9 col-xs-12">
          <div class="form-group">
            <input type="text" class="form-control" name="student_identity" value="<?=set_value('student_identity',isset($student_identity)?$student_identity:'')?>" required>
          </div>
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row required">
        <div class="col-md-3 col-xs-12 title text-right"><span>出生日期 Date of Birth</span></div>
        <!-- col -->
        <div class="col-md-9 col-xs-12">
          <div class="form-group">
            <input type="text" class="form-control" name="student_birthday" value="<?=set_value('student_birthday',isset($student_birthday)?$student_birthday:'')?>" data-toggle="datepicker" autocomplete="off" required>
          </div>
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="btn-area text-center">
        <input type="reset" class="btn btn-default" value="重置 Reset">
        <input type="submit" class="btn btn-primary" value="查詢 Search">
      </div>
      <!-- btn-area -->
    </div>
    <!-- step-form-search -->
  </form>

  <?php if(isset($student_identity) && !empty($student_identity) && isset($student_birthday) && !empty($student_birthday)):?>
    <div class="step-form-title-style02 mt-3"><span class="cn">搜尋結果</span> <span class="en">Search Result</span></div>
    <!-- step-form-title -->
    <?php if(isset($search_list) && !empty($search_list) && is_array($search_list)):?>
      <?php if(isset($search_list['candidate_no']) && !empty($search_list['candidate_no'])):?>
      <div class="search-result" id="printArea">
        <h3 class="text-center">Colegio de Santa Rosa de Lima English Secondary <br> 聖羅撒英文中學</h3>
        <h2 class="text-center">准考證</h2>
        <table class="table table-bordered table-striped">
          <tbody>
            <tr>
              <th width="40%">考生編號：</th>
              <td><?php echo $search_list['candidate_no'];?></td>
            </tr>
            <tr>
              <th>英文姓名：</th>
              <td><?php echo $search_list['student_name_en'];?></td>
            </tr>
            <tr>
              <th>中文姓名：</th>
              <td><?php echo $search_list['student_name_cn'];?></td>
            </tr>
          </tbody>
        </table>
        <div class="btn-area text-center"><a href="javascript:;" class="btn btn-primary btn-print">列印 Print</a></div>
      </div>
      <?php else:?>
      <div class="no-data text-center mt-3">
        <div class="icon"><i class="fas fa-info"></i></div>
        <div class="content">暫無相關搜尋結果！No related search results</div>
      </div>
      <!-- no-data -->
      <?php endif;?>
    <?php else:?>
    <div class="no-data text-center mt-3">
      <div class="icon"><i class="fas fa-info"></i></div>
      <div class="content">對不起,暫無匹配該身份證號碼和出生日期資料！Sorry, no matching ID number and date of birth!</div>
    </div>
    <!-- no-data -->
    <?php endif;?>
  <?php endif;?>
</div>
<!-- container -->

<script src="<?=base_url('themes/front/vendor/datepicker/datepicker.min.js')?>"></script>
<script src="<?=base_url('themes/front/vendor/datepicker/i18n/datepicker.zh-CN.js')?>"></script>

<script>
  $(document).ready(function(){
    // btn-print
    $('a.btn-print').click(function(){
      window.print();
    });
  });
</script>
<?php $this->load->view('common/front_footer');?>