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
              <a href="<?=site_url('admin/slideshow/add')?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-plus"></i> <span class="layui-hide-xs">新增</span></a>
              <button type="button" onclick="delete_allitem('<?=site_url('admin/slideshow/delete_batch')?>')" class="layui-btn layui-btn-danger layui-btn-with-icon delete-all-item-btn"><i class="fas fa-trash"></i><span class="layui-hide-xs">批量刪除</span></button>
            </div>
          </div>
        </div><!-- layui-card-header -->
        <div class="layui-card-body">
          <form action="<?=site_url('admin/slideshow')?>" method="get" class="layui-form">
            <input type="hidden" name="field" value="<?=isset($field)?$field:'n.sort_order'?>" id="field">
            <input type="hidden" name="sort" value="<?=isset($sort)?$sort:'asc'?>" id="sort">
            <div class="layui-table-body">
               <table class="layui-table sortable layui-table-res">
                <thead>
                  <tr>
                    <th><input type="checkbox" name="chk_all" id="chk_all" lay-skin="primary" lay-filter="chk_all"></th>
                    <th>圖片</th>
                    <th class="table-sort"><a id="nd.title" class="<?=isset($field) && $field=='nd.title'?$sort:'';?>" href="javascript:;">標題描述</a></th>
                    <!-- <th><a id="n.page_controller" class="<?=isset($field) && $field=='n.page_controller'?$sort:'';?>" href="javascript:;">所屬頁面</a></th> -->
                    <!-- <th class="table-sort"><a id="n.page_position" class="<?=isset($field) && $field=='n.page_position'?$sort:'';?>" href="javascript:;">所屬位置</a></th> -->
                    <th class="table-sort"><a id="n.sort_order" class="<?=isset($field) && $field=='n.sort_order'?$sort:'';?>" href="javascript:;">排序</a></th>
                    <th class="table-sort"><a id="n.support_mobile" class="<?=isset($field) && $field=='n.support_mobile'?$sort:'';?>" href="javascript:;">兼容手機版</a></th>
                    <th class="table-sort"><a id="n.status" class="<?=isset($field) && $field=='n.status'?$sort:'';?>" href="javascript:;">狀態</a></th>
                    <th class="table-sort"><a id="n.updated_at" class="<?=isset($field) && $field=='n.updated_at'?$sort:'';?>" href="javascript:;">編輯於</a></th>
                    <th class="table-sort"><a id="n.created_at" class="<?=isset($field) && $field=='n.created_at'?$sort:'';?>" href="javascript:;">創建於</a></th>
                    <th>動作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><input type="text" class="layui-input" name="title" value="<?=isset($title)?$title:''?>"></td>
                    <!-- <td>
                      <?php if(isset($controller_option) && !empty($controller_option) && is_array($controller_option)):?>
                      <select id="page_controller" name="page_controller">
                        <option value="">--請選擇所屬頁面--</option>
                        <?php foreach($controller_option as $key => $value):?>
                        <option value="<?php echo $key;?>" <?=isset($page_controller)&&$page_controller==$key?'selected':''?>><?php echo $value;?></option>
                        <?php endforeach;?>
                      </select>
                      <?php endif;?>
                    </td> -->
                    <!-- <td>
                      <?php if(isset($position_option) && !empty($position_option)):?>
                      <select id="page_position" name="page_position">
                        <option value="">--請選擇所屬位置--</option>
                        <?php foreach($position_option as $key => $value):?>
                        <option value="<?php echo $key;?>" <?=isset($page_position)&&$page_position==$key?'selected':''?>><?php echo $value;?></option>
                        <?php endforeach;?>
                      </select>
                      <?php endif;?>
                    </td> -->
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                      <button type="submit" name="sub_filter" class="layui-btn layui-btn-sm operater-btn" title="篩查"><i class="fas fa-filter"></i></button>
                    </td>
                  </tr>
                  <?php if(isset($lists_count) && $lists_count>0):?>
                  <?php foreach($lists as $list):
                    $thumb_img=helper_create_thumb($list['main_image']);
                    $url=site_url('admin/slideshow/%s/'.$list['id']);
                    $page_controller_format=isset($controller_option)&&isset($controller_option[$list['page_controller']])?$controller_option[$list['page_controller']]:'';
                    $page_position_format=isset($position_option)&&isset($position_option[$list['page_position']])?$position_option[$list['page_position']]:'';
                  ?>
                  <tr>
                    <td><input type="checkbox" class="chk_one" value="<?=$list['id']?>" lay-skin="primary" lay-filter="chk_one"></td>
                    <td>
                      <div class="list-thumb">
                        <a href="<?=base_url($list['main_image'])?>" class="list-thumb-lightbox" data-lightbox="cover-image" data-title="封面圖">
                          <img src="<?=base_url($thumb_img)?>"/>
                          <span class="icon-area"><i class="fas fa-search"></i></span>
                        </a>
                      </div>
                    </td>
                    <td><?=$list['title']?></td>
                    <!-- <td><?=$page_controller_format?></td> -->
                    <!-- <td><?=$page_position_format?></td> -->
                    <td><?=$list['sort_order']?></td>
                    <td><?=$list['support_mobile']==2?'是':'否'?></td>
                    <td><?=$list['status_format']?></td>
                    <td><div class="words-single-line"><?=$list['updated_at']?></div></td>
                    <td><div class="words-single-line"><?=$list['created_at']?></div></td>
                    <td>
                      <div class="words-single-line">
                        <a href="<?=sprintf($url,"edit");?>" class="layui-btn layui-btn-primary layui-btn-sm operater-btn" title="編輯"><i class="fas fa-edit"></i></a>
                        <a href="javascript:;" onClick="delete_item('<?=sprintf($url,"delete")?>');" class="layui-btn layui-btn-primary layui-btn-sm operater-btn" title="刪除"><i class="fas fa-trash"></i></a>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach;?>
                  <?php else:?>
                  <tr>
                    <td colspan="10">
                      <div class="norecord mt-5 mb-5">
                        <div class="icon-area"><i class="fas fa-exclamation"></i></div>
                        <div class="con"><span>提示：</span>暫無任何資訊</div>
                      </div>
                      <!-- norecord -->
                    </td>
                  </tr>
                  <?php endif;?>
                </tbody>
              </table>
              <!-- layui-table -->
            </div>
            <!-- layui-table-body -->
            <div id="pagination" class="layui-controls c-pagination">
              <div class="layui-row">
                <div class="layui-col-md6 layui-col-xs12"><?php echo isset($pagination)?$pagination:null;?>　</div>
                <div class="layui-col-md6 layui-col-xs12"><div class="listscount">共有 <?php echo isset($lists_count)?$lists_count:0;?> 條記錄</div></div>
              </div><!-- layui-row -->
            </div><!-- pagination -->
          </form>
        </div>
        <!-- layui-card-body -->
      </div><!-- layui-card -->
    </div>
    <!-- col -->
  </div><!-- layui-row -->
</div><!-- layui-fluid -->
<script src="<?=base_url('themes/admin/vendor/lightbox2/js/lightbox.min.js')?>"></script>
<?php $this->load->view('common/admin_footer');?>