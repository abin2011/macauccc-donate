<?php $this->load->view('common/admin_header');?>
<div class="layui-fluid">
<div class="layui-row layui-col-space15">
<div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
  <?php $this->load->view('common/admin_notify');?>
  <div class="layui-card">
    <div class="layui-card-header">
      <div class="layui-row pt-1 pb-1">
        <div class="layui-col-md6"><h3><?php echo $CI_page_title;?></h3></div>
        <div class="layui-col-md6 text-right">
          <a href="<?=site_url('admin/page/add')?>" class="layui-btn layui-btn-primary layui-btn-with-icon"><i class="fas fa-plus"></i><span class="layui-hide-xs">新增</span></a>
        </div>
      </div>
    </div>
    <!-- layui-card-header -->
    <div class="layui-card-body">
      <form action="<?=site_url('admin/page')?>" method="get" class="layui-form">
        <input type="hidden" name="field" value="<?=isset($field)?$field:'sort_order'?>" id="field">
        <input type="hidden" name="sort" value="<?=isset($sort)?$sort:'asc'?>" id="sort">
        <div class="layui-table-body">
          <table class="layui-table sortable">
            <thead>
              <tr>
                <th class="table-sort"><a id="nd.title" class="<?=isset($field) && $field=='nd.title'?$sort:'';?>" href="javascript:;">標題名稱</a></th>
                <th class="table-sort"><a id="n.unique_url" class="<?=isset($field) && $field=='n.unique_url'?$sort:'';?>" href="javascript:;">靜態網址</a></th>
                <th class="table-sort"><a id="n.view_num" class="<?=isset($field) && $field=='n.view_num'?$sort:'';?>" href="javascript:;">已檢視</a></th>
                <th class="table-sort"><a id="n.status" class="<?=isset($field) && $field=='n.status'?$sort:'';?>" href="javascript:;">狀態</a></th>
                <th class="table-sort layui-hide"><a id="n.parent_id" class="<?=isset($field) && $field=='n.parent_id'?$sort:'';?>" href="javascript:;">所屬類別</a></th>
                <th class="table-sort"><a id="n.sort_order" class="<?=isset($field) && $field=='n.sort_order'?$sort:'';?>" href="javascript:;">排序</a></th>
                <th class="table-sort"><a id="n.updated_at" class="<?=isset($field) && $field=='n.updated_at'?$sort:'';?>" href="javascript:;">編輯於</a></th>
                <th class="table-sort"><a id="n.created_at" class="<?=isset($field) && $field=='n.created_at'?$sort:'';?>" href="javascript:;">創建於</a></th>
                <th>動作</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" class="layui-input" placeholder="標題名稱搜索" name="title" value="<?=isset($title)?$title:''?>" id="title"></td>
                <td><input type="text" class="layui-input" placeholder="靜態網址搜索" name="unique_url" value="<?=isset($unique_url)?$unique_url:''?>" id="unique_url"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="layui-hide">
                  <select name="parent_id" id="parent_id">
                     <option value="">請選擇</option>
                     <option value="0" <?=isset($parent_id) && is_numeric($parent_id) && 0==$parent_id?'selected':''?>>默認頁面</option>
                     <?php if(isset($menus) && is_array($menus) && count($menus)>0):?>
                      <?php foreach($menus as $key=>$menu):?>
                      <option <?=isset($parent_id) && $key==$parent_id?'selected':''?> value="<?=$key?>"><?=$menu?></option>
                      <?php endforeach;?>
                     <?php endif;?>
                  </select>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                  <button type="submit" class="layui-btn layui-btn-sm" name="sub_filter"><i class="fas fa-filter"></i></button>
                </td>
              </tr>
              <?php if(isset($lists_count) && $lists_count>0):?>
              <?php foreach($lists as $list):
                $url=site_url('admin/page/%s/'.$list['id']);
                $parent_id_format=isset($menus)&&!empty($list['parent_id'])?$menus[$list['parent_id']]:'默認頁面';
              ?>
              <tr>
                <td title="<?=$list['title']?>"><?=helper_strcut($list['title']);?></td>
                <td><?=$list['unique_url']?></td>
                <td><a href="javascript:;" onClick="reset_item('<?=sprintf($url,"reset")?>')"><?=$list['view_num']?></a></td>
                <td><?=$list['status_format']?></td>
                <td class="layui-hide"><?php echo $parent_id_format;?></td>
                <td><?=$list['sort_order']?></td> 
                <td><div class="words-single-line"><?=$list['updated_at']?></div></td>
                <td><div class="words-single-line"><?=$list['created_at']?></div></td>
                <td>
                  <div class="words-single-line">
                    <?php $borwer_url='page/'.$list['unique_url'];?>
                    <a href="<?=site_url($borwer_url)?>" target="_blank" class="layui-btn layui-btn-sm layui-btn-primary operater-btn" title="查看"><i class="fas fa-eye"></i></a>
                    <a href="<?=sprintf($url,"edit");?>" class="layui-btn layui-btn-sm layui-btn-primary operater-btn" title="編輯"><i class="fas fa-edit"></i></a>
                    <a href="javascript:;" onClick="delete_item('<?=sprintf($url,"delete")?>');" class="layui-btn layui-btn-sm layui-btn-primary operater-btn" title="刪除"><i class="fas fa-trash"></i></a>
                  </div>
                </td>
              </tr>
              <?php endforeach;?>
              <?php else:?>
              <tr>
                <td colspan="10" class="text-center"><div class="alert alert-warning">暫無任何資訊</div></td>
              </tr>
              <?php endif;?>
            </tbody>
          </table>
        </div>
        <div id="pagination" class="layui-controls c-pagination">
          <div class="layui-row">
            <div class="layui-col-md6 layui-col-xs12"><?php echo isset($pagination)?$pagination:null;?>　</div>
            <div class="layui-col-md6 layui-col-xs12"><div class="listscount">共有 <?php echo isset($lists_count)?$lists_count:0;?> 條記錄</div></div>
          </div><!-- layui-row -->
        </div><!-- pagination -->
      </form>
    </div>
  </div><!-- layui-card -->
</div><!-- col -->
</div><!-- layui-row -->
</div><!-- layui-fluid -->
<?php $this->load->view('common/admin_footer');?>
