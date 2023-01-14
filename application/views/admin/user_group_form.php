<?php $this->load->view('common/admin_header');?>
<div class="layui-fluid">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
      <?php $this->load->view('common/admin_notify');?>
      <div class="layui-card">
        <div class="layui-card-header">
          <div class="layui-row pt-1 pb-1">
            <div class="layui-col-xs6"><h3><?php echo $CI_page_title;?></h3></div>
            <div class="layui-col-xs6 text-right">
              <a href="<?=site_url('admin/user_group').'?'.base64_decode($this->input->cookie('url_query'))?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-arrow-left"></i> <span class="layui-hide-xs">返回</span></a>
            </div>
          </div>
        </div><!-- layui-card-header -->
        <div class="layui-card-body">
          <form id="form" action="<?=site_url('admin/user_group/modify');?>" method="post" class="layui-form">
            <input type="hidden" name="edit_id" value='<?=set_value('edit_id',isset($edit_id)?$edit_id:'')?>' />
            <div class="layui-form-item required">
              <label class="layui-form-label">用戶組</label>
              <div class="layui-input-block">
                <input type="text" class="layui-input" name="name" value="<?=set_value('name',isset($name)?$name:'')?>" required lay-verify="required" lay-verType="alert"/>
              </div>
            </div><!-- layui-form-item -->
            <div class="layui-form-item required">
              <label class="layui-form-label">操作權限</label>
              <div class="layui-input-block">
                <?php if(isset($permissions) && is_array($permissions)):?>
                <div class="control-selector">
                  <input type="radio" title="全選" name="cs" lay-filter="checkbox-all" data-checkbox="permission[]">
                  <input type="radio" title="反選" name="cs" lay-filter="checkbox-reverse" data-checkbox="permission[]">
                  <!-- <button type="button" class="layui-btn layui-btn-primary" id="checkbox-all" data-checkbox="permission[]">全選</button>
                  <button type="button" class="layui-btn layui-btn-primary" id="checkbox-reverse" data-checkbox="permission[]">反選</button> -->
                </div>
                <div class="c-scroll-box">
                  <?php foreach($permissions as $key=>$per):?>
                  <div class="item">
                    <input type="checkbox" title="<?=isset($perstring)&&isset($perstring[$per])?$perstring[$per]:$per?>" lay-skin="primary" <?=isset($permission) && in_array($per,$permission)?'checked':'' ?> <?=set_checkbox('permission[]',$per)?> value="<?=$per?>" name="permission[]" lay-verify="choiceRequired" lay-verType="alert">
                  </div>
                  <?php endforeach;?>
                </div>
                <?php endif;?>
              </div>
            </div>
            <!-- layui-form-item -->
            <div class="layui-controls">
              <button lay-submit lay-filter="form-user_group" class="layui-btn layui-btn-with-icon layui-btn-lg" name="btn_save"><i class="fas fa-check"></i>存儲</button>
              <button type="reset" class="layui-btn layui-btn-primary layui-btn-lg">重置</button>
            </div>
            <!-- layui-controls -->
          </form> 
        </div><!-- layui-card-body -->
      </div><!-- layui-card -->
    </div>
    <!-- col -->
  </div><!-- layui-row -->
</div><!-- layui-fluid -->
<script type="text/javascript" charset="utf-8">
layui.use(['form','element','jquery'],function(){
  var form = layui.form;
  //添加第三方驗證選擇框.
  form.verify({
    choiceRequired: function(value,item){
      var $ = layui.$;
      var verifyName=$(item).attr('name');
      var verifyType=$(item).attr('type');
      var formElem=$(item).parents('.layui-form');//获取当前所在的form元素，如果存在的话
      var verifyElem=formElem.find("input[name='"+verifyName+"']");//获取需要校验的元素
      var isTrue= verifyElem.is(':checked');//是否命中校验
      var focusElem = verifyElem.next().find('i.layui-icon');//焦点元素
      if(!isTrue || !value){
        //定位焦点
        focusElem.css(verifyType=='radio'?{"color":"#FF5722"}:{"border-color":"#FF5722"});
        //对非输入框设置焦点
        focusElem.first().attr("tabIndex","1").css("outline","0").blur(function() {
          focusElem.css(verifyType=='radio'?{"color":""}:{"border-color":""});
        }).focus();
        return '必填项不能为空';
      }
    }
  });
  // 全選
  form.on('radio(checkbox-all)', function(data){
    var checkbox_name=$(this).data('checkbox');
    if(data.value=='on'){
      $("input[name='"+checkbox_name+"']").prop("checked", true);
      form.render('checkbox');
    }
  });
  // 反選
  form.on('radio(checkbox-reverse)', function(data){
    var checkbox_name=$(this).data('checkbox');
    if(data.value=='on'){
      $("input[name='"+checkbox_name+"']").prop("checked", function(index, attr){
        return !attr;
      });
      form.render('checkbox');
    }
  });
});
</script>
<?php $this->load->view('common/admin_footer');?>