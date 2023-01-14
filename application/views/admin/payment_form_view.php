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
          <a href="<?=site_url('admin/payment').'?'.base64_decode($this->input->cookie('url_query'))?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-arrow-left"></i> <span class="layui-hide-xs">返回</span></a>
        </div>
      </div>
    </div><!--layui-card-header-->
    <div class="layui-card-body">
      <form id="form" action="<?=site_url('admin/payment/modify');?>" method="post" class="layui-form">
      <input type="hidden" name="edit_id" value='<?=set_value('edit_id',isset($edit_id)?$edit_id:'')?>' />
      <div class="layui-tab">
        <ul class="layui-tab-title">
          <li class="layui-this"><i class="fas fa-cogs"></i> 設定</li>
          <li><i class="fas fa-flag"></i> 語言</li>
          <!-- <li><i class="fas fa-images"></i> 相冊</li> -->
        </ul>
        <!-- layui-tab-title -->
        <div class="layui-tab-content pl-0 pr-0">
          <div class="layui-tab-item layui-show">
            <?php $img_main_image=set_value('main_image',isset($main_image) && file_exists($main_image)?$main_image:'');?>
            <?php $show_image=helper_create_thumb($img_main_image);?>
            <div class="layui-form-item mt-1">
              <label class="layui-form-label">封面圖</label>
              <div class="layui-input-block">
                <a id="thumbnail_main_image" href="<?=base_url().(!empty($img_main_image)?$img_main_image:'themes/admin/img/noimage.png')?>" class="thumbnail text-center" data-lightbox="imageCover" data-title="封面圖片">
                  <img id="img_main_image" src="<?=base_url($show_image)?>">
                  <div class="popup-box">
                    <i class="fas fa-search"></i>
                  </div>
                </a>
                <input id="old_main_image" type="hidden" name="main_image" value="<?php echo $img_main_image;?>">
                <div id="progress_single_upload" class="layui-progress layui-progress-big mt-1 layui-hide" lay-showpercent="true" lay-filter="progress_single_upload" style="width:200px;margin-top:-18px;">
                  <div class="layui-progress-bar layui-bg-blue" lay-percent="0%"></div>
                </div><!-- layui-progress -->
              </div>
              <div class="layui-input-block mt-1">
                <div class="layui-row layui-col-space10" style="width: 210px;">
                  <div class="layui-col-xs6">
                    <button id="btn_single_upload" type="button" class="layui-btn layui-btn-warm layui-btn-fluid layui-btn-with-icon">
                      <i class="fas fa-cloud-upload-alt"></i>上傳
                    </button>
                  </div>
                  <div class="layui-col-xs6">
                    <button type="button" data-upload_clear="main_image" class="layui-btn layui-btn-fluid layui-btn-with-icon layui-btn-danger <?=!empty($img_main_image)?'':'layui-hide'?>">
                      <i class="fas fa-trash"></i>刪除
                    </button>
                  </div>
                </div>
              </div>
              <div class="layui-input-block mt-1">
                <div class="layui-form-mid layui-word-aux"><i class="fas fa-info-circle"></i> 建議封面圖尺寸：640 x 360 (像素)</div>
              </div>
            </div><!-- layui-form-item -->
            <?php if($this->session->userdata('login_name')=='clickr'):?>
            <div class="layui-form-item required">
              <label class="layui-form-label">支付配置KEY</label>
              <div class="layui-input-block">
                <input type="text" class="layui-input" name="payment_key" value="<?=set_value('payment_key',isset($payment_key)?$payment_key:'')?>" required lay-verify="required" lay-verType="alert"/>
                <div class="layui-form-mid layui-word-aux layui-orange">開發人員使用,請勿修改</div>
              </div>
            </div>
            <?php endif;?>
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
                <div class="layui-form-mid layui-word-aux"><i class="fas fa-info-circle"></i> 默認按創建日期降序排序</div>
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
                      <a href="javascript:void(0);" class="layui-btn layui-btn-warm layui-btn-with-icon sync_lang" lang-num="<?=count($lang_array);?>" lang-id="<?=$lang_id?>" title="多語言内容同步"><i class="fas fa-sync"></i>同步</a>
                    </div>
                  </div><!-- layui-form-item -->
                  <?php endif;?>
                  <div class="layui-form-item required">
                    <label class="layui-form-label flag-lang-<?=$lang_id?>"><span class="flag-icon mr-1"></span>標題</label>
                    <div class="layui-input-block">
                      <input type="text" value="<?=set_value('descriptions['.$lang_id.'][title]',isset($descriptions[$lang_id]['title'])?$descriptions[$lang_id]['title']:'')?>" class="layui-input" name="descriptions[<?=$lang_id?>][title]" required lay-verify="required" lay-verType="alert">
                    </div>
                  </div><!-- layui-form-item -->
                  <div class="layui-form-item">
                    <label class="layui-form-label flag-lang-<?=$lang_id?>"><span class="flag-icon mr-1"></span>簡介</label>
                    <div class="layui-input-block">
                      <input type="text" value="<?=set_value('descriptions['.$lang_id.'][introduction]',isset($descriptions[$lang_id]['introduction'])?$descriptions[$lang_id]['introduction']:'')?>" class="layui-input" name="descriptions[<?=$lang_id?>][introduction]">
                    </div>
                  </div><!-- layui-form-item -->
                  <div class="layui-form-item layui-hide">
                    <label class="layui-form-label flag-lang-<?=$lang_id?>"><span class="flag-icon mr-1"></span>內容</label>
                    <div class="layui-input-block">
                      <textarea id="description<?=$lang_id?>" class="edui-default" name="descriptions[<?=$lang_id?>][content]"><?=set_value('descriptions['.$lang_id.'][content]',isset($descriptions[$lang_id]['content'])?$descriptions[$lang_id]['content']:'')?></textarea>
                    </div>
                  </div><!-- layui-form-item -->
                </div><!-- layui-tab-item -->
                <?php endforeach;?>
              </div>
            </div>
            <!--sub-tab end-->
          </div><!-- layui-tab-item -->
          <div class="layui-tab-item">
            <div class="operater-area mb-1 pt-1 pb-1 text-center">
              <button id="btn_album_upload" type="button" name="btn_upload" class="layui-btn layui-btn-warm layui-btn-lg layui-btn-with-icon"><i class="fas fa-cloud-upload-alt"></i>批量上傳圖像</button>
              <div class="tips mt-1">
                <i class="fas fa-info-circle"></i> 拖動圖像可進行排序。每張圖像的文件大小不得超過<span>5MB</span>，支援圖像格式：JPEG,JPG,PNG。
              </div>
            </div>
            <div class="progress-area">
              <div id="progress_album_upload" class="layui-progress mt-1 layui-progress-big layui-hide" lay-showPercent="yes" lay-filter="progress_album_upload">
                <div class="layui-progress-bar layui-bg-blue" lay-percent="0%"></div>
              </div><!-- layui-progress -->
            </div><!-- progress-area -->
            <div class="layui-row layui-col-space20 gallery-list mb-5 mt-1" id="gallertSortable">
              <?php if(isset($album) && !empty($album)):?>
              <?php foreach($album as $key => $item):?>
              <?php $album_thumb=helper_create_thumb($item['image']);?>
              <div class="layui-col-lg2 layui-col-md4 layui-col-sm3 layui-col-xs6">
                <div class="thumbnail">
                  <a href="<?php echo base_url($item['image']);?>" data-lightbox="gallery" data-title="show title">
                    <img src="<?php echo base_url($album_thumb);?>">
                    <div class="popup">
                      <div class="inner"><i class="fas fa-search"></i></div>
                    </div>
                  </a>
                  <span class="delete" data-file_path="<?php echo $item['image'];?>"><i class="fas fa-times"></i></span>
                </div>
                <div class="input-area">
                  <input type="hidden" name="album[<?=$key?>][image]" value="<?php echo $item['image'];?>">
                  <?php foreach($item['descriptions'] as $lang_id=>$description):?>
                  <div class="item flag-lang-<?=$lang_id?>">
                    <span class="flag-icon mr-1"></span>
                    <input type="text" name="album[<?=$key?>][descriptions][<?=$lang_id?>][title]" value="<?php echo $description['title'];?>" placeholder="<?php echo $lang_name;?>描述" class="layui-input">
                  </div>          
                  <?php endforeach;?>
                </div>
              </div>
              <?php endforeach;?>
              <?php else:?>
              <div class="norecord mt-5 mb-5">
                <div class="icon-area"><i class="fas fa-exclamation"></i></div>
                <div class="con"><span>提示：</span>暫無任何圖像</div>
              </div>
              <?php endif;?>
            </div><!-- gallery-list -->
          </div><!-- layui-tab-item -->
        </div><!-- layui-tab-content -->
      </div><!-- layui-tab -->
      <div class="layui-controls">
        <button lay-submit class="layui-btn layui-btn-with-icon layui-btn-lg" name="btn_save"><i class="fas fa-check"></i>存儲</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-lg">重置</button>
      </div>
      </form>
    </div>
  </div><!-- layui-card -->
</div><!--layui-col-xs12-->
</div><!-- layui-row -->
</div><!-- layui-fluid -->
<script id="album_template" type="text/html">
<div class="layui-col-lg2 layui-col-md4 layui-col-sm3 layui-col-xs6">
  <div class="thumbnail">
    <a href="{{ d.http_path }}" data-lightbox="gallery" data-title="show title">
      <img src="{{ d.thumb_image }}">
      <div class="popup">
        <div class="inner"><i class="fas fa-search"></i></div>
      </div>
    </a>
    <span class="delete" data-file_path="{{ d.file_path }}"><i class="fas fa-times"></i></span>
  </div>
  <div class="input-area">
    <input type="hidden" name="album[{{ d.index }}][image]" value="{{ d.file_path }}">
    <?php foreach($lang_array as $lang_id=>$lang_name):?>
    <div class="item flag-lang-<?=$lang_id?>">
      <span class="flag-icon mr-1"></span>
      <input type="text" name="album[{{ d.index }}][descriptions][<?=$lang_id?>][title]" placeholder="<?php echo $lang_name;?>描述" class="layui-input">
    </div>
    <?php endforeach;?>
  </div>
</div>
</script>
<script src="<?=base_url('themes/admin/vendor/sortable/Sortable.min.js')?>"></script>
<script src="<?=base_url('themes/admin/vendor/lightbox2/js/lightbox.min.js')?>"></script>
<script type="text/javascript" charset="utf-8">
layui.use(['form','upload','element','layer','laytpl','jquery'],function(){
  var upload = layui.upload
  ,element = layui.element
  ,form = layui.form
  ,laytpl = layui.laytpl
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
  //封面圖上傳
  upload.render({
    elem: '#btn_single_upload'
    ,url: "<?php echo site_url('admin/upload/single_upload')?>"
    ,method: 'post'
    ,acceptMime:'image/*'
    ,size:5*1024 //KB unit 
    ,data:{
      'folder'      : encodeURIComponent('uploads/payment/')
      ,'element_id' : 'main_image'
      ,'thumb_size' : '200,200'//縮圖寬高
      ,'is_file'    : '0'//是否文件
      ,'token'      : "<?php echo $this->session->userdata('user_token')?>"
    }
    ,xhr:xhrOnProgress
    ,progress:function(value){//上传进度回调 value进度值
      console.log(value+'=========value');
      element.progress('progress_single_upload', value+'%')//设置页面进度条
    }
    ,choose: function(obj){
      $('#progress_single_upload').removeClass('layui-hide');
    }
    ,done: function(result, index, upload){
      element.progress('progress_single_upload','0%')
      $('#progress_single_upload').addClass('layui-hide');
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
  //相冊上傳.
  upload.render({
    elem: '#btn_album_upload'
    ,url: "<?php echo site_url('admin/upload/single_upload')?>"
    ,method: 'post'
    ,acceptMime:'image/*'
    ,multiple:true
    ,number:30
    ,size:5*1024 //KB unit 
    ,data:{
      'folder'      : encodeURIComponent('uploads/payment/')
      ,'element_id' : 'main_image'
      ,'thumb_size' : '200,200'//縮圖寬高
      ,'is_file'    : '0'//是否文件
      ,'token'      : "<?php echo $this->session->userdata('user_token')?>"
    }
    ,xhr:xhrOnProgress
    ,progress:function(value){//上传进度回调 value进度值
      console.log(value+'=========value');
      element.progress('progress_album_upload',value+'%')//设置页面进度条
    }
    ,choose: function(obj){
      $('#progress_album_upload').removeClass('layui-hide');
      $('.operater-area').addClass('layui-hide');
    }
    ,allDone: function(obj){
      console.log(obj.aborted); //请求失败的文件数
      element.progress('progress_album_upload','0%');
      $('#progress_album_upload').addClass('layui-hide');
      $('.operater-area').removeClass('layui-hide');
    }
    ,done: function(result, index, upload){
      if(!$.isEmptyObject(result) && result.status=='success'){
        $('div.norecord').remove();//刪除相冊為空提示.
        var data=result.message;
        data['index']=$('#gallertSortable div.thumbnail').length;
        var album_template = $('#album_template').html();
        laytpl(album_template).render(data,function(html){
          $('#gallertSortable').append(html);
        });
      }else{
        layer.msg(result.message, {icon: 2,shift:6});
      }
    }
    ,error: function(index, upload){
      console.log("upload album_upload ajax error");
    }
  });
  // 刪除提示
  $(document).on('click', '#gallertSortable .delete', function(event){
    var parent_object=$(this).parents('div.layui-col-lg2');
    var file_path=$(this).data('file_path');
    layer.confirm('確認要刪除該相冊圖片嗎？', {icon:3,skin:'confirm-box',title:'提示'}, function(index){
      $.ajax({
        url:"<?=site_url('admin/upload/delete')?>",
        type: 'GET',
        dataType:'json',
        data: {
          'path'   : file_path
          ,'token' : "<?php echo $this->session->userdata('user_token')?>"
        }
      })
      .done(function(result){
        if(!$.isEmptyObject(result) && result.status=='success'){
          parent_object.remove();
        }else{
          layer.msg(result.message, {icon: 2,shift:6});
        }
      })
      .fail(function() {
        console.log("album image delete ajax error");
      });
      layer.close(index);
    });
  });
});
</script>
<?php $this->load->view('common/admin_ueditor');?>
<?php $this->load->view('common/admin_footer');?>