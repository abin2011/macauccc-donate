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
              <a href="javascript:void(0);" onClick="delete_item('<?=site_url("admin/operator/delete_all")?>');" class="layui-btn layui-btn-danger layui-btn-with-icon"><i class="fas fa-trash"></i> <span class="layui-hide-xs">清空日誌</span></a>
            </div>
          </div>
        </div><!-- layui-card-header -->
        <div class="layui-card-body">
          <form action="<?=site_url('admin/operator')?>" method="get">
            <input type="hidden" name="field" value="<?=isset($field)?$field:'created_at'?>" id="field">
            <input type="hidden" name="sort" value="<?=isset($sort)?$sort:'desc'?>" id="sort">
            <div class="layui-table-body">
              <table class="layui-table sortable">
                <thead>
                  <tr>
                    <th class="table-sort"><a id="title" class="<?=isset($field) && $field=='title'?$sort:'';?>" href="javascript:;">操作內容</a></th>
                    <th class="table-sort"><a id="operator" class="<?=isset($field) && $field=='operator'?$sort:'';?>" href="javascript:;">操作人</a></th>
                    <th class="table-sort"><a id="action" class="<?=isset($field) && $field=='action'?$sort:'';?>" href="javascript:;">動作</a></th>
                    <th class="table-sort">
                      <a id="result" class="<?=isset($field) && $field=='result'?$sort:'';?>" href="javascript:;">操作結果</a>
                    </th>
                    <th class="table-sort"><a id="urls" class="<?=isset($field) && $field=='urls'?$sort:'';?>" href="javascript:;">對應路徑</a></th>
                    <th class="table-sort"><a id="created_at" class="<?=isset($field) && $field=='created_at'?$sort:'';?>" href="javascript:;">操作時間</a></th>
                    <th>動作</th>
                  </tr> 
                </thead>
                <tbody>
                  <tr>
                    <td><input type="text" class="layui-input" name="title" value="<?=isset($title)?$title:''?>"></td>
                    <td><input type="text" class="layui-input" name="operator" value="<?=isset($operator)?$operator:''?>"></td>
                    <td><input type="text" class="layui-input" name="action" value="<?=isset($action)?$action:''?>"></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                      <button  type="submit" class="layui-btn layui-btn-sm" name="sub_filter"><i class="fas fa-filter"></i></button>
                    </td>
                  </tr>
                  <?php if(isset($lists_count) && $lists_count>0):?>
                  <?php foreach($lists as $list):
                    $urls=site_url('admin/operator/%s/'.$list['id']);
                  ?>
                  <tr>
                    <td title="<?=$list['title']?>"><?php echo helper_strcut($list['title'],30);?></td>
                    <td><?=$list['operator']?></td>
                    <td><?=$list['action']?></td>
                    <td><?=$list['result']?></td>
                    <td><?=$list['urls']?></td>
                    <td><div class="words-single-line"><?=$list['created_at']?></div></td>
                    <td>&nbsp;</td>
                  </tr>
                  <?php endforeach;?>
                  <?php else:?>
                  <tr>
                    <td colspan="7">
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
        </div><!-- layui-card -->
    </div><!-- layui-col -->
  </div><!-- layui-row -->
</div><!-- layui-fluid -->
<?php $this->load->view('common/admin_footer');?>