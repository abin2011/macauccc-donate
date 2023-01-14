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
            <a href="<?=site_url('admin/backup/add')?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-plus"></i> <span class="layui-hide-xs">新增備份</span></a>
          </div>
        </div>
      </div><!--layui-card-header-->
      <div class="layui-card-body">
        <form action="<?=site_url('admin/backup')?>" method="get">
          <input type="hidden" name="field" value="<?=isset($field)?$field:'sort_order'?>" id="field">
          <input type="hidden" name="sort" value="<?=isset($sort)?$sort:'asc'?>" id="sort">
          <div class="layui-table-body">
            <table class="layui-table sortable">
              <thead>
                <tr>
                  <th>編號</th>
                  <th>資料庫名稱</th>
                  <th>操作人</th>
                  <th>備份時間</th>
                  <th>動作</th>
                </tr> 
              </thead>
              <tbody>
                <?php if(isset($lists) && !empty($lists)):?>
                <?php foreach($lists as $key=>$list):
                  $url=site_url('admin/backup/%s/'.$list['filename']);
                ?>
                <tr>
                  <td><?=$key+1?></td>
                  <td><?=$list['filename']?></td>
                  <td><?=$list['author']?></td>
                  <td><div class="words-single-line"><?=date($default_admin_time,$list['created_at'])?></div></td>
                  <td>
                    <div class="words-single-line">
                      <a href="javascript:;" onClick="recover_data('<?=sprintf($url,"recover")?>');" class="layui-btn layui-btn-sm layui-btn-primary operater-btn" title="還原"><i class="fas fa-undo"></i></a>
                      <a href="<?=sprintf($url,"download");?>" class="layui-btn layui-btn-sm layui-btn-primary operater-btn" title="下載"><i class="fas fa-download"></i></a>
                      <a href="javascript:;" onClick="delete_item('<?=sprintf($url,"delete")?>');" class="layui-btn layui-btn-sm layui-btn-primary operater-btn" title="刪除"><i class="fas fa-trash"></i></a>
                    </div>
                  </td>
                </tr>
                <?php endforeach;?>
                <?php else:?>
                <tr>
                  <td colspan="8">
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
        </form>
      </div><!-- layui-card-body -->
    </div><!-- layui-card -->
  </div><!-- col -->
</div><!-- layui-row -->
</div><!-- layui-fluid -->
<?php $this->load->view('common/admin_footer');?>