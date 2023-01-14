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
          <button type="button" onclick="delete_allitem('<?=site_url('admin/feedback/delete_batch')?>')" class="layui-btn layui-btn-danger layui-btn-with-icon delete-all-item-btn"><i class="fas fa-trash"></i><span class="layui-hide-xs">批量刪除</span></button>
        </div>
      </div>
    </div><!-- layui-card-header -->
    <div class="layui-card-body">
      <form action="<?=site_url('admin/feedback')?>" method="get" class="layui-form">
        <input type="hidden" name="field" value="<?=isset($field)?$field:'sort_order'?>" id="field">
        <input type="hidden" name="sort" value="<?=isset($sort)?$sort:'asc'?>" id="sort">
        <div class="layui-table-body">
           <table class="layui-table sortable">
            <thead>
              <tr>
                <th><input type="checkbox" name="chk_all" id="chk_all" lay-skin="primary" lay-filter="chk_all"></th>
                <th class="table-sort"><a id="subject" class="<?=isset($field) && $field=='subject'?$sort:'';?>" href="javascript:;">主题</a></th>
                <th class="table-sort"><a id="name" class="<?=isset($field) && $field=='name'?$sort:'';?>" href="javascript:;">稱呼</a></th>
                <th class="table-sort"><a id="email" class="<?=isset($field) && $field=='email'?$sort:'';?>" href="javascript:;">電郵</a></th>
                <th class="table-sort"><a id="phone" class="<?=isset($field) && $field=='phone'?$sort:'';?>" href="javascript:;">電話</a></th>
                <th class="table-sort"><a id="status" class="<?=isset($field) && $field=='status'?$sort:'';?>" href="javascript:;">狀態</a></th>
                <th class="table-sort"><a id="updated_at" class="<?=isset($field) && $field=='updated_at'?$sort:'';?>" href="javascript:;">編輯於</a></th>
                <th class="table-sort"><a id="created_at" class="<?=isset($field) && $field=='created_at'?$sort:'';?>" href="javascript:;">創建於</a></th>
                <th>動作</th>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><input type="text" name="name" value="<?=isset($name)?$name:''?>" class="layui-input" /></td>
                <td><input type="text" name="email" value="<?=isset($email)?$email:''?>" class="layui-input" /></td>
                <td><input type="text" name="phone" value="<?=isset($phone)?$phone:''?>" class="layui-input" /></td>
                <td>
                  <select name="status">
                    <option value="">全部狀態</option>
                    <option value="1" <?=isset($status)&&$status==1?'selected':''?>>未讀</option>
                    <option value="2" <?=isset($status)&&$status==2?'selected':''?>>已讀</option>
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
                $url=site_url('admin/feedback/%s/'.$list['id']);
              ?>
              <tr>
                <td><input type="checkbox" class="chk_one" value="<?=$list['id']?>" lay-skin="primary" lay-filter="chk_one"></td>
                <td><?=$list['subject']?></td>
                <td title="<?=$list['name']?>"><?=helper_strcut($list['name']);?></td>
                <td><?=$list['email']?></td>
                <td><?=$list['phone']?></td>
                <td><?=$list['status_format']?></td>
                <td><?=$list['updated_at']?></td>
                <td><?=$list['created_at']?></td>
                <td>
                  <div class="words-single-line">
                    <a href="<?=sprintf($url,"view");?>" class="layui-btn layui-btn-primary layui-btn-sm operater-btn" title="查看"><i class="fas fa-eye"></i></a>
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