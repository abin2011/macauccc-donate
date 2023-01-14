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
          <a href="<?=site_url('admin/slideshow').'?'.base64_decode($this->input->cookie('url_query'))?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-arrow-left"></i> <span class="layui-hide-xs">返回</span></a>
        </div>
      </div>
    </div><!-- layui-card-header -->
    <div class="layui-card-body">
      <form id="form" action="<?=site_url('admin/slideshow/modify');?>" method="post" class="layui-form">
      <input type="hidden" name="edit_id" value="<?=set_value('edit_id',isset($edit_id)?$edit_id:'')?>" />
      <div class="layui-tab">
        <ul class="layui-tab-title">
          <li class="layui-this"><i class="fas fa-cogs"></i> 設定</li>
          <li><i class="fas fa-flag"></i> 语言</li>
        </ul>
        <!-- layui-tab-title -->
        <div class="layui-tab-content pl-0 pr-0">
          <div class="layui-tab-item layui-show">
            <?php $img_main_image=set_value('main_image',isset($main_image) && file_exists($main_image)?$main_image:'');?>
            <?php $show_image=helper_create_thumb($img_main_image);?>
            <div class="layui-form-item required mt-1">
              <label class="layui-form-label">繁體版圖片</label>
              <div class="layui-input-block">
                <a id="thumbnail_main_image" href="<?=base_url().(!empty($img_main_image)?$img_main_image:'themes/admin/img/noimage.png')?>" class="thumbnail text-center" data-lightbox="imageCover" data-title="幻燈片圖片">
                  <img id="img_main_image" src="<?=base_url($show_image)?>">
                  <div class="popup-box"><i class="fas fa-search"></i></div>
                </a>
                <input id="old_main_image" type="hidden" name="main_image" value="<?php echo $img_main_image;?>" required lay-verify="required" lay-verType="alert">
                <div class="layui-progress layui-progress-big mt-1 layui-hide" lay-showpercent="true" lay-filter="progress_main_image" style="width:200px;margin-top:-18px;">
                  <div class="layui-progress-bar layui-bg-blue" lay-percent="0%"></div>
                </div><!-- layui-progress -->
              </div>
              <div class="layui-input-block mt-1">
                <div class="layui-row layui-col-space10" style="width: 210px;">
                  <div class="layui-col-xs6">
                    <button id="btn_main_image" type="button" data-target="main_image" class="single_upload_group layui-btn layui-btn-warm layui-btn-fluid layui-btn-with-icon">
                      <i class="fas fa-cloud-upload-alt"></i>上傳
                    </button>
                  </div>
                  <div class="layui-col-xs6">
                    <button type="button" data-upload_clear="main_image" class="layui-btn layui-btn-fluid layui-btn-with-icon layui-btn-danger <?=empty($img_main_image)?'layui-hide':''?>">
                      <i class="fas fa-trash"></i>刪除
                    </button>
                  </div>
                </div>
              </div>
              <div class="layui-input-block mt-1">
                <div class="layui-form-mid layui-word-aux"><i class="fas fa-info-circle"></i> 建議幻燈片尺寸：1700x580 (像素)</div>
              </div>
            </div><!-- layui-form-item -->
            <div class="layui-form-item layui-hide">
              <label class="layui-form-label">頁面</label>
              <div class="layui-input-block">
                <?php if(isset($controller_option) && !empty($controller_option)):?>
                <select name="page_controller">
                  <?php foreach($controller_option as $key => $value):?>
                  <option value="<?php echo $key;?>" <?=set_select('page_controller',$key)?> <?=isset($page_controller)&&$page_controller==$key?'selected':''?>><?php echo $value;?></option>
                  <?php endforeach;?>
                </select>
                <?php endif;?>
                <div class="layui-form-mid layui-word-aux layui-orange">[通用右側廣告],如果有頁面右側廣告沒有創建,則使用通用右側廣告</div>
              </div>
            </div>
            <div class="layui-form-item layui-hide">
              <label class="layui-form-label">位置</label>
              <div class="layui-input-block">
                <?php if(isset($position_option) && !empty($position_option)):?>
                <select name="page_position">
                  <?php foreach($position_option as $key => $value):?>
                  <option value="<?php echo $key;?>" <?=set_select('page_position',$key)?> <?=isset($page_position)&&$page_position==$key?'selected':''?>><?php echo $value;?></option>
                  <?php endforeach;?>
                </select>
                <?php endif;?>
              </div>
            </div>
            <div class="layui-form-item layui-hide">
              <label class="layui-form-label">手機版獨立圖片</label>
              <div class="layui-input-block">
                <input type="radio" class="radio" lay-filter="support_mobile" name="support_mobile" value="1" <?=set_radio('support_mobile', '1', TRUE); ?> <?=isset($support_mobile) && $support_mobile==1?'checked':''?> title="否" />
                <input type="radio" class="radio" lay-filter="support_mobile" name="support_mobile" value="2" <?=set_radio('support_mobile', '2'); ?> <?=isset($support_mobile) && $support_mobile==2?'checked':''?> title="是" />
              </div>
            </div>
            <div id="mobile_image_control layui-hide" class="layui-form-item <?php echo isset($support_mobile)&&$support_mobile==2?'':'layui-hide';?>">
              <?php $show_mobile_image=set_value('mobile_image',isset($mobile_image) && file_exists($mobile_image)?$mobile_image:'');?>
              <?php $thumb_mobile_image=helper_create_thumb($show_mobile_image);?>
              <label class="layui-form-label">手機版圖片</label>
              <div class="layui-input-block">
                <a id="thumbnail_mobile_image" href="<?=base_url().(!empty($show_mobile_image)?$show_mobile_image:'themes/admin/img/noimage.png')?>" class="thumbnail text-center" data-lightbox="imageCover" data-title="手機版圖片">
                  <img id="img_mobile_image" src="<?=base_url($thumb_mobile_image)?>">
                  <div class="popup-box"><i class="fas fa-search"></i></div>
                </a>
                <input id="old_mobile_image" type="hidden" name="mobile_image" value="<?php echo $show_mobile_image;?>">
                <div class="layui-progress layui-progress-big mt-1 layui-hide" lay-showpercent="true" lay-filter="progress_mobile_image" style="width:200px;margin-top:-18px;">
                  <div class="layui-progress-bar layui-bg-blue" lay-percent="0%"></div>
                </div><!-- layui-progress -->
              </div>
              <div class="layui-input-block mt-1">
                <div class="layui-row layui-col-space10" style="width: 210px;">
                  <div class="layui-col-xs6">
                    <button id="btn_mobile_image" type="button" data-target="mobile_image" class="single_upload_group layui-btn layui-btn-warm layui-btn-fluid layui-btn-with-icon">
                      <i class="fas fa-cloud-upload-alt"></i>上傳
                    </button>
                  </div>
                  <div class="layui-col-xs6">
                    <button type="button" data-upload_clear="mobile_image" class="layui-btn layui-btn-fluid layui-btn-with-icon layui-btn-danger <?=empty($show_mobile_image)?'layui-hide':''?>">
                      <i class="fas fa-trash"></i>刪除
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="layui-form-item">
              <?php $show_main_image_cn=set_value('main_image_cn',isset($main_image_cn) && file_exists($main_image_cn)?$main_image_cn:'');?>
              <?php $thumb_main_image_cn=helper_create_thumb($show_main_image_cn);?>
              <label class="layui-form-label">簡體版圖片</label>
              <div class="layui-input-block">
                <a id="thumbnail_main_image_cn" href="<?=base_url().(!empty($show_main_image_cn)?$show_main_image_cn:'themes/admin/img/noimage.png')?>" class="thumbnail text-center" data-lightbox="imageCover" data-title="手機版圖片">
                  <img id="img_main_image_cn" src="<?=base_url($thumb_main_image_cn)?>">
                  <div class="popup-box"><i class="fas fa-search"></i></div>
                </a>
                <input id="old_main_image_cn" type="hidden" name="main_image_cn" value="<?php echo $show_main_image_cn;?>">
                <div class="layui-progress layui-progress-big mt-1 layui-hide" lay-showpercent="true" lay-filter="progress_main_image_cn" style="width:200px;margin-top:-18px;">
                  <div class="layui-progress-bar layui-bg-blue" lay-percent="0%"></div>
                </div><!-- layui-progress -->
              </div>
              <div class="layui-input-block mt-1">
                <div class="layui-row layui-col-space10" style="width: 210px;">
                  <div class="layui-col-xs6">
                    <button id="btn_main_image_cn" type="button" data-target="main_image_cn" class="single_upload_group layui-btn layui-btn-warm layui-btn-fluid layui-btn-with-icon">
                      <i class="fas fa-cloud-upload-alt"></i>上傳
                    </button>
                  </div>
                  <div class="layui-col-xs6">
                    <button type="button" data-upload_clear="main_image_cn" class="layui-btn layui-btn-fluid layui-btn-with-icon layui-btn-danger <?=empty($show_main_image_cn)?'layui-hide':''?>">
                      <i class="fas fa-trash"></i>刪除
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="layui-form-item">
              <?php $show_main_image_en=set_value('main_image_en',isset($main_image_en) && file_exists($main_image_en)?$main_image_en:'');?>
              <?php $thumb_main_image_en=helper_create_thumb($show_main_image_en);?>
              <label class="layui-form-label">英文版圖片</label>
              <div class="layui-input-block">
                <a id="thumbnail_main_image_en" href="<?=base_url().(!empty($show_main_image_en)?$show_main_image_en:'themes/admin/img/noimage.png')?>" class="thumbnail text-center" data-lightbox="imageCover" data-title="手機版圖片">
                  <img id="img_main_image_en" src="<?=base_url($thumb_main_image_en)?>">
                  <div class="popup-box"><i class="fas fa-search"></i></div>
                </a>
                <input id="old_main_image_en" class="single_upload_group" type="hidden" name="main_image_en" value="<?php echo $show_main_image_en;?>">
                <div class="layui-progress layui-progress-big mt-1 layui-hide" lay-showpercent="true" lay-filter="progress_main_image_en" style="width:200px;margin-top:-18px;">
                  <div class="layui-progress-bar layui-bg-blue" lay-percent="0%"></div>
                </div><!-- layui-progress -->
              </div>
              <div class="layui-input-block mt-1">
                <div class="layui-row layui-col-space10" style="width: 210px;">
                  <div class="layui-col-xs6">
                    <button id="btn_main_image_en" type="button" data-target="main_image_en" class="single_upload_group layui-btn layui-btn-warm layui-btn-fluid layui-btn-with-icon">
                      <i class="fas fa-cloud-upload-alt"></i>上傳
                    </button>
                  </div>
                  <div class="layui-col-xs6">
                    <button type="button" data-upload_clear="main_image_en" class="layui-btn layui-btn-fluid layui-btn-with-icon layui-btn-danger <?=empty($show_main_image_en)?'layui-hide':''?>">
                      <i class="fas fa-trash"></i>刪除
                    </button>
                  </div>
                </div>
              </div>
            </div>
              <div class="layui-form-item">
                <?php $show_main_image_pt=set_value('main_image_pt',isset($main_image_pt) && file_exists($main_image_pt)?$main_image_pt:'');?>
                <?php $thumb_main_image_pt=helper_create_thumb($show_main_image_pt);?>
                <label class="layui-form-label">葡文版圖片</label>
                <div class="layui-input-block">
                  <a id="thumbnail_main_image_pt" href="<?=base_url().(!empty($show_main_image_pt)?$show_main_image_pt:'themes/admin/img/noimage.png')?>" class="thumbnail text-center" data-lightbox="imageCover" data-title="手機版圖片">
                    <img id="img_main_image_pt" src="<?=base_url($thumb_main_image_pt)?>">
                    <div class="popup-box"><i class="fas fa-search"></i></div>
                  </a>
                  <input id="old_main_image_pt" class="single_upload_group" type="hidden" name="main_image_pt" value="<?php echo $show_main_image_pt;?>">
                  <div class="layui-progress layui-progress-big mt-1 layui-hide" lay-showpercent="true" lay-filter="progress_main_image_pt" style="width:200px;margin-top:-18px;">
                    <div class="layui-progress-bar layui-bg-blue" lay-percent="0%"></div>
                  </div><!-- layui-progress -->
                </div>
                <div class="layui-input-block mt-1">
                  <div class="layui-row layui-col-space10" style="width: 210px;">
                    <div class="layui-col-xs6">
                      <button id="btn_main_image_pt" type="button" data-target="main_image_pt" class="single_upload_group layui-btn layui-btn-warm layui-btn-fluid layui-btn-with-icon">
                        <i class="fas fa-cloud-upload-alt"></i>上傳
                      </button>
                    </div>
                    <div class="layui-col-xs6">
                      <button type="button" data-upload_clear="main_image_pt" class="layui-btn layui-btn-fluid layui-btn-with-icon layui-btn-danger <?=empty($show_main_image_pt)?'layui-hide':''?>">
                        <i class="fas fa-trash"></i>刪除
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            <div class="layui-form-item">
              <label class="layui-form-label">鏈接</label>
              <div class="layui-input-block">
                <input type="text" class="layui-input" name="target_link" value="<?=set_value('target_link',isset($target_link)?$target_link:'')?>"/>
                <div class="layui-form-mid layui-word-aux layui-orange">如果是本網站連接,則直接填寫 域名後面[company/view/141]網址段,連接自動轉換多語言連接.</div>
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">鏈接打開方式</label>
              <div class="layui-input-block">
                <input type="radio" class="radio" name="target_method" value="1" <?=set_radio('target_method',1,TRUE)?> <?=isset($target_method) && $target_method==1?'checked':''?> title="本窗口">
                <input type="radio" class="radio" name="target_method" value="2" <?=set_radio('target_method',2)?> <?=isset($target_method) && $target_method==2?'checked':''?> title="新窗口">
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">排序</label>
              <div class="layui-input-block">
                <input type="text" class="layui-input" name="sort_order" value='<?=set_value('sort_order',isset($sort_order)?$sort_order:0)?>'/>
                <div class="layui-form-mid layui-word-aux layui-orange">按順序排列,0-9 由小到大</div>
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">創建於</label>
              <div class="layui-input-block">
                <input type="text" class="layui-input date-time-picker" name="created_at" value='<?=set_value('created_at',isset($created_at)&&!empty($created_at)?$created_at:date($default_admin_time))?>'/>
              </div>
            </div><!-- layui-form-item -->
            <div class="layui-form-item required">
              <label class="layui-form-label">狀態</label>
              <div class="layui-input-block">
                <input type="radio" name="status" value="1" <?=set_radio('status', '1', TRUE); ?> <?=isset($status) && $status==1?'checked':''?> title="啓用" />
                <input type="radio" name="status" value="2" <?=set_radio('status', '2'); ?> <?=isset($status) && $status==2?'checked':''?> title="停用" />
              </div>
            </div><!-- layui-form-item -->
          </div><!-- layui-tab-item -->
          <div class="layui-tab-item">
            <!--sub-tab start-->
            <div class="layui-tab sub-tab layui-tab-brief">
              <ul class="layui-tab-title">
                <?php foreach($lang_array as $lang_id=>$lang_name):?>
                <li <?=isset($default_language)&&$default_language==$lang_id?'class="layui-this"':''?>><?php echo $lang_name;?></li>
                <?php endforeach;?>
              </ul>
              <div class="layui-tab-content">
                <?php foreach($lang_array as $lang_id=>$lang_name):?>
                <div class="layui-tab-item <?=isset($default_language)&&$default_language==$lang_id?'layui-show':''?>" id="tabsCon<?=$lang_id?>">
                  <?php if(count($lang_array)>1):?>
                  <div class="layui-form-item">
                    <label class="layui-form-label">多語言内容同步</label>
                    <div class="layui-input-block">
                      <a href="javascript:;" class="layui-btn layui-btn-warm layui-btn-with-icon sync_lang" lang-num="<?=count($lang_array);?>" lang-id="<?=$lang_id?>" title="多語言内容同步"><i class="fas fa-sync"></i>同步</a>
                    </div>
                  </div>
                  <?php endif;?>
                  <!-- layui-form-item -->
                  <div class="layui-form-item">
                    <label class="layui-form-label flag-lang-<?=$lang_id?>"><span class="flag-icon mr-1"></span>標題</label>
                    <div class="layui-input-block">
                      <input type="text" value="<?=set_value('descriptions['.$lang_id.'][title]',isset($descriptions[$lang_id]['title'])?$descriptions[$lang_id]['title']:'')?>" name="descriptions[<?=$lang_id?>][title]" class="layui-input">
                    </div>
                  </div><!-- layui-form-item -->
                  <div class="layui-form-item layui-hide">
                    <label class="layui-form-label flag-lang-<?=$lang_id?>"><span class="flag-icon mr-1"></span>簡介</label>
                    <div class="layui-input-block">
                      <input type="text" value="<?=set_value('descriptions['.$lang_id.'][introduction]',isset($descriptions[$lang_id]['introduction'])?$descriptions[$lang_id]['introduction']:'')?>" name="descriptions[<?=$lang_id?>][introduction]" class="layui-input">
                    </div>
                  </div><!-- layui-form-item -->
                  <div class="layui-form-item layui-hide">
                    <label class="layui-form-label flag-lang-<?=$lang_id?>"><span class="flag-icon mr-1"></span>內容</label>
                    <div class="layui-input-block">
                      <textarea id="description<?=$lang_id?>" class="details" editer="uediter" name="descriptions[<?=$lang_id?>][content]"><?=set_value('descriptions['.$lang_id.'][content]',isset($descriptions[$lang_id]['content'])?$descriptions[$lang_id]['content']:'')?></textarea>
                    </div>
                  </div><!-- layui-form-item -->
                </div><!-- layui-tab-item -->
                <?php endforeach;?>
              </div>
            </div><!--sub-tab end-->
          </div><!-- layui-tab-item --> 
        </div><!-- layui-tab-content -->
      </div><!-- layui-tab -->
      <div class="layui-controls">
        <button lay-submit class="layui-btn layui-btn-with-icon layui-btn-lg"><i class="fas fa-check"></i>存儲</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-lg">重置</button>
      </div><!-- layui-controls -->
      </form>
    </div><!-- layui-card-body -->
  </div><!-- layui-card -->
</div><!-- col -->
</div><!-- layui-row -->
</div><!-- layui-fluid -->
<script src="<?=base_url('themes/admin/vendor/lightbox2/js/lightbox.min.js')?>"></script>
<script type="text/javascript" charset="utf-8">
layui.use(['form','upload','element','layer','jquery'],function(){
  var upload = layui.upload
  ,element = layui.element
  ,form = layui.form
  ,layer = layui.layer;
  var xhrOnProgress=function(fun) {
    xhrOnProgress.onprogress = fun; //绑定监听
    //使用闭包实现监听绑
    return function() {
      //通过$.ajaxSettings.xhr();获得XMLHttpRequest对象
      var xhr = $.ajaxSettings.xhr();
       //判断监听函数是否为函数
      if (typeof xhrOnProgress.onprogress !== 'function')
        return xhr;
      //如果有监听函数并且xhr对象支持绑定时就把监听函数绑定上去
      if (xhrOnProgress.onprogress && xhr.upload) {
        xhr.upload.onprogress = xhrOnProgress.onprogress;
      }
      return xhr;
    }
  }

  //循環遍歷循環上傳控件
  $('button.single_upload_group').each(function() {
    var data_target = $(this).data('target');
    if(data_target){
      upload.render({
        elem: '#btn_'+data_target
        ,url: "<?php echo site_url('admin/upload/single_upload')?>"
        ,method: 'post'
        ,acceptMime:'image/*'
        ,size:5*1024 //KB unit 
        ,data:{
          'folder'      : encodeURIComponent('uploads/slideshow/')
          ,'element_id' : data_target
          ,'thumb_size' : '200,200'//縮圖寬高
          ,'is_file'    : '0'//是否文件
          ,'token'      : "<?php echo $this->session->userdata('user_token')?>"
        }
        ,xhr:xhrOnProgress
        ,progress:function(value){//上传进度回调 value进度值
          element.progress('progress_'+data_target, value+'%')//设置页面进度条
        }
        ,choose: function(obj){
          $('div[lay-filter="progress_'+data_target+'"]').removeClass('layui-hide');
        }
        ,done: function(result, index, upload){
          element.progress('progress_'+data_target,'0%')
          $('div[lay-filter="progress_'+data_target+'"]').addClass('layui-hide');
          if(!$.isEmptyObject(result) && result.status=='success'){
            var data=result.message;
            $("#old_"+data.element_id).val(data.file_path);
            $("#img_"+data.element_id).attr('src',data.thumb_image);
            if($('#thumbnail_'+data.element_id).length)
              $('#thumbnail_'+data.element_id).attr('href',data.http_path);
            if($("[data-upload_clear='"+data.element_id+"']").length)
              $("[data-upload_clear='"+data.element_id+"']").removeClass('layui-hide');
          }else{
            layer.msg(result.message, {icon: 2,shift:6});
          }
        }
        ,error: function(index, upload){
          console.log("upload single_upload ajax error");
        }
      });
    }
  });

  //刪除圖片
  $('button[data-upload_clear]').click(function() {
    var _self=$(this);
    var target_object=$(this).data('upload_clear');
    var default_img="<?=base_url('themes/admin/img/noimage.png')?>";
    layer.confirm('確定刪除?',{icon: 3, title:'提示'},function(index){
      $.ajax({
        url:"<?=site_url('admin/upload/delete')?>",
        type: 'GET',
        dataType:'json',
        data: {
          'path'   : $("input[name='"+target_object+"']").val()
          ,'token' : "<?php echo $this->session->userdata('user_token')?>"
        }
      })
      .done(function(result) {
        console.log(result);
        if(!$.isEmptyObject(result) && result.status=='success'){
          _self.addClass('layui-hide');
          $("#old_"+target_object).val('');
          $("#img_"+target_object).attr('src',default_img);
          if($('#thumbnail_'+target_object).length)
            $('#thumbnail_'+target_object).attr('href',default_img);
        }else{
          layer.msg(result.message, {icon: 2,shift:6});
        }
      })
      .fail(function() {
        console.log("upload clear ajax error");
      });
      layer.close(index);
    });
  });
  //啟動手機版.
  form.on('radio(support_mobile)', function(data){
    console.log(data.value);
    if(data.value==2){
      $('#mobile_image_control').removeClass('layui-hide');
    }else{
      $('#mobile_image_control').addClass('layui-hide');
    }
  });  
});
</script>
<?php $this->load->view('common/admin_ueditor');?>
<?php $this->load->view('common/admin_footer');?>