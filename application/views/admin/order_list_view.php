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
          <a href="<?=isset($export_url)?$export_url:'javascript:;';?>" class="layui-btn layui-btn-with-icon layui-btn-warm"><i class="fas fa-table"></i>結果匯出Excel</a>
          <button type="button" onclick="delete_allitem('<?=site_url('admin/order/delete_batch')?>')" class="layui-btn layui-btn-danger layui-btn-with-icon delete-all-item-btn"><i class="fas fa-trash"></i><span class="layui-hide-xs">批量刪除</span></button>
        </div>
      </div>
    </div><!-- layui-card-header -->
    <div class="layui-card-body">
      <form action="<?=site_url('admin/order')?>" method="get" class="layui-form">
        <input type="hidden" name="field" value="<?=isset($field)?$field:'sort_order'?>" id="field">
        <input type="hidden" name="sort" value="<?=isset($sort)?$sort:'asc'?>" id="sort">
        <div class="layui-table-body">
           <table class="layui-table sortable">
            <thead>
              <tr>
                <th><input type="checkbox" name="chk_all" id="chk_all" lay-skin="primary" lay-filter="chk_all"></th>
                <th class="table-sort"><a id="number" class="<?=isset($field) && $field=='donate_firstname'?$sort:'';?>" href="javascript:;">訂單號</a></th>
                <th class="table-sort"><a id="donate_firstname" class="<?=isset($field) && $field=='donate_firstname'?$sort:'';?>" href="javascript:;">捐款人</a></th>
                <th class="table-sort"><a id="donate_money" class="<?=isset($field) && $field=='donate_money'?$sort:'';?>" href="javascript:;">金額</a></th>
                <th class="table-sort"><a id="order_status_id" class="<?=isset($field) && $field=='order_status_id'?$sort:'';?>" href="javascript:;">支付狀態</a></th>
                <th class="table-sort"><a id="donate_phone" class="<?=isset($field) && $field=='donate_phone'?$sort:'';?>" href="javascript:;">聯絡電話</a></th>
                <th class="table-sort"><a id="donate_email" class="<?=isset($field) && $field=='donate_email'?$sort:'';?>" href="javascript:;">聯絡電郵</a></th>
                <th class="table-sort"><a id="donate_country" class="<?=isset($field) && $field=='donate_country'?$sort:'';?>" href="javascript:;">國家/地區</a></th>
                <th class="table-sort"><a id="status" class="<?=isset($field) && $field=='status'?$sort:'';?>" href="javascript:;">狀態</a></th>
                <th class="table-sort"><a id="updated_at" class="<?=isset($field) && $field=='updated_at'?$sort:'';?>" href="javascript:;">編輯於</a></th>
                <th class="table-sort"><a id="created_at" class="<?=isset($field) && $field=='created_at'?$sort:'';?>" href="javascript:;">創建於</a></th>
                <th>動作</th>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input type="text" name="number" value="<?=isset($number)?$number:''?>" class="layui-input" /></td>
                <td><input type="text" name="donate_name" value="<?=isset($donate_name)?$donate_name:''?>" class="layui-input" /></td>
                <td><input type="text" name="donate_money" value="<?=isset($donate_money)?$donate_money:''?>" class="layui-input" /></td>
                <td>
                  <select name="order_status_id">
                    <option value="">全部狀態</option>
                    <?php if(isset($order_status_option) && !empty($order_status_option)):?>
                    <?php foreach($order_status_option as $option_id=>$option_text):?>
                    <option value="<?php echo $option_id;?>" <?=isset($order_status_id)&&$order_status_id==$option_id?'selected':''?> ><?php echo $option_text;?></option>
                    <?php endforeach;?>
                    <?php endif;?>
                  </select>
                </td>
                <td><input type="text" name="donate_phone" value="<?=isset($donate_phone)?$donate_phone:''?>" class="layui-input" /></td>
                <td><input type="text" name="donate_email" value="<?=isset($donate_email)?$donate_email:''?>" class="layui-input" /></td>
                <td><input type="text" name="donate_country" value="<?=isset($donate_country)?$donate_country:''?>" class="layui-input" /></td>
                <td>
                  <select name="status">
                    <option value="">全部狀態</option>
                    <option value="1" <?=isset($status)&&$status==1?'selected':''?>>待處理</option>
                    <option value="2" <?=isset($status)&&$status==2?'selected':''?>>已處理</option>
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
                $url=site_url('admin/order/%s/'.$list['id']);
              ?>
              <tr>
                <td><input type="checkbox" class="chk_one" value="<?=$list['id']?>" lay-skin="primary" lay-filter="chk_one"></td>
                <td><?=$list['number'];?></td>
                <td><?=$list['donate_firstname'].' '.$list['donate_lastname'].' '.$list['donate_gender']?></td>
                <td><?=$list['donate_money'];?></td>
                <td><span class="layui-badge layui-bg-<?=$order_status_class[$list['order_status_id']]?>"><?=helper_type_parameter('order_status_option',$list['order_status_id'])?></span></td>
                <td><?=$list['donate_phone']?></td>
                <td><?=$list['donate_email']?></td>
                <td><?=$list['donate_country']?></td>
                <td><?=$list['status_format']?></td>
                <td><?=$list['updated_at']?></td>
                <td><?=$list['created_at']?></td>
                <td>
                  <div class="words-single-line">
                    <a href="<?=sprintf($url,"view")?>" class="layui-btn layui-btn-primary layui-btn-sm operater-btn" title="查看詳情"><i class="fas fa-file"></i></a>
                    <a href="javascript:;" onClick="delete_item('<?=sprintf($url,"delete")?>');" class="layui-btn layui-btn-primary layui-btn-sm operater-btn" title="刪除"><i class="fas fa-trash"></i></a>
                  </div>
                </td>
              </tr>
              <?php endforeach;?>
              <?php else:?>
              <tr>
                <td colspan="15">
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
