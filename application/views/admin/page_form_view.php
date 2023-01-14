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
          <a href="<?=site_url('admin/page').'?'.base64_decode($this->input->cookie('url_query'))?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-arrow-left"></i> <span class="layui-hide-xs">返回</span></a>
        </div>
      </div>
    </div><!--layui-card-header-->
      <div class="layui-card-body">
        <form id="form" action="<?=site_url('admin/page/modify');?>" method="post" class="layui-form">
        <input type="hidden" name="edit_id" value='<?=set_value('edit_id',isset($edit_id)?$edit_id:'')?>' />
        <div class="layui-tab">
          <ul class="layui-tab-title">
            <li class="layui-this"><i class="fas fa-cogs"></i> 設定</li>
            <li><i class="fas fa-flag"></i> 語言</li>
          </ul>
          <!-- layui-tab-title -->
          <div class="layui-tab-content pl-0 pr-0">
            <div class="layui-tab-item layui-show">
              <?php $img_main_image=set_value('main_image',isset($main_image) && file_exists($main_image)?$main_image:'');?>
              <?php $show_image=!empty($img_main_image)?imagelib::resize_thumb($img_main_image,200,200):'themes/admin/img/noimage.png';?>
              <div class="layui-form-item layui-hide">
                <label class="layui-form-label">封面圖片</label>
                <div class="layui-input-block">
                  <a id="thumbnail_main_image" href="<?=base_url().(!empty($img_main_image)?$img_main_image:'themes/admin/img/noimage.png')?>" class="thumbnail text-center" data-lightbox="imageCover" data-title="封面圖片">
                    <img id="img_main_image" src="<?=base_url($show_image)?>">
                  </a>
                  <input id="old_main_image" type="hidden" name="main_image" value="<?php echo $img_main_image;?>">
                  <div class="layui-progress layui-progress-big mt-1" lay-showpercent="true" lay-filter="progress_single_upload" style="max-width:200px;">
                    <div class="layui-progress-bar layui-bg-red" lay-percent="0%"></div>
                  </div><!-- layui-progress -->
                </div>
                <div class="layui-input-block mt-1">
                  <button id="btn_single_upload" type="button" class="layui-btn layui-btn-with-icon layui-btn-sm"><i class="fas fa-cloud-upload-alt"></i>上傳圖像</button>
                  <button type="button" data-upload_clear="main_image" class="layui-btn layui-btn-danger layui-btn-with-icon layui-btn-sm btn-clear-upload <?=empty($img_main_image)?'layui-hide':''?>"><i class="fas fa-trash"></i> 刪除圖像</a>
                </div>
              </div><!-- layui-form-item -->
              <div class="layui-form-item required">
                <label class="layui-form-label">靜態網址</label>
                <div class="layui-input-block">
                  <input type="text" class="layui-input" name="unique_url" <?=(isset($unique_url) && in_array($unique_url,array('about','contact','tutorpay','price','question')))?'readonly="readonly"':''?> value="<?=set_value('unique_url',isset($unique_url)?$unique_url:'')?>" required lay-verify="required" lay-verType="alert"/>
                </div>
              </div>
              <div class="layui-form-item layui-hide">
                <label class="layui-form-label">所屬類別</label>
                <div class="layui-input-block">
                  <select name="parent_id" id="parent_id">
                    <option value="0">默認頁面</option>
                    <?php if(isset($menus) && is_array($menus) && count($menus)>0):?>
                    <?php foreach($menus as $key=>$menu):?>
                    <option <?=isset($parent_id) && $key==$parent_id?'selected':''?> <?=set_select('parent_id',$key)?> value="<?=$key?>"><?=$menu?></option>
                    <?php endforeach;?>
                    <?php endif;?>
                  </select>
                </div>
              </div>
              <div class="layui-form-item layui-hide">
                <label class="layui-form-label">菜單顯示</label>
                <div class="layui-input-block">
                  <input type="radio" name="is_menu" value="1" <?=set_radio('is_menu', '1', TRUE); ?> <?=isset($is_menu) && $is_menu==1?'checked':''?> title="否" />
                  <input type="radio" name="is_menu" value="2" <?=set_radio('is_menu', '2'); ?> <?=isset($is_menu) && $is_menu==2?'checked':''?> title="是"/>
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
                    <div class="layui-form-item">
                      <label class="layui-form-label flag-lang-<?=$lang_id?>"><span class="flag-icon mr-1"></span>內容</label>
                      <div class="layui-input-block">
                        <textarea id="description<?=$lang_id?>" class="details" editer="uediter" name="descriptions[<?=$lang_id?>][content]"><?=set_value('descriptions['.$lang_id.'][content]',isset($descriptions[$lang_id]['content'])?$descriptions[$lang_id]['content']:'')?></textarea>
                      </div>
                    </div><!-- layui-form-item -->
                  </div><!-- layui-tab-item -->
                  <?php endforeach;?>
                </div>
              </div>
              <!--sub-tab end-->
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
<script type="text/javascript" charset="utf-8">
layui.use(['form','element','jquery'],function(){
});
</script>
<?php $this->load->view('common/admin_ueditor');?>
<?php $this->load->view('common/admin_footer');?>
