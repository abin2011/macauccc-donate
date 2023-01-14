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
            <a href="<?=site_url('admin/user/add')?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-plus"></i> <span class="layui-hide-xs">新增</span></a>
          </div>
        </div>
      </div><!--layui-card-header-->
      <div class="layui-card-body">
        <form action="<?=site_url('admin/user')?>" method="get" class="layui-form">
          <input type="hidden" name="field" value="<?=isset($field)?$field:'n.sort_order'?>" id="field">
          <input type="hidden" name="sort" value="<?=isset($sort)?$sort:'asc'?>" id="sort">
          <div class="layui-table-body">
            <table class="layui-table sortable">
              <thead>
                <tr>
                  <th class="table-sort"><a id="n.nickname" class="<?=isset($field) && $field=='n.nickname'?$sort:'';?>" href="#">暱稱</a></th>
                  <th class="table-sort"><a id="n.login_name" class="<?=isset($field) && $field=='n.login_name'?$sort:'';?>" href="#">賬戶</a></th>
                  <th class="table-sort"><a id="n.group_id" class="<?=isset($field) && $field=='n.group_id'?$sort:'';?>" href="#">用戶組</a></th>
                  <th class="table-sort"><a id="n.email" class="<?=isset($field) && $field=='n.email'?$sort:'';?>" href="#">電郵</a></th>
                  <th class="table-sort"><a id="n.status" class="<?=isset($field) && $field=='n.status'?$sort:'';?>" href="#">狀態</a></th>
                  <th class="table-sort"><a id="n.updated_at" class="<?=isset($field) && $field=='n.updated_at'?$sort:'';?>" href="#">編輯於</a></th>
                  <th class="table-sort"><a id="n.created_at" class="<?=isset($field) && $field=='n.created_at'?$sort:'';?>" href="#">創建於</a></th>
                  <th>動作</th>
                </tr> 
              </thead>
              <tbody>
                <tr>
                  <td><input type="text" class="layui-input" name="nickname" value="<?=set_value('nickname',isset($nickname)?$nickname:'')?>"></td>
                  <td><input type="text" class="layui-input" name="login_name" value="<?=set_value('login_name',isset($login_name)?$login_name:'')?>"></td>
                  <td>
                    <select name="group_id" id="group_id">
                      <option value="">請選擇組別</option>
                      <?php if(isset($user_groups) && is_array($user_groups)):?>
                      <?php foreach($user_groups as $group):?>
                      <option value="<?=$group['id']?>" <?=isset($group_id) && $group_id==$group['id']?'selected':''?>><?=$group['name']?></option>
                      <?php endforeach;?>
                      <?php endif;?>
                    </select>
                  </td>
                  <td><input type="text" class="layui-input" name="email" value="<?=set_value('email',isset($email)?$email:'')?>"></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>
                    <button type="submit" class="layui-btn layui-btn-sm operater-btn" name="sub_filter" title="篩查"><i class="fas fa-filter"></i></button>
                  </td>
                </tr>
                <?php if(isset($lists_count) && $lists_count>0):?>
                <?php foreach($lists as $list):
                  $url=site_url("admin/user/%s/".$list['id']);
                ?>
                <tr>
                  <td><?=$list['nickname']?></td>
                  <td><?=$list['login_name']?></td>
                  <td><?=$list['group_name']?></td>
                  <td><?=$list['email']?></td>
                  <td><?=$list['status_format']?></td>
                  <td><div class="words-single-line"><?=$list['updated_at']?></div></td>
                  <td><div class="words-single-line"><?=$list['created_at']?></div></td>
                  <td>
                    <div class="words-single-line">
                      <?php if($list['login_name']!='clickr'):?>
                      <a href="<?=sprintf($url,"edit");?>" class="layui-btn layui-btn-primary layui-btn-sm operater-btn" title="編輯"><i class="fas fa-edit"></i></a>
                      <a href="javascript:;" onClick="delete_item('<?=sprintf($url,"delete")?>');" class="layui-btn layui-btn-primary layui-btn-sm operater-btn" title="刪除"><i class="fas fa-trash"></i></a>
                      <?php endif;?>
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
          <div id="pagination" class="layui-controls c-pagination">
            <div class="layui-row">
              <div class="layui-col-md6 layui-col-xs12"><?php echo isset($pagination)?$pagination:null;?>　</div>
              <div class="layui-col-md6 layui-col-xs12"><div class="listscount">共有 <?php echo isset($lists_count)?$lists_count:0;?> 條記錄</div></div>
            </div><!-- layui-row -->
          </div><!-- pagination -->
        </form>
      </div><!-- layui-card-body -->
    </div><!-- layui-card -->
  </div>
  <!-- col -->
</div><!-- layui-row -->
</div><!-- layui-fluid -->
<?php $this->load->view('common/admin_footer');?>