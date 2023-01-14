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
          <button type="button" onclick="delete_allitem('<?=site_url('admin/visit/delete_batch')?>')" class="layui-btn layui-btn-danger layui-btn-with-icon delete-all-item-btn"><i class="fas fa-trash"></i><span class="layui-hide-xs">批量刪除</span></button>
        </div>
      </div>
    </div><!-- layui-card-header -->
    <div class="layui-card-body">
      <div class="visit-total mt-1 mb-1">
        <div class="layui-row">
          <div class="layui-col-md6">
            <h2><b>總訪問量：<?php echo $site_visit_count;?></b></h2>
          </div>
          <div class="layui-col-md6 text-right">
            <div class="content">
              <i class="fas fa-info-circle"></i> 同一個訪問者IP一天內多次訪問只計一次! 大部分搜索引擎爬蟲訪問記錄過濾,不做記錄！
            </div>
          </div>
        </div>
      </div>
      <!-- visit-total -->
      <form action="<?=site_url('admin/visit')?>" method="get" class="layui-form">
        <input type="hidden" name="field" value="<?=isset($field)?$field:'sort_order'?>" id="field">
        <input type="hidden" name="sort" value="<?=isset($sort)?$sort:'asc'?>" id="sort">
        <div class="layui-table-body">
           <table class="layui-table sortable">
            <thead>
              <tr>
                <th><input type="checkbox" name="chk_all" id="chk_all" lay-skin="primary" lay-filter="chk_all"></th>
                <th class="table-sort"><a id="ip_address" class="<?=isset($field) && $field=='ip_address'?$sort:'';?>" href="javascript:;">訪問者IP</a></th>
                <th class="table-sort"><a id="visit_date" class="<?=isset($field) && $field=='visit_date'?$sort:'';?>" href="javascript:;">瀏覽日期</a></th>
                <th class="table-sort"><a id="source_url" class="<?=isset($field) && $field=='source_url'?$sort:'';?>" href="javascript:;">來源頁面</a></th>
                <th class="table-sort"><a id="visit_url" class="<?=isset($field) && $field=='visit_url'?$sort:'';?>" href="javascript:;">瀏覽頁面</a></th>
                <th class="table-sort"><a id="device" class="<?=isset($field) && $field=='device'?$sort:'';?>" href="javascript:;">瀏覽設備</a></th>
                <th class="table-sort"><a id="user_agent" class="<?=isset($field) && $field=='user_agent'?$sort:'';?>" href="javascript:;">用戶代理</a></th>
                <th class="table-sort"><a id="created_at" class="<?=isset($field) && $field=='created_at'?$sort:'';?>" href="javascript:;">創建於</a></th>
                <th>動作</th>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input type="text" name="ip_address" value="<?=isset($ip_address)?$ip_address:''?>" class="layui-input" /></td>
                <td><input type="text" name="visit_date" value="<?=isset($visit_date)?$visit_date:''?>" class="layui-input date-picker" /></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                  <select name="device">
                    <option value="">請選擇</option>
                    <option value="computer" <?=isset($device)&&$device=='computer'?'selected':''?>>電腦</option>
                    <option value="mobile" <?=isset($device)&&$device=='mobile'?'selected':''?>>手機</option>
                  </select>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                  <button type="submit" name="sub_filter" class="layui-btn layui-btn-sm operater-btn" title="篩查"><i class="fas fa-filter"></i></button>
                </td>
              </tr>
            </thead>
            <tbody>
              <?php if(isset($lists_count) && $lists_count>0):?>
              <?php foreach($lists as $list):
                $url=site_url('admin/visit/%s/'.$list['id']);
              ?>
              <tr>
                <td><input type="checkbox" class="chk_one" value="<?=$list['id']?>" lay-skin="primary" lay-filter="chk_one"></td>
                <td><?=$list['ip_address']?></td>
                <td><?=$list['visit_date']?></td>
                <td title="<?=$list['source_url']?>"><?=helper_strcut($list['source_url']);?></td>
                <td title="<?=$list['visit_url']?>"><?=helper_strcut($list['visit_url']);?></td>
                <td><?=$list['device_format']?></td>
                <td title="<?=$list['user_agent']?>"><?=helper_strcut($list['user_agent']);?></td>
                <td><?=$list['created_at']?></td>
                <td>
                  <div class="words-single-line">
                    <a href="javascript:;" onClick="delete_item('<?=sprintf($url,"delete")?>');" class="layui-btn layui-btn-primary layui-btn-sm operater-btn" title="刪除"><i class="fas fa-trash"></i></a>
                  </div>
                </td>
              </tr>
              <?php endforeach;?>
              <?php else:?>
              <tr>
                <td colspan="10" class="text-center">
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
</div><!-- layui-row -->
</div><!-- layui-fluid -->
<?php $this->load->view('common/admin_footer');?>