<?php $this->load->view('common/admin_header');?>
<div class="layui-fluid">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-sm6 layui-col-md6">
      <div class="layui-card">
        <div class="layui-card-header">
          今日新增捐款人
          <span class="layui-badge layui-bg-cyan layuiadmin-badge">單位：個</span>
        </div>
        <div class="layui-card-body layuiadmin-card-list">
          <p class="layuiadmin-big-font"><?php echo isset($today_order)?$today_order:'';?></p>
          <p>
            總和
            <span class="layuiadmin-span-color"><?php echo isset($total_order)?$total_order:'';?> <i class="fas fa-user-friends"></i></span>
          </p>
        </div>
      </div>
    </div><!-- layui-col -->
    <div class="layui-col-sm6 layui-col-md6">
      <div class="layui-card">
        <div class="layui-card-header">
          今日站點瀏覽
          <span class="layui-badge layui-bg-orange layuiadmin-badge">單位：個</span>
        </div>
        <div class="layui-card-body layuiadmin-card-list">
          <p class="layuiadmin-big-font"><?php echo isset($today_visit)?$today_visit:'';?></p>
          <p>
            總數
            <span class="layuiadmin-span-color"><?php echo isset($total_visit)?$total_visit:'';?><i class="fas fa-user-friends"></i></span>
          </p>
        </div>
      </div>
    </div>
    <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
      <div class="layui-card">
        <div class="layui-card-header">快捷导航</div>
        <div class="layui-card-body">
          <div class="layadmin-shortcut">
            <ul class="layui-row layui-col-space10 layui-this">
              <li class="layui-col-md2 layui-col-sm3 layui-col-xs4">
                <a href="<?=site_url('admin/setting/config')?>">
                  <i class="fas fa-cog"></i>
                  <span>基本設定</span>
                </a>
              </li>
              <li class="layui-col-md2 layui-col-sm3 layui-col-xs4">
                <a href="<?=site_url('admin/user')?>">
                  <i class="fas fa-user-circle"></i>
                  <span>用戶管理</span>
                </a>
              </li>
              <li class="layui-col-md2 layui-col-sm3 layui-col-xs4">
                <a href="<?=site_url('admin/operator')?>">
                  <i class="fas fa-book-open"></i>
                  <span>操作日誌</span>
                </a>
              </li>
              <li class="layui-col-md2 layui-col-sm3 layui-col-xs4">
                <a href="<?=site_url('admin/page')?>">
                  <i class="fas fa-layer-group"></i>
                  <span>基本頁面</span>
                </a>
              </li>
              <li class="layui-col-md2 layui-col-sm3 layui-col-xs4">
                <a href="<?=site_url('admin/payment')?>">
                  <i class="fas fa-credit-card"></i>
                  <span>支付管理</span>
                </a>
              </li>
              <li class="layui-col-md2 layui-col-sm3 layui-col-xs4">
                <a href="<?=site_url('admin/order')?>">
                  <i class="fas fa-file-invoice"></i>
                  <span>捐款管理</span>
                </a>
              </li>
              <!-- <li class="layui-col-md2 layui-col-sm3 layui-col-xs4">
                <a href="<?=site_url('admin/exhibit')?>">
                  <i class="fas fa-tags"></i>
                  <span>展品徵集</span>
                </a>
              </li>
              <li class="layui-col-md2 layui-col-sm3 layui-col-xs4">
                <a href="<?=site_url('admin/product')?>">
                  <i class="fas fa-clone"></i>
                  <span>產品管理</span>
                </a>
              </li>
              <li class="layui-col-md2 layui-col-sm3 layui-col-xs4">
                <a href="<?=site_url('admin/slideshow')?>">
                  <i class="fas fa-ad"></i>
                  <span>幻燈片管理</span>
                </a>
              </li> -->
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- layui-col -->
    <div class="layui-col-md6 layui-col-xs12">
      <div class="layui-card">
        <div class="layui-card-header">訪問總計</div>
        <div class="layui-card-body">
          <div class="layadmin-dataview" id="myChartTotal"></div>
        </div>
      </div><!-- layui-card -->
    </div>
    <!-- layui-col -->
    <div class="layui-col-md6 layui-col-xs12">
      <div class="layui-card">
        <div class="layui-card-header">
          <div class="layui-row">
            <div class="layui-col-md6">最近訪問記錄</div>
            <div class="layui-col-md6 text-right">
              <a class="text-right" href="<?=site_url('admin/visit')?>">更多 <i class="fas fa-angle-right"></i></a>
            </div>
          </div>
        </div>
        <div class="layui-card-body">
          <table class="layui-table sortable">
            <thead>
              <tr>
                <th>訪問者IP</th>
                <th>瀏覽日期</th>
                <th>瀏覽設備</th>
                <th>創建於</th>
              </tr>
            </thead>
            <tbody>
              <?php if(isset($recent_visit) && !empty($recent_visit)):?>
              <?php foreach($recent_visit as $list):?>
              <tr>
                <td><?=$list['ip_address']?></td>
                <td><?=$list['visit_date']?></td>
                <td><?=$list['device_format']?></td>
                <td><?=$list['created_at']?></td>
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
        </div>
      </div><!-- layui-card -->
    </div><!-- layui-col -->
  </div><!-- layui-row -->
  <div class="footer">
    <div class="layui-row">
      <div class="layui-col-xs6 text-left">Clickr CMS Version 4.1</div>
      <div class="layui-col-xs6 text-right">
        &copy; Copyright 2013-<?=date('Y')?> Powered by <a href="https://www.clickrweb.com" target="_blank">Clickr</a>
      </div>
    </div>
  </div><!--footer-->
</div><!-- layui-fluid -->
<script src="<?=base_url('themes/admin/vendor/echarts/echarts.min.js')?>"></script>
<script src="<?=base_url('themes/admin/vendor/echarts/macarons.js')?>"></script>
<script type="text/javascript" charset="utf-8">
// 總計
var myChartTotal = echarts.init(document.getElementById('myChartTotal'),'macarons');
option = {
  tooltip : {
    show: true,
    feature : {
      mark : {show: true},
      dataZoom : {show: true},
      dataView : {show: true, readOnly: false},
      magicType: {show: true, type: ['line', 'bar']},
      restore : {show: true},
      saveAsImage : {show: true}
    }
  },
  xAxis: {
    type: 'category',
    boundaryGap: false,
    data: ['昨天', '今天', '最近7天', '總瀏覽']
  },
  yAxis: {
    type: 'value'
  },
  series: [{
    data: ["<?=$yesterday_visit?>","<?=$today_visit?>","<?=$week_visit?>","<?=$total_visit?>"],
    type: 'line',
    areaStyle: {}
  }]
};
myChartTotal.setOption(option);
</script>
<?php $this->load->view('common/admin_footer');?>
