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
              <?php if($this->session->userdata('login_name')=='clickr'):?>
              <a href="<?=site_url('admin/payment/add')?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-plus"></i> <span class="layui-hide-xs">新增</span></a>
              <?php endif;?>
              <button type="button" onclick="delete_allitem('<?=site_url('admin/payment/delete_batch')?>')" class="layui-btn layui-btn-danger layui-btn-with-icon delete-all-item-btn"><i class="fas fa-trash"></i><span class="layui-hide-xs">批量刪除</span></button>
            </div>
          </div>
        </div><!-- layui-card-header -->
        <div class="layui-card-body">
          <form action="<?=site_url('admin/payment')?>" method="get" class="layui-form">
            <input type="hidden" name="field" value="<?=isset($field)?$field:'n.sort_order'?>" id="field">
            <input type="hidden" name="sort" value="<?=isset($sort)?$sort:'asc'?>" id="sort">
            <div class="layui-table-body">
              <table class="layui-table sortable">
                <thead>
                  <tr>
                    <th><input type="checkbox" name="chk_all" id="chk_all" lay-skin="primary" lay-filter="chk_all"></th>
                    <th>封面圖</th>
                    <th class="table-sort"><a id="nd.title" class="<?=isset($field) && $field=='nd.title'?$sort:'';?>" href="javascript:;">標題描述</a></th>
                    <th class="table-sort"><a id="n.status" class="<?=isset($field) && $field=='n.status'?$sort:'';?>" href="javascript:;">狀態</a></th>
                    <th class="table-sort"><a id="n.sort_order" class="<?=isset($field) && $field=='n.sort_order'?$sort:'';?>" href="javascript:;">排序</a></th>
                    <th class="table-sort"><a id="n.updated_at" class="<?=isset($field) && $field=='n.updated_at'?$sort:'';?>" href="javascript:;">編輯於</a></th>
                    <th class="table-sort"><a id="n.created_at" class="<?=isset($field) && $field=='n.created_at'?$sort:'';?>" href="javascript:;">創建於</a></th>
                    <th>動作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><input type="text" name="title" value="<?=isset($title)?$title:''?>" class="layui-input"/></td>
                    <td>
                      <select name="status" id="status">
                        <option value="">全部狀態</option>
                        <option value="1">啟用</option>
                        <option value="2">禁用</option>
                      </select>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                      <button type="submit" name="sub_filter" class="layui-btn layui-btn-sm operater-btn" title="篩查"><i class="fas fa-filter"></i></button>
                    </td>
                  </tr>
                  <?php if(isset($lists_count) && $lists_count>0):?>
                  <?php foreach($lists as $list):
                    $thumb_img=isset($list['main_image']) && file_exists($list['main_image'])?Imagelib::resize_thumb($list['main_image'],100,50):'themes/admin/img/noimage.png';
                    $url=site_url('admin/payment/%s/'.$list['id']);
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
                    <td>
                      <?=$list['title']?>
                      <br><?=$list['introduction']?>
                    </td>
                    <td><?=$list['status_format']?></td>
                    <td><?=$list['sort_order']?></td>
                    <td><div class="words-single-line"><?=$list['updated_at']?></div></td>
                    <td><div class="words-single-line"><?=$list['created_at']?></div></td>
                    <td>
                      <div class="words-single-line">
                        <a href="<?=sprintf($url,"edit");?>" class="layui-btn layui-btn-primary layui-btn-sm operater-btn" title="編輯"><i class="fas fa-edit"></i></a>
                        <a href="<?=sprintf($url,"setting");?>" class="layui-btn layui-btn-primary layui-btn-sm operater-btn" title="配置"><i class="fas fa-cog"></i></a>
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
                      </div><!-- norecord -->
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