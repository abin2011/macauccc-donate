/**
 * @Author            :  Clickr Abin Jason
 * @Create Date       :  2019-6-24
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time: 2019-11-26 17:16:36
 * @email             :  info@clickrweb.com
 * @description       :  後台公共文件.
 */

// layui框架調用
layui.use(['element','layer','form','table','jquery','laydate'], function(){
  var element = layui.element;
  var layer = layui.layer;
  var table = layui.table;
  var laydate = layui.laydate;
  var form = layui.form;

  // 通用：側欄收起菜單文字提示
  var menuTips;
  $('.layui-nav-tree>li>a').hover(function(){
    if($('.layadmin-tabspage-none').hasClass("layadmin-side-shrink")){
      menuTips=layer.tips($(this).attr('title'),this,{time: 0});
    }
    else{
      layer.close(menuTips);
    }
  },function(){
    layer.close(menuTips);
  });

  // 通用：提示框
  var commonTips;
  $('.operater-btn').hover(function(){
    commonTips=layer.tips($(this).attr('title'),this,{
      time: 0,
      tips: 1
    });
  },function(){
    layer.close(commonTips);
  });

  // 通用：日曆選擇器（日期)
  $('.date-picker').each(function(){
    laydate.render({
      elem: this
      ,format:'yyyy-MM-dd'
      ,lang: 'mo'
    });
  });

  // 通用：日曆選擇器（日期+時間）
  $('.date-time-picker').each(function(){
    laydate.render({
      elem: this
      ,format:'yyyy-MM-dd HH:mm:ss'
      ,type:'datetime'
      ,lang: 'mo'
    });
  });

  // 通用：全選 / 反選
  form.on('checkbox(chk_all)', function(data){
    var chkStatus = data.elem.checked;
    if(chkStatus == true){
      $(".chk_one").prop("checked", true);
      form.render('checkbox');
    }else{
      $(".chk_one").prop("checked", false);
      form.render('checkbox');
    }
  });

  // 通用：一个未选中全选取消选中
  form.on('checkbox(chk_one)', function(data){
    var item = $(".chk_one");
    for(var i=0;i<item.length;i++){
      if(item[i].checked == false){
        $("#chk_all").prop("checked", false);
        form.render('checkbox');
      }
    }
  });

});

// 通用：刪除選項
function delete_item(url){
  var id=arguments[2]?arguments[2]:0;
  if(url!=null && url.length>0){
    layer.confirm('您確定要刪除該選項嗎？', {
      btn: ['確定','取消'],
      title:'操作',
      skin: 'confirm-box'
    },function(){
      if(id>0){
        if(url.indexOf("?")<0)
          url+=id;
        else
          url=url+"&"+id;
      }
      location.href=url;
    });

  }
}

// 通用：標記處理
function mark_data(url){
  var id=arguments[2]?arguments[2]:0;
  if(url!=null && url.length>0){
    layer.confirm('您確定要標記處理嗎？', {
      btn: ['確定','取消'],
      title:'操作',
      skin: 'confirm-box'
    },function(){
      if(id>0){
        if(url.indexOf("?")<0)
          url+=id;
        else
          url=url+"&"+id;
      }
      location.href=url;
    });
  }
}

// 通用：數據還原
function recover_data(url){
  var id=arguments[2]?arguments[2]:0;
  if(url!=null && url.length>0){
    layer.confirm('您確定要還原數據嗎？', {
      btn: ['確定','取消'],
      title:'操作',
      skin: 'confirm-box'
    },function(){
      if(id>0){
        if(url.indexOf("?")<0)
          url+=id;
        else
          url=url+"&"+id;
      }
      location.href=url;
    });
  }
}

// 通用：重置瀏覽量.
function reset_item(url){
  var id=arguments[2]?arguments[2]:0;
  if(url!=null && url.length>0){
    layer.confirm('您確定要重置檢視量嗎', {
      btn: ['確定','取消'],
      title:'操作',
      skin: 'confirm-box'
    },function(){
      if(id>0){
        if(url.indexOf("?")<0)
          url+=id;
        else
          url=url+"&"+id;
      }
      location.href=url;
    });
  }
}

// 通用：批量刪除
function delete_allitem(urls) {
  var delete_string="";
  $("input.chk_one").each(function(){
    if($(this).prop("checked") == true){
      delete_string+=","+$(this).val()
    }
  });
  delete_string=delete_string.substring(1);
  // console.log(delete_string);

  if(delete_string!=""){
    layer.confirm('您確定要刪除選中選項嗎', {
      btn: ['確定','取消'],
      title:'操作',
      skin: 'confirm-box'
    },function(){
      if(urls.indexOf("?")<0)
        urls=urls+'?delete_string='+delete_string;
      else
        urls=urls+'&delete_string='+delete_string;
      console.log('delete_allitem urls=='+urls);
      location.href=urls;
    });
  }else{
    layer.msg('請先選擇要刪除的選項');
  }
}

// 通用：圖片排序
if($("#gallertSortable").length){
  var sortableEl = document.getElementById('gallertSortable');
  var sortable = Sortable.create(sortableEl,{
    animation: 300,
    handle: "#gallertSortable .thumbnail"
  });
}

// 通用：菜單func
function mainMenu(){
  // PC menu
  var isShow = true;
  $('#LAY_app_flexible').click(function(){
    $('.layui-nav-item cite').each(function(){
      if($(this).is(':hidden')){
        $(this).show();
      }else{
        $(this).hide();
      }
    });
    if(isShow){
      $('.layadmin-tabspage-none').addClass('layadmin-side-shrink');
      $("#LAY_app_flexible").addClass('layui-icon-spread-left');
      $("#LAY_app_flexible").removeClass('layui-icon-shrink-right');
      $('dd cite').each(function(){
        $(this).hide();
      });
      $('.layui-logo').addClass('layui-logo-sm');
      isShow =false;
    }else{
      $('.layadmin-tabspage-none').removeClass('layadmin-side-shrink');
      $("#LAY_app_flexible").addClass('layui-icon-shrink-right');
      $("#LAY_app_flexible").removeClass('layui-icon-spread-left');
      $('dd cite').each(function(){
        $(this).show();
      });
      $('.layui-logo').removeClass('layui-logo-sm');
      isShow =true;
    }
  });
  // PC menu expand with li
  $('.layui-nav-item>a').click(function(){
    if ($(this).parent().find('.layui-nav-child').length && isShow==false) {
      $('.layadmin-tabspage-none').removeClass('layadmin-side-shrink');
      $("#LAY_app_flexible").addClass('layui-icon-shrink-right');
      $("#LAY_app_flexible").removeClass('layui-icon-spread-left');
      $(".layui-nav-item cite").show();
      $('.layui-logo').removeClass('layui-logo-sm');
      isShow=true;
    }
  });
  // Mobile menu
  $('#sideMenuMobileTrigger').click(function(){
    $('.layadmin-tabspage-none').addClass('layadmin-side-spread-sm');
    $("#sideMenuMobileTrigger").addClass('layui-icon-shrink-right');
    $("#sideMenuMobileTrigger").removeClass('layui-icon-spread-left');
  });
  $(".layadmin-body-shade").click(function(){
    $('.layadmin-tabspage-none').removeClass('layadmin-side-spread-sm');
    $("#sideMenuMobileTrigger").removeClass('layui-icon-shrink-right');
    $("#sideMenuMobileTrigger").addClass('layui-icon-spread-left');
  });
}


// jQuery
$(document).ready(function() {
  
  // 通用：調用菜單
  mainMenu();

  // 通用：錯誤提示(關閉)
  if($(".alert").length){
    var boxAlert = $(".alert");
    $(".alert .close").click(function(){
      boxAlert.fadeOut();
    });
  }

  // 通用：數據表格排序
  $('table.layui-table thead th a').click(function() {
    var field=$(this).attr('id');
    var sort=$(this).attr('class');
    if(sort=='' || sort=='undefined' || sort=='desc'){
      sort='asc';
    }else{
      sort='desc';
    }
    $("#field").val(field);
    $("#sort").val(sort);
    $(this).parents('form').submit();
  });


  // 通用： CI調試
  $("#codeigniter_profiler").appendTo('.layadmin-tabsbody-item');

});